<?php

namespace App\Providers;

use App\Contracts\Services\ImageServiceInterface;
use App\Services\LocalImageService;
use App\Services\ImageValidationService;
use Illuminate\Support\ServiceProvider;

class ImageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(ImageServiceInterface::class, function ($app) {
            return new LocalImageService($app->make(ImageValidationService::class));
        });

        // Alias pour faciliter l'utilisation
        $this->app->alias(ImageServiceInterface::class, 'image.service');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
