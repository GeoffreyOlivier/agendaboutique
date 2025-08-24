<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    /**
     * Assigner le rôle boutique à l'utilisateur
     */
    public function assignShopRole()
    {
        $user = Auth::user();
        $user->assignShopRole();
        
        return redirect()->route('dashboard')
            ->with('success', 'Rôle boutique assigné avec succès !');
    }

    /**
     * Assigner le rôle artisan à l'utilisateur
     */
    public function assignArtisanRole()
    {
        $user = Auth::user();
        $user->assignArtisanRole();
        
        return redirect()->route('dashboard')
            ->with('success', 'Rôle artisan assigné avec succès !');
    }

    /**
     * Assigner les deux rôles à l'utilisateur
     */
    public function assignBothRoles()
    {
        $user = Auth::user();
        $user->assignShopAndArtisanRoles();
        
        return redirect()->route('dashboard')
            ->with('success', 'Rôles boutique et artisan assignés avec succès !');
    }
}
