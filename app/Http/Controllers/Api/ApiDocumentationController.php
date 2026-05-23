<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiDocumentationController extends Controller
{
    public function index(Request $request)
    {
        $docs = [
            'application' => 'VehiclePro',
            'version' => '1.0.0',
            'framework' => 'Laravel 13',
            'description' => 'RESTful JSON API for vehicles and nested service records (Sanctum Bearer tokens).',
            'base_url' => url('/api'),
            'authentication' => [
                'type' => 'Bearer Token',
                'header' => 'Authorization: Bearer {token}',
                'obtain_token' => 'POST /api/login or POST /api/register (returns token in data.token)',
                'spa_flow' => 'After Jetstream web login, a frontend token is issued via session and exposed in the api-token meta tag.',
                'logout' => 'POST /api/logout (revokes current token)',
            ],
            'endpoints' => [
                'auth' => [
                    'POST /api/register' => 'Register and receive a token',
                    'POST /api/login' => 'Login and receive a token',
                    'POST /api/logout' => 'Revoke current token (auth required)',
                    'GET /api/user' => 'Current user (UserResource)',
                ],
                'profile' => [
                    'GET /api/profile' => 'Profile (auth)',
                    'PUT /api/profile' => 'Update profile (auth)',
                    'PUT /api/profile/password' => 'Change password (auth)',
                ],
                'dashboard' => [
                    'GET /api/dashboard' => 'Analytics payload for dashboard UI (auth)',
                ],
                'vehicles' => [
                    'GET /api/vehicles' => 'List vehicles (search, fuel_type, sort, page)',
                    'POST /api/vehicles' => 'Create vehicle (multipart for image)',
                    'GET /api/vehicles/{vehicle}' => 'Show vehicle with services',
                    'PUT /api/vehicles/{vehicle}' => 'Update vehicle',
                    'DELETE /api/vehicles/{vehicle}' => 'Soft delete vehicle',
                ],
                'service_records' => [
                    'GET /api/vehicles/{vehicle}/services' => 'List services for a vehicle',
                    'POST /api/vehicles/{vehicle}/services' => 'Create service record',
                    'GET /api/vehicles/{vehicle}/services/{service}' => 'Show service record',
                    'PUT /api/vehicles/{vehicle}/services/{service}' => 'Update service record',
                    'DELETE /api/vehicles/{vehicle}/services/{service}' => 'Soft delete service record',
                ],
                'reminders' => [
                    'GET /api/reminders' => 'List all pending reminders for user',
                    'POST /api/reminders/{reminder}/complete' => 'Mark a reminder as completed',
                ],
                'fuel_logs' => [
                    'GET /api/vehicles/{vehicle}/fuel-logs' => 'List fuel logs for a vehicle',
                    'POST /api/vehicles/{vehicle}/fuel-logs' => 'Add new fuel log',
                ],
                'trash' => [
                    'GET /api/trash' => 'List soft-deleted vehicles and services (auth)',
                    'POST /api/trash/vehicles/{id}/restore' => 'Restore vehicle',
                    'DELETE /api/trash/vehicles/{id}' => 'Force delete vehicle',
                    'POST /api/trash/services/{id}/restore' => 'Restore service record',
                    'DELETE /api/trash/services/{id}' => 'Force delete service record',
                ],
                'export' => [
                    'GET /api/export/vehicles?search=&fuel_type=&sort=' => 'Download fleet CSV (auth)',
                    'GET /api/export/vehicles/{vehicle}/services' => 'Download service history CSV (auth)',
                ],
            ],
            'response_envelope' => [
                'success' => true,
                'message' => 'Human-readable message',
                'data' => 'Payload or null',
            ],
            'markdown_reference' => url('/docs/API.md'),
        ];

        if ($request->wantsJson()) {
            return response()->json($docs);
        }

        return view('api.docs', compact('docs'));
    }
}
