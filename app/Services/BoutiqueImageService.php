<?php

namespace App\Services;

use App\Contracts\Services\ImageServiceInterface;
use Illuminate\Http\UploadedFile;

class BoutiqueImageService
{
    protected ImageServiceInterface $imageService;
    protected string $basePath = 'boutiques/photos';

    public function __construct(ImageServiceInterface $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Stocker la photo principale d'une boutique
     */
    public function storePhoto(UploadedFile $file, int $boutiqueId): array
    {
        $path = "{$this->basePath}/{$boutiqueId}";
        
        $options = [
            'optimize' => true,
            'max_width' => 1200,
            'max_height' => 800,
            'quality' => 85,
        ];
        
        return $this->imageService->store($file, $path, $options);
    }

    /**
     * Mettre à jour la photo d'une boutique
     */
    public function updatePhoto(UploadedFile $file, int $boutiqueId, ?string $oldPhotoPath = null): array
    {
        // Supprimer l'ancienne photo si elle existe
        if ($oldPhotoPath) {
            $this->imageService->delete($oldPhotoPath);
        }
        
        // Stocker la nouvelle photo
        return $this->storePhoto($file, $boutiqueId);
    }

    /**
     * Supprimer la photo d'une boutique
     */
    public function deletePhoto(string $photoPath): bool
    {
        return $this->imageService->delete($photoPath);
    }

    /**
     * Obtenir l'URL de la photo
     */
    public function getPhotoUrl(string $photoPath): string
    {
        return $this->imageService->getUrl($photoPath);
    }

    /**
     * Obtenir les métadonnées de la photo
     */
    public function getPhotoMetadata(string $photoPath): ?array
    {
        return $this->imageService->getMetadata($photoPath);
    }
}
