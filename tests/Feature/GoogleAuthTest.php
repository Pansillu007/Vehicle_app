<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\FrontendTokenService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;
use Mockery;
use Tests\TestCase;

class GoogleAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.google.client_id' => 'test-client-id',
            'services.google.client_secret' => 'test-client-secret',
            'services.google.redirect' => 'http://localhost/auth/google/callback',
        ]);
    }

    protected function mockSocialiteUser(): SocialiteUser
    {
        $user = Mockery::mock(SocialiteUser::class);
        $user->shouldReceive('getId')->andReturn('google-callback-99');
        $user->shouldReceive('getEmail')->andReturn('callback@example.com');
        $user->shouldReceive('getName')->andReturn('Callback User');
        $user->shouldReceive('getAvatar')->andReturn('https://example.com/photo.jpg');

        return $user;
    }

    public function test_google_redirect_route_exists(): void
    {
        $provider = Mockery::mock(\Laravel\Socialite\Contracts\Provider::class);
        $provider->shouldReceive('redirect')->andReturn(redirect('https://accounts.google.com/o/oauth2/auth'));

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $this->get('/auth/google/redirect')->assertRedirect();
    }

    public function test_google_callback_creates_user_session_and_sanctum_token(): void
    {
        $provider = Mockery::mock(\Laravel\Socialite\Contracts\Provider::class);
        $provider->shouldReceive('user')->andReturn($this->mockSocialiteUser());

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $response = $this->get('/auth/google/callback');

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs(User::where('email', 'callback@example.com')->first());

        $this->assertDatabaseHas('users', [
            'email' => 'callback@example.com',
            'google_id' => 'google-callback-99',
            'provider' => 'google',
        ]);

        $this->assertDatabaseCount('personal_access_tokens', 1);
        $this->assertNotNull(session(FrontendTokenService::SESSION_KEY));
    }

    public function test_google_routes_require_guest(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->get('/auth/google/redirect')->assertRedirect(route('dashboard'));
    }

    public function test_email_password_login_still_works(): void
    {
        $user = User::factory()->create([
            'email' => 'classic@example.com',
            'password' => 'password',
        ]);

        $this->post('/login', [
            'email' => 'classic@example.com',
            'password' => 'password',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);
    }
}
