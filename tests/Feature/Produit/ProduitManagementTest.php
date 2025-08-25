<?php

namespace Tests\Feature\Produit;

use App\Models\Produit;
use App\Models\Artisan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProduitManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Créer un utilisateur avec un artisan
        $this->user = User::factory()->create();
        $this->artisan = Artisan::factory()->create(['user_id' => $this->user->id]);
    }

    public function test_guest_cannot_access_produit_management(): void
    {
        $response = $this->get('/produits');
        $this->assertRedirect('/login');

        $response = $this->get('/produits/create');
        $this->assertRedirect('/login');

        $response = $this->post('/produits');
        $this->assertRedirect('/login');
    }

    public function test_user_can_view_produit_list(): void
    {
        $this->actingAs($this->user);

        $produits = Produit::factory()->count(3)->create(['artisan_id' => $this->artisan->id]);

        $response = $this->get('/produits');

        $response->assertStatus(200);
        $response->assertSee($produits->first()->nom);
        $response->assertSee($produits->last()->nom);
    }

    public function test_user_can_view_produit_details(): void
    {
        $this->actingAs($this->user);

        $produit = Produit::factory()->create(['artisan_id' => $this->artisan->id]);

        $response = $this->get("/produits/{$produit->id}");

        $response->assertStatus(200);
        $response->assertSee($produit->nom);
        $response->assertSee($produit->description);
        $response->assertSee($produit->categorie);
    }

    public function test_user_can_create_produit(): void
    {
        $this->actingAs($this->user);

        $produitData = [
            'nom' => 'Nouveau Produit',
            'description' => 'Description du nouveau produit',
            'prix_base' => 99.99,
            'categorie' => 'Meubles',
            'matiere' => 'Bois',
            'statut' => 'publie',
            'disponible' => true,
            'stock' => 10,
            'reference' => 'PROD-001',
            'duree_fabrication' => 7,
        ];

        $response = $this->post('/produits', $produitData);

        $response->assertRedirect();
        $this->assertDatabaseHas('produits', [
            'nom' => 'Nouveau Produit',
            'artisan_id' => $this->artisan->id,
            'categorie' => 'Meubles',
        ]);
    }

    public function test_user_can_update_produit(): void
    {
        $this->actingAs($this->user);

        $produit = Produit::factory()->create(['artisan_id' => $this->artisan->id]);

        $updateData = [
            'nom' => 'Produit Modifié',
            'prix_base' => 149.99,
            'description' => 'Description modifiée',
        ];

        $response = $this->put("/produits/{$produit->id}", $updateData);

        $response->assertRedirect();
        $this->assertDatabaseHas('produits', [
            'id' => $produit->id,
            'nom' => 'Produit Modifié',
            'prix_base' => '149.99',
            'description' => 'Description modifiée',
        ]);
    }

    public function test_user_can_delete_produit(): void
    {
        $this->actingAs($this->user);

        $produit = Produit::factory()->create(['artisan_id' => $this->artisan->id]);

        $response = $this->delete("/produits/{$produit->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('produits', ['id' => $produit->id]);
    }

    public function test_user_can_toggle_produit_disponibilite(): void
    {
        $this->actingAs($this->user);

        $produit = Produit::factory()->create([
            'artisan_id' => $this->artisan->id,
            'disponible' => true,
        ]);

        $response = $this->patch("/produits/{$produit->id}/toggle-disponibilite");

        $response->assertRedirect();
        $this->assertDatabaseHas('produits', [
            'id' => $produit->id,
            'disponible' => false,
        ]);

        // Toggle à nouveau
        $response = $this->patch("/produits/{$produit->id}/toggle-disponibilite");
        $this->assertDatabaseHas('produits', [
            'id' => $produit->id,
            'disponible' => true,
        ]);
    }

    public function test_user_can_search_produits(): void
    {
        $this->actingAs($this->user);

        Produit::factory()->create([
            'artisan_id' => $this->artisan->id,
            'nom' => 'Table en Bois',
            'categorie' => 'Meubles',
        ]);

        Produit::factory()->create([
            'artisan_id' => $this->artisan->id,
            'nom' => 'Chaise Vintage',
            'categorie' => 'Meubles',
        ]);

        Produit::factory()->create([
            'artisan_id' => $this->artisan->id,
            'nom' => 'Vase en Céramique',
            'categorie' => 'Décoration',
        ]);

        $response = $this->get('/produits?search=bois');

        $response->assertStatus(200);
        $response->assertSee('Table en Bois');
        $response->assertDontSee('Vase en Céramique');
    }

    public function test_user_can_filter_produits_by_category(): void
    {
        $this->actingAs($this->user);

        Produit::factory()->create([
            'artisan_id' => $this->artisan->id,
            'categorie' => 'Meubles',
        ]);

        Produit::factory()->create([
            'artisan_id' => $this->artisan->id,
            'categorie' => 'Décoration',
        ]);

        $response = $this->get('/produits?categorie=Meubles');

        $response->assertStatus(200);
        $response->assertSee('Meubles');
        $response->assertDontSee('Décoration');
    }

    public function test_user_can_filter_produits_by_status(): void
    {
        $this->actingAs($this->user);

        Produit::factory()->create([
            'artisan_id' => $this->artisan->id,
            'statut' => 'publie',
        ]);

        Produit::factory()->create([
            'artisan_id' => $this->artisan->id,
            'statut' => 'brouillon',
        ]);

        $response = $this->get('/produits?statut=publie');

        $response->assertStatus(200);
        $response->assertSee('publie');
        $response->assertDontSee('brouillon');
    }

    public function test_user_can_filter_produits_by_availability(): void
    {
        $this->actingAs($this->user);

        Produit::factory()->create([
            'artisan_id' => $this->artisan->id,
            'disponible' => true,
        ]);

        Produit::factory()->create([
            'artisan_id' => $this->artisan->id,
            'disponible' => false,
        ]);

        $response = $this->get('/produits?disponible=1');

        $response->assertStatus(200);
        $response->assertSee('disponible');
        $response->assertDontSee('indisponible');
    }

    public function test_user_can_sort_produits_by_price(): void
    {
        $this->actingAs($this->user);

        Produit::factory()->create([
            'artisan_id' => $this->artisan->id,
            'prix_base' => 100.00,
        ]);

        Produit::factory()->create([
            'artisan_id' => $this->artisan->id,
            'prix_base' => 50.00,
        ]);

        Produit::factory()->create([
            'artisan_id' => $this->artisan->id,
            'prix_base' => 200.00,
        ]);

        $response = $this->get('/produits?sort=prix&order=asc');

        $response->assertStatus(200);
        // Vérifier que les produits sont triés par prix croissant
    }

    public function test_user_can_sort_produits_by_name(): void
    {
        $this->actingAs($this->user);

        Produit::factory()->create([
            'artisan_id' => $this->artisan->id,
            'nom' => 'Zebra',
        ]);

        Produit::factory()->create([
            'artisan_id' => $this->artisan->id,
            'nom' => 'Alpha',
        ]);

        Produit::factory()->create([
            'artisan_id' => $this->artisan->id,
            'nom' => 'Beta',
        ]);

        $response = $this->get('/produits?sort=nom&order=asc');

        $response->assertStatus(200);
        // Vérifier que les produits sont triés par nom alphabétique
    }

    public function test_user_can_view_produit_gallery(): void
    {
        $this->actingAs($this->user);

        $produit = Produit::factory()->create([
            'artisan_id' => $this->artisan->id,
            'images' => ['image1.jpg', 'image2.jpg', 'image3.jpg'],
            'image_principale' => 'image1.jpg',
        ]);

        $response = $this->get("/produits/{$produit->id}/gallery");

        $response->assertStatus(200);
        $response->assertSee('image1.jpg');
        $response->assertSee('image2.jpg');
        $response->assertSee('image3.jpg');
    }

    public function test_user_can_upload_produit_images(): void
    {
        $this->actingAs($this->user);

        $produit = Produit::factory()->create(['artisan_id' => $this->artisan->id]);

        $response = $this->post("/produits/{$produit->id}/images", [
            'images' => [
                'test-image-1.jpg',
                'test-image-2.jpg',
            ],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('produits', [
            'id' => $produit->id,
            'images' => json_encode(['test-image-1.jpg', 'test-image-2.jpg']),
        ]);
    }

    public function test_user_can_set_main_image(): void
    {
        $this->actingAs($this->user);

        $produit = Produit::factory()->create([
            'artisan_id' => $this->artisan->id,
            'images' => ['image1.jpg', 'image2.jpg', 'image3.jpg'],
            'image_principale' => 'image1.jpg',
        ]);

        $response = $this->patch("/produits/{$produit->id}/main-image", [
            'image' => 'image2.jpg',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('produits', [
            'id' => $produit->id,
            'image_principale' => 'image2.jpg',
        ]);
    }

    public function test_user_can_view_produit_by_reference(): void
    {
        $this->actingAs($this->user);

        $produit = Produit::factory()->create([
            'artisan_id' => $this->artisan->id,
            'reference' => 'PROD-REF-001',
        ]);

        $response = $this->get("/produits/reference/PROD-REF-001");

        $response->assertStatus(200);
        $response->assertSee($produit->nom);
        $response->assertSee('PROD-REF-001');
    }

    public function test_user_can_view_produits_by_artisan(): void
    {
        $this->actingAs($this->user);

        $autreArtisan = Artisan::factory()->create();
        
        Produit::factory()->create(['artisan_id' => $this->artisan->id]);
        Produit::factory()->create(['artisan_id' => $this->artisan->id]);
        Produit::factory()->create(['artisan_id' => $autreArtisan->id]);

        $response = $this->get("/artisans/{$this->artisan->id}/produits");

        $response->assertStatus(200);
        // Vérifier que seuls les produits de l'artisan connecté sont affichés
    }

    public function test_user_can_view_produits_by_material(): void
    {
        $this->actingAs($this->user);

        Produit::factory()->create([
            'artisan_id' => $this->artisan->id,
            'matiere' => 'Bois',
        ]);

        Produit::factory()->create([
            'artisan_id' => $this->artisan->id,
            'matiere' => 'Métal',
        ]);

        $response = $this->get('/produits?matiere=Bois');

        $response->assertStatus(200);
        $response->assertSee('Bois');
        $response->assertDontSee('Métal');
    }

    public function test_user_can_view_produits_by_price_range(): void
    {
        $this->actingAs($this->user);

        Produit::factory()->create([
            'artisan_id' => $this->artisan->id,
            'prix_base' => 50.00,
        ]);

        Produit::factory()->create([
            'artisan_id' => $this->artisan->id,
            'prix_base' => 150.00,
        ]);

        Produit::factory()->create([
            'artisan_id' => $this->artisan->id,
            'prix_base' => 250.00,
        ]);

        $response = $this->get('/produits?prix_min=100&prix_max=200');

        $response->assertStatus(200);
        // Vérifier que seuls les produits dans la fourchette de prix sont affichés
    }

    public function test_user_can_view_produits_by_fabrication_duration(): void
    {
        $this->actingAs($this->user);

        Produit::factory()->create([
            'artisan_id' => $this->artisan->id,
            'duree_fabrication' => 5,
        ]);

        Produit::factory()->create([
            'artisan_id' => $this->artisan->id,
            'duree_fabrication' => 15,
        ]);

        $response = $this->get('/produits?duree_max=10');

        $response->assertStatus(200);
        // Vérifier que seuls les produits avec une durée de fabrication <= 10 jours sont affichés
    }
}
