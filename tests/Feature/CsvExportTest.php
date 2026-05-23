<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CsvExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_export_vehicles_csv(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);
        Vehicle::factory()->create(['user_id' => $user->id, 'name' => 'Export Test Car']);

        $response = $this->get('/api/export/vehicles');

        $response->assertOk();
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
        $this->assertStringContainsString('Export Test Car', $response->getContent());
        $this->assertStringContainsString('Name', $response->getContent());
    }

    public function test_user_can_export_vehicle_services_csv(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);
        $vehicle = Vehicle::factory()->create(['user_id' => $user->id]);

        $this->postJson("/api/vehicles/{$vehicle->id}/services", [
            'service_type' => 'Oil Change',
            'description' => 'Test export',
            'service_date' => '2024-06-01',
            'cost' => 99.99,
            'mileage' => 1000,
            'service_provider' => 'Garage',
        ])->assertCreated();

        $response = $this->get("/api/export/vehicles/{$vehicle->id}/services");

        $response->assertOk();
        $this->assertStringContainsString('Oil Change', $response->getContent());
        $this->assertStringContainsString('Service Type', $response->getContent());
    }

    public function test_export_requires_authentication(): void
    {
        $this->getJson('/api/export/vehicles')->assertUnauthorized();
    }
}
