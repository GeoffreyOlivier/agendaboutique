<?php

namespace App\Contracts\Repositories;

use App\Models\Craftsman;
use Illuminate\Database\Eloquent\Collection;

interface CraftsmanRepositoryInterface extends RepositoryInterface
{
    /**
     * Create a new craftsman
     */
    public function create(array $data): Craftsman;

    /**
     * Update a craftsman
     */
    public function update(\Illuminate\Database\Eloquent\Model $craftsman, array $data): bool;

    /**
     * Delete a craftsman
     */
    public function delete(\Illuminate\Database\Eloquent\Model $craftsman): bool;

    /**
     * Find a craftsman by ID
     */
    public function find(int $id): ?Craftsman;

    /**
     * Find a craftsman by user ID
     */
    public function findByUserId(int $userId): ?Craftsman;

    /**
     * Get approved craftsmen
     */
    public function getApprovedCraftsmen(): Collection;

    /**
     * Get craftsmen by specialty
     */
    public function getCraftsmenBySpecialty(string $specialty): Collection;

    /**
     * Get pending craftsmen
     */
    public function getPendingCraftsmen(): Collection;

    /**
     * Search craftsmen by criteria
     */
    public function searchCraftsmen(array $criteria): Collection;
}
