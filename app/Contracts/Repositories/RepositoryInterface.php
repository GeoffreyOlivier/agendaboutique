<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface RepositoryInterface
{
    /**
     * Create a new model
     */
    public function create(array $data): Model;

    /**
     * Update a model
     */
    public function update(Model $model, array $data): bool;

    /**
     * Delete a model
     */
    public function delete(Model $model): bool;

    /**
     * Find a model by ID
     */
    public function find(int $id): ?Model;

    /**
     * Get all models
     */
    public function all(): Collection;

    /**
     * Paginate results
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    /**
     * Count total records
     */
    public function count(): int;

    /**
     * Check if a record exists
     */
    public function exists(int $id): bool;
}
