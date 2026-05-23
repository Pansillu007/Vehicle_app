<?php

namespace Tests\Feature;

use App\Models\ServiceRecord;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class VehicleManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function actingAsApi(User $user): self
    {
        Sanctum::actingAs($user, ['*']);

        return $this;
    }

    public function test_authenticated_user_can_manage_vehicles_and_services_via_api(): void
    {
        $user = User::factory()->create();
        $this->actingAsApi($user);

        $createResponse = $this->postJson('/api/vehicles', [
            'name' => 'Family SUV',
            'make' => 'Toyota',
            'model' => 'RAV4',
            'number_plate' => 'ABC-1234',
            'year' => 2022,
            'color' => 'Silver',
            'mileage' => 15000,
            'fuel_type' => 'Petrol',
            'vin_number' => null,
        ]);

        $createResponse->assertCreated();
        $vehicle = Vehicle::where('number_plate', 'ABC-1234')->first();
        $this->assertNotNull($vehicle);
        $this->assertSame($user->id, $vehicle->user_id);

        $this->get(route('vehicles.show', $vehicle))->assertOk();

        $serviceResponse = $this->postJson("/api/vehicles/{$vehicle->id}/services", [
            'service_type' => 'Oil Change',
            'description' => 'Full synthetic oil change',
            'service_date' => '2024-06-01',
            'cost' => 89.99,
            'mileage' => 16000,
            'service_provider' => 'Quick Lube',
        ]);

        $serviceResponse->assertCreated();
        $service = ServiceRecord::first();
        $this->assertNotNull($service);

        $this->get(route('vehicles.services.edit', [$vehicle, $service]))->assertOk();

        $this->putJson("/api/vehicles/{$vehicle->id}/services/{$service->id}", [
            'service_type' => 'Oil Change Premium',
            'description' => 'Updated service notes',
            'service_date' => '2024-06-01',
            'cost' => 99.99,
            'mileage' => 16000,
            'service_provider' => 'Quick Lube',
        ])->assertOk();

        $this->assertSame('Oil Change Premium', $service->fresh()->service_type);

        $this->putJson("/api/vehicles/{$vehicle->id}", [
            'name' => 'Family SUV Updated',
            'make' => 'Toyota',
            'model' => 'RAV4',
            'number_plate' => 'ABC-1234',
            'year' => 2022,
            'color' => 'Blue',
            'mileage' => 17000,
            'fuel_type' => 'Hybrid',
        ])->assertOk();

        $this->assertSame('Family SUV Updated', $vehicle->fresh()->name);

        $this->deleteJson("/api/vehicles/{$vehicle->id}/services/{$service->id}")->assertOk();
        $this->assertSoftDeleted('service_records', ['id' => $service->id]);

        $this->deleteJson("/api/vehicles/{$vehicle->id}")->assertOk();
        $this->assertSoftDeleted('vehicles', ['id' => $vehicle->id]);
    }

    public function test_service_record_must_belong_to_vehicle(): void
    {
        $user = User::factory()->create();
        $this->actingAsApi($user);

        $vehicleA = Vehicle::factory()->create(['user_id' => $user->id, 'number_plate' => 'AAA-1111']);
        $vehicleB = Vehicle::factory()->create(['user_id' => $user->id, 'number_plate' => 'BBB-2222']);

        $serviceOnB = ServiceRecord::factory()->create(['vehicle_id' => $vehicleB->id]);

        $this->get(route('vehicles.services.edit', [$vehicleA, $serviceOnB]))->assertNotFound();
    }

    public function test_user_cannot_access_another_users_vehicle(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $vehicle = Vehicle::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($other)
            ->get(route('vehicles.show', $vehicle))
            ->assertForbidden();
    }
}
