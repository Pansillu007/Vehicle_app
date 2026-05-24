<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ApiAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_view_another_users_vehicle_via_api(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $vehicle = Vehicle::factory()->create(['user_id' => $owner->id]);

        Sanctum::actingAs($other, ['*']);

        $this->getJson("/api/vehicles/{$vehicle->id}")
            ->assertForbidden();
    }

    public function test_user_cannot_delete_another_users_vehicle_via_api(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $vehicle = Vehicle::factory()->create(['user_id' => $owner->id]);

        Sanctum::actingAs($other, ['*']);

        $this->deleteJson("/api/vehicles/{$vehicle->id}")
            ->assertForbidden();
    }
}
