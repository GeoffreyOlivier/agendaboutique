<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_with_shop_role()
    {
        // Créer le rôle shop
        Role::create(['name' => 'shop', 'guard_name' => 'web']);

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'shop',
        ]);

        $response->assertRedirect('/dashboard');
        
        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $user = User::where('email', 'test@example.com')->first();
        $this->assertTrue($user->hasRole('shop'));
    }

    public function test_user_can_register_with_craftsman_role()
    {
        // Créer le rôle craftsman
        Role::create(['name' => 'craftsman', 'guard_name' => 'web']);

        $response = $this->post('/register', [
            'name' => 'Test Artisan',
            'email' => 'artisan@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'craftsman',
        ]);

        $response->assertRedirect('/dashboard');
        
        $this->assertDatabaseHas('users', [
            'name' => 'Test Artisan',
            'email' => 'artisan@example.com',
        ]);

        $user = User::where('email', 'artisan@example.com')->first();
        $this->assertTrue($user->hasRole('craftsman'));
    }

    public function test_registration_requires_role()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            // Pas de rôle
        ]);

        $response->assertSessionHasErrors(['role']);
    }

    public function test_registration_validates_role_values()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'invalid_role',
        ]);

        $response->assertSessionHasErrors(['role']);
    }
}
