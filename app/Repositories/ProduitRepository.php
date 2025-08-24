<?php

namespace App\Repositories;

use App\Models\Produit;
use App\Models\Artisan;
use Illuminate\Database\Eloquent\Collection;

class ProduitRepository extends BaseRepository
{
    public function __construct(Produit $model)
    {
        parent::__construct($model);
    }

    /**
     * Obtenir tous les produits disponibles
     */
    public function getAvailableProduits(): Collection
    {
        return $this->model->disponibles()->actifs()->with('artisan')->get();
    }

    /**
     * Obtenir les produits par catégorie
     */
    public function getProduitsByCategory(string $category): Collection
    {
        return $this->model->parCategorie($category)
            ->disponibles()
            ->actifs()
            ->with('artisan')
            ->get();
    }

    /**
     * Obtenir les produits d'un artisan
     */
    public function getProduitsByArtisan(Artisan $artisan): Collection
    {
        return $this->model->where('artisan_id', $artisan->id)
            ->with('artisan')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Obtenir les produits publiés
     */
    public function getPublishedProduits(): Collection
    {
        return $this->model->where('statut', 'publie')
            ->where('disponible', true)
            ->with('artisan')
            ->get();
    }

    /**
     * Obtenir les produits en brouillon
     */
    public function getDraftProduits(): Collection
    {
        return $this->model->where('statut', 'brouillon')
            ->with('artisan')
            ->get();
    }

    /**
     * Rechercher des produits avec critères
     */
    public function searchProduits(array $criteria): Collection
    {
        $query = $this->model->disponibles()->actifs();

        if (isset($criteria['categorie'])) {
            $query->where('categorie', 'like', '%' . $criteria['categorie'] . '%');
        }

        if (isset($criteria['prix_min'])) {
            $query->where('prix_base', '>=', $criteria['prix_min']);
        }

        if (isset($criteria['prix_max'])) {
            $query->where('prix_base', '<=', $criteria['prix_max']);
        }

        if (isset($criteria['matiere'])) {
            $query->where('matiere', 'like', '%' . $criteria['matiere'] . '%');
        }

        if (isset($criteria['artisan_id'])) {
            $query->where('artisan_id', $criteria['artisan_id']);
        }

        return $query->with('artisan')->get();
    }

    /**
     * Obtenir les produits par gamme de prix
     */
    public function getProduitsByPriceRange(float $min, float $max): Collection
    {
        return $this->model->disponibles()
            ->actifs()
            ->where('prix_base', '>=', $min)
            ->where('prix_base', '<=', $max)
            ->with('artisan')
            ->get();
    }

    /**
     * Obtenir les produits avec pagination
     */
    public function getProduitsPaginated(int $perPage = 12)
    {
        return $this->model->disponibles()
            ->actifs()
            ->with('artisan')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Obtenir les produits récents
     */
    public function getRecentProduits(int $limit = 8): Collection
    {
        return $this->model->disponibles()
            ->actifs()
            ->with('artisan')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Obtenir les produits populaires (par nombre de vues ou commandes)
     */
    public function getPopularProduits(int $limit = 8): Collection
    {
        return $this->model->disponibles()
            ->actifs()
            ->with('artisan')
            ->orderBy('created_at', 'desc') // À remplacer par un vrai algorithme de popularité
            ->limit($limit)
            ->get();
    }

    /**
     * Obtenir les statistiques des produits
     */
    public function getProduitsStats(): array
    {
        return [
            'total' => $this->model->count(),
            'disponibles' => $this->model->where('disponible', true)->count(),
            'publies' => $this->model->where('statut', 'publie')->count(),
            'brouillons' => $this->model->where('statut', 'brouillon')->count(),
            'avec_images' => $this->model->whereNotNull('image_principale')->count(),
        ];
    }

    /**
     * Obtenir les produits par statut
     */
    public function getProduitsByStatus(string $status): Collection
    {
        return $this->model->where('statut', $status)
            ->with('artisan')
            ->get();
    }

    /**
     * Vérifier si un produit existe
     */
    public function produitExists(int $id): bool
    {
        return $this->model->where('id', $id)->exists();
    }

    /**
     * Obtenir les produits avec leurs images
     */
    public function getProduitsWithImages(): Collection
    {
        return $this->model->disponibles()
            ->actifs()
            ->whereNotNull('image_principale')
            ->with('artisan')
            ->get();
    }
}
