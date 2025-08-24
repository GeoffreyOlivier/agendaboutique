<?php

namespace App\Services;

use App\Contracts\Services\ImageServiceInterface;
use App\Services\ImageValidationService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class LocalImageService implements ImageServiceInterface
{
    /**
     * Le disque de stockage à utiliser
     */
    protected string $disk;

    /**
     * Options par défaut pour l'optimisation
     */
    protected array $defaultOptions;

    /**
     * Service de validation des images
     */
    protected ImageValidationService $validationService;

    public function __construct(ImageValidationService $validationService)
    {
        $this->disk = config('filesystems.default', 'public');
        $this->defaultOptions = [
            'max_width' => 1920,
            'max_height' => 1080,
            'quality' => 85,
            'format' => 'jpg',
        ];
        $this->validationService = $validationService;
    }

    /**
     * Stocker une image
     */
    public function store(UploadedFile $file, string $path, ?array $options = null): array
    {
        // Valider l'image avant de la stocker
        try {
            $imageInfo = $this->validationService->validate($file);
        } catch (\Exception $e) {
            throw new \Exception('Erreur de validation de l\'image : ' . $e->getMessage());
        }
        
        $options = array_merge($this->defaultOptions, $options ?? []);
        
        // Générer un nom de fichier unique
        $filename = $this->generateUniqueFilename($file);
        $fullPath = trim($path, '/') . '/' . $filename;
        
        // Stocker l'image originale
        $storedPath = Storage::disk($this->disk)->putFileAs(
            dirname($fullPath),
            $file,
            basename($fullPath)
        );
        
        if (!$storedPath) {
            throw new \Exception('Impossible de stocker l\'image');
        }
        
        // Optimiser l'image si demandé
        if (isset($options['optimize']) && $options['optimize']) {
            $this->optimize($fullPath, $options);
        }
        
        // Retourner les informations de l'image
        return [
            'path' => $fullPath,
            'filename' => $filename,
            'original_name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'url' => $this->getUrl($fullPath),
            'metadata' => $this->getMetadata($fullPath),
        ];
    }

    /**
     * Stocker plusieurs images
     */
    public function storeMultiple(array $files, string $path, ?array $options = null): array
    {
        $results = [];
        
        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $results[] = $this->store($file, $path, $options);
            }
        }
        
        return $results;
    }

    /**
     * Supprimer une image
     */
    public function delete(string $path): bool
    {
        if ($this->exists($path)) {
            return Storage::disk($this->disk)->delete($path);
        }
        
        return false;
    }

    /**
     * Supprimer plusieurs images
     */
    public function deleteMultiple(array $paths): array
    {
        $results = [];
        
        foreach ($paths as $path) {
            $results[$path] = $this->delete($path);
        }
        
        return $results;
    }

    /**
     * Obtenir l'URL publique d'une image
     */
    public function getUrl(string $path): string
    {
        if ($this->disk === 'public') {
            return Storage::disk($this->disk)->url($path);
        }
        
        return Storage::disk($this->disk)->url($path);
    }

    /**
     * Vérifier si une image existe
     */
    public function exists(string $path): bool
    {
        return Storage::disk($this->disk)->exists($path);
    }

    /**
     * Obtenir les métadonnées d'une image
     */
    public function getMetadata(string $path): ?array
    {
        if (!$this->exists($path)) {
            return null;
        }
        
        try {
            $fullPath = Storage::disk($this->disk)->path($path);
            $image = Image::make($fullPath);
            
            return [
                'width' => $image->width(),
                'height' => $image->height(),
                'size' => Storage::disk($this->disk)->size($path),
                'mime_type' => $image->mime(),
                'aspect_ratio' => $image->width() / $image->height(),
            ];
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Optimiser une image (redimensionnement, compression)
     */
    public function optimize(string $path, array $options = []): bool
    {
        if (!$this->exists($path)) {
            return false;
        }
        
        try {
            $options = array_merge($this->defaultOptions, $options);
            $fullPath = Storage::disk($this->disk)->path($path);
            $image = Image::make($fullPath);
            
            // Redimensionner si nécessaire
            if (isset($options['max_width']) || isset($options['max_height'])) {
                $image->resize(
                    $options['max_width'] ?? null,
                    $options['max_height'] ?? null,
                    function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    }
                );
            }
            
            // Sauvegarder avec la qualité spécifiée
            $image->save($fullPath, $options['quality'] ?? 85);
            
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Générer un nom de fichier unique
     */
    protected function generateUniqueFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $baseName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $timestamp = now()->timestamp;
        $random = Str::random(8);
        
        return "{$baseName}_{$timestamp}_{$random}.{$extension}";
    }

    /**
     * Obtenir le disque de stockage utilisé
     */
    public function getDisk(): string
    {
        return $this->disk;
    }

    /**
     * Définir le disque de stockage
     */
    public function setDisk(string $disk): self
    {
        $this->disk = $disk;
        return $this;
    }
}
