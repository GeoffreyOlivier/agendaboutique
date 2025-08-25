<?php

namespace Database\Factories;

use App\Models\Produit;
use App\Models\Artisan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produit>
 */
class ProduitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $prixBase = fake()->randomFloat(2, 10, 500);
        $prixMin = $prixBase * 0.8;
        $prixMax = $prixBase * 1.2;

        return [
            'artisan_id' => Artisan::factory(),
            'nom' => fake()->words(3, true),
            'description' => fake()->paragraph(),
            'prix_base' => $prixBase,
            'prix_min' => $prixMin,
            'prix_max' => $prixMax,
            'prix_masque' => fake()->boolean(20), // 20% de chance d'avoir un prix masqué
            'categorie' => fake()->randomElement(['Meubles', 'Décoration', 'Textile', 'Bijoux', 'Poterie', 'Peinture']),
            'tags' => fake()->words(3, false),
            'images' => [
                'produit-1.jpg',
                'produit-2.jpg',
                'produit-3.jpg'
            ],
            'image_principale' => 'produit-1.jpg',
            'matiere' => fake()->randomElement(['Bois', 'Métal', 'Tissu', 'Céramique', 'Verre', 'Pierre']),
            'dimensions' => [
                'longueur' => fake()->numberBetween(10, 200),
                'largeur' => fake()->numberBetween(10, 200),
                'hauteur' => fake()->numberBetween(5, 100)
            ],
            'instructions_entretien' => fake()->paragraph(),
            'statut' => 'publie',
            'disponible' => true,
            'stock' => fake()->numberBetween(0, 50),
            'reference' => fake()->bothify('PROD-####-????'),
            'duree_fabrication' => fake()->numberBetween(1, 30),
        ];
    }

    /**
     * Produit avec prix masqué
     */
    public function prixMasque(): static
    {
        return $this->state(fn (array $attributes) => [
            'prix_masque' => true,
            'prix_base' => null,
            'prix_min' => null,
            'prix_max' => null,
        ]);
    }

    /**
     * Produit avec prix fixe
     */
    public function prixFixe(): static
    {
        $prix = fake()->randomFloat(2, 10, 500);
        return $this->state(fn (array $attributes) => [
            'prix_base' => $prix,
            'prix_min' => null,
            'prix_max' => null,
            'prix_masque' => false,
        ]);
    }

    /**
     * Produit avec fourchette de prix
     */
    public function fourchettePrix(): static
    {
        $prixMin = fake()->randomFloat(2, 10, 200);
        $prixMax = $prixMin + fake()->randomFloat(2, 50, 300);
        return $this->state(fn (array $attributes) => [
            'prix_base' => null,
            'prix_min' => $prixMin,
            'prix_max' => $prixMax,
            'prix_masque' => false,
        ]);
    }

    /**
     * Produit disponible
     */
    public function disponible(): static
    {
        return $this->state(fn (array $attributes) => [
            'disponible' => true,
            'stock' => fake()->numberBetween(1, 50),
        ]);
    }

    /**
     * Produit indisponible
     */
    public function indisponible(): static
    {
        return $this->state(fn (array $attributes) => [
            'disponible' => false,
            'stock' => 0,
        ]);
    }

    /**
     * Produit en rupture de stock
     */
    public function ruptureStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'disponible' => true,
            'stock' => 0,
        ]);
    }

    /**
     * Produit brouillon
     */
    public function brouillon(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'brouillon',
        ]);
    }

    /**
     * Produit publié
     */
    public function publie(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'publie',
        ]);
    }

    /**
     * Produit archivé
     */
    public function archive(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'archive',
        ]);
    }

    /**
     * Produit par catégorie spécifique
     */
    public function categorie(string $categorie): static
    {
        return $this->state(fn (array $attributes) => [
            'categorie' => $categorie,
        ]);
    }

    /**
     * Produit avec matériau spécifique
     */
    public function matiere(string $matiere): static
    {
        return $this->state(fn (array $attributes) => [
            'matiere' => $matiere,
        ]);
    }

    /**
     * Produit avec dimensions spécifiques
     */
    public function dimensions(int $longueur, int $largeur, int $hauteur): static
    {
        return $this->state(fn (array $attributes) => [
            'dimensions' => [
                'longueur' => $longueur,
                'largeur' => $largeur,
                'hauteur' => $hauteur,
            ],
        ]);
    }

    /**
     * Produit avec images spécifiques
     */
    public function images(array $images): static
    {
        return $this->state(fn (array $attributes) => [
            'images' => $images,
            'image_principale' => $images[0] ?? null,
        ]);
    }

    /**
     * Produit avec tags spécifiques
     */
    public function tags(array $tags): static
    {
        return $this->state(fn (array $attributes) => [
            'tags' => $tags,
        ]);
    }

    /**
     * Produit avec durée de fabrication spécifique
     */
    public function dureeFabrication(int $jours): static
    {
        return $this->state(fn (array $attributes) => [
            'duree_fabrication' => $jours,
        ]);
    }
}
