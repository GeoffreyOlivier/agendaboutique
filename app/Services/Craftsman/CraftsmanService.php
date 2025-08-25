<?php

namespace App\Services\Craftsman;

use App\Models\Craftsman;
use App\Models\User;
use App\Services\CraftsmanImageService;
use App\Repositories\CraftsmanRepository;
use App\Contracts\Services\CraftsmanServiceInterface;
use App\Contracts\Repositories\CraftsmanRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CraftsmanService implements CraftsmanServiceInterface
{
    protected CraftsmanImageService $imageService;
    protected CraftsmanRepositoryInterface $craftsmanRepository;

    public function __construct(CraftsmanImageService $imageService, CraftsmanRepositoryInterface $craftsmanRepository)
    {
        $this->imageService = $imageService;
        $this->craftsmanRepository = $craftsmanRepository;
    }

    /**
     * Create a new craftsman profile
     */
    public function createCraftsman(array $data, User $user): Craftsman
    {
        try {
            DB::beginTransaction();

            // Handle avatar if provided
            $avatar = null;
            if (isset($data['avatar']) && $data['avatar']) {
                $avatarData = $this->imageService->storeAvatar($data['avatar'], $user->id);
                $avatar = $avatarData['path'];
            }

            $craftsmanData = [
                'user_id' => $user->id,
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'] ?? null,
                'description' => $data['description'] ?? null,
                'specialty' => $data['specialty'] ?? null,
                'experience_years' => $data['experience_years'] ?? null,
                'education' => $data['education'] ?? null,
                'certifications' => $data['certifications'] ?? [],
                'address' => $data['address'] ?? null,
                'city' => $data['city'] ?? null,
                'postal_code' => $data['postal_code'] ?? null,
                'country' => $data['country'] ?? 'France',
                'phone' => $data['phone'] ?? null,
                'email' => $data['email'] ?? $user->email,
                'website' => $data['website'] ?? null,
                'instagram_url' => $data['instagram_url'] ?? null,
                'tiktok_url' => $data['tiktok_url'] ?? null,
                'facebook_url' => $data['facebook_url'] ?? null,
                'linkedin_url' => $data['linkedin_url'] ?? null,
                'portfolio_url' => $data['portfolio_url'] ?? null,
                'hourly_rate' => $data['hourly_rate'] ?? null,
                'daily_rate' => $data['daily_rate'] ?? null,
                'availability' => $data['availability'] ?? 'available',
                'status' => 'pending',
                'active' => true,
                'avatar' => $avatar,
            ];

            $craftsman = $this->craftsmanRepository->create($craftsmanData);

            DB::commit();
            Log::info("Craftsman profile created successfully for user {$user->id}");

            return $craftsman;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error creating craftsman profile for user {$user->id}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update a craftsman profile
     */
    public function updateCraftsman(Craftsman $craftsman, array $data): Craftsman
    {
        try {
            DB::beginTransaction();

            // Handle avatar if provided
            if (isset($data['avatar']) && $data['avatar']) {
                $avatarData = $this->imageService->updateAvatar(
                    $data['avatar'], 
                    $craftsman->user_id, 
                    $craftsman->avatar
                );
                $data['avatar'] = $avatarData['path'];
            }

            $this->craftsmanRepository->update($craftsman, $data);

            DB::commit();
            Log::info("Craftsman profile {$craftsman->id} updated successfully");

            return $craftsman;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error updating craftsman profile {$craftsman->id}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete a craftsman profile
     */
    public function deleteCraftsman(Craftsman $craftsman): bool
    {
        try {
            DB::beginTransaction();

            // Delete avatar if it exists
            if ($craftsman->avatar) {
                $this->imageService->deleteAvatar($craftsman->avatar);
            }

            // Detach shop relationships
            $craftsman->shops()->detach();

            // Delete all products
            foreach ($craftsman->products as $product) {
                $product->delete();
            }

            // Delete craftsman profile via repository
            $this->craftsmanRepository->delete($craftsman);

            DB::commit();
            Log::info("Craftsman profile {$craftsman->id} deleted successfully");

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error deleting craftsman profile {$craftsman->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Approve a craftsman
     */
    public function approveCraftsman(Craftsman $craftsman): bool
    {
        try {
            $this->craftsmanRepository->update($craftsman, ['status' => 'approved']);
            Log::info("Craftsman {$craftsman->id} approved");
            return true;
        } catch (\Exception $e) {
            Log::error("Error approving craftsman {$craftsman->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Reject a craftsman
     */
    public function rejectCraftsman(Craftsman $craftsman, string $reason = null): bool
    {
        try {
            $this->craftsmanRepository->update($craftsman, [
                'status' => 'rejected',
                'rejection_reason' => $reason
            ]);
            Log::info("Craftsman {$craftsman->id} rejected: {$reason}");
            return true;
        } catch (\Exception $e) {
            Log::error("Error rejecting craftsman {$craftsman->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Toggle craftsman status
     */
    public function toggleCraftsmanStatus(Craftsman $craftsman): bool
    {
        try {
            $this->craftsmanRepository->update($craftsman, ['active' => !$craftsman->active]);
            $status = $craftsman->active ? 'activated' : 'deactivated';
            Log::info("Craftsman {$craftsman->id} {$status}");
            return true;
        } catch (\Exception $e) {
            Log::error("Error changing craftsman status {$craftsman->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get approved craftsmen
     */
    public function getApprovedCraftsmen()
    {
        return $this->craftsmanRepository->getApprovedCraftsmen();
    }

    /**
     * Get craftsmen by specialty
     */
    public function getCraftsmanBySpecialty(string $specialty)
    {
        return $this->craftsmanRepository->getCraftsmenBySpecialty($specialty);
    }

    /**
     * Get pending craftsmen
     */
    public function getPendingCraftsmen()
    {
        return $this->craftsmanRepository->getPendingCraftsmen();
    }

    /**
     * Check if user can modify craftsman profile
     */
    public function canUserModifyCraftsman(User $user, Craftsman $craftsman): bool
    {
        return $user->id === $craftsman->user_id && $user->hasRole('craftsman');
    }

    /**
     * Get craftsman statistics
     */
    public function getCraftsmanStats(Craftsman $craftsman): array
    {
        return [
            'products_count' => $craftsman->products()->count(),
            'shops_count' => $craftsman->shops()->count(),
            'status' => $craftsman->status,
            'active' => $craftsman->active,
            'availability' => $craftsman->availability,
        ];
    }

    /**
     * Search craftsmen
     */
    public function searchCraftsmen(array $criteria)
    {
        return $this->craftsmanRepository->searchCraftsmen($criteria);
    }
}
