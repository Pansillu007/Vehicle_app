<?php

namespace Tests\Feature;

<<<<<<< HEAD
use Tests\TestCase;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\ServiceRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
=======
use App\Models\ServiceRecord;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
>>>>>>> ec6237d (Third Week of Assignment small changes)

class VehicleManagementTest extends TestCase
{
    use RefreshDatabase;

<<<<<<< HEAD
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
=======
    public function test_authenticated_user_can_manage_vehicles_and_services(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $createResponse = $this->post(route('vehicles.store'), [
            'name' => 'Family SUV',
            'make' => 'Toyota',
            'model' => 'RAV4',
            'number_plate' => 'ABC-1234',
            'year' => 2022,
            'color' => 'Silver',
            'mileage' => 15000,
            'fuel_type' => 'Petrol',
            'vin_number' => '',
        ]);

        $createResponse->assertRedirect(route('vehicles.index'));
        $createResponse->assertSessionHas('success');

        $vehicle = Vehicle::where('number_plate', 'ABC-1234')->first();
        $this->assertNotNull($vehicle);
        $this->assertNull($vehicle->vin_number);
        $this->assertSame($user->id, $vehicle->user_id);

        $this->get(route('vehicles.show', $vehicle))->assertOk();

        $serviceResponse = $this->post(route('vehicles.services.store', $vehicle), [
            'service_type' => 'Oil Change',
            'description' => 'Full synthetic oil change',
            'service_date' => '2024-06-01',
            'cost' => 89.99,
            'mileage' => 16000,
            'service_provider' => 'Quick Lube',
        ]);

        $serviceResponse->assertRedirect(route('vehicles.show', $vehicle));
        $serviceResponse->assertSessionHas('success');

        $service = ServiceRecord::first();
        $this->assertNotNull($service);
        $this->assertSame($vehicle->id, $service->vehicle_id);

        $this->get(route('vehicles.services.edit', [$vehicle, $service]))->assertOk();

        $updateService = $this->put(route('vehicles.services.update', [$vehicle, $service]), [
            'service_type' => 'Oil Change Premium',
            'description' => 'Updated service notes',
            'service_date' => '2024-06-01',
            'cost' => 99.99,
            'mileage' => 16000,
            'service_provider' => 'Quick Lube',
        ]);

        $updateService->assertRedirect(route('vehicles.show', $vehicle));
        $this->assertSame('Oil Change Premium', $service->fresh()->service_type);

        $updateVehicle = $this->put(route('vehicles.update', $vehicle), [
            'name' => 'Family SUV Updated',
            'make' => 'Toyota',
            'model' => 'RAV4',
            'number_plate' => 'ABC-1234',
            'year' => 2022,
            'color' => 'Blue',
            'mileage' => 17000,
            'fuel_type' => 'Hybrid',
            'vin_number' => '',
        ]);

        $updateVehicle->assertRedirect(route('vehicles.index'));
        $this->assertSame('Family SUV Updated', $vehicle->fresh()->name);

        $deleteService = $this->delete(route('vehicles.services.destroy', [$vehicle, $service]));
        $deleteService->assertRedirect(route('vehicles.show', $vehicle));
        $this->assertSoftDeleted('service_records', ['id' => $service->id]);

        $deleteVehicle = $this->delete(route('vehicles.destroy', $vehicle));
        $deleteVehicle->assertRedirect(route('vehicles.index'));
        $this->assertSoftDeleted('vehicles', ['id' => $vehicle->id]);
    }

    public function test_service_record_must_belong_to_vehicle(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $vehicleA = Vehicle::create([
            'user_id' => $user->id,
            'name' => 'Car A',
            'make' => 'Ford',
            'model' => 'Focus',
            'number_plate' => 'AAA-1111',
            'year' => 2020,
            'color' => 'Red',
            'mileage' => 1000,
            'fuel_type' => 'Petrol',
        ]);

        $vehicleB = Vehicle::create([
            'user_id' => $user->id,
            'name' => 'Car B',
            'make' => 'Ford',
            'model' => 'Fiesta',
            'number_plate' => 'BBB-2222',
            'year' => 2019,
            'color' => 'Blue',
            'mileage' => 2000,
            'fuel_type' => 'Petrol',
        ]);

        $serviceOnB = ServiceRecord::create([
            'vehicle_id' => $vehicleB->id,
            'service_type' => 'Brakes',
            'description' => 'Pad replacement',
            'service_date' => '2024-01-15',
            'cost' => 200,
            'mileage' => 2100,
            'service_provider' => 'Garage',
        ]);

        $this->get(route('vehicles.services.edit', [$vehicleA, $serviceOnB]))->assertNotFound();
    }

    public function test_user_cannot_access_another_users_vehicle(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $vehicle = Vehicle::create([
            'user_id' => $owner->id,
            'name' => 'Owner Car',
            'make' => 'Honda',
            'model' => 'Civic',
            'number_plate' => 'OWN-9999',
            'year' => 2021,
            'color' => 'Black',
            'mileage' => 5000,
            'fuel_type' => 'Petrol',
        ]);

        $this->actingAs($other)
            ->get(route('vehicles.show', $vehicle))
            ->assertForbidden();
>>>>>>> ec6237d (Third Week of Assignment small changes)
    }
}
