<?php

namespace Tests\Unit\Controllers\Auth;

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Tests\TestCase;

class AuthenticatedSessionControllerTest extends TestCase
{
    use RefreshDatabase;

    protected AuthenticatedSessionController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new AuthenticatedSessionController();
    }

    public function test_create_method_returns_login_view(): void
    {
        $response = $this->controller->create();

        $this->assertInstanceOf(View::class, $response);
        $this->assertEquals('auth.login', $response->getName());
    }

    public function test_store_method_authenticates_user_and_redirects(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        // Créer une mock request
        $request = $this->createMock(LoginRequest::class);
        $request->shouldReceive('authenticate')->once();
        $request->shouldReceive('session->regenerate')->once();

        // Mock la redirection
        $request->shouldReceive('intended')
            ->with(RouteServiceProvider::HOME)
            ->andReturn('/dashboard');

        $response = $this->controller->store($request);

        $this->assertEquals('/dashboard', $response->getTargetUrl());
    }

    public function test_destroy_method_logs_out_user_and_redirects(): void
    {
        $user = User::factory()->create();

        // Créer une mock request
        $request = $this->createMock(Request::class);
        $request->shouldReceive('session->invalidate')->once();
        $request->shouldReceive('session->regenerateToken')->once();

        // Mock Auth::guard
        $guard = $this->createMock(\Illuminate\Contracts\Auth\StatefulGuard::class);
        $guard->shouldReceive('logout')->once();
        
        Auth::shouldReceive('guard')
            ->with('web')
            ->andReturn($guard);

        $response = $this->controller->destroy($request);

        $this->assertEquals('/', $response->getTargetUrl());
    }

    public function test_store_method_handles_authentication_failure(): void
    {
        $request = $this->createMock(LoginRequest::class);
        
        // Simuler une échec d'authentification
        $request->shouldReceive('authenticate')
            ->andThrow(new \Illuminate\Validation\ValidationException(
                validator([], []),
                response()->json(['message' => 'Invalid credentials'], 422)
            ));

        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $this->controller->store($request);
    }

    public function test_destroy_method_handles_session_errors(): void
    {
        $user = User::factory()->create();

        $request = $this->createMock(Request::class);
        
        // Simuler une erreur de session
        $request->shouldReceive('session->invalidate')
            ->andThrow(new \Exception('Session error'));

        $guard = $this->createMock(\Illuminate\Contracts\Auth\StatefulGuard::class);
        $guard->shouldReceive('logout')->once();
        
        Auth::shouldReceive('guard')
            ->with('web')
            ->andReturn($guard);

        $this->expectException(\Exception::class);

        $this->controller->destroy($request);
    }

    public function test_create_method_returns_correct_view_data(): void
    {
        $response = $this->controller->create();

        $this->assertInstanceOf(View::class, $response);
        
        // Vérifier que la vue est bien 'auth.login'
        $this->assertEquals('auth.login', $response->getName());
    }

    public function test_store_method_regenerates_session(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $request = $this->createMock(LoginRequest::class);
        $request->shouldReceive('authenticate')->once();
        
        // Vérifier que la session est régénérée
        $request->shouldReceive('session->regenerate')->once();

        $request->shouldReceive('intended')
            ->with(RouteServiceProvider::HOME)
            ->andReturn('/dashboard');

        $this->controller->store($request);
    }

    public function test_destroy_method_invalidates_session(): void
    {
        $user = User::factory()->create();

        $request = $this->createMock(Request::class);
        
        // Vérifier que la session est invalidée
        $request->shouldReceive('session->invalidate')->once();
        $request->shouldReceive('session->regenerateToken')->once();

        $guard = $this->createMock(\Illuminate\Contracts\Auth\StatefulGuard::class);
        $guard->shouldReceive('logout')->once();
        
        Auth::shouldReceive('guard')
            ->with('web')
            ->andReturn($guard);

        $this->controller->destroy($request);
    }

    public function test_destroy_method_regenerates_token(): void
    {
        $user = User::factory()->create();

        $request = $this->createMock(Request::class);
        $request->shouldReceive('session->invalidate')->once();
        
        // Vérifier que le token est régénéré
        $request->shouldReceive('session->regenerateToken')->once();

        $guard = $this->createMock(\Illuminate\Contracts\Auth\StatefulGuard::class);
        $guard->shouldReceive('logout')->once();
        
        Auth::shouldReceive('guard')
            ->with('web')
            ->andReturn($guard);

        $this->controller->destroy($request);
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
        $destroyMethod = $reflection->getMethod('destroy');

        $this->assertTrue($createMethod->isPublic());
        $this->assertTrue($storeMethod->isPublic());
        $this->assertTrue($destroyMethod->isPublic());
    }
}
