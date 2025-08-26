<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        // Créer les rôles s'ils n'existent pas déjà
        Role::firstOrCreate(['name' => 'admin'], [
            'guard_name' => 'web',
            'description' => 'The admin user has full access to all features including the ability to access the admin panel.',
        ]);

        Role::firstOrCreate(['name' => 'registered'], [
            'guard_name' => 'web',
            'description' => 'This is the default user role. If a user has this role they have created an account; however, they have are not a subscriber.',
        ]);

        Role::firstOrCreate(['name' => 'basic'], [
            'guard_name' => 'web',
            'description' => 'This is the basic plan role. This role is usually associated with a user who has subscribed to the basic plan.',
        ]);

        Role::firstOrCreate(['name' => 'premium'], [
            'guard_name' => 'web',
            'description' => 'This is the premium plan role. This role is usually associated with a user who has subscribed to the premium plan.',
        ]);

        Role::firstOrCreate(['name' => 'pro'], [
            'guard_name' => 'web',
            'description' => 'This is the pro plan role. This role is usually associated with a user who has subscribed to the pro plan.',
        ]);

        Role::firstOrCreate(['name' => 'shop'], [
            'guard_name' => 'web',
            'description' => 'Propriétaire de boutique avec accès à la caisse et gestion des demandes d\'artisans.',
        ]);

        Role::firstOrCreate(['name' => 'artisan'], [
            'guard_name' => 'web',
            'description' => 'Artisan avec possibilité de gérer ses products et expositions.',
        ]);

        Role::firstOrCreate(['name' => 'shop-artisan'], [
            'guard_name' => 'web',
            'description' => 'Utilisateur ayant à la fois un statut de propriétaire de boutique et d\'artisan.',
        ]);
    }
}
