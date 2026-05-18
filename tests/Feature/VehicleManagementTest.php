<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\ServiceRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class VehicleManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_register_via_api()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'User registered successfully',
            ])
            ->assertJsonStructure([
                'data' => [
                    'user' => ['id', 'name', 'email'],
                    'token',
                ]
            ]);
    }

    /** @test */
    public function user_can_login_via_api()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Login successful',
            ])
            ->assertJsonStructure([
                'data' => [
                    'user' => ['id', 'name', 'email'],
                    'token',
                ]
            ]);
    }

    /** @test */
    public function user_cannot_login_with_invalid_credentials()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'wrong@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(422);
    }

    /** @test */
    public function authenticated_user_can_create_vehicle()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/vehicles', [
            'name' => 'Test Vehicle',
            'make' => 'Toyota',
            'model' => 'Vios',
            'year' => 2020,
            'number_plate' => 'ABC123',
            'color' => 'Silver',
            'mileage' => 15000,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Vehicle created successfully',
            ]);

        $this->assertDatabaseHas('vehicles', [
            'user_id' => $user->id,
            'name' => 'Test Vehicle',
            'number_plate' => 'ABC123',
        ]);
    }

    /** @test */
    public function user_can_view_their_vehicles()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Vehicle::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/vehicles');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonCount(3, 'data.data');
    }

    /** @test */
    public function user_cannot_view_other_users_vehicles()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        Sanctum::actingAs($user1);

        Vehicle::factory()->create(['user_id' => $user2->id]);

        $response = $this->getJson('/api/vehicles');

        $response->assertStatus(200)
            ->assertJsonCount(0, 'data.data');
    }

    /** @test */
    public function user_can_update_their_vehicle()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $vehicle = Vehicle::factory()->create(['user_id' => $user->id]);

        $response = $this->putJson("/api/vehicles/{$vehicle->id}", [
            'name' => 'Updated Vehicle',
            'make' => 'Honda',
            'model' => 'Civic',
            'year' => 2021,
            'number_plate' => 'XYZ789',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Vehicle updated successfully',
            ]);

        $this->assertDatabaseHas('vehicles', [
            'id' => $vehicle->id,
            'name' => 'Updated Vehicle',
        ]);
    }

    /** @test */
    public function user_cannot_update_other_users_vehicle()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        Sanctum::actingAs($user1);

        $vehicle = Vehicle::factory()->create(['user_id' => $user2->id]);

        $response = $this->putJson("/api/vehicles/{$vehicle->id}", [
            'name' => 'Hacked Vehicle',
            'make' => 'Honda',
            'model' => 'Civic',
            'number_plate' => 'HACKED',
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function user_can_delete_their_vehicle()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $vehicle = Vehicle::factory()->create(['user_id' => $user->id]);

        $response = $this->deleteJson("/api/vehicles/{$vehicle->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Vehicle deleted successfully',
            ]);

        $this->assertDatabaseMissing('vehicles', [
            'id' => $vehicle->id,
        ]);
    }

    /** @test */
    public function user_can_create_service_record()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $vehicle = Vehicle::factory()->create(['user_id' => $user->id]);

        $response = $this->postJson("/api/vehicles/{$vehicle->id}/services", [
            'service_type' => 'Oil Change',
            'description' => 'Regular maintenance',
            'cost' => 150.00,
            'service_date' => now()->format('Y-m-d'),
            'service_provider' => 'Service Center',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Service record created successfully',
            ]);

        $this->assertDatabaseHas('service_records', [
            'vehicle_id' => $vehicle->id,
            'service_type' => 'Oil Change',
        ]);
    }

    /** @test */
    public function user_can_view_service_history()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $vehicle = Vehicle::factory()->create(['user_id' => $user->id]);
        ServiceRecord::factory()->count(5)->create(['vehicle_id' => $vehicle->id]);

        $response = $this->getJson("/api/vehicles/{$vehicle->id}/services");

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data.data');
    }

    /** @test */
    public function user_can_logout()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token->plainTextToken,
        ])->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Logged out successfully',
            ]);
    }

    /** @test */
    public function unauthenticated_user_cannot_access_protected_routes()
    {
        $this->getJson('/api/vehicles')->assertStatus(401);
        $this->postJson('/api/logout')->assertStatus(401);
    }
}
