<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\FrontendTokenService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SessionBootstrapTest extends TestCase
{
    use RefreshDatabase;

    public function test_session_bootstrap_establishes_web_session_from_sanctum_token(): void
    {
        $user = User::factory()->create();
        $token = app(FrontendTokenService::class)->issue($user);

        $this->postJson('/auth/session/bootstrap', ['token' => $token])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.token', $token)
            ->assertJsonPath('data.redirect', route('dashboard'));

        $this->assertAuthenticatedAs($user);
        $this->assertSame($token, session(FrontendTokenService::SESSION_KEY));
    }

    public function test_session_check_reports_authenticated_user_after_bootstrap(): void
    {
        $user = User::factory()->create();
        $token = app(FrontendTokenService::class)->issue($user);

        $this->postJson('/auth/session/bootstrap', ['token' => $token])->assertOk();

        $this->getJson('/auth/session/check')
            ->assertOk()
            ->assertJsonPath('success', true);
    }

    public function test_session_bootstrap_rejects_invalid_token(): void
    {
        $this->postJson('/auth/session/bootstrap', ['token' => 'invalid-token'])
            ->assertUnauthorized()
            ->assertJsonPath('success', false);

        $this->assertGuest();
    }
}
