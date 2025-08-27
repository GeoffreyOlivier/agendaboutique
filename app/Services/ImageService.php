<?php

namespace App\Services;

use App\Contracts\Services\ImageServiceInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService implements ImageServiceInterface
{
    protected string $disk = 'public';

    /**
     * Stocker une image
     */
    public function store(UploadedFile $file, string $path, ?array $options = null): array
    {
        $fileName = $this->generateFileName($file);
        $fullPath = $path . '/' . $fileName;
        
        // Stocker le fichier
        $storedPath = $file->storeAs($path, $fileName, $this->disk);
        
        return [
            'path' => $storedPath,
            'original_name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'url' => Storage::disk($this->disk)->url($storedPath),
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
        return Storage::disk($this->disk)->delete($path);
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

        $fullPath = Storage::disk($this->disk)->path($path);
        
        return [
            'size' => Storage::disk($this->disk)->size($path),
            'last_modified' => Storage::disk($this->disk)->lastModified($path),
            'mime_type' => Storage::disk($this->disk)->mimeType($path),
            'url' => $this->getUrl($path),
        ];
    }

    /**
     * Optimiser une image (redimensionnement, compression)
     * Note: Cette implémentation basique ne fait pas d'optimisation réelle
     * Pour une vraie optimisation, utilisez Intervention Image ou similaire
     */
    public function optimize(string $path, array $options = []): bool
    {
        // Pour l'instant, on considère que l'optimisation réussit toujours
        // Dans une vraie implémentation, on utiliserait Intervention Image
        return $this->exists($path);
    }

    /**
     * Générer un nom de fichier unique
     */
    protected function generateFileName(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $timestamp = now()->format('Y-m-d_H-i-s');
        $random = Str::random(8);
        
        return Str::slug($name) . '_' . $timestamp . '_' . $random . '.' . $extension;
    }
}
