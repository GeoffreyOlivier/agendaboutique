<?php

namespace App\Http\Controllers;

use App\Http\Requests\SwitchInterfaceRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class InterfaceController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        // Rediriger automatiquement vers le bon dashboard selon le rôle
        if ($user->isShop() && $user->isArtisan()) {
            // Si l'utilisateur a les deux rôles, vérifier l'interface actuelle
            $currentInterface = Session::get('current_interface', 'shop');
            if ($currentInterface === 'artisan') {
                return $this->showArtisanDashboard($user);
            } else {
                return $this->showShopDashboard($user);
            }
        } elseif ($user->isShop()) {
            // Si l'utilisateur a seulement le rôle shop
            return $this->showShopDashboard($user);
        } elseif ($user->isArtisan()) {
            // Si l'utilisateur a seulement le rôle artisan
            return $this->showArtisanDashboard($user);
        }
        
        // Si l'utilisateur n'a aucun rôle spécifique, afficher le dashboard par défaut
        return $this->showDefaultDashboard($user);
    }



    public function craftsmanDashboard()
    {
        $user = Auth::user();
        
        // Sauvegarder l'interface actuelle pour les utilisateurs avec les deux rôles
        if ($user->isShopAndArtisan()) {
            Session::put('current_interface', 'craftsman');
        }
        
        return $this->showArtisanDashboard($user);
    }

    public function defaultDashboard()
    {
        $user = Auth::user();
        return $this->showDefaultDashboard($user);
    }

    public function switchInterface(SwitchInterfaceRequest $request)
    {
        $user = Auth::user();
        
        $currentInterface = Session::get('current_interface', 'shop');
        $newInterface = $currentInterface === 'shop' ? 'artisan' : 'shop';
        
        Session::put('current_interface', $newInterface);
        
        $message = $newInterface === 'shop' ? 'Interface boutique activée' : 'Interface artisan activée';
        
        return redirect()->route('dashboard')->with('success', $message);
    }

    private function showShopDashboard($user)
    {
        $shop = $user->shop;
        $hasShop = $shop !== null;
        $demandes = $shop ? $shop->requests()->latest()->take(5)->get() : collect();
        $craftsmen = $shop ? $shop->craftsmen()->take(5)->get() : collect();
        
        return view('interfaces.shop.dashboard', compact('user', 'shop', 'demandes', 'craftsmen', 'hasShop'));
    }

    public function shopCraftsmen()
    {
        $user = Auth::user();
        
        $shop = $user->shop;
        
        // Récupérer tous les artisans approuvés avec leurs informations
        $craftsmen = \App\Models\Craftsman::with(['user', 'products'])
            ->where('status', 'approved')
            ->get();
        
        return view('interfaces.shop.craftsmen', compact('user', 'shop', 'craftsmen'));
    }

    public function shopCraftsmanProfile($craftsmanId)
    {
        $user = Auth::user();
    
        $shop = $user->shop;
    
        $craftsman = \App\Models\Craftsman::with(['user', 'products'])
            ->where('id', $craftsmanId)
            ->where('status', 'approved')
            ->firstOrFail();
    
        return view('interfaces.shop.craftsman-profile', compact('user', 'shop', 'craftsman'));
    }
    

    private function showArtisanDashboard($user)
    {
        $artisan = $user->craftsman;
        $products = $artisan ? $artisan->products()->latest()->take(5)->get() : collect();
        $demandes = $artisan ? $artisan->requests()->latest()->take(5)->get() : collect();
        
        return view('interfaces.artisan.dashboard', compact('user', 'artisan', 'products', 'demandes'));
    }

    private function showDefaultDashboard($user)
    {
        return view('interfaces.default.dashboard', compact('user'));
    }
}
