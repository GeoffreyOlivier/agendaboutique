<?php

namespace Database\Factories;

use App\Models\Artisan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Artisan>
 */
class ArtisanFactory extends Factory
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
            'nom' => fake()->lastName(),
            'prenom' => fake()->firstName(),
            'description' => fake()->paragraph(),
            'specialite' => fake()->randomElement(['Menuiserie', 'Électricité', 'Plomberie', 'Peinture', 'Maçonnerie']),
            'experience_annees' => fake()->numberBetween(1, 30),
            'formation' => fake()->sentence(),
            'certifications' => [fake()->word(), fake()->word()],
            'adresse' => fake()->streetAddress(),
            'ville' => fake()->city(),
            'code_postal' => fake()->postcode(),
            'pays' => 'France',
            'telephone' => fake()->phoneNumber(),
            'email' => fake()->email(),
            'site_web' => fake()->url(),
            'instagram_url' => fake()->url(),
            'tiktok_url' => fake()->url(),
            'facebook_url' => fake()->url(),
            'linkedin_url' => fake()->url(),
            'portfolio_url' => fake()->url(),
            'tarif_horaire' => fake()->numberBetween(20, 100),
            'tarif_jour' => fake()->numberBetween(150, 500),
            'disponibilite' => fake()->randomElement(['disponible', 'partiellement_disponible', 'indisponible']),
            'statut' => 'en_attente',
            'actif' => true,
            'avatar' => null,
        ];
    }

    /**
     * Indique que l'artisan est approuvé
     */
    public function approuve(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'approuve',
        ]);
    }

    /**
     * Indique que l'artisan est rejeté
     */
    public function rejete(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'rejete',
            'raison_rejet' => fake()->sentence(),
        ]);
    }

    /**
     * Indique que l'artisan est inactif
     */
    public function inactif(): static
    {
        return $this->state(fn (array $attributes) => [
            'actif' => false,
        ]);
    }

    /**
     * Indique que l'artisan est disponible
     */
    public function disponible(): static
    {
        return $this->state(fn (array $attributes) => [
            'disponibilite' => 'disponible',
        ]);
    }

    /**
     * Indique que l'artisan est indisponible
     */
    public function indisponible(): static
    {
        return $this->state(fn (array $attributes) => [
            'disponibilite' => 'indisponible',
        ]);
    }
}
