# Vehicle Maintenance Management System - Laravel 12 SaaS Application

## Project Overview
A professional, scalable, secure Vehicle Maintenance Management System built with Laravel 12 for university assignment (SSP2).

## Features Implemented

### Core Features
✅ **User Authentication** - Laravel Jetstream (Livewire) with 2FA support
✅ **Vehicle Management** - Full CRUD operations with search and pagination
✅ **Service Records Management** - Linked to vehicles with filtering
✅ **Dashboard** - User-specific statistics and recent activity
✅ **Role-Based Access Control** - Admin and User roles with middleware
✅ **Secure Form Validation** - Server-side validation with error handling
✅ **API Endpoints** - RESTful API with Laravel Sanctum authentication

### Advanced Features (High Marks)
✅ **Eloquent Relationships** - User → Vehicles → ServiceRecords
✅ **Middleware Protection** - Custom CheckRole middleware
✅ **API Authentication** - Token-based access via Sanctum
✅ **Pagination** - All listings use pagination (10 per page)
✅ **Search/Filter** - Vehicles and service records searchable
✅ **Error Handling** - Comprehensive validation and authorization
✅ **Clean UI** - Tailwind CSS with responsive design
✅ **Secure Data** - CSRF protection, validation, password hashing

## Tech Stack
- **Backend:** Laravel 12
- **Database:** MySQL
- **Authentication:** Laravel Jetstream (Livewire)
- **API Authentication:** Laravel Sanctum
- **Frontend:** Tailwind CSS
- **Build Tool:** Vite

## Installation & Setup

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL

### Steps

1. **Clone the repository**
```bash
cd vehicle_main_app
```

2. **Install dependencies**
```bash
composer install
npm install
```

3. **Configure environment**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure database in .env**
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vehicle_db
DB_USERNAME=root
DB_PASSWORD=
```

5. **Create database**
```bash
mysql -u root -p
CREATE DATABASE vehicle_db;
exit;
```

6. **Run migrations and seed**
```bash
php artisan migrate:fresh --seed
```

7. **Build assets**
```bash
npm run build
```

8. **Start development server**
```bash
php artisan serve
npm run dev
```

## Test Credentials

### Admin User
- Email: admin@example.com
- Password: password

### Regular Users
- Email: john@example.com
- Password: password

- Email: jane@example.com
- Password: password

## Project Structure

### Models
- `app/Models/User.php` - User with roles and vehicle relationships
- `app/Models/Vehicle.php` - Vehicle with service record relationships
- `app/Models/ServiceRecord.php` - Maintenance service records

### Controllers
- `app/Http/Controllers/VehicleController.php` - Vehicle CRUD (web)
- `app/Http/Controllers/ServiceRecordController.php` - Service records CRUD (web)
- `app/Http/Controllers/Api/VehicleApiController.php` - Vehicle API
- `app/Http/Controllers/Api/ServiceRecordApiController.php` - Service API

### Middleware
- `app/Http/Middleware/CheckRole.php` - Role-based access control

### Views
- `resources/views/dashboard.blade.php` - Dashboard with statistics
- `resources/views/vehicles/` - Vehicle CRUD views
- `resources/views/services/` - Service record views

### Routes
- `routes/web.php` - Web routes with auth middleware
- `routes/api.php` - API routes with Sanctum middleware

## API Documentation

### Authentication
All API endpoints require Bearer token in header:
```
Authorization: Bearer {token}
```

### Endpoints

#### Vehicles
- `GET /api/vehicles` - List all user vehicles
- `POST /api/vehicles` - Create new vehicle
- `GET /api/vehicles/{id}` - Get vehicle details
- `PUT /api/vehicles/{id}` - Update vehicle
- `DELETE /api/vehicles/{id}` - Delete vehicle

#### Service Records
- `GET /api/vehicles/{vehicle_id}/services` - List services
- `POST /api/vehicles/{vehicle_id}/services` - Create service record
- `GET /api/vehicles/{vehicle_id}/services/{id}` - Get service details
- `PUT /api/vehicles/{vehicle_id}/services/{id}` - Update service
- `DELETE /api/vehicles/{vehicle_id}/services/{id}` - Delete service

### Example API Request
```bash
curl -X GET http://localhost:8000/api/vehicles \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

## Database Schema

### Users Table
- id, name, email, role, password, timestamps

### Vehicles Table
- id, user_id (FK), name, make, model, year, number_plate (unique), color, mileage, timestamps

### Service Records Table
- id, vehicle_id (FK), service_type, description, cost, service_date, service_provider, timestamps

## Eloquent Relationships

```php
User hasMany Vehicle
Vehicle belongsTo User
Vehicle hasMany ServiceRecord
ServiceRecord belongsTo Vehicle
```

## Security Features
- CSRF protection on all forms
- Password hashing (bcrypt)
- Role-based authorization
- Form validation (server-side)
- SQL injection prevention (Eloquent ORM)
- XSS prevention (Blade templating)

## Marking Criteria Coverage

### Functionality (40%)
✅ Complete CRUD operations
✅ Search and filtering
✅ Pagination
✅ Role-based access
✅ API endpoints

### Code Quality (25%)
✅ MVC architecture
✅ Eloquent ORM
✅ Validation
✅ Clean code structure
✅ Laravel best practices

### Security (20%)
✅ Authentication & Authorization
✅ CSRF protection
✅ Input validation
✅ Password hashing
✅ Middleware protection

### UI/UX (15%)
✅ Tailwind CSS
✅ Responsive design
✅ User-friendly forms
✅ Error messages
✅ Success notifications

## Notes for Submission
- All code follows Laravel 12 standards
- No plain PHP used (only Laravel methods)
- Proper folder structure maintained
- Production-ready architecture
- Scalable design patterns
