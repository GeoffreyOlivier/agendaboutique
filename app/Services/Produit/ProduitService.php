<?php

namespace App\Services\Produit;

use App\Models\Produit;
use App\Models\Artisan;
use App\Services\Produit\ProduitImageService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProduitService
{
    protected ProduitImageService $imageService;

    public function __construct(ProduitImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Créer un nouveau produit
     */
    public function createProduit(array $data, Artisan $artisan): Produit
    {
        try {
            DB::beginTransaction();

            // Gérer les images si elles sont fournies
            $images = [];
            if (isset($data['images']) && is_array($data['images'])) {
                foreach ($data['images'] as $image) {
                    $imageData = $this->imageService->storeImage($image, $artisan->id);
                    $images[] = $imageData['path'];
                }
            }

            // Gérer l'image principale
            $imagePrincipale = null;
            if (isset($data['image_principale']) && $data['image_principale']) {
                $imageData = $this->imageService->storeImage($data['image_principale'], $artisan->id);
                $imagePrincipale = $imageData['path'];
            } elseif (!empty($images)) {
                $imagePrincipale = $images[0];
            }

            $produit = Produit::create([
                'artisan_id' => $artisan->id,
                'nom' => $data['nom'],
                'description' => $data['description'] ?? null,
                'prix_base' => $data['prix_base'] ?? null,
                'prix_min' => $data['prix_min'] ?? null,
                'prix_max' => $data['prix_max'] ?? null,
                'prix_masque' => $data['prix_masque'] ?? false,
                'categorie' => $data['categorie'] ?? null,
                'tags' => $data['tags'] ?? [],
                'images' => $images,
                'image_principale' => $imagePrincipale,
                'matiere' => $data['matiere'] ?? null,
                'dimensions' => $data['dimensions'] ?? [],
                'instructions_entretien' => $data['instructions_entretien'] ?? null,
                'statut' => 'brouillon',
                'disponible' => $data['disponible'] ?? true,
                'stock' => $data['stock'] ?? null,
                'reference' => $data['reference'] ?? $this->generateReference($artisan),
                'duree_fabrication' => $data['duree_fabrication'] ?? null,
            ]);

            DB::commit();
            Log::info("Produit créé avec succès pour l'artisan {$artisan->id}");

            return $produit;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur lors de la création du produit pour l'artisan {$artisan->id}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Mettre à jour un produit
     */
    public function updateProduit(Produit $produit, array $data): Produit
    {
        try {
            DB::beginTransaction();

            // Gérer les nouvelles images
            if (isset($data['images']) && is_array($data['images'])) {
                $oldImages = $produit->images ?? [];
                
                // Supprimer les anciennes images
                foreach ($oldImages as $oldImage) {
                    $this->imageService->deleteImage($oldImage);
                }

                // Stocker les nouvelles images
                $newImages = [];
                foreach ($data['images'] as $image) {
                    $imageData = $this->imageService->storeImage($image, $produit->artisan_id);
                    $newImages[] = $imageData['path'];
                }
                $data['images'] = $newImages;
            }

            // Gérer l'image principale
            if (isset($data['image_principale']) && $data['image_principale']) {
                if ($produit->image_principale && $produit->image_principale !== $data['image_principale']) {
                    $this->imageService->deleteImage($produit->image_principale);
                }
                
                $imageData = $this->imageService->storeImage($data['image_principale'], $produit->artisan_id);
                $data['image_principale'] = $imageData['path'];
            }

            $produit->update($data);

            DB::commit();
            Log::info("Produit {$produit->id} mis à jour avec succès");

            return $produit;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur lors de la mise à jour du produit {$produit->id}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Supprimer un produit
     */
    public function deleteProduit(Produit $produit): bool
    {
        try {
            DB::beginTransaction();

            // Supprimer toutes les images
            if ($produit->images && is_array($produit->images)) {
                foreach ($produit->images as $image) {
                    $this->imageService->deleteImage($image);
                }
            }

            if ($produit->image_principale) {
                $this->imageService->deleteImage($produit->image_principale);
            }

            $produit->delete();

            DB::commit();
            Log::info("Produit {$produit->id} supprimé avec succès");

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur lors de la suppression du produit {$produit->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Publier un produit
     */
    public function publishProduit(Produit $produit): bool
    {
        try {
            $produit->update(['statut' => 'publie']);
            Log::info("Produit {$produit->id} publié");
            return true;
        } catch (\Exception $e) {
            Log::error("Erreur lors de la publication du produit {$produit->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Mettre en brouillon un produit
     */
    public function draftProduit(Produit $produit): bool
    {
        try {
            $produit->update(['statut' => 'brouillon']);
            Log::info("Produit {$produit->id} mis en brouillon");
            return true;
        } catch (\Exception $e) {
            Log::error("Erreur lors de la mise en brouillon du produit {$produit->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtenir les produits disponibles
     */
    public function getAvailableProduits()
    {
        return Produit::disponibles()->actifs()->with('artisan')->get();
    }

    /**
     * Obtenir les produits par catégorie
     */
    public function getProduitsByCategory(string $category)
    {
        return Produit::parCategorie($category)->disponibles()->actifs()->with('artisan')->get();
    }

    /**
     * Obtenir les produits d'un artisan
     */
    public function getProduitsByArtisan(Artisan $artisan)
    {
        return $artisan->produits()->with('artisan')->get();
    }

    /**
     * Vérifier si un utilisateur peut modifier un produit
     */
    public function canUserModifyProduit(User $user, Produit $produit): bool
    {
        return $user->hasRole('artisan') && $user->artisan && $user->artisan->id === $produit->artisan_id;
    }

    /**
     * Générer une référence unique pour un produit
     */
    protected function generateReference(Artisan $artisan): string
    {
        $prefix = strtoupper(substr($artisan->nom ?? 'ART', 0, 3));
        $timestamp = now()->format('Ymd');
        $random = strtoupper(substr(md5(uniqid()), 0, 4));
        
        return "{$prefix}-{$timestamp}-{$random}";
    }

    /**
     * Obtenir les statistiques d'un produit
     */
    public function getProduitStats(Produit $produit): array
    {
        return [
            'statut' => $produit->statut,
            'disponible' => $produit->disponible,
            'nombre_images' => is_array($produit->images) ? count($produit->images) : 0,
            'a_image_principale' => !empty($produit->image_principale),
            'prix_formate' => $produit->prix_formate,
        ];
    }
}
