<?php

namespace App\Contracts\Repositories;

use App\Models\Artisan;
use App\Contracts\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface ArtisanRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Créer un nouvel artisan
     */
    public function create(array $data): Artisan;

    /**
     * Mettre à jour un artisan
     */
    public function update(Model $artisan, array $data): bool;

    /**
     * Supprimer un artisan
     */
    public function delete(Model $artisan): bool;

    /**
     * Trouver un artisan par ID
     */
    public function find(int $id): ?Artisan;

    /**
     * Trouver un artisan par ID utilisateur
     */
    public function findByUserId(int $userId): ?Artisan;

    /**
     * Obtenir les artisans approuvés
     */
    public function getApprovedArtisans(): Collection;

    /**
     * Obtenir les artisans par spécialité
     */
    public function getArtisansBySpeciality(string $speciality): Collection;

    /**
     * Obtenir les artisans en attente d'approbation
     */
    public function getPendingArtisans(): Collection;

    /**
     * Rechercher des artisans selon des critères
     */
    public function searchArtisans(array $criteria): Collection;
}
