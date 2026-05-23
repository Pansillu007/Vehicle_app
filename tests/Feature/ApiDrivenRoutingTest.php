<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ApiDrivenRoutingTest extends TestCase
{
    use RefreshDatabase;

    public function test_web_routes_are_registered_without_api_prefix(): void
    {
        $this->assertTrue(Route::has('dashboard'));
        $this->assertTrue(Route::has('vehicles.index'));
        $this->assertTrue(Route::has('home'));

        $this->assertSame('/dashboard', route('dashboard', [], false));
        $this->assertSame('/vehicles', route('vehicles.index', [], false));
    }

    public function test_json_api_routes_use_api_prefix(): void
    {
        $this->assertTrue(Route::has('api.vehicles.index'));
        $this->assertTrue(Route::has('api.dashboard'));
        $this->assertStringStartsWith('/api/', route('api.vehicles.index', [], false));
    }

    public function test_web_php_registers_view_routes_only(): void
    {
        $contents = file_get_contents(base_path('routes/web.php'));

        $this->assertStringContainsString("Route::get('/dashboard'", $contents);
        $this->assertStringNotContainsString('ApiVehicleController', $contents);
        $this->assertStringNotContainsString('vehicles.store', $contents);
    }

    public function test_api_php_registers_crud_routes(): void
    {
        $contents = file_get_contents(base_path('routes/api.php'));

        $this->assertStringContainsString("Route::apiResource('vehicles'", $contents);
        $this->assertStringContainsString("Route::apiResource('vehicles.services'", $contents);
        $this->assertStringContainsString('auth:sanctum', $contents);
        $this->assertStringContainsString("Route::get('/docs'", $contents);
        $this->assertStringContainsString('ApiTrashController', $contents);
    }

    public function test_web_php_does_not_register_trash_mutations(): void
    {
        $contents = file_get_contents(base_path('routes/web.php'));

        $this->assertStringContainsString("Route::get('/trash'", $contents);
        $this->assertStringNotContainsString('trash.vehicles.restore', $contents);
    }

    public function test_authenticated_dashboard_renders_without_server_side_stats(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Dashboard')
            ->assertSee('dashboard-app')
            ->assertDontSee('$chartData');
    }

    public function test_dashboard_api_requires_sanctum(): void
    {
        $this->getJson('/api/dashboard')->assertUnauthorized();
    }

    public function test_dashboard_api_returns_json_for_authenticated_user(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $this->getJson('/api/dashboard')
            ->assertOk()
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'totalVehicles',
                    'chartData',
                ],
            ]);
    }
}
