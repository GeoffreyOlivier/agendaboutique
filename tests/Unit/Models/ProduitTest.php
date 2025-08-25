<?php

namespace Tests\Unit\Models;

use App\Models\Produit;
use App\Models\Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProduitTest extends TestCase
{
    use RefreshDatabase;

    public function test_produit_can_be_created(): void
    {
        $produit = Produit::factory()->create([
            'nom' => 'Table en Bois',
            'categorie' => 'Meubles',
        ]);

        $this->assertDatabaseHas('produits', [
            'nom' => 'Table en Bois',
            'categorie' => 'Meubles',
        ]);

        $this->assertInstanceOf(Produit::class, $produit);
    }

    public function test_produit_has_required_attributes(): void
    {
        $produit = Produit::factory()->create([
            'nom' => 'Chaise Vintage',
            'description' => 'Une belle chaise vintage',
            'categorie' => 'Meubles',
            'prix_base' => 150.00,
        ]);

        $this->assertEquals('Chaise Vintage', $produit->nom);
        $this->assertEquals('Une belle chaise vintage', $produit->description);
        $this->assertEquals('Meubles', $produit->categorie);
        $this->assertEquals(150.00, $produit->prix_base);
        $this->assertNotNull($produit->created_at);
        $this->assertNotNull($produit->updated_at);
    }

    public function test_produit_belongs_to_artisan(): void
    {
        $artisan = Artisan::factory()->create();
        $produit = Produit::factory()->create(['artisan_id' => $artisan->id]);

        $this->assertInstanceOf(Artisan::class, $produit->artisan);
        $this->assertEquals($artisan->id, $produit->artisan->id);
    }

    public function test_produit_scope_disponibles(): void
    {
        Produit::factory()->create(['disponible' => true]);
        Produit::factory()->create(['disponible' => false]);
        Produit::factory()->create(['disponible' => true]);

        $disponibles = Produit::disponibles()->get();

        $this->assertCount(2, $disponibles);
        $this->assertTrue($disponibles->every(fn($p) => $p->disponible === true));
    }

    public function test_produit_scope_actifs(): void
    {
        Produit::factory()->create(['statut' => 'brouillon']);
        Produit::factory()->create(['statut' => 'publie']);
        Produit::factory()->create(['statut' => 'archive']);
        Produit::factory()->create(['statut' => 'publie']);

        $actifs = Produit::actifs()->get();

        $this->assertCount(2, $actifs);
        $this->assertTrue($actifs->every(fn($p) => $p->statut === 'publie'));
    }

    public function test_produit_scope_par_categorie(): void
    {
        Produit::factory()->create(['categorie' => 'Meubles']);
        Produit::factory()->create(['categorie' => 'Décoration']);
        Produit::factory()->create(['categorie' => 'Meubles']);

        $meubles = Produit::parCategorie('Meubles')->get();

        $this->assertCount(2, $meubles);
        $this->assertTrue($meubles->every(fn($p) => $p->categorie === 'Meubles'));
    }

    public function test_produit_accessor_prix_formate_prix_base(): void
    {
        $produit = Produit::factory()->create([
            'prix_base' => 125.50,
            'prix_min' => null,
            'prix_max' => null,
        ]);

        $this->assertEquals('125,50 €', $produit->prix_formate);
    }

    public function test_produit_accessor_prix_formate_fourchette(): void
    {
        $produit = Produit::factory()->create([
            'prix_base' => null,
            'prix_min' => 100.00,
            'prix_max' => 200.00,
        ]);

        $this->assertEquals('100,00 - 200,00 €', $produit->prix_formate);
    }

    public function test_produit_accessor_prix_formate_sur_demande(): void
    {
        $produit = Produit::factory()->create([
            'prix_base' => null,
            'prix_min' => null,
            'prix_max' => null,
        ]);

        $this->assertEquals('Prix sur demande', $produit->prix_formate);
    }

    public function test_produit_accessor_image_principale_directe(): void
    {
        $produit = Produit::factory()->create([
            'image_principale' => 'image-directe.jpg',
            'images' => ['image1.jpg', 'image2.jpg'],
        ]);

        $this->assertEquals('image-directe.jpg', $produit->image_principale);
    }

    public function test_produit_accessor_image_principale_premiere_image(): void
    {
        $produit = Produit::factory()->create([
            'image_principale' => null,
            'images' => ['image1.jpg', 'image2.jpg'],
        ]);

        $this->assertEquals('image1.jpg', $produit->image_principale);
    }

    public function test_produit_accessor_image_principale_null(): void
    {
        $produit = Produit::factory()->create([
            'image_principale' => null,
            'images' => null,
        ]);

        $this->assertNull($produit->image_principale);
    }

    public function test_produit_accessor_prix_alias(): void
    {
        $produit = Produit::factory()->create(['prix_base' => 99.99]);

        $this->assertEquals(99.99, $produit->prix);
    }

    public function test_produit_setter_prix_alias(): void
    {
        $produit = Produit::factory()->create();
        $produit->prix = 88.88;

        $this->assertEquals(88.88, $produit->prix_base);
    }

    public function test_produit_can_be_updated(): void
    {
        $produit = Produit::factory()->create([
            'nom' => 'Ancien Nom',
            'prix_base' => 100.00,
        ]);

        $produit->update([
            'nom' => 'Nouveau Nom',
            'prix_base' => 150.00,
        ]);

        $this->assertEquals('Nouveau Nom', $produit->nom);
        $this->assertEquals(150.00, $produit->prix_base);
        $this->assertDatabaseHas('produits', [
            'id' => $produit->id,
            'nom' => 'Nouveau Nom',
            'prix_base' => 150.00,
        ]);
    }

    public function test_produit_can_be_deleted(): void
    {
        $produit = Produit::factory()->create();

        $produitId = $produit->id;
        $produit->delete();

        $this->assertDatabaseMissing('produits', [
            'id' => $produitId,
        ]);
    }

    public function test_produit_factory_creates_valid_produit(): void
    {
        $produit = Produit::factory()->create();

        $this->assertInstanceOf(Produit::class, $produit);
        $this->assertNotEmpty($produit->nom);
        $this->assertNotEmpty($produit->description);
        $this->assertNotEmpty($produit->categorie);
        $this->assertDatabaseHas('produits', [
            'id' => $produit->id,
            'nom' => $produit->nom,
            'categorie' => $produit->categorie,
        ]);
    }

    public function test_produit_factory_creates_multiple_produits(): void
    {
        $produits = Produit::factory()->count(3)->create();

        $this->assertCount(3, $produits);
        $this->assertInstanceOf(Produit::class, $produits->first());
        $this->assertInstanceOf(Produit::class, $produits->last());
    }

    public function test_produit_factory_states(): void
    {
        $produitPrixMasque = Produit::factory()->prixMasque()->create();
        $produitPrixFixe = Produit::factory()->prixFixe()->create();
        $produitFourchette = Produit::factory()->fourchettePrix()->create();
        $produitDisponible = Produit::factory()->disponible()->create();
        $produitIndisponible = Produit::factory()->indisponible()->create();
        $produitBrouillon = Produit::factory()->brouillon()->create();
        $produitPublie = Produit::factory()->publie()->create();

        $this->assertTrue($produitPrixMasque->prix_masque);
        $this->assertNotNull($produitPrixFixe->prix_base);
        $this->assertNotNull($produitFourchette->prix_min);
        $this->assertNotNull($produitFourchette->prix_max);
        $this->assertTrue($produitDisponible->disponible);
        $this->assertFalse($produitIndisponible->disponible);
        $this->assertEquals('brouillon', $produitBrouillon->statut);
        $this->assertEquals('publie', $produitPublie->statut);
    }

    public function test_produit_factory_custom_states(): void
    {
        $produit = Produit::factory()
            ->categorie('Bijoux')
            ->matiere('Argent')
            ->dimensions(10, 5, 2)
            ->images(['bijou1.jpg', 'bijou2.jpg'])
            ->tags(['élégant', 'moderne', 'unique'])
            ->dureeFabrication(7)
            ->create();

        $this->assertEquals('Bijoux', $produit->categorie);
        $this->assertEquals('Argent', $produit->matiere);
        $this->assertEquals(10, $produit->dimensions['longueur']);
        $this->assertEquals(5, $produit->dimensions['largeur']);
        $this->assertEquals(2, $produit->dimensions['hauteur']);
        $this->assertContains('bijou1.jpg', $produit->images);
        $this->assertContains('élégant', $produit->tags);
        $this->assertEquals(7, $produit->duree_fabrication);
    }

    public function test_produit_boolean_casts(): void
    {
        $produit = Produit::factory()->create([
            'prix_masque' => true,
            'disponible' => true,
        ]);

        $this->assertIsBool($produit->prix_masque);
        $this->assertIsBool($produit->disponible);
        $this->assertTrue($produit->prix_masque);
        $this->assertTrue($produit->disponible);

        $produit->update([
            'prix_masque' => false,
            'disponible' => false,
        ]);

        $this->assertFalse($produit->prix_masque);
        $this->assertFalse($produit->disponible);
    }

    public function test_produit_decimal_casts(): void
    {
        $produit = Produit::factory()->create([
            'prix_base' => 123.45,
            'prix_min' => 100.00,
            'prix_max' => 150.00,
        ]);

        // Les casts decimal:2 retournent des chaînes, pas des floats
        $this->assertIsString($produit->prix_base);
        $this->assertIsString($produit->prix_min);
        $this->assertIsString($produit->prix_max);
        $this->assertEquals('123.45', $produit->prix_base);
        $this->assertEquals('100.00', $produit->prix_min);
        $this->assertEquals('150.00', $produit->prix_max);
    }

    public function test_produit_array_casts(): void
    {
        $tags = ['artisanat', 'fait-main', 'unique'];
        $dimensions = ['longueur' => 50, 'largeur' => 30, 'hauteur' => 20];
        $images = ['image1.jpg', 'image2.jpg'];

        $produit = Produit::factory()->create([
            'tags' => $tags,
            'dimensions' => $dimensions,
            'images' => $images,
        ]);

        $this->assertIsArray($produit->tags);
        $this->assertIsArray($produit->dimensions);
        $this->assertIsArray($produit->images);
        $this->assertEquals($tags, $produit->tags);
        $this->assertEquals($dimensions, $produit->dimensions);
        $this->assertEquals($images, $produit->images);
    }

    public function test_produit_relationships_are_loaded(): void
    {
        $artisan = Artisan::factory()->create();
        $produit = Produit::factory()->create(['artisan_id' => $artisan->id]);

        $produit->load('artisan');

        $this->assertTrue($produit->relationLoaded('artisan'));
        $this->assertEquals($artisan->id, $produit->artisan->id);
    }

    public function test_produit_has_fillable_attributes(): void
    {
        $produit = Produit::factory()->create([
            'nom' => 'Produit Test',
            'description' => 'Description test',
            'prix_base' => 99.99,
            'categorie' => 'Test',
            'tags' => ['test1', 'test2'],
            'matiere' => 'Test',
            'statut' => 'publie',
            'disponible' => true,
            'stock' => 10,
            'reference' => 'TEST-001',
            'duree_fabrication' => 5,
        ]);

        $this->assertEquals('Produit Test', $produit->nom);
        $this->assertEquals('Description test', $produit->description);
        $this->assertEquals(99.99, $produit->prix_base);
        $this->assertEquals('Test', $produit->categorie);
        $this->assertContains('test1', $produit->tags);
        $this->assertEquals('Test', $produit->matiere);
        $this->assertEquals('publie', $produit->statut);
        $this->assertTrue($produit->disponible);
        $this->assertEquals(10, $produit->stock);
        $this->assertEquals('TEST-001', $produit->reference);
        $this->assertEquals(5, $produit->duree_fabrication);
    }
}
