<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Craftsman;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'craftsman_id' => Craftsman::factory(),
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->paragraph(),
            'base_price' => $this->faker->optional()->randomFloat(2, 10, 500),
            'min_price' => $this->faker->optional()->randomFloat(2, 5, 200),
            'max_price' => $this->faker->optional()->randomFloat(2, 50, 1000),
            'price_hidden' => $this->faker->boolean(20),
            'category' => $this->faker->randomElement([
                'Furniture', 'Decoration', 'Jewelry', 'Textiles', 'Ceramics', 
                'Metalwork', 'Woodwork', 'Glass', 'Leather', 'Paper'
            ]),
            'tags' => $this->faker->randomElements([
                'handmade', 'craftsman', 'unique', 'sustainable', 'vintage', 
                'modern', 'traditional', 'eco-friendly', 'luxury', 'affordable'
            ], $this->faker->numberBetween(1, 5)),
            'images' => $this->faker->optional()->randomElements([
                'product1.jpg', 'product2.jpg', 'product3.jpg', 'product4.jpg'
            ], $this->faker->numberBetween(1, 4)),
            'main_image' => $this->faker->optional()->randomElement([
                'main1.jpg', 'main2.jpg', 'main3.jpg'
            ]),
            'material' => $this->faker->randomElement([
                'Wood', 'Metal', 'Ceramic', 'Glass', 'Textile', 'Leather', 
                'Paper', 'Stone', 'Plastic', 'Mixed Materials'
            ]),
            'dimensions' => [
                'width' => $this->faker->numberBetween(10, 200),
                'height' => $this->faker->numberBetween(10, 200),
                'depth' => $this->faker->numberBetween(5, 100),
                'unit' => 'cm'
            ],
            'care_instructions' => $this->faker->optional()->paragraph(),
            'status' => $this->faker->randomElement(['draft', 'published', 'archived']),
            'available' => $this->faker->boolean(80),
            'stock' => $this->faker->optional()->numberBetween(0, 100),
            'reference' => $this->faker->unique()->regexify('[A-Z]{2}[0-9]{6}'),
            'production_time' => $this->faker->optional()->randomElement([
                '1-2 days', '3-5 days', '1 week', '2 weeks', '1 month', 'Custom'
            ]),
        ];
    }

    /**
     * Indicate that the product is available.
     */
    public function available(): static
    {
        return $this->state(fn (array $attributes) => [
            'available' => true,
        ]);
    }

    /**
     * Indicate that the product is unavailable.
     */
    public function unavailable(): static
    {
        return $this->state(fn (array $attributes) => [
            'available' => false,
        ]);
    }

    /**
     * Indicate that the product is published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
        ]);
    }

    /**
     * Indicate that the product is draft.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
        ]);
    }

    /**
     * Indicate that the product is archived.
     */
    public function archived(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'archived',
        ]);
    }

    /**
     * Indicate that the product has a base price.
     */
    public function withBasePrice(): static
    {
        return $this->state(fn (array $attributes) => [
            'base_price' => $this->faker->randomFloat(2, 20, 300),
            'min_price' => null,
            'max_price' => null,
        ]);
    }

    /**
     * Indicate that the product has a price range.
     */
    public function withPriceRange(): static
    {
        $min = $this->faker->randomFloat(2, 10, 100);
        $max = $min + $this->faker->randomFloat(2, 50, 200);
        
        return $this->state(fn (array $attributes) => [
            'base_price' => null,
            'min_price' => $min,
            'max_price' => $max,
        ]);
    }

    /**
     * Indicate that the product has no price (price on request).
     */
    public function priceOnRequest(): static
    {
        return $this->state(fn (array $attributes) => [
            'base_price' => null,
            'min_price' => null,
            'max_price' => null,
        ]);
    }

    /**
     * Indicate that the product has hidden price.
     */
    public function priceHidden(): static
    {
        return $this->state(fn (array $attributes) => [
            'price_hidden' => true,
        ]);
    }

    /**
     * Indicate that the product is in stock.
     */
    public function inStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'available' => true,
            'stock' => $this->faker->numberBetween(1, 50),
        ]);
    }

    /**
     * Indicate that the product is out of stock.
     */
    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'available' => true,
            'stock' => 0,
        ]);
    }
}
