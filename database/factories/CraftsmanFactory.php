<?php

namespace Database\Factories;

use App\Models\Craftsman;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Craftsman>
 */
class CraftsmanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Craftsman::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'description' => $this->faker->paragraph(),
            'specialty' => $this->faker->randomElement([
                'Woodworking', 'Metalworking', 'Textiles', 'Ceramics', 
                'Glassblowing', 'Leatherworking', 'Jewelry Making', 'Pottery'
            ]),
            'experience_years' => $this->faker->numberBetween(1, 30),
            'education' => $this->faker->randomElement([
                'Self-taught', 'Apprenticeship', 'Art School', 'University', 'Workshop Training'
            ]),
            'certifications' => $this->faker->optional()->randomElements([
                'Master Craftsman', 'Quality Certification', 'Safety Training', 'Environmental Certification'
            ], $this->faker->numberBetween(0, 3)),
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'postal_code' => $this->faker->postcode(),
            'country' => $this->faker->country(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->email(),
            'website' => $this->faker->optional()->url(),
            'instagram_url' => $this->faker->optional()->url(),
            'tiktok_url' => $this->faker->optional()->url(),
            'facebook_url' => $this->faker->optional()->url(),
            'linkedin_url' => $this->faker->optional()->url(),
            'portfolio_url' => $this->faker->optional()->url(),
            'hourly_rate' => $this->faker->optional()->randomFloat(2, 20, 100),
            'daily_rate' => $this->faker->optional()->randomFloat(2, 150, 800),
            'availability' => $this->faker->randomElement(['available', 'busy', 'unavailable']),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'active' => $this->faker->boolean(80),
            'avatar' => $this->faker->optional()->imageUrl(200, 200, 'people'),
            'rejection_reason' => null,
        ];
    }

    /**
     * Indicate that the craftsman is approved.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
        ]);
    }

    /**
     * Indicate that the craftsman is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }

    /**
     * Indicate that the craftsman is rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
            'rejection_reason' => $this->faker->sentence(),
        ]);
    }

    /**
     * Indicate that the craftsman is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'active' => true,
        ]);
    }

    /**
     * Indicate that the craftsman is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'active' => false,
        ]);
    }

    /**
     * Indicate that the craftsman is available.
     */
    public function available(): static
    {
        return $this->state(fn (array $attributes) => [
            'availability' => 'available',
        ]);
    }

    /**
     * Indicate that the craftsman is busy.
     */
    public function busy(): static
    {
        return $this->state(fn (array $attributes) => [
            'availability' => 'busy',
        ]);
    }

    /**
     * Indicate that the craftsman is unavailable.
     */
    public function unavailable(): static
    {
        return $this->state(fn (array $attributes) => [
            'availability' => 'unavailable',
        ]);
    }
}
