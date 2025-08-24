<?php

namespace App\Contracts\Services;

use App\Models\Produit;
use App\Models\Artisan;

interface ProduitServiceInterface
{
    public function createProduit(array $data, Artisan $artisan): Produit;
    public function updateProduit(Produit $produit, array $data): Produit;
    public function deleteProduit(Produit $produit): bool;
    public function publishProduit(Produit $produit): bool;
    public function draftProduit(Produit $produit): bool;
    public function getAvailableProduits();
    public function getProduitsByCategory(string $category);
    public function getProduitsByArtisan(Artisan $artisan);
    public function canUserModifyProduit(User $user, Produit $produit): bool;
    public function getProduitStats(Produit $produit): array;
}
