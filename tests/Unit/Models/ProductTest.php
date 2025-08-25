<?php

namespace Tests\Unit\Models;

use App\Models\Product;
use App\Models\Craftsman;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_can_be_created(): void
    {
        $product = Product::factory()->create([
            'name' => 'Wooden Table',
            'category' => 'Furniture',
        ]);

        $this->assertDatabaseHas('products', [
            'name' => 'Wooden table', // Mutateur transforme en "Wooden table"
            'category' => 'Furniture',
        ]);

        $this->assertInstanceOf(Product::class, $product);
    }

    public function test_product_has_required_attributes(): void
    {
        $product = Product::factory()->create([
            'name' => 'Vintage Chair',
            'description' => 'A beautiful vintage chair',
            'category' => 'Furniture',
            'base_price' => 150.00,
        ]);

        $this->assertEquals('Vintage chair', $product->name); // Mutateur transforme en "Vintage chair"
        $this->assertEquals('A beautiful vintage chair', $product->description);
        $this->assertEquals('Furniture', $product->category);
        $this->assertEquals(150.00, $product->base_price);
        $this->assertNotNull($product->created_at);
        $this->assertNotNull($product->updated_at);
    }

    public function test_product_belongs_to_craftsman(): void
    {
        $craftsman = Craftsman::factory()->create();
        $product = Product::factory()->create(['craftsman_id' => $craftsman->id]);

        $this->assertInstanceOf(Craftsman::class, $product->craftsman);
        $this->assertEquals($craftsman->id, $product->craftsman->id);
    }

    public function test_product_scope_available(): void
    {
        Product::factory()->create(['available' => true]);
        Product::factory()->create(['available' => false]);
        Product::factory()->create(['available' => true]);

        $available = Product::available()->get();

        $this->assertCount(2, $available);
        $this->assertTrue($available->every(fn($p) => $p->available === true));
    }

    public function test_product_scope_active(): void
    {
        Product::factory()->create(['status' => 'draft']);
        Product::factory()->create(['status' => 'published']);
        Product::factory()->create(['status' => 'archived']);
        Product::factory()->create(['status' => 'published']);

        $active = Product::active()->get();

        $this->assertCount(2, $active);
        $this->assertTrue($active->every(fn($p) => $p->status === 'published'));
    }

    public function test_product_scope_by_category(): void
    {
        Product::factory()->create(['category' => 'Furniture']);
        Product::factory()->create(['category' => 'Decoration']);
        Product::factory()->create(['category' => 'Furniture']);

        $furniture = Product::byCategory('Furniture')->get();

        $this->assertCount(2, $furniture);
        $this->assertTrue($furniture->every(fn($p) => $p->category === 'Furniture'));
    }

    public function test_product_accessor_formatted_price_base_price(): void
    {
        $product = Product::factory()->create([
            'base_price' => 125.50,
            'min_price' => null,
            'max_price' => null,
        ]);

        $this->assertEquals('125.50 €', $product->formatted_price);
    }

    public function test_product_accessor_formatted_price_price_range(): void
    {
        $product = Product::factory()->create([
            'base_price' => null,
            'min_price' => 100.00,
            'max_price' => 200.00,
        ]);

        $this->assertEquals('100.00 - 200.00 €', $product->formatted_price);
    }

    public function test_product_accessor_formatted_price_no_price(): void
    {
        $product = Product::factory()->create([
            'base_price' => null,
            'min_price' => null,
            'max_price' => null,
        ]);

        $this->assertEquals('Price on request', $product->formatted_price);
    }

    public function test_product_accessor_main_image_from_main_image(): void
    {
        $product = Product::factory()->create([
            'main_image' => 'path/to/main.jpg',
            'images' => ['path/to/other1.jpg', 'path/to/other2.jpg'],
        ]);

        $this->assertEquals('path/to/main.jpg', $product->main_image);
    }

    public function test_product_accessor_main_image_from_images_array(): void
    {
        $product = Product::factory()->create([
            'main_image' => null,
            'images' => ['path/to/first.jpg', 'path/to/second.jpg'],
        ]);

        $this->assertEquals('path/to/first.jpg', $product->main_image);
    }

    public function test_product_accessor_main_image_no_images(): void
    {
        $product = Product::factory()->create([
            'main_image' => null,
            'images' => null,
        ]);

        $this->assertNull($product->main_image);
    }

    public function test_product_accessor_status_label(): void
    {
        $draft = Product::factory()->create(['status' => 'draft']);
        $published = Product::factory()->create(['status' => 'published']);
        $archived = Product::factory()->create(['status' => 'archived']);

        $this->assertEquals('Draft', $draft->status_label);
        $this->assertEquals('Published', $published->status_label);
        $this->assertEquals('Archived', $archived->status_label);
    }

    public function test_product_accessor_availability_label_available_in_stock(): void
    {
        $product = Product::factory()->create([
            'available' => true,
            'stock' => 5,
        ]);

        $this->assertEquals('In Stock', $product->availability_label);
    }

    public function test_product_accessor_availability_label_available_out_of_stock(): void
    {
        $product = Product::factory()->create([
            'available' => true,
            'stock' => 0,
        ]);

        $this->assertEquals('Out of Stock', $product->availability_label);
    }

    public function test_product_accessor_availability_label_unavailable(): void
    {
        $product = Product::factory()->create([
            'available' => false,
            'stock' => 10,
        ]);

        $this->assertEquals('Unavailable', $product->availability_label);
    }

    public function test_product_alias_price_attribute(): void
    {
        $product = Product::factory()->create(['base_price' => 99.99]);

        $this->assertEquals(99.99, $product->price);
    }

    public function test_product_alias_price_setter(): void
    {
        $product = Product::factory()->create();
        $product->price = 149.99;

        $this->assertEquals(149.99, $product->base_price);
    }

    public function test_product_mutators(): void
    {
        $product = Product::factory()->create([
            'name' => 'test product',
            'category' => 'test category',
            'material' => 'test material',
        ]);

        $this->assertEquals('Test product', $product->name);
        $this->assertEquals('Test category', $product->category);
        $this->assertEquals('Test material', $product->material);
    }

    public function test_product_scope_published(): void
    {
        Product::factory()->create(['status' => 'draft']);
        Product::factory()->create(['status' => 'published']);
        Product::factory()->create(['status' => 'archived']);

        $published = Product::published()->get();

        $this->assertCount(1, $published);
        $this->assertEquals('published', $published->first()->status);
    }

    public function test_product_scope_draft(): void
    {
        Product::factory()->create(['status' => 'draft']);
        Product::factory()->create(['status' => 'published']);
        Product::factory()->create(['status' => 'draft']);

        $drafts = Product::draft()->get();

        $this->assertCount(2, $drafts);
        $this->assertTrue($drafts->every(fn($p) => $p->status === 'draft'));
    }

    public function test_product_casts(): void
    {
        $product = Product::factory()->create([
            'base_price' => '150.50',
            'min_price' => '100.00',
            'max_price' => '200.00',
            'price_hidden' => 1,
            'tags' => ['wood', 'vintage'],
            'dimensions' => ['width' => 50, 'height' => 80],
            'images' => ['image1.jpg', 'image2.jpg'],
            'available' => 1,
        ]);

        // Recharger le modèle depuis la base de données pour vérifier les casts
        $product->refresh();

        // Les casts decimal retournent des chaînes, pas des floats
        $this->assertIsString($product->base_price);
        $this->assertIsString($product->min_price);
        $this->assertIsString($product->max_price);
        $this->assertIsBool($product->price_hidden);
        $this->assertIsArray($product->tags);
        $this->assertIsArray($product->dimensions);
        $this->assertIsArray($product->images);
        $this->assertIsBool($product->available);
    }
}
