<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Boutique;
use Illuminate\Support\Facades\Hash;

class ShopUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Créer un utilisateur boutique de test
        $user = User::create([
            'name' => 'Boutique Test',
            'email' => 'boutique_' . time() . '@test.com',
            'username' => 'boutique_test_' . time(),
            'password' => Hash::make('password'),
            'verified' => 1,
        ]);

        // Assigner le rôle boutique
        $user->assignShopRole();

        // Créer une boutique pour cet utilisateur
        $boutique = $user->boutique()->create([
            'nom' => 'Boutique Test',
            'description' => 'Une boutique de test pour démontrer les fonctionnalités',
            'adresse' => '123 Rue de la Boutique',
            'ville' => 'Paris',
            'code_postal' => '75001',
            'pays' => 'France',
            'telephone' => '01 23 45 67 89',
            'email' => 'contact@boutiquetest.com',
            'taille' => 'moyenne',
            'statut' => 'approuve',
            'actif' => true,
        ]);
    }
}
