<?php

namespace App\Contracts\Services;

use App\Models\Artisan;
use App\Models\User;

interface ArtisanServiceInterface
{
    /**
     * Créer un nouveau profil artisan
     */
    public function createArtisan(array $data, User $user): Artisan;

    /**
     * Mettre à jour un profil artisan
     */
    public function updateArtisan(Artisan $artisan, array $data): Artisan;

    /**
     * Supprimer un profil artisan
     */
    public function deleteArtisan(Artisan $artisan): bool;

    /**
     * Approuver un artisan
     */
    public function approveArtisan(Artisan $artisan): bool;

    /**
     * Rejeter un artisan
     */
    public function rejectArtisan(Artisan $artisan, string $raison = null): bool;

    /**
     * Activer/Désactiver un artisan
     */
    public function toggleArtisanStatus(Artisan $artisan): bool;

    /**
     * Obtenir les artisans approuvés
     */
    public function getApprovedArtisans();

    /**
     * Obtenir les artisans par spécialité
     */
    public function getArtisanBySpeciality(string $speciality);

    /**
     * Obtenir les artisans en attente d'approbation
     */
    public function getPendingArtisans();

    /**
     * Vérifier si un utilisateur peut modifier un profil artisan
     */
    public function canUserModifyArtisan(User $user, Artisan $artisan): bool;

    /**
     * Obtenir les statistiques d'un artisan
     */
    public function getArtisanStats(Artisan $artisan): array;

    /**
     * Rechercher des artisans
     */
    public function searchArtisans(array $criteria);
}
