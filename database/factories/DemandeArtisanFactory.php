<?php

namespace Database\Factories;

use App\Models\DemandeArtisan;
use App\Models\Boutique;
use App\Models\craftsman;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DemandeArtisan>
 */
class DemandeArtisanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'boutique_id' => Boutique::factory(),
            'artisan_id' => craftsman::factory(),
            'statut' => 'en_attente',
            'message' => fake()->paragraph(),
            'date_demande' => fake()->dateTimeBetween('-1 year', 'now'),
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'updated_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * Demande acceptée
     */
    public function acceptee(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'acceptee',
        ]);
    }

    /**
     * Demande refusée
     */
    public function refusee(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'refusee',
        ]);
    }
}
