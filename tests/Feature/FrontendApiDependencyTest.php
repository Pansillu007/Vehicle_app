<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * Proves vehicle CRUD is only available through api.php (no web fallbacks).
 */
class FrontendApiDependencyTest extends TestCase
{
    use RefreshDatabase;

    public function test_vehicle_store_is_not_available_on_web_routes(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/vehicles', [
            'name' => 'Web Fallback',
            'make' => 'Test',
            'model' => 'Test',
            'number_plate' => 'WEB-999',
            'year' => 2024,
            'color' => 'Red',
            'mileage' => 1000,
            'fuel_type' => 'Petrol',
        ]);

        // No web POST handler — Laravel returns 405 (method not allowed) or 404.
        $this->assertContains($response->status(), [404, 405]);
    }

    public function test_vehicle_mutations_require_api_routes(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $this->postJson('/api/vehicles', [
            'name' => 'API Vehicle',
            'make' => 'Toyota',
            'model' => 'Camry',
            'number_plate' => 'API-100',
            'year' => 2024,
            'color' => 'Blue',
            'mileage' => 5000,
            'fuel_type' => 'Petrol',
        ])->assertCreated()
            ->assertJsonPath('success', true);

        $vehicleId = $this->getJson('/api/vehicles')
            ->json('data.items.0.id');

        $this->assertNotNull($vehicleId);

        $this->putJson("/api/vehicles/{$vehicleId}", [
            'name' => 'API Vehicle Updated',
            'make' => 'Toyota',
            'model' => 'Camry',
            'number_plate' => 'API-100',
            'year' => 2024,
            'color' => 'Green',
            'mileage' => 6000,
            'fuel_type' => 'Petrol',
        ])->assertOk()
            ->assertJsonPath('data.name', 'API Vehicle Updated');

        $this->deleteJson("/api/vehicles/{$vehicleId}")
            ->assertOk()
            ->assertJsonPath('success', true);
    }
}
