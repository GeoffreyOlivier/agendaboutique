<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Shop;
use App\Models\Craftsman;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class TestUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Créer les rôles s'ils n'existent pas
        $shopRole = Role::firstOrCreate(['name' => 'shop'], ['guard_name' => 'web']);
        $artisanRole = Role::firstOrCreate(['name' => 'artisan'], ['guard_name' => 'web']);

        // === BOUTIQUES ===
        
        // Boutique 1
        $user1 = User::create([
            'name' => 'Marie Dubois',
            'email' => 'marie.dubois@boutique.fr',
            'username' => 'mariedubois',
            'password' => Hash::make('adminboutique'),
            'verified' => 1,
        ]);
        $user1->assignRole($shopRole);
        
        Shop::create([
            'user_id' => $user1->id,
            'name' => 'Atelier Créatif Marie',
            'description' => 'Boutique d\'artisanat local spécialisée dans la création textile.',
            'address' => '15 Rue de la Créativité',
            'city' => 'Lyon',
            'postal_code' => '69001',
            'phone' => '04 78 12 34 56',
            'email' => 'contact@atelier-marie.fr',
            'size' => 'medium',
            'status' => 'approved',
            'active' => true,
        ]);

        // Boutique 2
        $user2 = User::create([
            'name' => 'Pierre Martin',
            'email' => 'pierre.martin@boutique.fr',
            'username' => 'pierremartin',
            'password' => Hash::make('adminboutique'),
            'verified' => 1,
        ]);
        $user2->assignRole($shopRole);
        
        Shop::create([
            'user_id' => $user2->id,
            'name' => 'Galerie Pierre & Co',
            'description' => 'Galerie d\'art contemporain et boutique d\'objets uniques.',
            'address' => '28 Avenue des Arts',
            'city' => 'Marseille',
            'postal_code' => '13001',
            'phone' => '04 91 23 45 67',
            'email' => 'info@galerie-pierre.fr',
            'size' => 'large',
            'status' => 'approved',
            'active' => true,
        ]);

        // Boutique 3
        $user3 = User::create([
            'name' => 'Sophie Bernard',
            'email' => 'sophie.bernard@boutique.fr',
            'username' => 'sophiebernard',
            'password' => Hash::make('adminboutique'),
            'verified' => 1,
        ]);
        $user3->assignRole($shopRole);
        
        Shop::create([
            'user_id' => $user3->id,
            'name' => 'Boutique Artisanale Sophie',
            'description' => 'Spécialisée dans la vente de products artisanaux traditionnels.',
            'address' => '7 Place du Marché',
            'city' => 'Bordeaux',
            'postal_code' => '33000',
            'phone' => '05 56 78 90 12',
            'email' => 'contact@boutique-sophie.fr',
            'size' => 'small',
            'status' => 'approved',
            'active' => true,
        ]);

        // === ARTISANS ===
        
        // Artisan 1
        $user4 = User::create([
            'name' => 'Jean Dupont',
            'email' => 'jean.dupont@artisan.fr',
            'username' => 'jeandupont',
            'password' => Hash::make('adminartisan'),
            'verified' => 1,
        ]);
        $user4->assignRole($artisanRole);
        
        Craftsman::create([
            'user_id' => $user4->id,
            'first_name' => 'Jean',
            'last_name' => 'Dupont',
            'description' => 'Artisan verrier spécialisé dans la création de vitraux.',
            'specialty' => 'Vitrail, Verre soufflé, Décoration',
            'experience_years' => 15,
            'address' => '123 Rue des Artisans',
            'city' => 'Paris',
            'postal_code' => '75001',
            'phone' => '01 42 34 56 78',
            'email' => 'atelier@jean-dupont.fr',
            'website' => 'www.jean-dupont-artisan.fr',
            'status' => 'approved',
            'active' => true,
        ]);

        // Artisan 2
        $user5 = User::create([
            'name' => 'Claire Moreau',
            'email' => 'claire.moreau@artisan.fr',
            'username' => 'clairemoreau',
            'password' => Hash::make('adminartisan'),
            'verified' => 1,
        ]);
        $user5->assignRole($artisanRole);
        
        Craftsman::create([
            'user_id' => $user5->id,
            'first_name' => 'Claire',
            'last_name' => 'Moreau',
            'description' => 'Céramiste passionnée par la création de pièces uniques.',
            'specialty' => 'Céramique, Grès, Porcelaine',
            'experience_years' => 8,
            'address' => '45 Chemin des Créateurs',
            'city' => 'Toulouse',
            'postal_code' => '31000',
            'phone' => '05 61 23 45 67',
            'email' => 'contact@claire-moreau.fr',
            'website' => 'www.claire-moreau-ceramique.fr',
            'status' => 'approved',
            'active' => true,
        ]);

        // Artisan 3
        $user6 = User::create([
            'name' => 'Thomas Leroy',
            'email' => 'thomas.leroy@artisan.fr',
            'username' => 'thomasleroy',
            'password' => Hash::make('adminartisan'),
            'verified' => 1,
        ]);
        $user6->assignRole($artisanRole);
        
        Craftsman::create([
            'user_id' => $user6->id,
            'first_name' => 'Thomas',
            'last_name' => 'Leroy',
            'description' => 'Ébéniste traditionnel créant des meubles sur mesure.',
            'specialty' => 'Ébénisterie, Marqueterie, Restauration',
            'experience_years' => 12,
            'address' => '78 Impasse du Bois',
            'city' => 'Nantes',
            'postal_code' => '44000',
            'phone' => '02 40 12 34 56',
            'email' => 'thomas@leroy-ebeniste.fr',
            'website' => 'www.thomas-leroy-ebeniste.fr',
            'status' => 'approved',
            'active' => true,
        ]);

        $this->command->info('✅ 3 shops et 3 craftsmen créés avec succès !');
    }
}