<?php

namespace Database\Factories;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shop>
 */
class ShopFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Shop::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->company(),
            'description' => $this->faker->paragraph(),
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'postal_code' => $this->faker->postcode(),
            'country' => $this->faker->country(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->companyEmail(),
            'size' => $this->faker->randomElement(['small', 'medium', 'large']),
            'siret' => $this->faker->optional()->numerify('##########'),
            'vat_number' => $this->faker->optional()->numerify('FR##########'),
            'deposit_sale_rent' => $this->faker->optional()->randomFloat(2, 50, 500),
            'permanent_rent' => $this->faker->optional()->randomFloat(2, 100, 1000),
            'deposit_sale_commission' => $this->faker->optional()->randomFloat(2, 5, 25),
            'permanent_commission' => $this->faker->optional()->randomFloat(2, 10, 30),
            'monthly_permanences' => $this->faker->optional()->numberBetween(1, 30),
            'website' => $this->faker->optional()->url(),
            'instagram_url' => $this->faker->optional()->url(),
            'tiktok_url' => $this->faker->optional()->url(),
            'facebook_url' => $this->faker->optional()->url(),
            'opening_hours' => $this->faker->optional()->randomElement([
                'Monday-Friday: 9AM-6PM, Saturday: 10AM-4PM',
                'Tuesday-Saturday: 10AM-7PM',
                'Monday-Sunday: 8AM-8PM'
            ]),
            'photo' => $this->faker->optional()->imageUrl(400, 300, 'business'),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'active' => $this->faker->boolean(80),
        ];
    }

    /**
     * Indicate that the shop is approved.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
        ]);
    }

    /**
     * Indicate that the shop is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }

    /**
     * Indicate that the shop is rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
        ]);
    }

    /**
     * Indicate that the shop is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'active' => true,
        ]);
    }

    /**
     * Indicate that the shop is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'active' => false,
        ]);
    }

    /**
     * Indicate that the shop is small.
     */
    public function small(): static
    {
        return $this->state(fn (array $attributes) => [
            'size' => 'small',
        ]);
    }

    /**
     * Indicate that the shop is medium.
     */
    public function medium(): static
    {
        return $this->state(fn (array $attributes) => [
            'size' => 'medium',
        ]);
    }

    /**
     * Indicate that the shop is large.
     */
    public function large(): static
    {
        return $this->state(fn (array $attributes) => [
            'size' => 'large',
        ]);
    }

    /**
     * Indicate that the shop has deposit sale options.
     */
    public function withDepositSale(): static
    {
        return $this->state(fn (array $attributes) => [
            'deposit_sale_rent' => $this->faker->randomFloat(2, 50, 300),
            'deposit_sale_commission' => $this->faker->randomFloat(2, 5, 20),
        ]);
    }

    /**
     * Indicate that the shop has permanent exhibition options.
     */
    public function withPermanentExhibition(): static
    {
        return $this->state(fn (array $attributes) => [
            'permanent_rent' => $this->faker->randomFloat(2, 100, 800),
            'permanent_commission' => $this->faker->randomFloat(2, 10, 25),
        ]);
    }

    /**
     * Indicate that the shop has both options.
     */
    public function withBothOptions(): static
    {
        return $this->state(fn (array $attributes) => [
            'deposit_sale_rent' => $this->faker->randomFloat(2, 50, 300),
            'deposit_sale_commission' => $this->faker->randomFloat(2, 5, 20),
            'permanent_rent' => $this->faker->randomFloat(2, 100, 800),
            'permanent_commission' => $this->faker->randomFloat(2, 10, 25),
        ]);
    }
}
