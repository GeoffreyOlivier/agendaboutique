<?php

namespace App\Services\Artisan;

use App\Models\Artisan;
use App\Models\User;
use App\Services\ArtisanImageService;
use App\Repositories\ArtisanRepository;
use App\Contracts\Services\ArtisanServiceInterface;
use App\Contracts\Repositories\ArtisanRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ArtisanService implements ArtisanServiceInterface
{
    protected ArtisanImageService $imageService;
    protected ArtisanRepositoryInterface $artisanRepository;

    public function __construct(ArtisanImageService $imageService, ArtisanRepositoryInterface $artisanRepository)
    {
        $this->imageService = $imageService;
        $this->artisanRepository = $artisanRepository;
    }

    /**
     * Créer un nouveau profil artisan
     */
    public function createArtisan(array $data, User $user): Artisan
    {
        try {
            DB::beginTransaction();

            // Gérer l'avatar si fourni
            $avatar = null;
            if (isset($data['avatar']) && $data['avatar']) {
                $avatarData = $this->imageService->storeAvatar($data['avatar'], $user->id);
                $avatar = $avatarData['path'];
            }

            $artisanData = [
                'user_id' => $user->id,
                'nom' => $data['nom'],
                'prenom' => $data['prenom'] ?? null,
                'description' => $data['description'] ?? null,
                'specialite' => $data['specialite'] ?? null,
                'experience_annees' => $data['experience_annees'] ?? null,
                'formation' => $data['formation'] ?? null,
                'certifications' => $data['certifications'] ?? [],
                'adresse' => $data['adresse'] ?? null,
                'ville' => $data['ville'] ?? null,
                'code_postal' => $data['code_postal'] ?? null,
                'pays' => $data['pays'] ?? 'France',
                'telephone' => $data['telephone'] ?? null,
                'email' => $data['email'] ?? $user->email,
                'site_web' => $data['site_web'] ?? null,
                'instagram_url' => $data['instagram_url'] ?? null,
                'tiktok_url' => $data['tiktok_url'] ?? null,
                'facebook_url' => $data['facebook_url'] ?? null,
                'linkedin_url' => $data['linkedin_url'] ?? null,
                'portfolio_url' => $data['portfolio_url'] ?? null,
                'tarif_horaire' => $data['tarif_horaire'] ?? null,
                'tarif_jour' => $data['tarif_jour'] ?? null,
                'disponibilite' => $data['disponibilite'] ?? 'disponible',
                'statut' => 'en_attente',
                'actif' => true,
                'avatar' => $avatar,
            ];

            $artisan = $this->artisanRepository->create($artisanData);

            DB::commit();
            Log::info("Profil artisan créé avec succès pour l'utilisateur {$user->id}");

            return $artisan;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur lors de la création du profil artisan pour l'utilisateur {$user->id}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Mettre à jour un profil artisan
     */
    public function updateArtisan(Artisan $artisan, array $data): Artisan
    {
        try {
            DB::beginTransaction();

            // Gérer l'avatar si fourni
            if (isset($data['avatar']) && $data['avatar']) {
                $avatarData = $this->imageService->updateAvatar(
                    $data['avatar'], 
                    $artisan->user_id, 
                    $artisan->avatar
                );
                $data['avatar'] = $avatarData['path'];
            }

            $this->artisanRepository->update($artisan, $data);

            DB::commit();
            Log::info("Profil artisan {$artisan->id} mis à jour avec succès");

            return $artisan;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur lors de la mise à jour du profil artisan {$artisan->id}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Supprimer un profil artisan
     */
    public function deleteArtisan(Artisan $artisan): bool
    {
        try {
            DB::beginTransaction();

            // Supprimer l'avatar si il existe
            if ($artisan->avatar) {
                $this->imageService->deleteAvatar($artisan->avatar);
            }

            // Supprimer les relations avec les boutiques
            $artisan->boutiques()->detach();

            // Supprimer tous les produits
            foreach ($artisan->produits as $produit) {
                $produit->delete();
            }

            // Supprimer le profil artisan via le repository
            $this->artisanRepository->delete($artisan);

            DB::commit();
            Log::info("Profil artisan {$artisan->id} supprimé avec succès");

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur lors de la suppression du profil artisan {$artisan->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Approuver un artisan
     */
    public function approveArtisan(Artisan $artisan): bool
    {
        try {
            $this->artisanRepository->update($artisan, ['statut' => 'approuve']);
            Log::info("Artisan {$artisan->id} approuvé");
            return true;
        } catch (\Exception $e) {
            Log::error("Erreur lors de l'approbation de l'artisan {$artisan->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Rejeter un artisan
     */
    public function rejectArtisan(Artisan $artisan, string $raison = null): bool
    {
        try {
            $this->artisanRepository->update($artisan, [
                'statut' => 'rejete',
                'raison_rejet' => $raison
            ]);
            Log::info("Artisan {$artisan->id} rejeté: {$raison}");
            return true;
        } catch (\Exception $e) {
            Log::error("Erreur lors du rejet de l'artisan {$artisan->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Activer/Désactiver un artisan
     */
    public function toggleArtisanStatus(Artisan $artisan): bool
    {
        try {
            $this->artisanRepository->update($artisan, ['actif' => !$artisan->actif]);
            $status = $artisan->actif ? 'activé' : 'désactivé';
            Log::info("Artisan {$artisan->id} {$status}");
            return true;
        } catch (\Exception $e) {
            Log::error("Erreur lors du changement de statut de l'artisan {$artisan->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtenir les artisans approuvés
     */
    public function getApprovedArtisans()
    {
        return $this->artisanRepository->getApprovedArtisans();
    }

    /**
     * Obtenir les artisans par spécialité
     */
    public function getArtisanBySpeciality(string $speciality)
    {
        return $this->artisanRepository->getArtisansBySpeciality($speciality);
    }

    /**
     * Obtenir les artisans en attente d'approbation
     */
    public function getPendingArtisans()
    {
        return $this->artisanRepository->getPendingArtisans();
    }

    /**
     * Vérifier si un utilisateur peut modifier un profil artisan
     */
    public function canUserModifyArtisan(User $user, Artisan $artisan): bool
    {
        return $user->id === $artisan->user_id && $user->hasRole('artisan');
    }

    /**
     * Obtenir les statistiques d'un artisan
     */
    public function getArtisanStats(Artisan $artisan): array
    {
        return [
            'nombre_produits' => $artisan->produits()->count(),
            'nombre_boutiques' => $artisan->boutiques()->count(),
            'statut' => $artisan->statut,
            'actif' => $artisan->actif,
            'disponibilite' => $artisan->disponibilite,
        ];
    }

    /**
     * Rechercher des artisans
     */
    public function searchArtisans(array $criteria)
    {
        return $this->artisanRepository->searchArtisans($criteria);
    }
}
