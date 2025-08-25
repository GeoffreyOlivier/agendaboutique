<?php

namespace App\Repositories;

use App\Models\Artisan;
use App\Models\User;
use App\Contracts\Repositories\ArtisanRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ArtisanRepository extends BaseRepository implements ArtisanRepositoryInterface
{
    public function __construct(Artisan $model)
    {
        parent::__construct($model);
    }

    /**
     * Trouver un artisan par utilisateur
     */
    public function findByUser(User $user): ?Artisan
    {
        return $this->model->where('user_id', $user->id)->first();
    }

    /**
     * Trouver un artisan par utilisateur ou lever une exception
     */
    public function findByUserOrFail(User $user): Artisan
    {
        return $this->model->where('user_id', $user->id)->firstOrFail();
    }

    /**
     * Trouver un artisan par ID utilisateur
     */
    public function findByUserId(int $userId): ?Artisan
    {
        return $this->model->where('user_id', $userId)->first();
    }

    /**
     * Obtenir tous les artisans approuvés
     */
    public function getApprovedArtisans(): Collection
    {
        return $this->model->where('statut', 'approuve')
            ->where('actif', true)
            ->with('user')
            ->get();
    }

    /**
     * Obtenir les artisans par spécialité
     */
    public function getArtisansBySpeciality(string $speciality): Collection
    {
        return $this->model->where('specialite', 'like', '%' . $speciality . '%')
            ->where('statut', 'approuve')
            ->where('actif', true)
            ->with('user')
            ->get();
    }

    /**
     * Obtenir les artisans en attente d'approbation
     */
    public function getPendingArtisans(): Collection
    {
        return $this->model->where('statut', 'en_attente')
            ->with('user')
            ->get();
    }

    /**
     * Obtenir les artisans par ville
     */
    public function getArtisansByCity(string $city): Collection
    {
        return $this->model->where('ville', 'like', '%' . $city . '%')
            ->where('statut', 'approuve')
            ->where('actif', true)
            ->with('user')
            ->get();
    }

    /**
     * Obtenir les artisans par disponibilité
     */
    public function getArtisansByAvailability(string $availability): Collection
    {
        return $this->model->where('disponibilite', $availability)
            ->where('statut', 'approuve')
            ->where('actif', true)
            ->with('user')
            ->get();
    }

    /**
     * Rechercher des artisans avec critères
     */
    public function searchArtisans(array $criteria): Collection
    {
        $query = $this->model->where('statut', 'approuve')->where('actif', true);

        if (isset($criteria['specialite'])) {
            $query->where('specialite', 'like', '%' . $criteria['specialite'] . '%');
        }

        if (isset($criteria['ville'])) {
            $query->where('ville', 'like', '%' . $criteria['ville'] . '%');
        }

        if (isset($criteria['disponibilite'])) {
            $query->where('disponibilite', $criteria['disponibilite']);
        }

        if (isset($criteria['tarif_max'])) {
            $query->where('tarif_horaire', '<=', $criteria['tarif_max']);
        }

        if (isset($criteria['experience_min'])) {
            $query->where('experience_annees', '>=', $criteria['experience_min']);
        }

        return $query->with('user')->get();
    }

    /**
     * Obtenir les artisans avec leurs produits
     */
    public function getArtisansWithProduits(): Collection
    {
        return $this->model->where('statut', 'approuve')
            ->where('actif', true)
            ->with(['user', 'produits'])
            ->get();
    }

    /**
     * Obtenir les artisans avec pagination
     */
    public function getArtisansPaginated(int $perPage = 15)
    {
        return $this->model->where('statut', 'approuve')
            ->where('actif', true)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Obtenir les artisans récents
     */
    public function getRecentArtisans(int $limit = 5): Collection
    {
        return $this->model->where('statut', 'approuve')
            ->where('actif', true)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Obtenir les artisans par expérience
     */
    public function getArtisansByExperience(int $minYears, int $maxYears = null): Collection
    {
        $query = $this->model->where('statut', 'approuve')
            ->where('actif', true)
            ->where('experience_annees', '>=', $minYears);

        if ($maxYears) {
            $query->where('experience_annees', '<=', $maxYears);
        }

        return $query->with('user')->get();
    }

    /**
     * Obtenir les artisans par gamme de tarifs
     */
    public function getArtisansByTariffRange(float $min, float $max): Collection
    {
        return $this->model->where('statut', 'approuve')
            ->where('actif', true)
            ->where('tarif_horaire', '>=', $min)
            ->where('tarif_horaire', '<=', $max)
            ->with('user')
            ->get();
    }

    /**
     * Obtenir les statistiques des artisans
     */
    public function getArtisansStats(): array
    {
        return [
            'total' => $this->model->count(),
            'approuves' => $this->model->where('statut', 'approuve')->count(),
            'en_attente' => $this->model->where('statut', 'en_attente')->count(),
            'rejetes' => $this->model->where('statut', 'rejete')->count(),
            'actifs' => $this->model->where('actif', true)->count(),
            'disponibles' => $this->model->where('disponibilite', 'disponible')->count(),
        ];
    }

    /**
     * Obtenir les artisans par statut
     */
    public function getArtisansByStatus(string $status): Collection
    {
        return $this->model->where('statut', $status)
            ->with('user')
            ->get();
    }

    /**
     * Vérifier si un utilisateur a un profil artisan
     */
    public function userHasArtisan(User $user): bool
    {
        return $this->model->where('user_id', $user->id)->exists();
    }

    /**
     * Obtenir les artisans avec leurs boutiques
     */
    public function getArtisansWithBoutiques(): Collection
    {
        return $this->model->where('statut', 'approuve')
            ->where('actif', true)
            ->with(['user', 'boutiques'])
            ->get();
    }
}
