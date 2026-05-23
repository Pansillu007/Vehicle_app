# VehiclePro — SSP2 Submission Guide

**Student project:** Vehicle Management System  
**Stack:** Laravel 13 · Jetstream (Livewire) · Sanctum · REST API · Axios SPA layer  
**Repository folder:** `vehiclepro/`

This document is written for **lecturers and viva examiners**. It maps every SSP2 requirement to concrete files and demo steps.

---

## Quick start (demo in 5 minutes)

```bash
cd vehiclepro
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install && npm run build
php artisan serve
```

| Account | Email | Password | Role |
|---------|-------|----------|------|
| Admin | admin@vehiclepro.com | password | Admin (sees all vehicles) |
| User | test@example.com | password | User (own vehicles only) |

1. Open `http://127.0.0.1:8000/login` → sign in as **test@example.com**
2. Open **DevTools → Network** → go to **Vehicles** → confirm `GET /api/vehicles` with `Authorization: Bearer …`
3. **Add Vehicle** → confirm `POST /api/vehicles`
4. Open **API Docs** in the nav → `GET /api/docs` JSON catalogue
5. **Export CSV** on fleet page → `GET /api/export/vehicles`
6. Log out → confirm `POST /api/logout` then session logout

---

## Architecture (what to mark)

```
Browser (Blade + Vite + Axios)
    │  Bearer token from <meta name="api-token"> (Sanctum)
    ▼
routes/api.php  ──► Api*Controller ──► Models / Policies / Resources
    │
routes/web.php  ──► PageController (views only, GET)
    │
Fortify/Jetstream ──► Session auth for login/register/profile
```

**Rule:** All **mutations** (create, update, delete, restore, export data) go through **`/api/*`** with `auth:sanctum`.  
**Web routes** only return Blade HTML (plus PDF downloads and admin read-only pages).

**Proof test:** `tests/Feature/FrontendApiDependencyTest.php` — POST to `/vehicles` returns 405/404.

---

## Requirement checklist (100% coverage)

### 1. RESTful API (`routes/api.php`)

| Method | Endpoint | Controller |
|--------|----------|------------|
| POST | `/api/register` | AuthController |
| POST | `/api/login` | AuthController (throttled) |
| POST | `/api/logout` | AuthController |
| GET | `/auth/google/redirect` | GoogleAuthController (OAuth) |
| GET | `/auth/google/callback` | GoogleAuthController (OAuth) |
| GET | `/api/docs` | ApiDocumentationController |
| GET | `/api/user` | UserResource |
| GET | `/api/dashboard` | ApiDashboardController |
| GET/PUT | `/api/profile` | ApiProfileController |
| apiResource | `/api/vehicles` | ApiVehicleController |
| apiResource | `/api/vehicles/{vehicle}/services` | ApiServiceController |
| GET | `/api/export/vehicles` | ApiExportController (CSV) |
| GET | `/api/export/vehicles/{vehicle}/services` | ApiExportController (CSV) |
| Trash | `/api/trash/...` | ApiTrashController |

JSON envelope: `{ success, message, data }` via `RespondsWithApiJson`.

### 2. Sanctum authentication

- Package: `laravel/sanctum`
- Middleware: `auth:sanctum` on protected API group
- Token name: `frontend`
- SPA flow: `ApiTokenComposer` → meta tag → `localStorage` → Axios `Authorization: Bearer`
- Logout: `revokeApiToken()` in `resources/js/api/auth.js` calls `POST /api/logout` before Fortify logout
- API clients: `POST /api/login` returns `{ data: { token, user } }`

### 3. Frontend CRUD via Axios (not Laravel form POST)

| Feature | JS module | API |
|---------|-----------|-----|
| Dashboard | `pages/dashboard.js` | GET `/api/dashboard` |
| Vehicle list/delete | `pages/vehicles-index.js` | GET/DELETE `/api/vehicles` |
| Vehicle form | `pages/vehicle-form.js` | POST/PUT `/api/vehicles` |
| Service list/delete | `pages/vehicle-show.js` | GET/DELETE `.../services` |
| Service form | `pages/service-form.js` | POST/PUT `.../services` |
| Profile | `pages/profile-forms.js` | PUT `/api/profile` |
| Trash actions | `pages/trash-index.js` | POST/DELETE `/api/trash/...` |
| CSV export | `api/export.js` | GET `/api/export/...` |

### 4. MVC

- **Models:** `User`, `Vehicle`, `ServiceRecord`
- **API controllers:** `app/Http/Controllers/Api/*`
- **View controllers:** `PageController`, `TrashController` (index only)
- **Services:** `DashboardAnalyticsService`, `CsvExportService`, `ServiceReminderService`, `ActivityLogger`
- **Policies:** `VehiclePolicy`, `UserPolicy`

### 5. Eloquent relationships

- `User hasMany Vehicle`
- `Vehicle belongsTo User`, `hasMany ServiceRecord`
- `ServiceRecord belongsTo Vehicle`
- Eager loading in API show/index and dashboard analytics

### 6. API resources

- `VehicleResource`, `ServiceRecordResource`, `UserResource`
- Auth login/register return `UserResource` shape

### 7. Jetstream + Livewire

- Stack: `livewire` in `config/jetstream.php`
- Livewire: notification bell, 2FA, account deletion, API token manager
- Profile name/email: API forms; security features: Livewire

### 8. UI/UX

- Tailwind + dark mode + glass UI
- Loading states, empty states, toasts, validation errors on forms
- Chart.js dashboard analytics
- Mobile-responsive navigation

### 9. Bonus features

| Feature | Location |
|---------|----------|
| Analytics dashboard | `/dashboard` + `/api/dashboard` |
| Charts | Chart.js in `dashboard.blade.php` |
| Dark mode | `app.js` + Alpine in layout |
| Search/filter | API query params + JS debounce |
| PDF export | `PdfExportController` (web) |
| **CSV export** | `ApiExportController` (API) |
| Notifications | `NotificationBell` Livewire + DB notifications |
| Roles | `UserRole` enum, `admin` middleware |
| Profile | API + Jetstream |
| Vehicle images | Multipart API upload |
| Service reminders | `ServiceReminderService` |
| API documentation | `/api/docs` + `docs/API.md` + Postman collection |
| Admin panel | `/admin/*` |
| Soft delete + trash | SoftDeletes + trash API/UI |
| Activity log | `ActivityLog` model |
| Rate limiting | Login/register `throttle:10,1` |

### 10. Security

- CSRF on web forms
- `$fillable` on models
- Policies on all sensitive actions
- Validation on API controllers
- Sanctum token revocation on logout
- Throttled auth endpoints

### 11. Database

- Migrations with FK cascade
- Seeders: `DatabaseSeeder`
- Factories for all models

### 12–13. Code quality & performance

- Feature tests: 60+ assertions covering API, auth, trash, CSV, routing
- `DashboardAnalyticsService` uses eager loading; vehicle index paginated
- No duplicate legacy API controllers

---

## Viva questions — suggested answers

**Q: Where is CRUD implemented?**  
A: In `ApiVehicleController` and `ApiServiceController`; the browser calls them via Axios modules in `resources/js/api/`.

**Q: How do you prove the UI needs the API?**  
A: Disable `Route::apiResource('vehicles'...)` in `api.php` — pages load but lists and forms fail. Tests in `FrontendApiDependencyTest`.

**Q: How does Sanctum work with Jetstream?**  
A: User logs in with Fortify (session). `ApiTokenComposer` issues a Sanctum token stored in session and exposed in the layout meta tag. JavaScript copies it to `localStorage` for API calls.

**Q: Nested services REST design?**  
A: Services belong to a vehicle; routes are `/api/vehicles/{vehicle}/services/{service}` so ownership is enforced at the URL and policy level.

---

## Files to open during marking

1. `routes/api.php` — all API endpoints  
2. `routes/web.php` — GET-only views  
3. `resources/js/api/client.js` — Bearer interceptor  
4. `app/Http/Controllers/Api/ApiVehicleController.php` — CRUD + validation  
5. `tests/Feature/CompleteSystemTest.php` — end-to-end API tests  
6. `docs/API.md` — human-readable API reference  
7. `docs/VehiclePro.postman_collection.json` — import into Postman  

---

## Estimated grade alignment

| Criterion | Expected |
|-----------|----------|
| API architecture | Distinction |
| Sanctum | Distinction |
| Axios CRUD | Distinction |
| MVC & relationships | Distinction |
| UI polish + bonuses | Distinction |
| Documentation & tests | Distinction |

**Target: 90–100%** when demonstrated live with Network tab evidence.
