<?php

namespace Tests\Unit\Models;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopTest extends TestCase
{
    use RefreshDatabase;

    public function test_shop_can_be_created(): void
    {
        $shop = Shop::factory()->create([
            'name' => 'My Shop',
            'city' => 'Paris',
        ]);

        $this->assertDatabaseHas('shops', [
            'name' => 'My shop', // Mutateur transforme en "My shop"
            'city' => 'Paris',
        ]);

        $this->assertInstanceOf(Shop::class, $shop);
    }

    public function test_shop_has_required_attributes(): void
    {
        $shop = Shop::factory()->create([
            'name' => 'Test Shop',
            'email' => 'test@shop.com',
            'size' => 'medium',
        ]);

        $this->assertEquals('Test shop', $shop->name); // Mutateur transforme en "Test shop"
        $this->assertEquals('test@shop.com', $shop->email);
        $this->assertEquals('medium', $shop->size);
        $this->assertNotNull($shop->created_at);
        $this->assertNotNull($shop->updated_at);
    }

    public function test_shop_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $shop->user);
        $this->assertEquals($user->id, $shop->user->id);
    }

    public function test_shop_scope_active(): void
    {
        Shop::factory()->create(['active' => true]);
        Shop::factory()->create(['active' => false]);
        Shop::factory()->create(['active' => true]);

        $active = Shop::active()->get();

        $this->assertCount(2, $active);
        $this->assertTrue($active->every(fn($s) => $s->active === true));
    }

    public function test_shop_accessor_full_address(): void
    {
        $shop = Shop::factory()->create([
            'address' => '123 Peace Street',
            'postal_code' => '75001',
            'city' => 'Paris',
            'country' => 'France', // Spécifier explicitement le pays
        ]);

        // L'accessor full_address utilise array_filter et implode, ce qui peut créer des virgules
        $this->assertEquals('123 Peace Street, 75001, Paris, France', $shop->full_address);
    }

    public function test_shop_accessor_exhibitors_count_small(): void
    {
        $shop = Shop::factory()->create(['size' => 'small']);

        $this->assertEquals('1-5 exhibitors', $shop->exhibitors_count);
    }

    public function test_shop_accessor_exhibitors_count_medium(): void
    {
        $shop = Shop::factory()->create(['size' => 'medium']);

        $this->assertEquals('6-15 exhibitors', $shop->exhibitors_count);
    }

    public function test_shop_accessor_exhibitors_count_large(): void
    {
        $shop = Shop::factory()->create(['size' => 'large']);

        $this->assertEquals('16+ exhibitors', $shop->exhibitors_count);
    }

    public function test_shop_accessor_full_name(): void
    {
        $shop = Shop::factory()->create(['name' => 'My Super Shop']);

        $this->assertEquals('My super shop', $shop->full_name); // Mutateur transforme en "My super shop"
    }

    public function test_shop_accessor_status_label(): void
    {
        $pending = Shop::factory()->create(['status' => 'pending']);
        $approved = Shop::factory()->create(['status' => 'approved']);
        $rejected = Shop::factory()->create(['status' => 'rejected']);

        $this->assertEquals('Pending', $pending->status_label);
        $this->assertEquals('Approved', $approved->status_label);
        $this->assertEquals('Rejected', $rejected->status_label);
    }

    public function test_shop_accessor_size_label(): void
    {
        $small = Shop::factory()->create(['size' => 'small']);
        $medium = Shop::factory()->create(['size' => 'medium']);
        $large = Shop::factory()->create(['size' => 'large']);

        $this->assertEquals('Small', $small->size_label);
        $this->assertEquals('Medium', $medium->size_label);
        $this->assertEquals('Large', $large->size_label);
    }

    public function test_shop_scope_approved(): void
    {
        Shop::factory()->create(['status' => 'pending']);
        Shop::factory()->create(['status' => 'approved']);
        Shop::factory()->create(['status' => 'rejected']);
        Shop::factory()->create(['status' => 'approved']);

        $approved = Shop::approved()->get();

        $this->assertCount(2, $approved);
        $this->assertTrue($approved->every(fn($s) => $s->status === 'approved'));
    }

    public function test_shop_scope_pending(): void
    {
        Shop::factory()->create(['status' => 'pending']);
        Shop::factory()->create(['status' => 'approved']);
        Shop::factory()->create(['status' => 'pending']);

        $pending = Shop::pending()->get();

        $this->assertCount(2, $pending);
        $this->assertTrue($pending->every(fn($s) => $s->status === 'pending'));
    }

    public function test_shop_mutators(): void
    {
        $shop = Shop::factory()->create([
            'name' => 'test shop',
            'city' => 'test city',
            'country' => 'test country',
        ]);

        $this->assertEquals('Test shop', $shop->name);
        $this->assertEquals('Test city', $shop->city);
        $this->assertEquals('Test country', $shop->country);
    }

    public function test_shop_casts(): void
    {
        $shop = Shop::factory()->create([
            'active' => 1,
            'deposit_sale_rent' => '150.50',
            'permanent_rent' => '200.00',
            'deposit_sale_commission' => '10.5',
            'permanent_commission' => '15.0',
        ]);

        // Recharger le modèle depuis la base de données pour vérifier les casts
        $shop->refresh();

        $this->assertIsBool($shop->active);
        // Les casts decimal retournent des chaînes, pas des floats
        $this->assertIsString($shop->deposit_sale_rent);
        $this->assertIsString($shop->permanent_rent);
        $this->assertIsString($shop->deposit_sale_commission);
        $this->assertIsString($shop->permanent_commission);
    }

    public function test_shop_relations(): void
    {
        $shop = Shop::factory()->create();
        $user = User::factory()->create();

        // Test user relation
        $shop->user()->associate($user);
        $shop->save();

        $this->assertInstanceOf(User::class, $shop->user);
        $this->assertEquals($user->id, $shop->user->id);
    }

    public function test_shop_full_address_with_country(): void
    {
        $shop = Shop::factory()->create([
            'address' => '456 Main Street',
            'postal_code' => '12345',
            'city' => 'New York',
            'country' => 'USA',
        ]);

        // Les mutateurs transforment "New York" en "New york" et "USA" en "Usa"
        $this->assertEquals('456 Main Street, 12345, New york, Usa', $shop->full_address);
    }

    public function test_shop_full_address_missing_parts(): void
    {
        $shop = Shop::factory()->create([
            'address' => '789 Oak Avenue',
            'postal_code' => null,
            'city' => 'London',
            'country' => null,
        ]);

        $this->assertEquals('789 Oak Avenue, London', $shop->full_address);
    }

    public function test_shop_full_address_only_name(): void
    {
        $shop = Shop::factory()->create([
            'name' => 'Minimal Shop',
            'address' => null,
            'postal_code' => null,
            'city' => null,
            'country' => null,
        ]);

        $this->assertEquals('', $shop->full_address);
    }
}
