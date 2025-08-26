<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InterfaceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductPublicController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\RoleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Routes publiques et protégées de l'application
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route publique pour afficher les produits
Route::get('/catalogue', [ProductPublicController::class, 'index'])->name('produits.public');

// Routes pour les interfaces selon les rôles
Route::middleware(['auth'])->group(function () {
    // Dashboard principal qui redirige selon le rôle
    Route::get('/dashboard', [InterfaceController::class, 'dashboard'])->name('dashboard');
    
    // Routes boutique (rôle 'shop')
    Route::middleware(['role:shop'])->prefix('shop')->name('shop.')->group(function () {
        Route::get('/artisans', [InterfaceController::class, 'shopArtisans'])->name('artisans');
        Route::get('/artisans/{artisan}', [InterfaceController::class, 'shopArtisanProfile'])->name('artisan.profile');
        Route::get('/create', [ShopController::class, 'create'])->name('create');
        Route::post('/store', [ShopController::class, 'store'])->name('store');
        Route::get('/{shop}/edit', [ShopController::class, 'edit'])->name('edit')->middleware('resource.owner:shop');
        Route::put('/{shop}', [ShopController::class, 'update'])->name('update')->middleware('resource.owner:shop');
    });
    
    // Routes artisan (rôle 'artisan')
    Route::middleware(['role:artisan'])->prefix('artisan')->name('artisan.')->group(function () {
        Route::get('/dashboard', [InterfaceController::class, 'artisanDashboard'])->name('dashboard');
    });
    
    // Interface par défaut
    Route::get('/default/dashboard', [InterfaceController::class, 'defaultDashboard'])->name('default.dashboard');
    
    // Routes pour assigner les rôles
    Route::post('/assign/shop-role', [RoleController::class, 'assignShopRole'])->name('assign.shop.role');
    Route::post('/assign/artisan-role', [RoleController::class, 'assignArtisanRole'])->name('assign.artisan.role');
    Route::post('/assign/both-roles', [RoleController::class, 'assignBothRoles'])->name('assign.both.roles');
    
    // Route pour switcher entre les interfaces
    Route::post('/switch-interface', [InterfaceController::class, 'switchInterface'])->name('switch.interface');
    
    // Routes chat
    Route::get('/chat/artisan/{artisan}', [ChatController::class, 'startConversationWithArtisan'])
        ->name('chat.artisan.start')
        ->middleware('role:shop');
    
    // Routes produits (rôle 'artisan' + propriété)
    Route::middleware(['role:artisan'])->group(function () {
        Route::resource('products', ProductController::class)
            ->except(['show', 'edit', 'update', 'destroy']);
        
        Route::middleware(['resource.owner:product'])->group(function () {
            Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
            Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
            Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
            Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        });
    });
});

require __DIR__.'/auth.php';
