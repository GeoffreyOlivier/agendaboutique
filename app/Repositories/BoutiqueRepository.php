<?php

namespace App\Repositories;

use App\Models\Boutique;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class BoutiqueRepository extends BaseRepository
{
    public function __construct(Boutique $model)
    {
        parent::__construct($model);
    }

    /**
     * Trouver une boutique par utilisateur
     */
    public function findByUser(User $user): ?Boutique
    {
        return $this->model->where('user_id', $user->id)->first();
    }

    /**
     * Trouver une boutique par utilisateur ou lever une exception
     */
    public function findByUserOrFail(User $user): Boutique
    {
        return $this->model->where('user_id', $user->id)->firstOrFail();
    }

    /**
     * Obtenir toutes les boutiques approuvées
     */
    public function getApprovedBoutiques(): Collection
    {
        return $this->model->approuvees()->actives()->with('user')->get();
    }

    /**
     * Obtenir toutes les boutiques en attente d'approbation
     */
    public function getPendingBoutiques(): Collection
    {
        return $this->model->where('statut', 'en_attente')->with('user')->get();
    }

    /**
     * Obtenir les boutiques par ville
     */
    public function getBoutiquesByCity(string $city): Collection
    {
        return $this->model->approuvees()
            ->actives()
            ->where('ville', 'like', '%' . $city . '%')
            ->with('user', 'artisans')
            ->get();
    }

    /**
     * Obtenir les boutiques par taille
     */
    public function getBoutiquesBySize(string $size): Collection
    {
        return $this->model->approuvees()
            ->actives()
            ->where('taille', $size)
            ->with('user', 'artisans')
            ->get();
    }

    /**
     * Rechercher des boutiques avec critères
     */
    public function searchBoutiques(array $criteria): Collection
    {
        $query = $this->model->approuvees()->actives();

        if (isset($criteria['ville'])) {
            $query->where('ville', 'like', '%' . $criteria['ville'] . '%');
        }

        if (isset($criteria['taille'])) {
            $query->where('taille', $criteria['taille']);
        }

        if (isset($criteria['specialite'])) {
            $query->whereHas('artisans', function ($q) use ($criteria) {
                $q->where('specialite', 'like', '%' . $criteria['specialite'] . '%');
            });
        }

        return $query->with('user', 'artisans')->get();
    }

    /**
     * Obtenir les boutiques avec leurs artisans
     */
    public function getBoutiquesWithArtisans(): Collection
    {
        return $this->model->approuvees()
            ->actives()
            ->with(['user', 'artisans.user'])
            ->get();
    }

    /**
     * Obtenir les statistiques des boutiques
     */
    public function getBoutiquesStats(): array
    {
        return [
            'total' => $this->model->count(),
            'approuvees' => $this->model->where('statut', 'approuve')->count(),
            'en_attente' => $this->model->where('statut', 'en_attente')->count(),
            'rejetees' => $this->model->where('statut', 'rejete')->count(),
            'actives' => $this->model->where('actif', true)->count(),
        ];
    }

    /**
     * Obtenir les boutiques par statut
     */
    public function getBoutiquesByStatus(string $status): Collection
    {
        return $this->model->where('statut', $status)->with('user')->get();
    }

    /**
     * Vérifier si un utilisateur a une boutique
     */
    public function userHasBoutique(User $user): bool
    {
        return $this->model->where('user_id', $user->id)->exists();
    }

    /**
     * Obtenir les boutiques avec pagination
     */
    public function getBoutiquesPaginated(int $perPage = 15)
    {
        return $this->model->approuvees()
            ->actives()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Obtenir les boutiques récentes
     */
    public function getRecentBoutiques(int $limit = 5): Collection
    {
        return $this->model->approuvees()
            ->actives()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
