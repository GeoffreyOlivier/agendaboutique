<?php

namespace Tests\Unit\Controllers\Auth;

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Event;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RegisteredUserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected RegisteredUserController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new RegisteredUserController();
    }

    public function test_create_method_returns_register_view(): void
    {
        $response = $this->controller->create();

        $this->assertEquals('auth.register', $response->getName());
    }

    public function test_store_method_creates_user_and_redirects(): void
    {
        Event::fake();

        $request = $this->createMock(Request::class);
        $request->shouldReceive('validate')->andReturn([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
        ]);

        $request->shouldReceive('name')->andReturn('John Doe');
        $request->shouldReceive('email')->andReturn('john@example.com');
        $request->shouldReceive('password')->andReturn('password123');

        // Mock la redirection
        $request->shouldReceive('redirect')
            ->with(RouteServiceProvider::HOME)
            ->andReturn(redirect('/dashboard'));

        $response = $this->controller->store($request);

        $this->assertEquals('/dashboard', $response->getTargetUrl());

        // Vérifier que l'utilisateur a été créé
        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        Event::assertDispatched(Registered::class);
    }

    public function test_store_method_assigns_registered_role(): void
    {
        Event::fake();

        $request = $this->createMock(Request::class);
        $request->shouldReceive('validate')->andReturn([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'password123',
        ]);

        $request->shouldReceive('name')->andReturn('Jane Doe');
        $request->shouldReceive('email')->andReturn('jane@example.com');
        $request->shouldReceive('password')->andReturn('password123');

        $request->shouldReceive('redirect')
            ->with(RouteServiceProvider::HOME)
            ->andReturn(redirect('/dashboard'));

        $this->controller->store($request);

        // Vérifier que le rôle a été créé
        $this->assertDatabaseHas('roles', [
            'name' => 'registered',
            'guard_name' => 'web',
        ]);

        // Vérifier que l'utilisateur a le rôle
        $user = User::where('email', 'jane@example.com')->first();
        $this->assertTrue($user->hasRole('registered'));
    }

    public function test_store_method_creates_role_if_not_exists(): void
    {
        Event::fake();

        // Supprimer le rôle s'il existe
        Role::where('name', 'registered')->delete();

        $request = $this->createMock(Request::class);
        $request->shouldReceive('validate')->andReturn([
            'name' => 'New User',
            'email' => 'new@example.com',
            'password' => 'password123',
        ]);

        $request->shouldReceive('name')->andReturn('New User');
        $request->shouldReceive('email')->andReturn('new@example.com');
        $request->shouldReceive('password')->andReturn('password123');

        $request->shouldReceive('redirect')
            ->with(RouteServiceProvider::HOME)
            ->andReturn(redirect('/dashboard'));

        $this->controller->store($request);

        // Vérifier que le rôle a été créé
        $this->assertDatabaseHas('roles', [
            'name' => 'registered',
            'guard_name' => 'web',
        ]);
    }

    public function test_store_method_uses_existing_role(): void
    {
        Event::fake();

        // Créer le rôle manuellement
        $role = Role::create(['name' => 'registered', 'guard_name' => 'web']);

        $request = $this->createMock(Request::class);
        $request->shouldReceive('validate')->andReturn([
            'name' => 'Existing User',
            'email' => 'existing@example.com',
            'password' => 'password123',
        ]);

        $request->shouldReceive('name')->andReturn('Existing User');
        $request->shouldReceive('email')->andReturn('existing@example.com');
        $request->shouldReceive('password')->andReturn('password123');

        $request->shouldReceive('redirect')
            ->with(RouteServiceProvider::HOME)
            ->andReturn(redirect('/dashboard'));

        $this->controller->store($request);

        // Vérifier que l'utilisateur a le rôle existant
        $user = User::where('email', 'existing@example.com')->first();
        $this->assertTrue($user->hasRole('registered'));
    }

    public function test_store_method_hashes_password(): void
    {
        Event::fake();

        $request = $this->createMock(Request::class);
        $request->shouldReceive('validate')->andReturn([
            'name' => 'Password User',
            'email' => 'password@example.com',
            'password' => 'plainpassword',
        ]);

        $request->shouldReceive('name')->andReturn('Password User');
        $request->shouldReceive('email')->andReturn('password@example.com');
        $request->shouldReceive('password')->andReturn('plainpassword');

        $request->shouldReceive('redirect')
            ->with(RouteServiceProvider::HOME)
            ->andReturn(redirect('/dashboard'));

        $this->controller->store($request);

        $user = User::where('email', 'password@example.com')->first();
        $this->assertTrue(Hash::check('plainpassword', $user->password));
        $this->assertNotEquals('plainpassword', $user->password);
    }

    public function test_store_method_logs_in_user(): void
    {
        Event::fake();

        $request = $this->createMock(Request::class);
        $request->shouldReceive('validate')->andReturn([
            'name' => 'Login User',
            'email' => 'login@example.com',
            'password' => 'password123',
        ]);

        $request->shouldReceive('name')->andReturn('Login User');
        $request->shouldReceive('email')->andReturn('login@example.com');
        $request->shouldReceive('password')->andReturn('password123');

        $request->shouldReceive('redirect')
            ->with(RouteServiceProvider::HOME)
            ->andReturn(redirect('/dashboard'));

        $this->controller->store($request);

        // Vérifier que l'utilisateur est connecté
        $this->assertAuthenticated();
    }

    public function test_store_method_dispatches_registered_event(): void
    {
        Event::fake();

        $request = $this->createMock(Request::class);
        $request->shouldReceive('validate')->andReturn([
            'name' => 'Event User',
            'email' => 'event@example.com',
            'password' => 'password123',
        ]);

        $request->shouldReceive('name')->andReturn('Event User');
        $request->shouldReceive('email')->andReturn('event@example.com');
        $request->shouldReceive('password')->andReturn('password123');

        $request->shouldReceive('redirect')
            ->with(RouteServiceProvider::HOME)
            ->andReturn(redirect('/dashboard'));

        $this->controller->store($request);

        Event::assertDispatched(Registered::class, function ($event) {
            return $event->user->email === 'event@example.com';
        });
    }

    public function test_store_method_validation_failure(): void
    {
        $request = $this->createMock(Request::class);
        
        // Simuler une échec de validation
        $request->shouldReceive('validate')
            ->andThrow(new ValidationException(
                validator([], []),
                response()->json(['message' => 'Validation failed'], 422)
            ));

        $this->expectException(ValidationException::class);

        $this->controller->store($request);
    }

    public function test_controller_extends_base_controller(): void
    {
        $this->assertInstanceOf(\App\Http\Controllers\Controller::class, $this->controller);
    }

    public function test_controller_methods_are_public(): void
    {
        $reflection = new \ReflectionClass($this->controller);
        
        $createMethod = $reflection->getMethod('create');
        $storeMethod = $reflection->getMethod('store');

        $this->assertTrue($createMethod->isPublic());
        $this->assertTrue($storeMethod->isPublic());
    }

    public function test_create_method_returns_correct_view(): void
    {
        $response = $this->controller->create();
        
        $this->assertEquals('auth.register', $response->getName());
    }

    public function test_store_method_creates_user_with_correct_data(): void
    {
        Event::fake();

        $request = $this->createMock(Request::class);
        $request->shouldReceive('validate')->andReturn([
            'name' => 'Correct User',
            'email' => 'correct@example.com',
            'password' => 'correctpassword',
        ]);

        $request->shouldReceive('name')->andReturn('Correct User');
        $request->shouldReceive('email')->andReturn('correct@example.com');
        $request->shouldReceive('password')->andReturn('correctpassword');

        $request->shouldReceive('redirect')
            ->with(RouteServiceProvider::HOME)
            ->andReturn(redirect('/dashboard'));

        $this->controller->store($request);

        $this->assertDatabaseHas('users', [
            'name' => 'Correct User',
            'email' => 'correct@example.com',
        ]);

        $user = User::where('email', 'correct@example.com')->first();
        $this->assertEquals('Correct User', $user->name);
        $this->assertEquals('correct@example.com', $user->email);
    }
}
