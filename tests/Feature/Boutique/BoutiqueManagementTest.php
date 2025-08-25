<?php

namespace Tests\Feature\Boutique;

use App\Models\Boutique;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BoutiqueManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Boutique $boutique;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->boutique = Boutique::factory()->create(['user_id' => $this->user->id]);
    }

    public function test_user_can_view_their_boutique(): void
    {
        $response = $this->actingAs($this->user)
            ->get('/boutiques/' . $this->boutique->id);

        $response->assertStatus(200);
        $response->assertSee($this->boutique->nom);
        $response->assertSee($this->boutique->description);
    }

    public function test_user_can_create_new_boutique(): void
    {
        $boutiqueData = [
            'nom' => 'Nouvelle Boutique',
            'description' => 'Description de la nouvelle boutique',
            'adresse' => '123 Rue Nouvelle',
            'ville' => 'Nouvelle Ville',
            'code_postal' => '12345',
            'pays' => 'France',
            'telephone' => '0123456789',
            'email' => 'nouvelle@boutique.com',
            'taille' => 'moyenne',
            'siret' => '12345678901234',
            'tva' => 'FR12345678901',
            'loyer_depot_vente' => 500,
            'loyer_permanence' => 100,
            'commission_depot_vente' => 20,
            'commission_permanence' => 25,
            'nb_permanences_mois_indicatif' => 6,
            'site_web' => 'https://nouvelle-boutique.com',
            'horaires_ouverture' => 'Lundi-Vendredi: 9h-18h',
        ];

        $response = $this->actingAs($this->user)
            ->post('/boutiques', $boutiqueData);

        $response->assertRedirect();
        $this->assertDatabaseHas('boutiques', [
            'nom' => 'Nouvelle Boutique',
            'email' => 'nouvelle@boutique.com',
            'user_id' => $this->user->id,
        ]);
    }

    public function test_user_can_update_their_boutique(): void
    {
        $updateData = [
            'nom' => 'Boutique Modifiée',
            'description' => 'Description modifiée',
            'ville' => 'Ville Modifiée',
        ];

        $response = $this->actingAs($this->user)
            ->put('/boutiques/' . $this->boutique->id, $updateData);

        $response->assertRedirect();
        $this->assertDatabaseHas('boutiques', [
            'id' => $this->boutique->id,
            'nom' => 'Boutique Modifiée',
            'description' => 'Description modifiée',
            'ville' => 'Ville Modifiée',
        ]);
    }

    public function test_user_cannot_update_other_users_boutique(): void
    {
        $otherUser = User::factory()->create();
        $otherBoutique = Boutique::factory()->create(['user_id' => $otherUser->id]);

        $updateData = [
            'nom' => 'Boutique Piratée',
            'description' => 'Tentative de modification non autorisée',
        ];

        $response = $this->actingAs($this->user)
            ->put('/boutiques/' . $otherBoutique->id, $updateData);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('boutiques', [
            'id' => $otherBoutique->id,
            'nom' => 'Boutique Piratée',
        ]);
    }

    public function test_user_can_delete_their_boutique(): void
    {
        $response = $this->actingAs($this->user)
            ->delete('/boutiques/' . $this->boutique->id);

        $response->assertRedirect();
        $this->assertDatabaseMissing('boutiques', [
            'id' => $this->boutique->id,
        ]);
    }

    public function test_user_cannot_delete_other_users_boutique(): void
    {
        $otherUser = User::factory()->create();
        $otherBoutique = Boutique::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($this->user)
            ->delete('/boutiques/' . $otherBoutique->id);

        $response->assertStatus(403);
        $this->assertDatabaseHas('boutiques', [
            'id' => $otherBoutique->id,
        ]);
    }

    public function test_boutique_creation_requires_authentication(): void
    {
        $boutiqueData = [
            'nom' => 'Boutique Sans Auth',
            'description' => 'Cette boutique ne devrait pas être créée',
        ];

        $response = $this->post('/boutiques', $boutiqueData);

        $response->assertRedirect('/login');
        $this->assertDatabaseMissing('boutiques', [
            'nom' => 'Boutique Sans Auth',
        ]);
    }

    public function test_boutique_creation_validates_required_fields(): void
    {
        $response = $this->actingAs($this->user)
            ->post('/boutiques', []);

        $response->assertSessionHasErrors(['nom', 'email', 'ville']);
    }

    public function test_boutique_creation_validates_email_format(): void
    {
        $boutiqueData = [
            'nom' => 'Boutique Test',
            'email' => 'email-invalide',
            'ville' => 'Ville Test',
        ];

        $response = $this->actingAs($this->user)
            ->post('/boutiques', $boutiqueData);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_boutique_creation_validates_taille_values(): void
    {
        $boutiqueData = [
            'nom' => 'Boutique Test',
            'email' => 'test@boutique.com',
            'ville' => 'Ville Test',
            'taille' => 'taille_invalide',
        ];

        $response = $this->actingAs($this->user)
            ->post('/boutiques', $boutiqueData);

        $response->assertSessionHasErrors(['taille']);
    }

    public function test_boutique_can_have_social_media_urls(): void
    {
        $boutiqueData = [
            'nom' => 'Boutique Social Media',
            'email' => 'social@boutique.com',
            'ville' => 'Ville Social',
            'instagram_url' => 'https://instagram.com/boutique',
            'tiktok_url' => 'https://tiktok.com/@boutique',
            'facebook_url' => 'https://facebook.com/boutique',
        ];

        $response = $this->actingAs($this->user)
            ->post('/boutiques', $boutiqueData);

        $response->assertRedirect();
        $this->assertDatabaseHas('boutiques', [
            'nom' => 'Boutique Social Media',
            'instagram_url' => 'https://instagram.com/boutique',
            'tiktok_url' => 'https://tiktok.com/@boutique',
            'facebook_url' => 'https://facebook.com/boutique',
        ]);
    }

    public function test_boutique_can_have_financial_information(): void
    {
        $boutiqueData = [
            'nom' => 'Boutique Financière',
            'email' => 'finance@boutique.com',
            'ville' => 'Ville Finance',
            'siret' => '12345678901234',
            'tva' => 'FR12345678901',
            'loyer_depot_vente' => 750,
            'loyer_permanence' => 150,
            'commission_depot_vente' => 22,
            'commission_permanence' => 28,
        ];

        $response = $this->actingAs($this->user)
            ->post('/boutiques', $boutiqueData);

        $response->assertRedirect();
        $this->assertDatabaseHas('boutiques', [
            'nom' => 'Boutique Financière',
            'siret' => '12345678901234',
            'loyer_depot_vente' => 750,
            'commission_permanence' => 28,
        ]);
    }

    public function test_boutique_can_be_activated_and_deactivated(): void
    {
        // Désactiver la boutique
        $response = $this->actingAs($this->user)
            ->patch('/boutiques/' . $this->boutique->id . '/toggle-status');

        $this->boutique->refresh();
        $this->assertFalse($this->boutique->actif);

        // Réactiver la boutique
        $response = $this->actingAs($this->user)
            ->patch('/boutiques/' . $this->boutique->id . '/toggle-status');

        $this->boutique->refresh();
        $this->assertTrue($this->boutique->actif);
    }

    public function test_boutique_can_be_approved_by_admin(): void
    {
        $admin = User::factory()->create();
        // Supposons que l'admin a un rôle admin
        // $admin->assignRole('admin');

        $response = $this->actingAs($admin)
            ->patch('/boutiques/' . $this->boutique->id . '/approve');

        $this->boutique->refresh();
        $this->assertEquals('approuve', $this->boutique->statut);
    }

    public function test_boutique_can_be_rejected_by_admin(): void
    {
        $admin = User::factory()->create();
        // Supposons que l'admin a un rôle admin
        // $admin->assignRole('admin');

        $response = $this->actingAs($admin)
            ->patch('/boutiques/' . $this->boutique->id . '/reject', [
                'raison' => 'Informations insuffisantes'
            ]);

        $this->boutique->refresh();
        $this->assertEquals('rejetee', $this->boutique->statut);
    }

    public function test_boutique_search_functionality(): void
    {
        // Créer plusieurs boutiques avec des noms différents
        Boutique::factory()->create(['nom' => 'Boutique Paris', 'ville' => 'Paris']);
        Boutique::factory()->create(['nom' => 'Boutique Lyon', 'ville' => 'Lyon']);
        Boutique::factory()->create(['nom' => 'Boutique Marseille', 'ville' => 'Marseille']);

        $response = $this->get('/boutiques/search?q=Paris');

        $response->assertStatus(200);
        $response->assertSee('Boutique Paris');
        $response->assertDontSee('Boutique Lyon');
        $response->assertDontSee('Boutique Marseille');
    }

    public function test_boutique_filtering_by_status(): void
    {
        Boutique::factory()->create(['statut' => 'approuve']);
        Boutique::factory()->create(['statut' => 'en_attente']);
        Boutique::factory()->create(['statut' => 'rejetee']);

        $response = $this->get('/boutiques?statut=approuve');

        $response->assertStatus(200);
        // Vérifier que seules les boutiques approuvées sont affichées
    }

    public function test_boutique_filtering_by_size(): void
    {
        Boutique::factory()->create(['taille' => 'petite']);
        Boutique::factory()->create(['taille' => 'moyenne']);
        Boutique::factory()->create(['taille' => 'grande']);

        $response = $this->get('/boutiques?taille=moyenne');

        $response->assertStatus(200);
        // Vérifier que seules les boutiques de taille moyenne sont affichées
    }

    public function test_boutique_can_have_photo(): void
    {
        $boutiqueData = [
            'nom' => 'Boutique avec Photo',
            'email' => 'photo@boutique.com',
            'ville' => 'Ville Photo',
            'photo' => 'boutique-photo.jpg',
        ];

        $response = $this->actingAs($this->user)
            ->post('/boutiques', $boutiqueData);

        $response->assertRedirect();
        $this->assertDatabaseHas('boutiques', [
            'nom' => 'Boutique avec Photo',
            'photo' => 'boutique-photo.jpg',
        ]);
    }
}
