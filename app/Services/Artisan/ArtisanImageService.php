<?php

namespace App\Services\Artisan;

use App\Contracts\Services\ImageServiceInterface;
use Illuminate\Http\UploadedFile;

class ArtisanImageService
{
    protected ImageServiceInterface $imageService;
    protected string $basePath = 'artisans/avatars';

    public function __construct(ImageServiceInterface $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Stocker un avatar d'artisan
     */
    public function storeAvatar(UploadedFile $file, int $userId): array
    {
        $path = "{$this->basePath}/{$userId}";
        $options = [
            'optimize' => true,
            'max_width' => 400,
            'max_height' => 400,
            'quality' => 85,
            'crop' => 'square',
        ];

        return $this->imageService->store($file, $path, $options);
    }

    /**
     * Mettre à jour un avatar d'artisan
     */
    public function updateAvatar(UploadedFile $file, int $userId, ?string $oldAvatarPath = null): array
    {
        if ($oldAvatarPath) {
            $this->imageService->delete($oldAvatarPath);
        }

        return $this->storeAvatar($file, $userId);
    }

    /**
     * Supprimer un avatar d'artisan
     */
    public function deleteAvatar(string $avatarPath): bool
    {
        return $this->imageService->delete($avatarPath);
    }

    /**
     * Obtenir l'URL d'un avatar
     */
    public function getAvatarUrl(string $avatarPath): string
    {
        return $this->imageService->getUrl($avatarPath);
    }

    /**
     * Obtenir les métadonnées d'un avatar
     */
    public function getAvatarMetadata(string $avatarPath): ?array
    {
        return $this->imageService->getMetadata($avatarPath);
    }
}
