<?php

namespace Database\Factories;

use App\Models\Commande;
use App\Models\Boutique;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Commande>
 */
class CommandeFactory extends Factory
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
            'user_id' => User::factory(),
            'statut' => 'en_attente',
            'total' => fake()->randomFloat(2, 10, 1000),
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'updated_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * Commande confirmée
     */
    public function confirmee(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'confirmee',
        ]);
    }

    /**
     * Commande annulée
     */
    public function annulee(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'annulee',
        ]);
    }
}
