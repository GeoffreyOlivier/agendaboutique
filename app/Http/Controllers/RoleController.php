<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignRoleRequest;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    /**
     * Assigner le rôle boutique à l'utilisateur
     */
    public function assignShopRole(AssignRoleRequest $request)
    {
        $user = Auth::user();
        $user->assignShopRole();
        
        return redirect()->route('dashboard')
            ->with('success', 'Rôle boutique assigné avec succès !');
    }

    /**
     * Assigner le rôle artisan à l'utilisateur
     */
    public function assignArtisanRole(AssignRoleRequest $request)
    {
        $user = Auth::user();
        $user->assignArtisanRole();
        
        return redirect()->route('dashboard')
            ->with('success', 'Rôle artisan assigné avec succès !');
    }

    /**
     * Assigner le rôle craftsman à l'utilisateur (alias pour artisan)
     */
    public function assignCraftsmanRole(AssignRoleRequest $request)
    {
        return $this->assignArtisanRole($request);
    }

    /**
     * Assigner les deux rôles à l'utilisateur
     */
    public function assignBothRoles(AssignRoleRequest $request)
    {
        $user = Auth::user();
        $user->assignShopAndArtisanRoles();
        
        return redirect()->route('dashboard')
            ->with('success', 'Rôles boutique et artisan assignés avec succès !');
    }
}
