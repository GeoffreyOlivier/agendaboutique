<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ShopArtisanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer les rôles s'ils n'existent pas
        $shopRole = Role::firstOrCreate(['name' => 'shop'], [
            'guard_name' => 'web',
            'description' => 'Propriétaire de boutique avec accès à la caisse et gestion des demandes d\'artisans.',
        ]);

        $artisanRole = Role::firstOrCreate(['name' => 'craftsman'], [
            'guard_name' => 'web',
            'description' => 'craftsman avec possibilité de gérer ses products et expositions.',
        ]);

        // Créer les permissions pour les boutiques
        $shopPermissions = [
            'gestion_caisse',
            'gestion_demandes_artisans',
            'gestion_boutique',
            'voir_artisans',
            'contacter_artisans',
            'gestion_commandes',
            'gestion_stock',
            'gestion_finances',
        ];

        // Créer les permissions pour les artisans
        $artisanPermissions = [
            'gestion_produits',
            'gestion_expositions',
            'repondre_demandes',
            'gestion_profil_artisan',
            'voir_demandes_boutiques',
            'gestion_prix',
            'gestion_disponibilites',
            'gestion_photos',
        ];

        // Créer toutes les permissions
        $allPermissions = array_merge($shopPermissions, $artisanPermissions);
        
        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission], [
                'guard_name' => 'web',
            ]);
        }

        // Assigner les permissions aux rôles
        $shopRole->syncPermissions($shopPermissions);
        $artisanRole->syncPermissions($artisanPermissions);

        $this->command->info('Rôles shop et craftsman créés avec leurs permissions.');
    }
}
