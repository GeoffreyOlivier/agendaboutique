<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class UserRoleService
{
    /**
     * Assigner le rôle boutique à un utilisateur
     */
    public function assignShopRole(User $user): bool
    {
        try {
            $user->assignRole('shop');
            Log::info("Rôle 'shop' assigné à l'utilisateur {$user->id}");
            return true;
        } catch (\Exception $e) {
            Log::error("Erreur lors de l'assignation du rôle 'shop' à l'utilisateur {$user->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Assigner le rôle artisan à un utilisateur
     */
    public function assignArtisanRole(User $user): bool
    {
        try {
            $user->assignRole('artisan');
            Log::info("Rôle 'artisan' assigné à l'utilisateur {$user->id}");
            return true;
        } catch (\Exception $e) {
            Log::error("Erreur lors de l'assignation du rôle 'artisan' à l'utilisateur {$user->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Assigner les deux rôles (boutique et artisan) à un utilisateur
     */
    public function assignBothRoles(User $user): bool
    {
        try {
            $user->syncRoles(['shop', 'artisan']);
            Log::info("Rôles 'shop' et 'artisan' assignés à l'utilisateur {$user->id}");
            return true;
        } catch (\Exception $e) {
            Log::error("Erreur lors de l'assignation des rôles 'shop' et 'artisan' à l'utilisateur {$user->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Vérifier si un utilisateur a un rôle spécifique
     */
    public function hasRole(User $user, string $role): bool
    {
        return $user->hasRole($role);
    }

    /**
     * Vérifier si un utilisateur a plusieurs rôles
     */
    public function hasRoles(User $user, array $roles): bool
    {
        return $user->hasAllRoles($roles);
    }

    /**
     * Obtenir tous les rôles d'un utilisateur
     */
    public function getUserRoles(User $user): array
    {
        return $user->getRoleNames()->toArray();
    }

    /**
     * Vérifier si un utilisateur a un profil complet
     */
    public function hasCompleteProfile(User $user, string $roleType): bool
    {
        return match($roleType) {
            'shop' => $user->hasRole('shop') && $user->shop && $user->shop->status === 'approved',
            'artisan' => $user->hasRole('artisan') && $user->craftsman && $user->craftsman->status === 'approved',
            'shop-artisan' => $user->hasRole('shop-artisan') && 
                             $user->shop && $user->shop->status === 'approved' &&
                             $user->craftsman && $user->craftsman->status === 'approved',
            default => false
        };
    }

    /**
     * Nettoyer tous les rôles d'un utilisateur
     */
    public function clearAllRoles(User $user): bool
    {
        try {
            $user->syncRoles([]);
            Log::info("Tous les rôles supprimés pour l'utilisateur {$user->id}");
            return true;
        } catch (\Exception $e) {
            Log::error("Erreur lors de la suppression des rôles de l'utilisateur {$user->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Vérifier si un utilisateur peut avoir plusieurs rôles
     */
    public function canHaveMultipleRoles(User $user): bool
    {
        // Logique métier : un utilisateur peut avoir les deux rôles
        return true;
    }

    /**
     * Assigner le rôle combiné à un utilisateur
     */
    public function assignCombinedRole(User $user): bool
    {
        try {
            $user->assignRole('shop-artisan');
            Log::info("Rôle 'shop-artisan' assigné à l'utilisateur {$user->id}");
            return true;
        } catch (\Exception $e) {
            Log::error("Erreur lors de l'assignation du rôle 'shop-artisan' à l'utilisateur {$user->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtenir le rôle principal d'un utilisateur
     */
    public function getPrimaryRole(User $user): ?string
    {
        if ($user->hasRole('shop-artisan')) {
            return 'shop-artisan';
        }
        
        if ($user->hasRole('shop') && $user->hasRole('artisan')) {
            return 'both';
        }
        
        if ($user->hasRole('shop')) {
            return 'shop';
        }
        
        if ($user->hasRole('artisan')) {
            return 'artisan';
        }
        
        return null;
    }
}
