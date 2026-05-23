<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\GoogleAuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Mockery;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class GoogleAuthServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function mockGoogleUser(
        string $id = 'google-123',
        string $email = 'google.user@example.com',
        string $name = 'Google User',
        ?string $avatar = 'https://lh3.googleusercontent.com/a/default'
    ): SocialiteUser {
        $user = Mockery::mock(SocialiteUser::class);
        $user->shouldReceive('getId')->andReturn($id);
        $user->shouldReceive('getEmail')->andReturn($email);
        $user->shouldReceive('getName')->andReturn($name);
        $user->shouldReceive('getAvatar')->andReturn($avatar);

        return $user;
    }

    public function test_creates_new_user_from_google(): void
    {
        $service = app(GoogleAuthService::class);
        $user = $service->resolveUser($this->mockGoogleUser());

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'google.user@example.com',
            'google_id' => 'google-123',
            'provider' => 'google',
        ]);
        $this->assertNotNull($user->email_verified_at);
    }

    public function test_logs_in_existing_google_user(): void
    {
        $existing = User::factory()->create([
            'email' => 'google.user@example.com',
            'google_id' => 'google-123',
            'provider' => 'google',
        ]);

        $service = app(GoogleAuthService::class);
        $user = $service->resolveUser($this->mockGoogleUser());

        $this->assertSame($existing->id, $user->id);
    }

    public function test_links_google_to_existing_email_account(): void
    {
        $existing = User::factory()->create([
            'email' => 'google.user@example.com',
            'google_id' => null,
        ]);

        $service = app(GoogleAuthService::class);
        $user = $service->resolveUser($this->mockGoogleUser());

        $this->assertSame($existing->id, $user->id);
        $this->assertSame('google-123', $user->google_id);
        $this->assertSame('google', $user->provider);
    }

    public function test_rejects_conflicting_google_id_for_same_email(): void
    {
        User::factory()->create([
            'email' => 'google.user@example.com',
            'google_id' => 'other-google-id',
            'provider' => 'google',
        ]);

        $this->expectException(HttpException::class);

        app(GoogleAuthService::class)->resolveUser($this->mockGoogleUser());
    }
}
