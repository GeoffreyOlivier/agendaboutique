<?php

namespace App\Services\Product;

use App\Contracts\Services\ImageServiceInterface;
use Illuminate\Http\UploadedFile;

class ProductImageService
{
    protected ImageServiceInterface $imageService;
    protected string $basePath = 'products/images';

    public function __construct(ImageServiceInterface $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Stocker une image de produit
     */
    public function storeImage(UploadedFile $file, int $craftsmanId): array
    {
        $path = "{$this->basePath}/{$craftsmanId}";
        $options = [
            'optimize' => true,
            'max_width' => 1200,
            'max_height' => 800,
            'quality' => 85,
        ];

        return $this->imageService->store($file, $path, $options);
    }

    /**
     * Stocker plusieurs images de produit
     */
    public function storeMultipleImages(array $files, int $craftsmanId): array
    {
        $paths = [];
        foreach ($files as $file) {
            $imageData = $this->storeImage($file, $craftsmanId);
            $paths[] = $imageData['path'];
        }
        return $paths;
    }

    /**
     * Mettre à jour une image de produit
     */
    public function updateImage(UploadedFile $file, int $craftsmanId, ?string $oldImagePath = null): array
    {
        if ($oldImagePath) {
            $this->imageService->delete($oldImagePath);
        }

        return $this->storeImage($file, $craftsmanId);
    }

    /**
     * Supprimer une image de produit
     */
    public function deleteImage(string $imagePath): bool
    {
        return $this->imageService->delete($imagePath);
    }

    /**
     * Supprimer plusieurs images de produit
     */
    public function deleteMultipleImages(array $imagePaths): array
    {
        $results = [];
        foreach ($imagePaths as $path) {
            $results[$path] = $this->imageService->delete($path);
        }
        return $results;
    }

    /**
     * Obtenir l'URL d'une image
     */
    public function getImageUrl(string $imagePath): string
    {
        return $this->imageService->getUrl($imagePath);
    }

    /**
     * Obtenir les métadonnées d'une image
     */
    public function getImageMetadata(string $imagePath): ?array
    {
        return $this->imageService->getMetadata($imagePath);
    }

    /**
     * Créer une miniature d'une image
     */
    public function createThumbnail(string $imagePath, int $width = 300, int $height = 300): ?string
    {
        $options = [
            'optimize' => true,
            'max_width' => $width,
            'max_height' => $height,
            'quality' => 80,
            'crop' => 'square',
        ];

        if ($this->imageService->optimize($imagePath, $options)) {
            return $imagePath;
        }

        return null;
    }

    /**
     * Vérifier si une image existe
     */
    public function imageExists(string $imagePath): bool
    {
        return $this->imageService->exists($imagePath);
    }
}
