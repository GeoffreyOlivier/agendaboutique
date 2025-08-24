<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface BaseRepositoryInterface
{
    /**
     * Trouver un modèle par son ID
     */
    public function find(int $id): ?Model;

    /**
     * Trouver un modèle par son ID ou lever une exception
     */
    public function findOrFail(int $id): Model;

    /**
     * Trouver un modèle par un critère
     */
    public function findBy(string $field, mixed $value): ?Model;

    /**
     * Trouver tous les modèles
     */
    public function all(): Collection;

    /**
     * Créer un nouveau modèle
     */
    public function create(array $data): Model;

    /**
     * Mettre à jour un modèle
     */
    public function update(Model $model, array $data): bool;

    /**
     * Supprimer un modèle
     */
    public function delete(Model $model): bool;

    /**
     * Paginer les résultats
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    /**
     * Compter le nombre total d'enregistrements
     */
    public function count(): int;

    /**
     * Vérifier si un enregistrement existe
     */
    public function exists(int $id): bool;

    /**
     * Obtenir les modèles avec des relations
     */
    public function with(array $relations): self;

    /**
     * Appliquer des conditions WHERE
     */
    public function where(string $field, mixed $value): self;

    /**
     * Appliquer des conditions WHERE avec opérateur
     */
    public function whereOperator(string $field, string $operator, mixed $value): self;

    /**
     * Appliquer des conditions WHERE IN
     */
    public function whereIn(string $field, array $values): self;

    /**
     * Appliquer des conditions WHERE BETWEEN
     */
    public function whereBetween(string $field, array $values): self;

    /**
     * Trier les résultats
     */
    public function orderBy(string $field, string $direction = 'asc'): self;

    /**
     * Limiter le nombre de résultats
     */
    public function limit(int $limit): self;

    /**
     * Réinitialiser la requête
     */
    public function resetQuery(): self;
}
