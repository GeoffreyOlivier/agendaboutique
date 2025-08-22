<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Boutique;
use App\Models\Artisan;
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
        
        Boutique::create([
            'user_id' => $user1->id,
            'nom' => 'Atelier Créatif Marie',
            'description' => 'Boutique d\'artisanat local spécialisée dans la création textile.',
            'adresse' => '15 Rue de la Créativité',
            'ville' => 'Lyon',
            'code_postal' => '69001',
            'telephone' => '04 78 12 34 56',
            'email' => 'contact@atelier-marie.fr',
            'taille' => 'moyenne',
            'statut' => 'approuve',
            'actif' => true,
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
        
        Boutique::create([
            'user_id' => $user2->id,
            'nom' => 'Galerie Pierre & Co',
            'description' => 'Galerie d\'art contemporain et boutique d\'objets uniques.',
            'adresse' => '28 Avenue des Arts',
            'ville' => 'Marseille',
            'code_postal' => '13001',
            'telephone' => '04 91 23 45 67',
            'email' => 'info@galerie-pierre.fr',
            'taille' => 'grande',
            'statut' => 'approuve',
            'actif' => true,
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
        
        Boutique::create([
            'user_id' => $user3->id,
            'nom' => 'Boutique Artisanale Sophie',
            'description' => 'Spécialisée dans la vente de produits artisanaux traditionnels.',
            'adresse' => '7 Place du Marché',
            'ville' => 'Bordeaux',
            'code_postal' => '33000',
            'telephone' => '05 56 78 90 12',
            'email' => 'contact@boutique-sophie.fr',
            'taille' => 'petite',
            'statut' => 'approuve',
            'actif' => true,
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
        
        Artisan::create([
            'user_id' => $user4->id,
            'nom_artisan' => 'Jean Dupont',
            'description' => 'Artisan verrier spécialisé dans la création de vitraux.',
            'specialites' => json_encode(['Vitrail', 'Verre soufflé', 'Décoration']),
            'experience_annees' => 15,
            'adresse_atelier' => '123 Rue des Artisans',
            'ville_atelier' => 'Paris',
            'code_postal_atelier' => '75001',
            'telephone_atelier' => '01 42 34 56 78',
            'email_atelier' => 'atelier@jean-dupont.fr',
            'site_web' => 'www.jean-dupont-artisan.fr',
            'statut' => 'approuve',
            'actif' => true,
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
        
        Artisan::create([
            'user_id' => $user5->id,
            'nom_artisan' => 'Claire Moreau',
            'description' => 'Céramiste passionnée par la création de pièces uniques.',
            'specialites' => json_encode(['Céramique', 'Grès', 'Porcelaine']),
            'experience_annees' => 8,
            'adresse_atelier' => '45 Chemin des Créateurs',
            'ville_atelier' => 'Toulouse',
            'code_postal_atelier' => '31000',
            'telephone_atelier' => '05 61 23 45 67',
            'email_atelier' => 'contact@claire-moreau.fr',
            'site_web' => 'www.claire-moreau-ceramique.fr',
            'statut' => 'approuve',
            'actif' => true,
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
        
        Artisan::create([
            'user_id' => $user6->id,
            'nom_artisan' => 'Thomas Leroy',
            'description' => 'Ébéniste traditionnel créant des meubles sur mesure.',
            'specialites' => json_encode(['Ébénisterie', 'Marqueterie', 'Restauration']),
            'experience_annees' => 12,
            'adresse_atelier' => '78 Impasse du Bois',
            'ville_atelier' => 'Nantes',
            'code_postal_atelier' => '44000',
            'telephone_atelier' => '02 40 12 34 56',
            'email_atelier' => 'thomas@leroy-ebeniste.fr',
            'site_web' => 'www.thomas-leroy-ebeniste.fr',
            'statut' => 'approuve',
            'actif' => true,
        ]);

        $this->command->info('✅ 3 boutiques et 3 artisans créés avec succès !');
    }
}