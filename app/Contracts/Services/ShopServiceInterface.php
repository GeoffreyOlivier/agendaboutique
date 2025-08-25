<?php

namespace App\Contracts\Services;

use App\Models\Shop;

interface ShopServiceInterface
{
    /**
     * Create a new shop
     */
    public function createShop(array $data): Shop;

    /**
     * Update a shop
     */
    public function updateShop(Shop $shop, array $data): Shop;

    /**
     * Delete a shop
     */
    public function deleteShop(Shop $shop): bool;
}
