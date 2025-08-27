<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\craftsman;
use App\Models\Produit;
use Illuminate\Support\Facades\Hash;

class ArtisansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Créer des utilisateurs artisans
        $artisansData = [
            [
                'name' => 'Marie Dubois',
                'email' => 'marie.dubois@craftsman.com',
                'username' => 'marie_dubois',
                'password' => Hash::make('password'),
                'verified' => 1,
                'craftsman' => [
                    'nom_artisan' => 'Marie Dubois',
                    'description' => 'Artisane passionnée de céramique et de poterie. Je crée des pièces uniques et personnalisées pour votre intérieur.',
                    'specialites' => ['Céramique', 'Poterie', 'Décoration'],
                    'experience_annees' => 8,
                    'adresse_atelier' => '15 Rue des Artisans',
                    'ville_atelier' => 'Lyon',
                    'code_postal_atelier' => '69001',
                    'telephone_atelier' => '04 78 12 34 56',
                    'email_atelier' => 'marie.dubois@craftsman.com',
                    'statut' => 'approuve',
                    'actif' => true,
                    'bio' => 'Passionnée par l\'art de la terre depuis mon plus jeune âge, je crée des pièces uniques qui allient tradition et modernité.',
                    'techniques' => ['Tournage', 'Modelage', 'Émaillage'],
                    'materiaux_preferes' => ['Argile', 'Grès', 'Porcelaine']
                ]
            ],
            [
                'name' => 'Pierre Martin',
                'email' => 'pierre.martin@craftsman.com',
                'username' => 'pierre_martin',
                'password' => Hash::make('password'),
                'verified' => 1,
                'craftsman' => [
                    'nom_artisan' => 'Pierre Martin',
                    'description' => 'Ébéniste traditionnel spécialisé dans la création de meubles sur mesure et la restauration d\'antiquités.',
                    'specialites' => ['Ébénisterie', 'Menuiserie', 'Restauration'],
                    'experience_annees' => 12,
                    'adresse_atelier' => '28 Avenue du Bois',
                    'ville_atelier' => 'Bordeaux',
                    'code_postal_atelier' => '33000',
                    'telephone_atelier' => '05 56 78 90 12',
                    'email_atelier' => 'pierre.martin@craftsman.com',
                    'statut' => 'approuve',
                    'actif' => true,
                    'bio' => 'Ébéniste de père en fils, je perpétue les techniques ancestrales tout en apportant une touche contemporaine.',
                    'techniques' => ['Marqueterie', 'Sculpture sur bois', 'Finitions traditionnelles'],
                    'materiaux_preferes' => ['Chêne', 'Noyer', 'Acajou']
                ]
            ],
            [
                'name' => 'Sophie Laurent',
                'email' => 'sophie.laurent@craftsman.com',
                'username' => 'sophie_laurent',
                'password' => Hash::make('password'),
                'verified' => 1,
                'craftsman' => [
                    'nom_artisan' => 'Sophie Laurent',
                    'description' => 'Créatrice textile spécialisée dans la confection de vêtements et accessoires en laine et coton bio.',
                    'specialites' => ['Textile', 'Tricot', 'Crochet'],
                    'experience_annees' => 6,
                    'adresse_atelier' => '7 Place du Marché',
                    'ville_atelier' => 'Nantes',
                    'code_postal_atelier' => '44000',
                    'telephone_atelier' => '02 40 12 34 56',
                    'email_atelier' => 'sophie.laurent@craftsman.com',
                    'statut' => 'approuve',
                    'actif' => true,
                    'bio' => 'Passionnée de mode éthique, je crée des pièces uniques qui respectent l\'environnement et valorisent le savoir-faire artisanal.',
                    'techniques' => ['Tricot main', 'Crochet', 'Teinture naturelle'],
                    'materiaux_preferes' => ['Laine bio', 'Coton bio', 'Fibres naturelles']
                ]
            ]
        ];

        foreach ($artisansData as $artisanData) {
            // Créer l'utilisateur
            $user = User::create([
                'name' => $artisanData['name'],
                'email' => $artisanData['email'],
                'username' => $artisanData['username'],
                'password' => $artisanData['password'],
                'verified' => $artisanData['verified'],
            ]);

            // Assigner le rôle craftsman
            $user->assignArtisanRole();

            // Créer l'craftsman
            $craftsman = $user->craftsman()->create($artisanData['craftsman']);

            // Créer des products pour chaque craftsman
            $this->createProductsForArtisan($craftsman);
        }
    }

    private function createProductsForArtisan($craftsman)
    {
        $productsData = [
            [
                'nom' => 'Vase en céramique bleue',
                'description' => 'Magnifique vase en céramique émaillée bleue, parfait pour décorer votre intérieur.',
                'prix_base' => 85.00,
                'categorie' => 'Décoration',
                'tags' => ['Vase', 'Céramique', 'Bleu'],
                'images' => ['vase-bleu-1.jpg', 'vase-bleu-2.jpg', 'vase-bleu-3.jpg'],
                'matiere' => 'Céramique émaillée',
                'dimensions' => ['hauteur' => '25cm', 'diametre' => '15cm'],
                'statut' => 'publie',
                'disponible' => true,
                'stock' => 5
            ],
            [
                'nom' => 'Bol en grès émaillé',
                'description' => 'Bol artisanal en grès avec finition émaillée, idéal pour la cuisine ou la décoration.',
                'prix_base' => 45.00,
                'categorie' => 'Cuisine',
                'tags' => ['Bol', 'Grès', 'Cuisine'],
                'images' => ['bol-gres-1.jpg', 'bol-gres-2.jpg', 'bol-gres-3.jpg'],
                'matiere' => 'Grès émaillé',
                'dimensions' => ['hauteur' => '8cm', 'diametre' => '18cm'],
                'statut' => 'publie',
                'disponible' => true,
                'stock' => 8
            ],
            [
                'nom' => 'Assiette décorative',
                'description' => 'Assiette décorative en céramique avec motif floral, parfaite pour présenter vos plats.',
                'prix_base' => 65.00,
                'categorie' => 'Cuisine',
                'tags' => ['Assiette', 'Décorative', 'Floral'],
                'images' => ['assiette-1.jpg', 'assiette-2.jpg', 'assiette-3.jpg'],
                'matiere' => 'Céramique décorative',
                'dimensions' => ['diametre' => '28cm'],
                'statut' => 'publie',
                'disponible' => true,
                'stock' => 6
            ]
        ];

        if ($craftsman->nom_artisan === 'Pierre Martin') {
            $productsData = [
                [
                    'nom' => 'Table basse en chêne',
                    'description' => 'Table basse artisanale en chêne massif, finition huilée, design épuré et moderne.',
                    'prix_base' => 450.00,
                    'categorie' => 'Mobilier',
                    'tags' => ['Table', 'Chêne', 'Massif'],
                    'images' => ['table-chene-1.jpg', 'table-chene-2.jpg', 'table-chene-3.jpg'],
                    'matiere' => 'Chêne massif',
                    'dimensions' => ['longueur' => '120cm', 'largeur' => '60cm', 'hauteur' => '45cm'],
                    'statut' => 'publie',
                    'disponible' => true,
                    'stock' => 2
                ],
                [
                    'nom' => 'Chaise en noyer',
                    'description' => 'Chaise d\'appoint en noyer massif, assise en cuir, style contemporain.',
                    'prix_base' => 280.00,
                    'categorie' => 'Mobilier',
                    'tags' => ['Chaise', 'Noyer', 'Cuir'],
                    'images' => ['chaise-noyer-1.jpg', 'chaise-noyer-2.jpg', 'chaise-noyer-3.jpg'],
                    'matiere' => 'Noyer massif et cuir',
                    'dimensions' => ['hauteur' => '85cm', 'largeur' => '45cm', 'profondeur' => '50cm'],
                    'statut' => 'publie',
                    'disponible' => true,
                    'stock' => 4
                ],
                [
                    'nom' => 'Coffret à bijoux',
                    'description' => 'Coffret à bijoux en acajou avec marqueterie, intérieur velours, fermeture à clé.',
                    'prix_base' => 180.00,
                    'categorie' => 'Accessoires',
                    'tags' => ['Coffret', 'Bijoux', 'Marqueterie'],
                    'images' => ['coffret-1.jpg', 'coffret-2.jpg', 'coffret-3.jpg'],
                    'matiere' => 'Acajou et velours',
                    'dimensions' => ['longueur' => '25cm', 'largeur' => '18cm', 'hauteur' => '12cm'],
                    'statut' => 'publie',
                    'disponible' => true,
                    'stock' => 3
                ]
            ];
        }

        if ($craftsman->nom_artisan === 'Sophie Laurent') {
            $productsData = [
                [
                    'nom' => 'Écharpe en laine bio',
                    'description' => 'Écharpe tricotée main en laine bio, couleurs naturelles, douceur exceptionnelle.',
                    'prix_base' => 75.00,
                    'categorie' => 'Accessoires',
                    'tags' => ['Écharpe', 'Laine bio', 'Tricot main'],
                    'images' => ['echarpe-1.jpg', 'echarpe-2.jpg', 'echarpe-3.jpg'],
                    'matiere' => 'Laine bio 100%',
                    'dimensions' => ['longueur' => '180cm', 'largeur' => '25cm'],
                    'statut' => 'publie',
                    'disponible' => true,
                    'stock' => 10
                ],
                [
                    'nom' => 'Pull en coton bio',
                    'description' => 'Pull tricoté main en coton bio, coupe oversize, confortable et élégant.',
                    'prix_base' => 120.00,
                    'categorie' => 'Vêtements',
                    'tags' => ['Pull', 'Coton bio', 'Oversize'],
                    'images' => ['pull-1.jpg', 'pull-2.jpg', 'pull-3.jpg'],
                    'matiere' => 'Coton bio 100%',
                    'dimensions' => ['taille' => 'L/XL'],
                    'statut' => 'publie',
                    'disponible' => true,
                    'stock' => 7
                ],
                [
                    'nom' => 'Bonnet en laine',
                    'description' => 'Bonnet tricoté main en laine bio, motif côtes, parfait pour l\'hiver.',
                    'prix_base' => 45.00,
                    'categorie' => 'Accessoires',
                    'tags' => ['Bonnet', 'Laine bio', 'Hiver'],
                    'images' => ['bonnet-1.jpg', 'bonnet-2.jpg', 'bonnet-3.jpg'],
                    'matiere' => 'Laine bio 100%',
                    'dimensions' => ['taille' => 'Unique'],
                    'statut' => 'publie',
                    'disponible' => true,
                    'stock' => 15
                ]
            ];
        }

        foreach ($productsData as $productData) {
            $craftsman->products()->create($productData);
        }
    }
}
