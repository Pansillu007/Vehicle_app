<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiDocumentationController extends Controller
{
    public function index()
    {
        return response()->json([
            'application' => 'Vehicle Maintenance Management System',
            'version' => '1.0.0',
            'description' => 'Complete API documentation for vehicle and service record management',
            'base_url' => url('/api'),
            'authentication' => [
                'type' => 'Bearer Token',
                'header' => 'Authorization: Bearer {token}',
                'obtain_token' => 'POST /login (returns token in response)'
            ],
            'endpoints' => [
                'vehicles' => [
                    'GET /api/vehicles' => 'List all vehicles for authenticated user',
                    'POST /api/vehicles' => 'Create a new vehicle',
                    'GET /api/vehicles/{id}' => 'Get specific vehicle details',
                    'PUT /api/vehicles/{id}' => 'Update vehicle information',
                    'DELETE /api/vehicles/{id}' => 'Delete a vehicle'
                ],
                'service_records' => [
                    'GET /api/vehicles/{vehicle_id}/services' => 'List service records for a vehicle',
                    'POST /api/vehicles/{vehicle_id}/services' => 'Create a new service record',
                    'GET /api/services/{id}' => 'Get specific service record',
                    'PUT /api/services/{id}' => 'Update service record',
                    'DELETE /api/services/{id}' => 'Delete a service record'
                ]
            ],
            'vehicle_fields' => [
                'name' => 'string, required',
                'make' => 'string, required',
                'model' => 'string, required',
                'year' => 'integer, optional',
                'number_plate' => 'string, required, unique',
                'color' => 'string, optional',
                'mileage' => 'decimal, optional'
            ],
            'service_record_fields' => [
                'service_type' => 'string, required',
                'description' => 'text, optional',
                'cost' => 'decimal, required',
                'service_date' => 'date, required',
                'service_provider' => 'string, optional'
            ],
            'example_request' => [
                'method' => 'POST',
                'url' => '/api/vehicles',
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer {your-token}'
                ],
                'body' => [
                    'name' => 'My Car',
                    'make' => 'Toyota',
                    'model' => 'Camry',
                    'year' => 2024,
                    'number_plate' => 'ABC123',
                    'color' => 'Blue',
                    'mileage' => 15000.50
                ]
            ],
            'example_response' => [
                'success' => true,
                'message' => 'Vehicle created successfully',
                'data' => [
                    'id' => 1,
                    'name' => 'My Car',
                    'make' => 'Toyota',
                    'user_id' => 1,
                    'created_at' => '2024-01-01T00:00:00.000000Z',
                    'updated_at' => '2024-01-01T00:00:00.000000Z'
                ]
            ]
        ]);
    }
}
