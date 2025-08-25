<?php

namespace Tests\Unit\Models;

use App\Models\Boutique;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BoutiqueTest extends TestCase
{
    use RefreshDatabase;

    public function test_boutique_can_be_created(): void
    {
        $boutique = Boutique::factory()->create([
            'nom' => 'Ma Boutique',
            'ville' => 'Paris',
        ]);

        $this->assertDatabaseHas('boutiques', [
            'nom' => 'Ma Boutique',
            'ville' => 'Paris',
        ]);

        $this->assertInstanceOf(Boutique::class, $boutique);
    }

    public function test_boutique_has_required_attributes(): void
    {
        $boutique = Boutique::factory()->create([
            'nom' => 'Test Boutique',
            'email' => 'test@boutique.com',
            'taille' => 'moyenne',
        ]);

        $this->assertEquals('Test Boutique', $boutique->nom);
        $this->assertEquals('test@boutique.com', $boutique->email);
        $this->assertEquals('moyenne', $boutique->taille);
        $this->assertNotNull($boutique->created_at);
        $this->assertNotNull($boutique->updated_at);
    }

    public function test_boutique_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $boutique = Boutique::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $boutique->user);
        $this->assertEquals($user->id, $boutique->user->id);
    }

    public function test_boutique_scope_actives(): void
    {
        Boutique::factory()->create(['actif' => true]);
        Boutique::factory()->create(['actif' => false]);
        Boutique::factory()->create(['actif' => true]);

        $actives = Boutique::actives()->get();

        $this->assertCount(2, $actives);
        $this->assertTrue($actives->every(fn($b) => $b->actif === true));
    }

    public function test_boutique_accessor_adresse_complete(): void
    {
        $boutique = Boutique::factory()->create([
            'adresse' => '123 Rue de la Paix',
            'code_postal' => '75001',
            'ville' => 'Paris',
        ]);

        $this->assertEquals('123 Rue de la Paix, 75001 Paris', $boutique->adresse_complete);
    }

    public function test_boutique_accessor_nombre_exposants_petite(): void
    {
        $boutique = Boutique::factory()->create(['taille' => 'petite']);

        $this->assertEquals('1-5 exposants', $boutique->nombre_exposants);
    }

    public function test_boutique_accessor_nombre_exposants_moyenne(): void
    {
        $boutique = Boutique::factory()->create(['taille' => 'moyenne']);

        $this->assertEquals('6-15 exposants', $boutique->nombre_exposants);
    }

    public function test_boutique_accessor_nombre_exposants_grande(): void
    {
        $boutique = Boutique::factory()->create(['taille' => 'grande']);

        $this->assertEquals('16+ exposants', $boutique->nombre_exposants);
    }

    public function test_boutique_accessor_nom_complet(): void
    {
        $boutique = Boutique::factory()->create(['nom' => 'Ma Super Boutique']);

        $this->assertEquals('Ma Super Boutique', $boutique->nom_complet);
    }

    public function test_boutique_can_be_updated(): void
    {
        $boutique = Boutique::factory()->create([
            'nom' => 'Ancien Nom',
            'ville' => 'Ancienne Ville',
        ]);

        $boutique->update([
            'nom' => 'Nouveau Nom',
            'ville' => 'Nouvelle Ville',
        ]);

        $this->assertEquals('Nouveau Nom', $boutique->nom);
        $this->assertEquals('Nouvelle Ville', $boutique->ville);
        $this->assertDatabaseHas('boutiques', [
            'id' => $boutique->id,
            'nom' => 'Nouveau Nom',
            'ville' => 'Nouvelle Ville',
        ]);
    }

    public function test_boutique_can_be_deleted(): void
    {
        $boutique = Boutique::factory()->create();

        $boutiqueId = $boutique->id;
        $boutique->delete();

        $this->assertDatabaseMissing('boutiques', [
            'id' => $boutiqueId,
        ]);
    }

    public function test_boutique_factory_creates_valid_boutique(): void
    {
        $boutique = Boutique::factory()->create();

        $this->assertInstanceOf(Boutique::class, $boutique);
        $this->assertNotEmpty($boutique->nom);
        $this->assertNotEmpty($boutique->email);
        $this->assertNotEmpty($boutique->ville);
        $this->assertDatabaseHas('boutiques', [
            'id' => $boutique->id,
            'nom' => $boutique->nom,
            'email' => $boutique->email,
        ]);
    }

    public function test_boutique_factory_creates_multiple_boutiques(): void
    {
        $boutiques = Boutique::factory()->count(3)->create();

        $this->assertCount(3, $boutiques);
        $this->assertInstanceOf(Boutique::class, $boutiques->first());
        $this->assertInstanceOf(Boutique::class, $boutiques->last());
    }

    public function test_boutique_factory_states(): void
    {
        $boutiquePetite = Boutique::factory()->petite()->create();
        $boutiqueMoyenne = Boutique::factory()->moyenne()->create();
        $boutiqueGrande = Boutique::factory()->grande()->create();

        $this->assertEquals('petite', $boutiquePetite->taille);
        $this->assertEquals('moyenne', $boutiqueMoyenne->taille);
        $this->assertEquals('grande', $boutiqueGrande->taille);
    }

    public function test_boutique_factory_custom_states(): void
    {
        $boutique = Boutique::factory()
            ->horairesSpecifiques('Lundi-Dimanche: 24h/24')
            ->adresseSpecifique('Lyon', '69000')
            ->create();

        $this->assertEquals('Lundi-Dimanche: 24h/24', $boutique->horaires_ouverture);
        $this->assertEquals('Lyon', $boutique->ville);
        $this->assertEquals('69000', $boutique->code_postal);
    }

    public function test_boutique_boolean_cast(): void
    {
        $boutique = Boutique::factory()->create(['actif' => true]);

        $this->assertIsBool($boutique->actif);
        $this->assertTrue($boutique->actif);

        $boutique->update(['actif' => false]);

        $this->assertFalse($boutique->actif);
    }

    public function test_boutique_relationships_are_loaded(): void
    {
        $user = User::factory()->create();
        $boutique = Boutique::factory()->create(['user_id' => $user->id]);

        $boutique->load('user');

        $this->assertTrue($boutique->relationLoaded('user'));
        $this->assertEquals($user->id, $boutique->user->id);
    }

    public function test_boutique_has_fillable_attributes(): void
    {
        $boutique = Boutique::factory()->create([
            'nom' => 'Boutique Test',
            'description' => 'Description test',
            'adresse' => 'Adresse test',
            'ville' => 'Ville test',
            'code_postal' => '12345',
            'pays' => 'France',
            'telephone' => '0123456789',
            'email' => 'test@boutique.com',
            'taille' => 'petite',
            'siret' => '12345678901234',
            'tva' => 'FR12345678901',
            'loyer_depot_vente' => 500,
            'loyer_permanence' => 100,
            'commission_depot_vente' => 20,
            'commission_permanence' => 25,
            'nb_permanences_mois_indicatif' => 6,
            'site_web' => 'https://test.com',
            'instagram_url' => 'https://instagram.com/test',
            'tiktok_url' => 'https://tiktok.com/@test',
            'facebook_url' => 'https://facebook.com/test',
            'horaires_ouverture' => '9h-18h',
            'photo' => 'test.jpg',
            'statut' => 'en_attente',
            'actif' => true,
        ]);

        $this->assertEquals('Boutique Test', $boutique->nom);
        $this->assertEquals('Description test', $boutique->description);
        $this->assertEquals('Adresse test', $boutique->adresse);
        $this->assertEquals('Ville test', $boutique->ville);
        $this->assertEquals('12345', $boutique->code_postal);
        $this->assertEquals('France', $boutique->pays);
        $this->assertEquals('0123456789', $boutique->telephone);
        $this->assertEquals('test@boutique.com', $boutique->email);
        $this->assertEquals('petite', $boutique->taille);
        $this->assertEquals('12345678901234', $boutique->siret);
        $this->assertEquals('FR12345678901', $boutique->tva);
        $this->assertEquals(500, $boutique->loyer_depot_vente);
        $this->assertEquals(100, $boutique->loyer_permanence);
        $this->assertEquals(20, $boutique->commission_depot_vente);
        $this->assertEquals(25, $boutique->commission_permanence);
        $this->assertEquals(6, $boutique->nb_permanences_mois_indicatif);
        $this->assertEquals('https://test.com', $boutique->site_web);
        $this->assertEquals('https://instagram.com/test', $boutique->instagram_url);
        $this->assertEquals('https://tiktok.com/@test', $boutique->tiktok_url);
        $this->assertEquals('https://facebook.com/test', $boutique->facebook_url);
        $this->assertEquals('9h-18h', $boutique->horaires_ouverture);
        $this->assertEquals('test.jpg', $boutique->photo);
        $this->assertEquals('en_attente', $boutique->statut);
        $this->assertTrue($boutique->actif);
    }
}
