<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\Craftsman;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository extends BaseRepository
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    /**
     * Obtenir tous les products disponibles
     */
    public function getAvailableProducts(): Collection
    {
        return $this->model->available()->active()->with('craftsman')->get();
    }

    /**
     * Obtenir les products par catégorie
     */
    public function getProductsByCategory(string $category): Collection
    {
        return $this->model->byCategory($category)
            ->available()
            ->active()
            ->with('craftsman')
            ->get();
    }

    /**
     * Obtenir les products d'un craftsman
     */
    public function getProductsByCraftsman(Craftsman $craftsman): Collection
    {
        return $this->model->where('craftsman_id', $craftsman->id)
            ->with('craftsman')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Obtenir les products publiés
     */
    public function getPublishedProducts(): Collection
    {
        return $this->model->where('status', 'published')
            ->where('available', true)
            ->with('craftsman')
            ->get();
    }

    /**
     * Obtenir les products en brouillon
     */
    public function getDraftProducts(): Collection
    {
        return $this->model->where('status', 'draft')
            ->with('craftsman')
            ->get();
    }

    /**
     * Rechercher des products avec critères
     */
    public function searchProducts(array $criteria): Collection
    {
        $query = $this->model->available()->active();

        if (isset($criteria['category'])) {
            $query->where('category', 'like', '%' . $criteria['category'] . '%');
        }

        if (isset($criteria['min_price'])) {
            $query->where('base_price', '>=', $criteria['min_price']);
        }

        if (isset($criteria['max_price'])) {
            $query->where('base_price', '<=', $criteria['max_price']);
        }

        if (isset($criteria['material'])) {
            $query->where('material', 'like', '%' . $criteria['material'] . '%');
        }

        if (isset($criteria['craftsman_id'])) {
            $query->where('craftsman_id', $criteria['craftsman_id']);
        }

        return $query->with('craftsman')->get();
    }

    /**
     * Obtenir les products par gamme de prix
     */
    public function getProductsByPriceRange(float $min, float $max): Collection
    {
        return $this->model->available()
            ->active()
            ->where('base_price', '>=', $min)
            ->where('base_price', '<=', $max)
            ->with('craftsman')
            ->get();
    }

    /**
     * Obtenir les products avec pagination
     */
    public function getProductsPaginated(int $perPage = 12)
    {
        return $this->model->available()
            ->active()
            ->with('craftsman')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Obtenir les products récents
     */
    public function getRecentProducts(int $limit = 8): Collection
    {
        return $this->model->available()
            ->active()
            ->with('craftsman')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Obtenir les products populaires (par nombre de vues ou commandes)
     */
    public function getPopularProducts(int $limit = 8): Collection
    {
        return $this->model->available()
            ->active()
            ->with('craftsman')
            ->orderBy('created_at', 'desc') // À remplacer par un vrai algorithme de popularité
            ->limit($limit)
            ->get();
    }

    /**
     * Obtenir les statistiques des products
     */
    public function getProductsStats(): array
    {
        return [
            'total' => $this->model->count(),
            'available' => $this->model->where('available', true)->count(),
            'published' => $this->model->where('status', 'published')->count(),
            'drafts' => $this->model->where('status', 'draft')->count(),
            'with_images' => $this->model->whereNotNull('main_image')->count(),
        ];
    }

    /**
     * Obtenir les products par statut
     */
    public function getProductsByStatus(string $status): Collection
    {
        return $this->model->where('status', $status)
            ->with('craftsman')
            ->get();
    }

    /**
     * Vérifier si un produit existe
     */
    public function productExists(int $id): bool
    {
        return $this->model->where('id', $id)->exists();
    }

    /**
     * Obtenir les products avec leurs images
     */
    public function getProductsWithImages(): Collection
    {
        return $this->model->available()
            ->active()
            ->whereNotNull('main_image')
            ->with('craftsman')
            ->get();
    }
}
