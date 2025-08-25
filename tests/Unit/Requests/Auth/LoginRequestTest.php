<?php

namespace Tests\Unit\Requests\Auth;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class LoginRequestTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Mock RateLimiter
        RateLimiter::shouldReceive('tooManyAttempts')->andReturn(false);
        RateLimiter::shouldReceive('hit')->andReturn(1);
        RateLimiter::shouldReceive('clear')->andReturn(true);
        RateLimiter::shouldReceive('availableIn')->andReturn(60);
    }

    public function test_login_request_authorization(): void
    {
        $request = new LoginRequest();
        
        $this->assertTrue($request->authorize());
    }

    public function test_login_request_validation_rules(): void
    {
        $request = new LoginRequest();
        
        $rules = $request->rules();
        
        $this->assertArrayHasKey('email', $rules);
        $this->assertArrayHasKey('password', $rules);
        $this->assertContains('required', $rules['email']);
        $this->assertContains('string', $rules['email']);
        $this->assertContains('email', $rules['email']);
        $this->assertContains('required', $rules['password']);
        $this->assertContains('string', $rules['password']);
    }

    public function test_login_request_authenticates_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $request = new LoginRequest();
        $request->merge([
            'email' => $user->email,
            'password' => 'password123',
        ]);

        // Mock Auth::attempt pour retourner true
        Auth::shouldReceive('attempt')
            ->with(['email' => $user->email, 'password' => 'password123'], false)
            ->andReturn(true);

        $request->authenticate();

        // Si on arrive ici, c'est que l'authentification a réussi
        $this->assertTrue(true);
    }

    public function test_login_request_fails_with_invalid_credentials(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $request = new LoginRequest();
        $request->merge([
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        // Mock Auth::attempt pour retourner false
        Auth::shouldReceive('attempt')
            ->with(['email' => $user->email, 'password' => 'wrong-password'], false)
            ->andReturn(false);

        // Mock RateLimiter::hit
        RateLimiter::shouldReceive('hit')->andReturn(1);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The provided credentials do not match our records.');

        $request->authenticate();
    }

    public function test_login_request_with_remember_me(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $request = new LoginRequest();
        $request->merge([
            'email' => $user->email,
            'password' => 'password123',
            'remember' => 'on',
        ]);

        // Mock Auth::attempt avec remember = true
        Auth::shouldReceive('attempt')
            ->with(['email' => $user->email, 'password' => 'password123'], true)
            ->andReturn(true);

        $request->authenticate();

        $this->assertTrue(true);
    }

    public function test_login_request_rate_limiting(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $request = new LoginRequest();
        $request->merge([
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        // Mock RateLimiter pour simuler trop de tentatives
        RateLimiter::shouldReceive('tooManyAttempts')
            ->andReturn(true);
        RateLimiter::shouldReceive('availableIn')
            ->andReturn(300);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Too many login attempts. Please try again in 5 minutes.');

        $request->ensureIsNotRateLimited();
    }

    public function test_login_request_throttle_key(): void
    {
        $request = new LoginRequest();
        $request->merge([
            'email' => 'test@example.com',
        ]);

        // Mock l'IP
        $request->shouldReceive('ip')->andReturn('127.0.0.1');

        $throttleKey = $request->throttleKey();
        
        // La clé devrait contenir l'email et l'IP
        $this->assertStringContainsString('test@example.com', $throttleKey);
        $this->assertStringContainsString('127.0.0.1', $throttleKey);
    }

    public function test_login_request_validation_fails_without_email(): void
    {
        $request = new LoginRequest();
        
        $validator = validator([], $request->rules());
        
        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('email'));
    }

    public function test_login_request_validation_fails_without_password(): void
    {
        $request = new LoginRequest();
        
        $validator = validator([], $request->rules());
        
        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('password'));
    }

    public function test_login_request_validation_fails_with_invalid_email(): void
    {
        $request = new LoginRequest();
        
        $validator = validator([
            'email' => 'invalid-email',
            'password' => 'password123',
        ], $request->rules());
        
        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('email'));
    }

    public function test_login_request_validation_passes_with_valid_data(): void
    {
        $request = new LoginRequest();
        
        $validator = validator([
            'email' => 'valid@example.com',
            'password' => 'password123',
        ], $request->rules());
        
        $this->assertFalse($validator->fails());
    }

    public function test_login_request_handles_empty_strings(): void
    {
        $request = new LoginRequest();
        
        $validator = validator([
            'email' => '',
            'password' => '',
        ], $request->rules());
        
        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('email'));
        $this->assertTrue($validator->errors()->has('password'));
    }

    public function test_login_request_handles_null_values(): void
    {
        $request = new LoginRequest();
        
        $validator = validator([
            'email' => null,
            'password' => null,
        ], $request->rules());
        
        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('email'));
        $this->assertTrue($validator->errors()->has('password'));
    }
}
