<?php

namespace App\Services\Shop;

use App\Models\Shop;
use App\Models\User;
use App\Services\ShopImageService;
use App\Services\ValidationService;
use App\Repositories\ShopRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ShopService
{
    protected ShopImageService $imageService;
    protected ValidationService $validationService;
    protected ShopRepository $shopRepository;

    public function __construct(
        ShopImageService $imageService, 
        ValidationService $validationService,
        ShopRepository $shopRepository
    ) {
        $this->imageService = $imageService;
        $this->validationService = $validationService;
        $this->shopRepository = $shopRepository;
    }

    /**
     * Créer une nouvelle boutique
     */
    public function createShop(array $data, User $user): Shop
    {
        try {
            DB::beginTransaction();

            // Validation des données
            $data = $this->validationService->validateSocialUrls($data);
            $data = $this->validationService->validateGeographicData($data);
            $data = $this->validationService->validateContactInfo($data);
            $data = $this->validationService->validateFinancialInfo($data);

            $shopData = [
                'user_id' => $user->id,
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'address' => $data['address'] ?? null,
                'city' => $data['city'] ?? null,
                'postal_code' => $data['postal_code'] ?? null,
                'country' => $data['country'] ?? 'France',
                'phone' => $data['phone'] ?? null,
                'email' => $data['email'] ?? $user->email,
                'size' => $data['size'] ?? 'medium',
                'siret' => $data['siret'] ?? null,
                'vat_number' => $data['vat_number'] ?? null,
                'deposit_sale_rent' => $data['deposit_sale_rent'] ?? null,
                'permanent_rent' => $data['permanent_rent'] ?? null,
                'deposit_sale_commission' => $data['deposit_sale_commission'] ?? null,
                'permanent_commission' => $data['permanent_commission'] ?? null,
                'monthly_permanences' => $data['monthly_permanences'] ?? null,
                'website' => $data['website'] ?? null,
                'instagram_url' => $data['instagram_url'] ?? null,
                'tiktok_url' => $data['tiktok_url'] ?? null,
                'facebook_url' => $data['facebook_url'] ?? null,
                'opening_hours' => $data['opening_hours'] ?? null,
                'status' => 'pending',
                'active' => true,
            ];

            $shop = $this->shopRepository->create($shopData);

            DB::commit();
            Log::info("Shop créé avec succès pour l'utilisateur {$user->id}");

            return $shop;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur lors de la création de la boutique pour l'utilisateur {$user->id}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Mettre à jour une boutique
     */
    public function updateShop(Shop $shop, array $data): Shop
    {
        try {
            DB::beginTransaction();

            // Gérer la photo si elle est fournie
            if (isset($data['photo']) && $data['photo']) {
                $photoData = $this->imageService->updatePhoto(
                    $data['photo'], 
                    $shop->id, 
                    $shop->photo
                );
                $data['photo'] = $photoData['path'];
            }

            $this->shopRepository->update($shop, $data);

            DB::commit();
            Log::info("Shop {$shop->id} mise à jour avec succès");

            return $shop;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur lors de la mise à jour de la boutique {$shop->id}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Supprimer une boutique
     */
    public function deleteShop(Shop $shop): bool
    {
        try {
            DB::beginTransaction();

            // Supprimer la photo si elle existe
            if ($shop->photo) {
                $this->imageService->deletePhoto($shop->photo);
            }

            // Supprimer les relations avec les artisans
            $shop->craftsmen()->detach();

            // Supprimer la boutique via le repository
            $this->shopRepository->delete($shop);

            DB::commit();
            Log::info("Shop {$shop->id} supprimée avec succès");

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur lors de la suppression de la boutique {$shop->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Approuver une boutique
     */
    public function approveShop(Shop $shop): bool
    {
        try {
            $this->shopRepository->update($shop, ['status' => 'approved']);
            Log::info("Shop {$shop->id} approuvée");
            return true;
        } catch (\Exception $e) {
            Log::error("Erreur lors de l'approbation de la boutique {$shop->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Rejeter une boutique
     */
    public function rejectShop(Shop $shop, string $reason = null): bool
    {
        try {
            $this->shopRepository->update($shop, [
                'status' => 'rejected',
                'rejection_reason' => $reason
            ]);
            Log::info("Shop {$shop->id} rejetée: {$reason}");
            return true;
        } catch (\Exception $e) {
            Log::error("Erreur lors du rejet de la boutique {$shop->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Activer/Désactiver une boutique
     */
    public function toggleShopStatus(Shop $shop): bool
    {
        try {
            $this->shopRepository->update($shop, ['active' => !$shop->active]);
            $status = $shop->active ? 'activée' : 'désactivée';
            Log::info("Shop {$shop->id} {$status}");
            return true;
        } catch (\Exception $e) {
            Log::error("Erreur lors du changement de statut de la boutique {$shop->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtenir les boutiques approuvées
     */
    public function getApprovedShops()
    {
        return $this->shopRepository->getApprovedShops();
    }

    /**
     * Obtenir les boutiques en attente d'approbation
     */
    public function getPendingShops()
    {
        return $this->shopRepository->getPendingShops();
    }

    /**
     * Vérifier si un utilisateur peut modifier une boutique
     */
    public function canUserModifyShop(User $user, Shop $shop): bool
    {
        return $user->id === $shop->user_id && $user->hasRole('shop');
    }

    /**
     * Obtenir les statistiques d'une boutique
     */
    public function getShopStats(Shop $shop): array
    {
        return [
            'nombre_artisans' => $shop->craftsmen()->count(),
            'nombre_produits' => $shop->craftsmen()->withCount('products')->get()->sum('products_count'),
            'status' => $shop->status,
            'active' => $shop->active,
        ];
    }

    /**
     * Rechercher des boutiques
     */
    public function searchShops(array $criteria)
    {
        return $this->shopRepository->searchShops($criteria);
    }

    /**
     * Obtenir les boutiques par ville
     */
    public function getShopsByCity(string $city)
    {
        return $this->shopRepository->getShopsByCity($city);
    }

    /**
     * Obtenir les boutiques par taille
     */
    public function getShopsBySize(string $size)
    {
        return $this->shopRepository->getShopsBySize($size);
    }
}
