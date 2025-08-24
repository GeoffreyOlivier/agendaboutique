<?php

namespace App\Services;

use App\Contracts\Services\ImageServiceInterface;
use Illuminate\Http\UploadedFile;

class ProduitImageService
{
    protected ImageServiceInterface $imageService;
    protected string $basePath = 'produits';

    public function __construct(ImageServiceInterface $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Stocker les images d'un produit
     */
    public function storeImages(array $files, int $produitId): array
    {
        $path = "{$this->basePath}/{$produitId}";
        
        $options = [
            'optimize' => true,
            'max_width' => 1600,
            'max_height' => 1200,
            'quality' => 90,
        ];
        
        return $this->imageService->storeMultiple($files, $path, $options);
    }

    /**
     * Ajouter de nouvelles images à un produit
     */
    public function addImages(array $files, int $produitId, array $existingImages = []): array
    {
        $newImages = $this->storeImages($files, $produitId);
        
        // Combiner avec les images existantes
        return array_merge($existingImages, array_column($newImages, 'path'));
    }

    /**
     * Mettre à jour les images d'un produit
     */
    public function updateImages(
        array $files, 
        int $produitId, 
        array $existingImages = [], 
        array $imagesToDelete = []
    ): array {
        // Supprimer les images marquées pour suppression
        if (!empty($imagesToDelete)) {
            $this->imageService->deleteMultiple($imagesToDelete);
            $existingImages = array_diff($existingImages, $imagesToDelete);
        }
        
        // Ajouter les nouvelles images
        if (!empty($files)) {
            $newImages = $this->storeImages($files, $produitId);
            $existingImages = array_merge($existingImages, array_column($newImages, 'path'));
        }
        
        return array_values($existingImages); // Réindexer le tableau
    }

    /**
     * Supprimer toutes les images d'un produit
     */
    public function deleteAllImages(int $produitId): bool
    {
        $path = "{$this->basePath}/{$produitId}";
        
        // Supprimer le dossier entier
        return $this->imageService->delete($path);
    }

    /**
     * Supprimer une image spécifique
     */
    public function deleteImage(string $imagePath): bool
    {
        return $this->imageService->delete($imagePath);
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
     * Générer des vignettes pour les images
     */
    public function generateThumbnails(array $imagePaths, int $width = 300, int $height = 300): array
    {
        $thumbnails = [];
        
        foreach ($imagePaths as $imagePath) {
            if ($this->imageService->exists($imagePath)) {
                $thumbnailPath = $this->generateThumbnailPath($imagePath);
                
                $options = [
                    'max_width' => $width,
                    'max_height' => $height,
                    'quality' => 80,
                ];
                
                if ($this->imageService->optimize($imagePath, $options)) {
                    $thumbnails[] = $thumbnailPath;
                }
            }
        }
        
        return $thumbnails;
    }

    /**
     * Générer le chemin de la vignette
     */
    protected function generateThumbnailPath(string $originalPath): string
    {
        $pathInfo = pathinfo($originalPath);
        return $pathInfo['dirname'] . '/thumbnails/' . $pathInfo['basename'];
    }
}
