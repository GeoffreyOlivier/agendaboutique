<?php

namespace App\Services\Product;

use App\Models\Product;
use App\Models\Craftsman;
use App\Models\User;
use App\Services\ProductImageService;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductService
{
    protected ProductImageService $imageService;
    protected ProductRepository $productRepository;

    public function __construct(ProductImageService $imageService, ProductRepository $productRepository)
    {
        $this->imageService = $imageService;
        $this->productRepository = $productRepository;
    }

    /**
     * Créer un nouveau produit
     */
    public function createProduct(array $data, Craftsman $craftsman): Product
    {
        try {
            DB::beginTransaction();

            // Gérer les images si elles sont fournies
            $images = [];
            if (isset($data['images']) && is_array($data['images'])) {
                foreach ($data['images'] as $image) {
                    $imageData = $this->imageService->storeImage($image, $craftsman->id);
                    $images[] = $imageData['path'];
                }
            }

            // Gérer l'image principale
            $mainImage = null;
            if (isset($data['main_image']) && $data['main_image']) {
                $imageData = $this->imageService->storeImage($data['main_image'], $craftsman->id);
                $mainImage = $imageData['path'];
            } elseif (!empty($images)) {
                $mainImage = $images[0];
            }

            $productData = [
                'craftsman_id' => $craftsman->id,
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'base_price' => $data['base_price'] ?? null,
                'min_price' => $data['min_price'] ?? null,
                'max_price' => $data['max_price'] ?? null,
                'price_hidden' => $data['price_hidden'] ?? false,
                'category' => $data['category'] ?? null,
                'tags' => $data['tags'] ?? [],
                'images' => $images,
                'main_image' => $mainImage,
                'material' => $data['material'] ?? null,
                'dimensions' => $data['dimensions'] ?? [],
                'care_instructions' => $data['care_instructions'] ?? null,
                'status' => 'draft',
                'available' => $data['available'] ?? true,
                'stock' => $data['stock'] ?? null,
                'reference' => $data['reference'] ?? $this->generateReference($craftsman),
                'production_time' => $data['production_time'] ?? null,
            ];

            $product = $this->productRepository->create($productData);

            DB::commit();
            Log::info("Product créé avec succès pour l'artisan {$craftsman->id}");

            return $product;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur lors de la création du produit pour l'artisan {$craftsman->id}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Mettre à jour un produit
     */
    public function updateProduct(Product $product, array $data): Product
    {
        try {
            DB::beginTransaction();

            // Gérer les nouvelles images
            if (isset($data['images']) && is_array($data['images'])) {
                $oldImages = $product->images ?? [];
                
                // Supprimer les anciennes images
                foreach ($oldImages as $oldImage) {
                    $this->imageService->deleteImage($oldImage);
                }

                // Stocker les nouvelles images
                $newImages = [];
                foreach ($data['images'] as $image) {
                    $imageData = $this->imageService->storeImage($image, $product->craftsman_id);
                    $newImages[] = $imageData['path'];
                }
                $data['images'] = $newImages;
            }

            // Gérer l'image principale
            if (isset($data['main_image']) && $data['main_image']) {
                if ($product->main_image && $product->main_image !== $data['main_image']) {
                    $this->imageService->deleteImage($product->main_image);
                }
                
                $imageData = $this->imageService->storeImage($data['main_image'], $product->craftsman_id);
                $data['main_image'] = $imageData['path'];
            }

            $this->productRepository->update($product, $data);

            DB::commit();
            Log::info("Product {$product->id} mis à jour avec succès");

            return $product;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur lors de la mise à jour du produit {$product->id}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Supprimer un produit
     */
    public function deleteProduct(Product $product): bool
    {
        try {
            DB::beginTransaction();

            // Supprimer toutes les images
            if ($product->images && is_array($product->images)) {
                foreach ($product->images as $image) {
                    $this->imageService->deleteImage($image);
                }
            }

            if ($product->main_image) {
                $this->imageService->deleteImage($product->main_image);
            }

            $this->productRepository->delete($product);

            DB::commit();
            Log::info("Product {$product->id} supprimé avec succès");

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur lors de la suppression du produit {$product->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Publier un produit
     */
    public function publishProduct(Product $product): bool
    {
        try {
            $this->productRepository->update($product, ['status' => 'published']);
            Log::info("Product {$product->id} publié");
            return true;
        } catch (\Exception $e) {
            Log::error("Erreur lors de la publication du produit {$product->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Mettre en brouillon un produit
     */
    public function draftProduct(Product $product): bool
    {
        try {
            $this->productRepository->update($product, ['status' => 'draft']);
            Log::info("Product {$product->id} mis en brouillon");
            return true;
        } catch (\Exception $e) {
            Log::error("Erreur lors de la mise en brouillon du produit {$product->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtenir les products disponibles
     */
    public function getAvailableProducts()
    {
        return $this->productRepository->getAvailableProducts();
    }

    /**
     * Obtenir les products par catégorie
     */
    public function getProductsByCategory(string $category)
    {
        return $this->productRepository->getProductsByCategory($category);
    }

    /**
     * Obtenir les products d'un artisan
     */
    public function getProductsByCraftsman(Craftsman $craftsman)
    {
        return $this->productRepository->getProductsByCraftsman($craftsman);
    }

    /**
     * Vérifier si un utilisateur peut modifier un produit
     */
    public function canUserModifyProduct(User $user, Product $product): bool
    {
        return $user->hasRole('craftsman') && $user->craftsman && $user->craftsman->id === $product->craftsman_id;
    }

    /**
     * Générer une référence unique pour un produit
     */
    protected function generateReference(Craftsman $craftsman): string
    {
        $prefix = strtoupper(substr($craftsman->name ?? 'CRA', 0, 3));
        $timestamp = now()->format('Ymd');
        $random = strtoupper(substr(md5(uniqid()), 0, 4));
        
        return "{$prefix}-{$timestamp}-{$random}";
    }

    /**
     * Obtenir les statistiques d'un produit
     */
    public function getProductStats(Product $product): array
    {
        return [
            'status' => $product->status,
            'available' => $product->available,
            'nombre_images' => is_array($product->images) ? count($product->images) : 0,
            'a_image_principale' => !empty($product->main_image),
            'prix_formate' => $product->formatted_price,
        ];
    }
}
