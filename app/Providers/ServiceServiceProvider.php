<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\Services\BoutiqueServiceInterface;
use App\Contracts\Services\ProduitServiceInterface;
use App\Services\Boutique\BoutiqueService;
use App\Services\Produit\ProduitService;
use App\Services\User\UserRoleService;
use App\Services\Artisan\ArtisanService;
use App\Services\BoutiqueImageService;
use App\Services\ProduitImageService;
use App\Services\ArtisanImageService;
use App\Repositories\BoutiqueRepository;
use App\Repositories\ProduitRepository;
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
        $this->app->singleton(BoutiqueServiceInterface::class, BoutiqueService::class);
        $this->app->singleton(ProduitServiceInterface::class, ProduitService::class);
        
        // Services principaux avec leurs dépendances
        $this->app->singleton(BoutiqueService::class);
        $this->app->singleton(ProduitService::class);
        $this->app->singleton(ArtisanService::class);
        
        // Services d'images
        $this->app->singleton(BoutiqueImageService::class);
        $this->app->singleton(ProduitImageService::class);
        $this->app->singleton(ArtisanImageService::class);

        // Repositories
        $this->app->singleton(BoutiqueRepository::class);
        $this->app->singleton(ProduitRepository::class);
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
