<?php

namespace App\Repositories;

use App\Contracts\Repositories\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseRepository implements RepositoryInterface
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Create a new model
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * Update a model
     */
    public function update(Model $model, array $data): bool
    {
        return $model->update($data);
    }

    /**
     * Delete a model
     */
    public function delete(Model $model): bool
    {
        return $model->delete();
    }

    /**
     * Find a model by ID
     */
    public function find(int $id): ?Model
    {
        return $this->model->find($id);
    }

    /**
     * Get all models
     */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * Paginate results
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->paginate($perPage);
    }

    /**
     * Count total records
     */
    public function count(): int
    {
        return $this->model->count();
    }

    /**
     * Check if a record exists
     */
    public function exists(int $id): bool
    {
        return $this->model->where('id', $id)->exists();
    }
}
