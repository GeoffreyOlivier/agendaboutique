<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InterfaceController;
use App\Http\Controllers\ProduitController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Routes pour les interfaces selon les rôles
Route::middleware(['auth'])->group(function () {
    // Dashboard principal qui redirige selon le rôle
    Route::get('/dashboard', [InterfaceController::class, 'dashboard'])->name('dashboard');
    
    // Interface boutique
    Route::middleware(['auth'])->group(function () {
        Route::get('/shop/dashboard', [InterfaceController::class, 'shopDashboard'])->name('shop.dashboard');
    });
    
    // Interface artisan
    Route::middleware(['auth'])->group(function () {
        Route::get('/artisan/dashboard', [InterfaceController::class, 'artisanDashboard'])->name('artisan.dashboard');
    });
    
    // Interface par défaut
    Route::middleware(['auth'])->group(function () {
        Route::get('/default/dashboard', [InterfaceController::class, 'defaultDashboard'])->name('default.dashboard');
    });
    
    // Routes pour assigner les rôles
    Route::post('/assign/shop-role', function () {
        $user = auth()->user();
        $user->assignShopRole();
        return redirect()->route('dashboard')->with('success', 'Rôle boutique assigné avec succès !');
    })->name('assign.shop.role');

    Route::post('/assign/artisan-role', function () {
        $user = auth()->user();
        $user->assignArtisanRole();
        return redirect()->route('dashboard')->with('success', 'Rôle artisan assigné avec succès !');
    })->name('assign.artisan.role');

    Route::post('/assign/both-roles', function () {
        $user = auth()->user();
        $user->assignShopAndArtisanRoles();
        return redirect()->route('dashboard')->with('success', 'Rôles boutique et artisan assignés avec succès !');
    })->name('assign.both.roles');
    
    // Route pour switcher entre les interfaces
    Route::post('/switch-interface', [InterfaceController::class, 'switchInterface'])->name('switch.interface');
    
    // Routes pour la gestion des produits (artisans uniquement)
    Route::resource('produits', ProduitController::class);
});

require __DIR__.'/auth.php';
