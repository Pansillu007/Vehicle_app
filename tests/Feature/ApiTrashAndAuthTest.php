<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ApiTrashAndAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_docs_endpoint_is_public(): void
    {
        $this->getJson('/api/docs')
            ->assertOk()
            ->assertJsonPath('application', 'VehiclePro')
            ->assertJsonStructure(['endpoints', 'authentication']);
    }

    public function test_logout_revokes_sanctum_token(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('frontend', ['*'])->plainTextToken;

        $this->withToken($token)
            ->postJson('/api/logout')
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseCount('personal_access_tokens', 0);
        $this->assertNull(PersonalAccessToken::findToken($token));
    }

    public function test_trash_mutations_are_not_on_web_routes(): void
    {
        $contents = file_get_contents(base_path('routes/web.php'));

        $this->assertStringNotContainsString('trash.vehicles.restore', $contents);
        $this->assertStringNotContainsString('forceDeleteVehicle', $contents);
    }

    public function test_user_can_restore_vehicle_via_api(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $vehicle = Vehicle::factory()->create(['user_id' => $user->id]);
        $vehicle->delete();

        $this->postJson("/api/trash/vehicles/{$vehicle->id}/restore")
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('vehicles', [
            'id' => $vehicle->id,
            'deleted_at' => null,
        ]);
    }

    public function test_login_returns_user_resource_shape(): void
    {
        $user = User::factory()->create([
            'email' => 'api-login@example.com',
            'password' => 'password',
        ]);

        $this->postJson('/api/login', [
            'email' => 'api-login@example.com',
            'password' => 'password',
        ])
            ->assertOk()
            ->assertJsonStructure([
                'success',
                'data' => [
                    'token',
                    'user' => ['id', 'name', 'email', 'role'],
                ],
            ])
            ->assertJsonMissingPath('data.user.password');
    }
}
