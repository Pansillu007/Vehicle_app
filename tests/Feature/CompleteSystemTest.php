<?php

namespace Tests\Feature;

use App\Models\ServiceRecord;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CompleteSystemTest extends TestCase
{
    use RefreshDatabase;

    protected function actingAsApi(User $user): self
    {
        Sanctum::actingAs($user, ['*']);

        return $this;
    }

    protected function vehiclePayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'My Car',
            'make' => 'Toyota',
            'model' => 'Camry',
            'year' => 2024,
            'number_plate' => 'ABC123',
            'color' => 'Blue',
            'mileage' => 15000,
            'fuel_type' => 'Petrol',
            'vin_number' => null,
        ], $overrides);
    }

    public function test_user_can_create_vehicle_via_api(): void
    {
        $user = User::factory()->create();
        $this->actingAsApi($user);

        $this->postJson('/api/vehicles', $this->vehiclePayload())
            ->assertCreated();

        $this->assertDatabaseHas('vehicles', [
            'user_id' => $user->id,
            'number_plate' => 'ABC123',
        ]);
    }

    public function test_user_can_view_vehicles_page(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        Vehicle::factory()->create(['user_id' => $user->id]);

        $this->get(route('vehicles.index'))->assertOk()->assertSee('My Vehicles');
    }

    public function test_user_can_update_vehicle_via_api(): void
    {
        $user = User::factory()->create();
        $this->actingAsApi($user);
        $vehicle = Vehicle::factory()->create(['user_id' => $user->id, 'number_plate' => 'ABC123']);

        $this->putJson("/api/vehicles/{$vehicle->id}", $this->vehiclePayload([
            'name' => 'Updated Car',
            'number_plate' => 'XYZ789',
        ]))->assertOk();

        $this->assertDatabaseHas('vehicles', [
            'id' => $vehicle->id,
            'name' => 'Updated Car',
            'number_plate' => 'XYZ789',
        ]);
    }

    public function test_user_can_delete_vehicle_via_api(): void
    {
        $user = User::factory()->create();
        $this->actingAsApi($user);
        $vehicle = Vehicle::factory()->create(['user_id' => $user->id]);

        $this->deleteJson("/api/vehicles/{$vehicle->id}")->assertOk();
        $this->assertSoftDeleted('vehicles', ['id' => $vehicle->id]);
    }

    public function test_user_can_create_service_record_via_api(): void
    {
        $user = User::factory()->create();
        $this->actingAsApi($user);
        $vehicle = Vehicle::factory()->create(['user_id' => $user->id]);

        $this->postJson("/api/vehicles/{$vehicle->id}/services", [
            'service_type' => 'Oil Change',
            'description' => 'Regular maintenance',
            'service_date' => '2024-01-15',
            'cost' => 75.50,
            'mileage' => 15000,
            'service_provider' => 'Quick Lube Shop',
        ])->assertCreated();

        $this->assertDatabaseHas('service_records', [
            'vehicle_id' => $vehicle->id,
            'service_type' => 'Oil Change',
        ]);
    }

    public function test_user_cannot_access_other_users_vehicle(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $this->actingAs($user1);
        $vehicle = Vehicle::factory()->create(['user_id' => $user2->id]);

        $this->get(route('vehicles.edit', $vehicle))->assertForbidden();
    }

    public function test_dashboard_renders_for_authenticated_user(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        Vehicle::factory()->count(2)->create(['user_id' => $user->id]);

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Dashboard');
    }

    public function test_api_returns_vehicle_list(): void
    {
        $user = User::factory()->create();
        $this->actingAsApi($user);
        Vehicle::factory()->count(2)->create(['user_id' => $user->id]);

        $this->getJson('/api/vehicles')->assertOk();
    }

    public function test_api_creates_vehicle(): void
    {
        $user = User::factory()->create();
        $this->actingAsApi($user);

        $this->postJson('/api/vehicles', $this->vehiclePayload([
            'number_plate' => 'API123',
        ]))->assertCreated();

        $this->assertDatabaseHas('vehicles', ['number_plate' => 'API123']);
    }

    public function test_api_returns_service_records(): void
    {
        $user = User::factory()->create();
        $this->actingAsApi($user);
        $vehicle = Vehicle::factory()->create(['user_id' => $user->id]);
        ServiceRecord::factory()->create(['vehicle_id' => $vehicle->id]);

        $this->getJson("/api/vehicles/{$vehicle->id}/services")->assertOk();
    }

    public function test_validation_fails_for_missing_vehicle_fields(): void
    {
        $user = User::factory()->create();
        $this->actingAsApi($user);

        $this->postJson('/api/vehicles', ['name' => 'Incomplete'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['make', 'model', 'number_plate']);
    }
}
