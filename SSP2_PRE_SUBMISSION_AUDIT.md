# VehiclePro — SSP2 Pre-Submission Audit Report

**Date:** May 2026  
**Project path:** `vehiclepro/`  
**Framework:** Laravel 13.8 (assignment-compatible with Laravel 12 stack)  
**Tests:** 73 passed, 4 skipped (77 total)

---

## Executive summary

| Area | Status | Score band |
|------|--------|------------|
| Frontend CRUD via APIs | **PASS** | Distinction |
| RESTful API | **PASS** | Distinction |
| Bearer token (Sanctum) | **PASS** | Distinction |
| Database & models (≥5) | **PASS** (6 models) | Distinction |
| Factories (≥5) | **PASS** (6 factories) | Distinction |
| Livewire (≥5) | **PASS** (6 app + 5 Jetstream integrated) | Merit–Distinction |
| Relationships | **PASS** | Distinction |
| Authentication | **PASS** | Distinction |
| Dashboard | **PASS** | Distinction |
| UI/UX | **PASS** | Distinction |
| Security | **PASS** | Minor notes |
| Testing | **PASS** | Distinction |
| Bonus features | **EXCELLENT** | Distinction |
| Code quality | **PASS** | Merit–Distinction |
| Report readiness | **PASS** | Distinction |
| Viva readiness | **GOOD** | Prepare auth-flow explanation |

**Estimated mark: 88–96 / 100** (Distinction), assuming live demo with Network tab evidence.

---

## 1. Frontend CRUD through APIs

| Check | Status | Evidence |
|-------|--------|----------|
| Create | ✅ | `vehicle-form.js`, `service-form.js` → POST |
| Retrieve | ✅ | `vehicles-index.js`, `vehicle-show.js` → GET list/delete |
| Update | ✅ | PUT via forms |
| Delete | ✅ | DELETE + soft delete |
| Axios | ✅ | `resources/js/api/client.js` |
| Async | ✅ | `async/await` in page modules |
| No Blade CRUD POST | ✅ | `FrontendApiDependencyTest` — POST `/vehicles` → 404/405 |
| Dynamic updates | ✅ | List re-fetch after delete; dashboard renders without full reload |

**Proof:** Comment out `Route::apiResource('vehicles'...)` in `api.php` — fleet UI breaks while Blade shells still load.

**Minor gap:** Vehicle/service **show** pages use server-rendered Blade; `vehiclesApi.get()` / `servicesApi.get()` exist but are not used on show pages (acceptable for SPA-lite).

---

## 2. RESTful API architecture

| Check | Status |
|-------|--------|
| `routes/api.php` | ✅ 27 routes |
| `Route::apiResource` | ✅ `vehicles`, `vehicles.services` |
| HTTP verbs | ✅ GET/POST/PUT/DELETE |
| JSON envelope | ✅ `{ success, message, data }` via `RespondsWithApiJson` |
| Status codes | ✅ 201 create, 401/403/422 |
| Naming | ✅ RESTful nested resources |

**Docs:** `GET /api/docs`, `docs/API.md`, Postman collection.

---

## 3. Bearer token authentication

| Check | Status |
|-------|--------|
| Sanctum | ✅ |
| Token on login | ✅ `vehiclepro-token` |
| `Authorization: Bearer` | ✅ Axios interceptor |
| `localStorage` key | ✅ `vehiclepro_api_token` |
| Logout revokes | ✅ `POST /api/logout` |
| Unauthorized blocked | ✅ `auth:sanctum` + policies |
| Session bridge | ✅ `SessionBootstrapController` |

---

## 4. Database & models (≥5)

| Model | Migration | Relationships |
|-------|-----------|---------------|
| User | ✅ | hasMany vehicles, reminders, fuelLogs, activityLogs |
| Vehicle | ✅ | belongsTo user; hasMany services, reminders, fuelLogs |
| ServiceRecord | ✅ | belongsTo vehicle |
| Reminder | ✅ | belongsTo user, vehicle |
| FuelLog | ✅ | belongsTo user, vehicle |
| ActivityLog | ✅ | belongsTo user; morphTo subject |

**Notifications:** Laravel `notifications` table (not Eloquent model in `app/Models` — acceptable).

**FKs:** Cascade on domain tables; soft deletes on Vehicle/ServiceRecord.

---

## 5. Factories (≥5)

6 factories: User, Vehicle, ServiceRecord, Reminder, FuelLog, ActivityLog.

Used in PHPUnit extensively. Seeder uses manual `create()` for demo data (factories still demonstrable via `php artisan tinker` / tests).

---

## 6. Livewire components (≥5)

### Integrated in production UI

1. `NotificationBell` — navigation  
2. `profile.two-factor-authentication-form` — profile  
3. `profile.logout-other-browser-sessions-form` — profile  
4. `profile.delete-user-form` — profile  
5. `api.api-token-manager` — API tokens page  

### App components (`app/Livewire/` — 6)

| Component | Integrated in main UI? |
|-----------|------------------------|
| NotificationBell | ✅ Nav |
| DashboardStats | ⚠️ Not mounted (API dashboard used) |
| RecentActivity | ⚠️ Not mounted |
| UpcomingReminders | ⚠️ Not mounted |
| VehicleList | ⚠️ Not mounted (API fleet used) |
| VehicleServices | ⚠️ Not mounted |

**Viva note:** Explain that custom Livewire widgets are **alternate server-rendered implementations**; primary SSP2 CRUD path is API+Axios. Jetstream Livewire satisfies the “5 components” requirement with working UI.

---

## 7. Relationships

✅ hasMany / belongsTo / morphTo / eager loading in API controllers and `DashboardAnalyticsService`.

---

## 8. Authentication

| Feature | Status |
|---------|--------|
| API login/register | ✅ |
| Logout | ✅ API + Fortify |
| Google OAuth | ✅ |
| 2FA | ✅ Fortify + API branch |
| Session persistence | ✅ bootstrap + database sessions |
| Profile/password API | ✅ `profile-forms.js` |

---

## 9. Dashboard

✅ API-driven (`GET /api/dashboard`), Chart.js, reminders, activity, responsive layout.

---

## 10. UI/UX

✅ Dark mode, glass UI, loading/empty states, toasts, mobile nav, animations.

---

## 11. Security

✅ `auth:sanctum`, policies, validation, `$fillable`, CSRF on web, throttle on auth, admin middleware.

**Minor:** Admin user delete via `DELETE` web route (not API) — document as admin-only exception.

---

## 12. Testing

23 Feature test files, 202 assertions. Covers API CRUD, auth, trash, CSV, routing, session bootstrap, Google OAuth, Livewire profile features.

---

## 13. Bonus marks achieved

| Feature | Present |
|---------|---------|
| Google OAuth | ✅ |
| PDF exports | ✅ |
| CSV API export | ✅ |
| Admin panel | ✅ |
| Notifications | ✅ |
| Trash / soft delete | ✅ |
| Charts | ✅ |
| Dark mode | ✅ |
| API documentation | ✅ |
| Activity log | ✅ |
| Rate limiting | ✅ |
| Passkeys (Fortify) | ✅ |
| Image upload (vehicles) | ✅ |

**Suggested extras (optional):** Reminder/FuelLog API resources; OpenAPI/Swagger; PWA offline banner.

---

## 14. Code quality

✅ Clear separation: `Api/*` vs `PageController`, services, policies, resources.  
⚠️ Some orphaned Livewire; submission docs updated for accuracy.

---

## 15. Report readiness

| Material | Location |
|----------|----------|
| ER diagram | `docs/er-diagram.mmd` |
| API docs | `docs/API.md`, `/api/docs` |
| DB design | `DATABASE_DESIGN_SSP2.md` |
| Submission guide | `SSP2_SUBMISSION.md` |
| Testing guide | `API_TESTING_GUIDE.md` |
| Postman | `docs/VehiclePro.postman_collection.json` |

---

## 16. Viva / demo readiness

### Likely questions

1. Where is CRUD? → `ApiVehicleController`, `ApiServiceController`, Axios modules.  
2. Prove UI depends on API? → `FrontendApiDependencyTest`, disable `api.php` routes live.  
3. Sanctum + sessions? → API login → token → session bootstrap → Bearer on API.  
4. Why nested services routes? → Ownership enforced by URL + policies.  
5. Livewire vs Axios? → Livewire for notifications/profile security; CRUD via API.

### Weak explanation areas

- Orphan `app/Livewire` components (know they exist as alternatives).  
- Laravel 12 vs 13 naming in brief.  
- Reminder/FuelLog models without API CRUD (seeded + dashboard only).

### Demo script (5 min)

1. Login → Network: `/api/login`, `/auth/session/bootstrap`, Bearer on `/api/vehicles`.  
2. CRUD vehicle + service.  
3. Trash restore.  
4. Open `/api/docs`.  
5. Profile 2FA / API tokens (Livewire).  
6. Admin panel (if admin user).  
7. `php artisan test`.

---

## 17. Missing / weak items

| Item | Severity | Action |
|------|----------|--------|
| 5 custom Livewire not on main pages | Low | Explain Jetstream integration |
| Seeder not using factories | Low | Factories proven in tests |
| Admin delete via web | Low | Document exception |
| Notification mark-read via web POST | Low | Acceptable |
| No Reminder/FuelLog API | Low | Bonus opportunity |
| `npm run build` required for production | Medium | Run before submit |

---

## 18. Final readiness

**Status: READY FOR SUBMISSION** after:

- [x] `php artisan migrate --seed`  
- [x] `npm run build`  
- [x] `php artisan test`  
- [ ] Capture screenshots (login, dashboard, Network tab, API docs, CRUD, admin)  
- [ ] Align `APP_URL` with browser URL  
- [ ] Rehearse viva answers  

---

*Generated by pre-submission audit. See `SSP2_SUBMISSION.md` for lecturer-facing demo steps.*
