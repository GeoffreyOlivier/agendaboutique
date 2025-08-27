<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\Services\ShopServiceInterface;
use App\Contracts\Services\ProductServiceInterface;
use App\Services\Shop\ShopService;
use App\Services\Product\ProductService;
use App\Services\User\UserRoleService;
use App\Services\craftsman\ArtisanService;
use App\Services\ShopImageService;
use App\Services\ProductImageService;
use App\Services\ArtisanImageService;
use App\Repositories\ShopRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ArtisanRepository;

class ServiceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Services principaux
        $this->app->singleton(UserRoleService::class);
        $this->app->singleton(ArtisanService::class);
        
        // Services avec interfaces
        $this->app->singleton(ShopServiceInterface::class, ShopService::class);
        $this->app->singleton(ProductServiceInterface::class, ProductService::class);
        
        // Services principaux avec leurs dépendances
        $this->app->singleton(ShopService::class);
        $this->app->singleton(ProductService::class);
        $this->app->singleton(ArtisanService::class);
        
        // Services d'images
        $this->app->singleton(ShopImageService::class);
        $this->app->singleton(ProductImageService::class);
        $this->app->singleton(ArtisanImageService::class);

        // Repositories
        $this->app->singleton(ShopRepository::class);
        $this->app->singleton(ProductRepository::class);
        $this->app->singleton(ArtisanRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
