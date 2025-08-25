<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface BaseRepositoryInterface
{
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
     * Trouver un modèle par ID
     */
    public function find(int $id): ?Model;

    /**
     * Obtenir tous les modèles
     */
    public function all(): Collection;

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
}
