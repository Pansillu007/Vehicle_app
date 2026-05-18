<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\ServiceRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CompleteSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_and_login()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    public function test_user_can_create_vehicle()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->post('/vehicles', [
            'name' => 'My Car',
            'make' => 'Toyota',
            'model' => 'Camry',
            'year' => 2024,
            'number_plate' => 'ABC123',
            'color' => 'Blue',
            'mileage' => 15000.50,
        ]);

        $response->assertRedirect(route('vehicles.index'));
        $this->assertDatabaseHas('vehicles', [
            'user_id' => $user->id,
            'name' => 'My Car',
            'number_plate' => 'ABC123',
        ]);
    }

    public function test_user_can_view_vehicles()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Vehicle::factory()->create(['user_id' => $user->id]);

        $response = $this->get('/vehicles');

        $response->assertStatus(200);
        $response->assertSee('My Vehicles');
    }

    public function test_user_can_update_vehicle()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $vehicle = Vehicle::factory()->create(['user_id' => $user->id]);

        $response = $this->put("/vehicles/{$vehicle->id}", [
            'name' => 'Updated Car',
            'make' => 'Honda',
            'model' => 'Civic',
            'year' => 2023,
            'number_plate' => 'XYZ789',
            'color' => 'Red',
            'mileage' => 20000,
        ]);

        $response->assertRedirect(route('vehicles.index'));
        $this->assertDatabaseHas('vehicles', [
            'id' => $vehicle->id,
            'name' => 'Updated Car',
            'number_plate' => 'XYZ789',
        ]);
    }

    public function test_user_can_delete_vehicle()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $vehicle = Vehicle::factory()->create(['user_id' => $user->id]);

        $response = $this->delete("/vehicles/{$vehicle->id}");

        $response->assertRedirect(route('vehicles.index'));
        $this->assertDatabaseMissing('vehicles', ['id' => $vehicle->id]);
    }

    public function test_user_can_create_service_record()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $vehicle = Vehicle::factory()->create(['user_id' => $user->id]);

        $response = $this->post("/vehicles/{$vehicle->id}/services", [
            'service_type' => 'Oil Change',
            'description' => 'Regular maintenance',
            'cost' => 75.50,
            'service_date' => '2024-01-15',
            'service_provider' => 'Quick Lube Shop',
        ]);

        $response->assertRedirect(route('vehicles.services.index', $vehicle));
        $this->assertDatabaseHas('service_records', [
            'vehicle_id' => $vehicle->id,
            'service_type' => 'Oil Change',
            'cost' => 75.50,
        ]);
    }

    public function test_user_can_view_service_records()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $vehicle = Vehicle::factory()->create(['user_id' => $user->id]);
        ServiceRecord::factory()->create(['vehicle_id' => $vehicle->id]);

        $response = $this->get("/vehicles/{$vehicle->id}/services");

        $response->assertStatus(200);
        $response->assertSee('Service Records');
        $response->assertSee($vehicle->name);
    }

    public function test_user_cannot_access_other_users_vehicle()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        Sanctum::actingAs($user1);

        $vehicle = Vehicle::factory()->create(['user_id' => $user2->id]);

        $response = $this->get("/vehicles/{$vehicle->id}/edit");

        $response->assertStatus(403);
    }

    public function test_dashboard_shows_correct_statistics()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Vehicle::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Total Vehicles');
        $response->assertSee('Service Records');
    }

    public function test_api_returns_vehicle_list()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Vehicle::factory()->count(2)->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/vehicles');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_api_creates_vehicle()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/vehicles', [
            'name' => 'API Car',
            'make' => 'Ford',
            'model' => 'Mustang',
            'year' => 2024,
            'number_plate' => 'API123',
            'color' => 'Black',
            'mileage' => 5000,
        ]);

        $response->assertStatus(201)
            ->assertJson(['success' => true]);
        $this->assertDatabaseHas('vehicles', ['number_plate' => 'API123']);
    }

    public function test_api_returns_service_records()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $vehicle = Vehicle::factory()->create(['user_id' => $user->id]);
        ServiceRecord::factory()->create(['vehicle_id' => $vehicle->id]);

        $response = $this->getJson("/api/vehicles/{$vehicle->id}/services");

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_validation_fails_for_missing_fields()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->post('/vehicles', [
            'name' => '',
            'make' => '',
            'model' => '',
            'number_plate' => '',
        ]);

        $response->assertSessionHasErrors(['name', 'make', 'model', 'number_plate']);
    }
}
