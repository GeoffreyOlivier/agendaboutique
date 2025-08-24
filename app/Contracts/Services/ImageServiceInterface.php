<?php

namespace App\Contracts\Services;

use Illuminate\Http\UploadedFile;

interface ImageServiceInterface
{
    /**
     * Stocker une image
     */
    public function store(UploadedFile $file, string $path, ?array $options = null): array;

    /**
     * Stocker plusieurs images
     */
    public function storeMultiple(array $files, string $path, ?array $options = null): array;

    /**
     * Supprimer une image
     */
    public function delete(string $path): bool;

    /**
     * Supprimer plusieurs images
     */
    public function deleteMultiple(array $paths): array;

    /**
     * Obtenir l'URL publique d'une image
     */
    public function getUrl(string $path): string;

    /**
     * Vérifier si une image existe
     */
    public function exists(string $path): bool;

    /**
     * Obtenir les métadonnées d'une image
     */
    public function getMetadata(string $path): ?array;

    /**
     * Optimiser une image (redimensionnement, compression)
     */
    public function optimize(string $path, array $options = []): bool;
}
