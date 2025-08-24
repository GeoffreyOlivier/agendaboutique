<?php

namespace App\Services\Boutique;

use App\Models\Boutique;
use App\Models\User;
use App\Services\BoutiqueImageService;
use App\Services\ValidationService;
use App\Repositories\BoutiqueRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BoutiqueService
{
    protected BoutiqueImageService $imageService;
    protected ValidationService $validationService;
    protected BoutiqueRepository $boutiqueRepository;

    public function __construct(
        BoutiqueImageService $imageService, 
        ValidationService $validationService,
        BoutiqueRepository $boutiqueRepository
    ) {
        $this->imageService = $imageService;
        $this->validationService = $validationService;
        $this->boutiqueRepository = $boutiqueRepository;
    }

    /**
     * Créer une nouvelle boutique
     */
    public function createBoutique(array $data, User $user): Boutique
    {
        try {
            DB::beginTransaction();

            // Validation des données
            $data = $this->validationService->validateSocialUrls($data);
            $data = $this->validationService->validateGeographicData($data);
            $data = $this->validationService->validateContactInfo($data);
            $data = $this->validationService->validateFinancialInfo($data);

            $boutiqueData = [
                'user_id' => $user->id,
                'nom' => $data['nom'],
                'description' => $data['description'] ?? null,
                'adresse' => $data['adresse'] ?? null,
                'ville' => $data['ville'] ?? null,
                'code_postal' => $data['code_postal'] ?? null,
                'pays' => $data['pays'] ?? 'France',
                'telephone' => $data['telephone'] ?? null,
                'email' => $data['email'] ?? $user->email,
                'taille' => $data['taille'] ?? 'moyenne',
                'siret' => $data['siret'] ?? null,
                'tva' => $data['tva'] ?? null,
                'loyer_depot_vente' => $data['loyer_depot_vente'] ?? null,
                'loyer_permanence' => $data['loyer_permanence'] ?? null,
                'commission_depot_vente' => $data['commission_depot_vente'] ?? null,
                'commission_permanence' => $data['commission_permanence'] ?? null,
                'nb_permanences_mois_indicatif' => $data['nb_permanences_mois_indicatif'] ?? null,
                'site_web' => $data['site_web'] ?? null,
                'instagram_url' => $data['instagram_url'] ?? null,
                'tiktok_url' => $data['tiktok_url'] ?? null,
                'facebook_url' => $data['facebook_url'] ?? null,
                'horaires_ouverture' => $data['horaires_ouverture'] ?? null,
                'statut' => 'en_attente',
                'actif' => true,
            ];

            $boutique = $this->boutiqueRepository->create($boutiqueData);

            DB::commit();
            Log::info("Boutique créée avec succès pour l'utilisateur {$user->id}");

            return $boutique;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur lors de la création de la boutique pour l'utilisateur {$user->id}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Mettre à jour une boutique
     */
    public function updateBoutique(Boutique $boutique, array $data): Boutique
    {
        try {
            DB::beginTransaction();

            // Gérer la photo si elle est fournie
            if (isset($data['photo']) && $data['photo']) {
                $photoData = $this->imageService->updatePhoto(
                    $data['photo'], 
                    $boutique->id, 
                    $boutique->photo
                );
                $data['photo'] = $photoData['path'];
            }

            $this->boutiqueRepository->update($boutique, $data);

            DB::commit();
            Log::info("Boutique {$boutique->id} mise à jour avec succès");

            return $boutique;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur lors de la mise à jour de la boutique {$boutique->id}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Supprimer une boutique
     */
    public function deleteBoutique(Boutique $boutique): bool
    {
        try {
            DB::beginTransaction();

            // Supprimer la photo si elle existe
            if ($boutique->photo) {
                $this->imageService->deletePhoto($boutique->photo);
            }

            // Supprimer les relations avec les artisans
            $boutique->artisans()->detach();

            // Supprimer la boutique via le repository
            $this->boutiqueRepository->delete($boutique);

            DB::commit();
            Log::info("Boutique {$boutique->id} supprimée avec succès");

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur lors de la suppression de la boutique {$boutique->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Approuver une boutique
     */
    public function approveBoutique(Boutique $boutique): bool
    {
        try {
            $this->boutiqueRepository->update($boutique, ['statut' => 'approuve']);
            Log::info("Boutique {$boutique->id} approuvée");
            return true;
        } catch (\Exception $e) {
            Log::error("Erreur lors de l'approbation de la boutique {$boutique->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Rejeter une boutique
     */
    public function rejectBoutique(Boutique $boutique, string $raison = null): bool
    {
        try {
            $this->boutiqueRepository->update($boutique, [
                'statut' => 'rejete',
                'raison_rejet' => $raison
            ]);
            Log::info("Boutique {$boutique->id} rejetée: {$raison}");
            return true;
        } catch (\Exception $e) {
            Log::error("Erreur lors du rejet de la boutique {$boutique->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Activer/Désactiver une boutique
     */
    public function toggleBoutiqueStatus(Boutique $boutique): bool
    {
        try {
            $this->boutiqueRepository->update($boutique, ['actif' => !$boutique->actif]);
            $status = $boutique->actif ? 'activée' : 'désactivée';
            Log::info("Boutique {$boutique->id} {$status}");
            return true;
        } catch (\Exception $e) {
            Log::error("Erreur lors du changement de statut de la boutique {$boutique->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtenir les boutiques approuvées
     */
    public function getApprovedBoutiques()
    {
        return $this->boutiqueRepository->getApprovedBoutiques();
    }

    /**
     * Obtenir les boutiques en attente d'approbation
     */
    public function getPendingBoutiques()
    {
        return $this->boutiqueRepository->getPendingBoutiques();
    }

    /**
     * Vérifier si un utilisateur peut modifier une boutique
     */
    public function canUserModifyBoutique(User $user, Boutique $boutique): bool
    {
        return $user->id === $boutique->user_id && $user->hasRole('shop');
    }

    /**
     * Obtenir les statistiques d'une boutique
     */
    public function getBoutiqueStats(Boutique $boutique): array
    {
        return [
            'nombre_artisans' => $boutique->artisans()->count(),
            'nombre_produits' => $boutique->artisans()->withCount('produits')->get()->sum('produits_count'),
            'statut' => $boutique->statut,
            'actif' => $boutique->actif,
        ];
    }

    /**
     * Rechercher des boutiques
     */
    public function searchBoutiques(array $criteria)
    {
        return $this->boutiqueRepository->searchBoutiques($criteria);
    }

    /**
     * Obtenir les boutiques par ville
     */
    public function getBoutiquesByCity(string $city)
    {
        return $this->boutiqueRepository->getBoutiquesByCity($city);
    }

    /**
     * Obtenir les boutiques par taille
     */
    public function getBoutiquesBySize(string $size)
    {
        return $this->boutiqueRepository->getBoutiquesBySize($size);
    }
}
