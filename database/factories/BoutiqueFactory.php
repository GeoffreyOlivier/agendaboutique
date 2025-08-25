<?php

namespace Database\Factories;

use App\Models\Boutique;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Boutique>
 */
class BoutiqueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'nom' => fake()->company(),
            'description' => fake()->paragraph(),
            'adresse' => fake()->streetAddress(),
            'ville' => fake()->city(),
            'code_postal' => fake()->postcode(),
            'pays' => 'France',
            'telephone' => fake()->phoneNumber(),
            'email' => fake()->companyEmail(),
            'taille' => fake()->randomElement(['petite', 'moyenne', 'grande']),
            'siret' => fake()->numerify('##########'),
            'tva' => fake()->numerify('FR##########'),
            'loyer_depot_vente' => fake()->numberBetween(100, 1000),
            'loyer_permanence' => fake()->numberBetween(50, 500),
            'commission_depot_vente' => fake()->numberBetween(10, 30),
            'commission_permanence' => fake()->numberBetween(15, 40),
            'nb_permanences_mois_indicatif' => fake()->numberBetween(1, 12),
            'site_web' => fake()->url(),
            'instagram_url' => fake()->url(),
            'tiktok_url' => fake()->url(),
            'facebook_url' => fake()->url(),
            'horaires_ouverture' => 'Lundi-Vendredi: 9h-18h, Samedi: 9h-17h',
            'photo' => null,
            'statut' => 'en_attente',
            'actif' => true,
        ];
    }

    /**
     * Indique que la boutique est approuvée
     */
    public function approuvee(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'approuve',
        ]);
    }

    /**
     * Indique que la boutique est rejetée
     */
    public function rejetee(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'rejetee',
        ]);
    }

    /**
     * Indique que la boutique est inactive
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'actif' => false,
        ]);
    }

    /**
     * Boutique de petite taille
     */
    public function petite(): static
    {
        return $this->state(fn (array $attributes) => [
            'taille' => 'petite',
        ]);
    }

    /**
     * Boutique de moyenne taille
     */
    public function moyenne(): static
    {
        return $this->state(fn (array $attributes) => [
            'taille' => 'moyenne',
        ]);
    }

    /**
     * Boutique de grande taille
     */
    public function grande(): static
    {
        return $this->state(fn (array $attributes) => [
            'taille' => 'grande',
        ]);
    }

    /**
     * Boutique avec des horaires spécifiques
     */
    public function horairesSpecifiques(string $horaires): static
    {
        return $this->state(fn (array $attributes) => [
            'horaires_ouverture' => $horaires,
        ]);
    }

    /**
     * Boutique avec une adresse spécifique
     */
    public function adresseSpecifique(string $ville, string $codePostal): static
    {
        return $this->state(fn (array $attributes) => [
            'ville' => $ville,
            'code_postal' => $codePostal,
        ]);
    }
}
