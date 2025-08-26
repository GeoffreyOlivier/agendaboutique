<?php

namespace App\Repositories;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class ShopRepository extends BaseRepository
{
    public function __construct(Shop $model)
    {
        parent::__construct($model);
    }

    /**
     * Trouver une boutique par utilisateur
     */
    public function findByUser(User $user): ?Shop
    {
        return $this->model->where('user_id', $user->id)->first();
    }

    /**
     * Trouver une boutique par utilisateur ou lever une exception
     */
    public function findByUserOrFail(User $user): Shop
    {
        return $this->model->where('user_id', $user->id)->firstOrFail();
    }

    /**
     * Obtenir toutes les boutiques approuvées
     */
    public function getApprovedShops(): Collection
    {
        return $this->model->approved()->active()->with('user')->get();
    }

    /**
     * Obtenir toutes les boutiques en attente d'approbation
     */
    public function getPendingShops(): Collection
    {
        return $this->model->where('status', 'pending')->with('user')->get();
    }

    /**
     * Obtenir les boutiques par ville
     */
    public function getShopsByCity(string $city): Collection
    {
        return $this->model->approved()
            ->active()
            ->where('city', 'like', '%' . $city . '%')
            ->with('user', 'craftsmen')
            ->get();
    }

    /**
     * Obtenir les boutiques par taille
     */
    public function getShopsBySize(string $size): Collection
    {
        return $this->model->approved()
            ->active()
            ->where('size', $size)
            ->with('user', 'craftsmen')
            ->get();
    }

    /**
     * Rechercher des boutiques avec critères
     */
    public function searchShops(array $criteria): Collection
    {
        $query = $this->model->approved()->active();

        if (isset($criteria['city'])) {
            $query->where('city', 'like', '%' . $criteria['city'] . '%');
        }

        if (isset($criteria['size'])) {
            $query->where('size', $criteria['size']);
        }

        if (isset($criteria['specialty'])) {
            $query->whereHas('craftsmen', function ($q) use ($criteria) {
                $q->where('specialty', 'like', '%' . $criteria['specialty'] . '%');
            });
        }

        return $query->with('user', 'craftsmen')->get();
    }

    /**
     * Obtenir les boutiques avec leurs artisans
     */
    public function getShopsWithCraftsmen(): Collection
    {
        return $this->model->approved()
            ->active()
            ->with(['user', 'craftsmen.user'])
            ->get();
    }

    /**
     * Obtenir les statistiques des boutiques
     */
    public function getShopsStats(): array
    {
        return [
            'total' => $this->model->count(),
            'approved' => $this->model->where('status', 'approved')->count(),
            'pending' => $this->model->where('status', 'pending')->count(),
            'rejected' => $this->model->where('status', 'rejected')->count(),
            'active' => $this->model->where('active', true)->count(),
        ];
    }

    /**
     * Obtenir les boutiques par statut
     */
    public function getShopsByStatus(string $status): Collection
    {
        return $this->model->where('status', $status)->with('user')->get();
    }

    /**
     * Vérifier si un utilisateur a une boutique
     */
    public function userHasShop(User $user): bool
    {
        return $this->model->where('user_id', $user->id)->exists();
    }

    /**
     * Obtenir les boutiques avec pagination
     */
    public function getShopsPaginated(int $perPage = 15)
    {
        return $this->model->approved()
            ->active()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Obtenir les boutiques récentes
     */
    public function getRecentShops(int $limit = 5): Collection
    {
        return $this->model->approved()
            ->active()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
