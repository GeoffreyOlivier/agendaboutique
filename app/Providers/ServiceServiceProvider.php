<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\Services\BoutiqueServiceInterface;
use App\Contracts\Services\ProduitServiceInterface;
use App\Services\Boutique\BoutiqueService;
use App\Services\Produit\ProduitService;
use App\Services\User\UserRoleService;
use App\Services\Artisan\ArtisanService;
use App\Services\Boutique\BoutiqueImageService;
use App\Services\Produit\ProduitImageService;
use App\Services\Artisan\ArtisanImageService;

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
        $this->app->singleton(BoutiqueServiceInterface::class, BoutiqueService::class);
        $this->app->singleton(ProduitServiceInterface::class, ProduitService::class);
        
        // Services d'images
        $this->app->singleton(BoutiqueImageService::class);
        $this->app->singleton(ProduitImageService::class);
        $this->app->singleton(ArtisanImageService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
