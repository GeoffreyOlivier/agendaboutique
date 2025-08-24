<?php

namespace App\Repositories;

use App\Contracts\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected Model $model;
    protected Builder $query;

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->resetQuery();
    }

    /**
     * Trouver un modèle par son ID
     */
    public function find(int $id): ?Model
    {
        return $this->model->find($id);
    }

    /**
     * Trouver un modèle par son ID ou lever une exception
     */
    public function findOrFail(int $id): Model
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Trouver un modèle par un critère
     */
    public function findBy(string $field, mixed $value): ?Model
    {
        return $this->model->where($field, $value)->first();
    }

    /**
     * Trouver tous les modèles
     */
    public function all(): Collection
    {
        return $this->query->get();
    }

    /**
     * Créer un nouveau modèle
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * Mettre à jour un modèle
     */
    public function update(Model $model, array $data): bool
    {
        return $model->update($data);
    }

    /**
     * Supprimer un modèle
     */
    public function delete(Model $model): bool
    {
        return $model->delete();
    }

    /**
     * Paginer les résultats
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->query->paginate($perPage);
    }

    /**
     * Compter le nombre total d'enregistrements
     */
    public function count(): int
    {
        return $this->query->count();
    }

    /**
     * Vérifier si un enregistrement existe
     */
    public function exists(int $id): bool
    {
        return $this->model->where('id', $id)->exists();
    }

    /**
     * Obtenir les modèles avec des relations
     */
    public function with(array $relations): self
    {
        $this->query->with($relations);
        return $this;
    }

    /**
     * Appliquer des conditions WHERE
     */
    public function where(string $field, mixed $value): self
    {
        $this->query->where($field, $value);
        return $this;
    }

    /**
     * Appliquer des conditions WHERE avec opérateur
     */
    public function whereOperator(string $field, string $operator, mixed $value): self
    {
        $this->query->where($field, $operator, $value);
        return $this;
    }

    /**
     * Appliquer des conditions WHERE IN
     */
    public function whereIn(string $field, array $values): self
    {
        $this->query->whereIn($field, $values);
        return $this;
    }

    /**
     * Appliquer des conditions WHERE BETWEEN
     */
    public function whereBetween(string $field, array $values): self
    {
        $this->query->whereBetween($field, $values);
        return $this;
    }

    /**
     * Trier les résultats
     */
    public function orderBy(string $field, string $direction = 'asc'): self
    {
        $this->query->orderBy($field, $direction);
        return $this;
    }

    /**
     * Limiter le nombre de résultats
     */
    public function limit(int $limit): self
    {
        $this->query->limit($limit);
        return $this;
    }

    /**
     * Réinitialiser la requête
     */
    public function resetQuery(): self
    {
        $this->query = $this->model->newQuery();
        return $this;
    }

    /**
     * Obtenir la requête builder
     */
    public function getQuery(): Builder
    {
        return $this->query;
    }

    /**
     * Exécuter la requête et obtenir les résultats
     */
    public function get(): Collection
    {
        return $this->query->get();
    }

    /**
     * Obtenir le premier résultat
     */
    public function first(): ?Model
    {
        return $this->query->first();
    }

    /**
     * Obtenir le premier résultat ou lever une exception
     */
    public function firstOrFail(): Model
    {
        return $this->query->firstOrFail();
    }
}
