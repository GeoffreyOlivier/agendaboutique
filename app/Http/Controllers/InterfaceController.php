<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

    public function shopDashboard()
    {
        $user = Auth::user();
        
        if (!$user->isShop()) {
            return redirect()->route('dashboard')->with('error', 'Vous n\'avez pas les permissions nécessaires.');
        }
        
        // Sauvegarder l'interface actuelle pour les utilisateurs avec les deux rôles
        if ($user->isShopAndArtisan()) {
            Session::put('current_interface', 'shop');
        }
        
        return $this->showShopDashboard($user);
    }

    public function artisanDashboard()
    {
        $user = Auth::user();
        
        if (!$user->isArtisan()) {
            return redirect()->route('dashboard')->with('error', 'Vous n\'avez pas les permissions nécessaires.');
        }
        
        // Sauvegarder l'interface actuelle pour les utilisateurs avec les deux rôles
        if ($user->isShopAndArtisan()) {
            Session::put('current_interface', 'artisan');
        }
        
        return $this->showArtisanDashboard($user);
    }

    public function defaultDashboard()
    {
        $user = Auth::user();
        return $this->showDefaultDashboard($user);
    }

    public function switchInterface(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isShopAndArtisan()) {
            return redirect()->route('dashboard')->with('error', 'Vous devez avoir les deux rôles pour changer d\'interface.');
        }
        
        $currentInterface = Session::get('current_interface', 'shop');
        $newInterface = $currentInterface === 'shop' ? 'artisan' : 'shop';
        
        Session::put('current_interface', $newInterface);
        
        $message = $newInterface === 'shop' ? 'Interface boutique activée' : 'Interface artisan activée';
        
        return redirect()->route('dashboard')->with('success', $message);
    }

    private function showShopDashboard($user)
    {
        $boutique = $user->boutique;
        $hasBoutique = $boutique !== null;
        $demandes = $boutique ? $boutique->demandes()->latest()->take(5)->get() : collect();
        $artisans = $boutique ? $boutique->artisans()->take(5)->get() : collect();
        
        return view('interfaces.shop.dashboard', compact('user', 'boutique', 'demandes', 'artisans', 'hasBoutique'));
    }

    public function shopArtisans()
    {
        $user = Auth::user();
        
        if (!$user->isShop()) {
            return redirect()->route('dashboard')->with('error', 'Vous n\'avez pas les permissions nécessaires.');
        }
        
        $boutique = $user->boutique;
        
        // Récupérer tous les artisans approuvés avec leurs informations
        $artisans = \App\Models\Artisan::with(['user', 'produits'])
            ->where('statut', 'approuve')
            ->get();
        
        return view('interfaces.shop.artisans', compact('user', 'boutique', 'artisans'));
    }

    public function shopArtisanProfile($artisanId)
    {
        $user = Auth::user();
        
        if (!$user->isShop()) {
            return redirect()->route('dashboard')->with('error', 'Vous n\'avez pas les permissions nécessaires.');
        }
        
        $boutique = $user->boutique;
        
        // Récupérer l'artisan avec tous ses produits et informations
        $artisan = \App\Models\Artisan::with(['user', 'produits'])
            ->where('id', $artisanId)
            ->where('statut', 'approuve')
            ->firstOrFail();
        
        return view('interfaces.shop.artisan-profile', compact('user', 'boutique', 'artisan'));
    }

    private function showArtisanDashboard($user)
    {
        $artisan = $user->artisan;
        $produits = $artisan ? $artisan->produits()->latest()->take(5)->get() : collect();
        $demandes = $artisan ? $artisan->demandes()->latest()->take(5)->get() : collect();
        
        return view('interfaces.artisan.dashboard', compact('user', 'artisan', 'produits', 'demandes'));
    }

    private function showDefaultDashboard($user)
    {
        return view('interfaces.default.dashboard', compact('user'));
    }
}
