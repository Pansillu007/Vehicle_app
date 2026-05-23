# VehiclePro API (Sanctum)

Base URL: `{{APP_URL}}/api`

Interactive JSON docs: `GET /api/docs`

## Authentication

Create a token via `POST /api/login` or `POST /api/register`, or use Profile → API Tokens (Jetstream).

After **web login**, the SPA reads the `api-token` meta tag (session-issued Sanctum token).

```http
POST /api/login
Authorization: Bearer {token}
POST /api/logout
```

## Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/docs` | API documentation (JSON) |
| POST | `/api/login` | Login, returns token + user |
| POST | `/api/logout` | Revoke current token |
| GET | `/api/user` | Current user |
| GET | `/api/profile` | Profile resource |
| PUT | `/api/profile` | Update profile |
| PUT | `/api/profile/password` | Change password |
| GET | `/api/dashboard` | Dashboard analytics |
| GET | `/api/vehicles?search=&sort=&page=` | List vehicles (paginated) |
| POST | `/api/vehicles` | Create vehicle |
| GET | `/api/vehicles/{id}` | Show vehicle + services |
| PUT | `/api/vehicles/{id}` | Update vehicle |
| DELETE | `/api/vehicles/{id}` | Soft delete vehicle |
| GET | `/api/vehicles/{id}/services` | List services |
| POST | `/api/vehicles/{id}/services` | Create service |
| GET | `/api/vehicles/{id}/services/{serviceId}` | Show service |
| PUT | `/api/vehicles/{id}/services/{serviceId}` | Update service |
| DELETE | `/api/vehicles/{id}/services/{serviceId}` | Soft delete service |
| GET | `/api/reminders` | List pending reminders |
| POST | `/api/reminders/{id}/complete` | Mark reminder as completed |
| GET | `/api/vehicles/{id}/fuel-logs` | List fuel logs |
| POST | `/api/vehicles/{id}/fuel-logs` | Add fuel log |
| POST | `/api/trash/vehicles/{id}/restore` | Restore vehicle |
| DELETE | `/api/trash/vehicles/{id}` | Force delete vehicle |
| POST | `/api/trash/services/{id}/restore` | Restore service |
| DELETE | `/api/trash/services/{id}` | Force delete service |
| GET | `/api/export/vehicles` | Download fleet CSV (respects search/fuel/sort) |
| GET | `/api/export/vehicles/{id}/services` | Download service history CSV |


Postman collection: `docs/VehiclePro.postman_collection.json`

## Sample create vehicle

```json
POST /api/vehicles
{
  "name": "Fleet Van",
  "make": "Ford",
  "model": "Transit",
  "number_plate": "FLT-100",
  "year": 2023,
  "color": "White",
  "mileage": 12000,
  "fuel_type": "Diesel"
}
```

## Admin accounts

Admin users see all vehicles via API. Regular users see only their own.
