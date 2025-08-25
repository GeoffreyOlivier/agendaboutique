<?php

namespace App\Contracts\Services;

use App\Models\Craftsman;
use App\Models\User;

interface CraftsmanServiceInterface
{
    /**
     * Create a new craftsman profile
     */
    public function createCraftsman(array $data, User $user): Craftsman;

    /**
     * Update a craftsman profile
     */
    public function updateCraftsman(Craftsman $craftsman, array $data): Craftsman;

    /**
     * Delete a craftsman profile
     */
    public function deleteCraftsman(Craftsman $craftsman): bool;

    /**
     * Approve a craftsman
     */
    public function approveCraftsman(Craftsman $craftsman): bool;

    /**
     * Reject a craftsman
     */
    public function rejectCraftsman(Craftsman $craftsman, string $reason = null): bool;

    /**
     * Toggle craftsman status
     */
    public function toggleCraftsmanStatus(Craftsman $craftsman): bool;

    /**
     * Get approved craftsmen
     */
    public function getApprovedCraftsmen();

    /**
     * Get craftsmen by specialty
     */
    public function getCraftsmanBySpecialty(string $specialty);

    /**
     * Get pending craftsmen
     */
    public function getPendingCraftsmen();

    /**
     * Check if user can modify craftsman profile
     */
    public function canUserModifyCraftsman(User $user, Craftsman $craftsman): bool;

    /**
     * Get craftsman statistics
     */
    public function getCraftsmanStats(Craftsman $craftsman): array;

    /**
     * Search craftsmen
     */
    public function searchCraftsmen(array $criteria);
}
