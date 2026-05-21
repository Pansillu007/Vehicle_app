# VehiclePro API (Sanctum)

Base URL: `{{APP_URL}}/api`

## Authentication

Create a token in Profile → API Tokens (enable Jetstream API feature) or:

```http
POST /login
```

Use Bearer token:

```
Authorization: Bearer {token}
```

## Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/user` | Current user |
| GET | `/api/profile` | Profile resource |
| PUT | `/api/profile` | Update profile |
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
