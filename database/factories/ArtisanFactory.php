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
            'nom_artisan' => fake()->lastName(),
            'description' => fake()->paragraph(),
            'specialites' => [fake()->randomElement(['Menuiserie', 'Électricité', 'Plomberie', 'Peinture', 'Maçonnerie'])],
            'experience_annees' => fake()->numberBetween(1, 30),
            'adresse_atelier' => fake()->streetAddress(),
            'ville_atelier' => fake()->city(),
            'code_postal_atelier' => fake()->postcode(),
            'telephone_atelier' => fake()->phoneNumber(),
            'email_atelier' => fake()->email(),
            'site_web' => fake()->url(),
            'reseaux_sociaux' => [
                'instagram' => fake()->url(),
                'tiktok' => fake()->url(),
                'facebook' => fake()->url(),
                'linkedin' => fake()->url(),
            ],
            'statut' => 'en_attente',
            'actif' => true,
            'bio' => fake()->paragraph(),
            'techniques' => [fake()->word(), fake()->word()],
            'materiaux_preferes' => [fake()->word(), fake()->word()],
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
