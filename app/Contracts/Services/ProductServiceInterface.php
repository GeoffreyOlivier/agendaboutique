<?php

namespace App\Contracts\Services;

use App\Models\Product;

interface ProductServiceInterface
{
    /**
     * Create a new product
     */
    public function createProduct(array $data): Product;

    /**
     * Update a product
     */
    public function updateProduct(Product $product, array $data): Product;

    /**
     * Delete a product
     */
    public function deleteProduct(Product $product): bool;
}
