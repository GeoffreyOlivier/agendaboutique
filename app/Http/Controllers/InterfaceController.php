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
            if ($currentInterface === 'craftsman') {
                return $this->showArtisanDashboard($user);
            } else {
                return $this->showShopDashboard($user);
            }
        } elseif ($user->isShop()) {
            // Si l'utilisateur a seulement le rôle shop
            return $this->showShopDashboard($user);
        } elseif ($user->isArtisan()) {
            // Si l'utilisateur a seulement le rôle craftsman
            return $this->showArtisanDashboard($user);
        }
        
        // Si l'utilisateur n'a aucun rôle spécifique (cas rare), 
        // rediriger vers une page d'erreur ou créer un compte admin
        abort(403, 'Aucun rôle valide trouvé. Veuillez contacter l\'administrateur.');
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
        $newInterface = $currentInterface === 'shop' ? 'craftsman' : 'shop';
        
        Session::put('current_interface', $newInterface);
        
        $message = $newInterface === 'shop' ? 'Interface boutique activée' : 'Interface craftsman activée';
        
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
        $craftsman = $user->craftsman;
        $products = $craftsman ? $craftsman->products()->latest()->take(5)->get() : collect();
        $demandes = $craftsman ? $craftsman->requests()->latest()->take(5)->get() : collect();
        
        return view('interfaces.craftsman.dashboard', compact('user', 'craftsman', 'products', 'demandes'));
    }

    private function showDefaultDashboard($user)
    {
        return view('interfaces.default.dashboard', compact('user'));
    }
}
