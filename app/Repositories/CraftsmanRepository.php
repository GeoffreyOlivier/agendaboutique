<?php

namespace App\Repositories;

use App\Models\Craftsman;
use App\Contracts\Repositories\CraftsmanRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CraftsmanRepository implements CraftsmanRepositoryInterface
{
    protected Craftsman $model;

    public function __construct(Craftsman $model)
    {
        $this->model = $model;
    }

    /**
     * Create a new craftsman
     */
    public function create(array $data): Craftsman
    {
        return $this->model->create($data);
    }

    /**
     * Update a craftsman
     */
    public function update(Model $craftsman, array $data): bool
    {
        return $craftsman->update($data);
    }

    /**
     * Delete a craftsman
     */
    public function delete(Model $craftsman): bool
    {
        return $craftsman->delete();
    }

    /**
     * Find a craftsman by ID
     */
    public function find(int $id): ?Craftsman
    {
        return $this->model->find($id);
    }

    /**
     * Find a craftsman by user ID
     */
    public function findByUserId(int $userId): ?Craftsman
    {
        return $this->model->where('user_id', $userId)->first();
    }

    /**
     * Get all craftsmen
     */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * Paginate craftsmen
     */
    public function paginate(int $perPage = 15): \Illuminate\Pagination\LengthAwarePaginator
    {
        return $this->model->paginate($perPage);
    }

    /**
     * Count total craftsmen
     */
    public function count(): int
    {
        return $this->model->count();
    }

    /**
     * Check if craftsman exists
     */
    public function exists(int $id): bool
    {
        return $this->model->where('id', $id)->exists();
    }

    /**
     * Get approved craftsmen
     */
    public function getApprovedCraftsmen(): Collection
    {
        return $this->model->where('status', 'approved')->where('active', true)->get();
    }

    /**
     * Get craftsmen by specialty
     */
    public function getCraftsmenBySpecialty(string $specialty): Collection
    {
        return $this->model->where('specialty', $specialty)
                          ->where('status', 'approved')
                          ->where('active', true)
                          ->get();
    }

    /**
     * Get pending craftsmen
     */
    public function getPendingCraftsmen(): Collection
    {
        return $this->model->where('status', 'pending')->get();
    }

    /**
     * Search craftsmen by criteria
     */
    public function searchCraftsmen(array $criteria): Collection
    {
        $query = $this->model->query();

        if (isset($criteria['specialty'])) {
            $query->where('specialty', 'like', '%' . $criteria['specialty'] . '%');
        }

        if (isset($criteria['city'])) {
            $query->where('city', 'like', '%' . $criteria['city'] . '%');
        }

        if (isset($criteria['status'])) {
            $query->where('status', $criteria['status']);
        }

        if (isset($criteria['active'])) {
            $query->where('active', $criteria['active']);
        }

        if (isset($criteria['availability'])) {
            $query->where('availability', $criteria['availability']);
        }

        return $query->get();
    }
}
