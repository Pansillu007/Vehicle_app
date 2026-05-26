# VehiclePro Security Implementation

## 1. Authentication & Authorization

### Web Authentication
- **Framework**: Laravel Jetstream + Fortify
- **Password hashing**: bcrypt (12 rounds, configured in `config/hashing.php`)
- **Session timeout**: 30 minutes (`SESSION_LIFETIME` in `.env`)
- **2FA**: TOTP (optional, required for admins)
- **Session fixation prevention**: `session()->invalidate()` before `regenerate()` in `SessionBootstrapController`

### API Authentication  
- **Framework**: Laravel Sanctum
- **Token type**: Bearer tokens (created via `createToken('vehiclepro-token', ['*'])`)
- **Expiration**: 60 minutes (configured in `config/sanctum.php`)
- **Storage**: localStorage with key `vehiclepro_api_token`
- **Revocation**: `POST /api/v1/logout` deletes token via `currentAccessToken()->delete()`
- **Issue endpoint**: `POST /api/v1/login` or `POST /api/v1/register` returns `{ data: { token, user } }`

### Authorization
- **Pattern**: Laravel Policies (app/Policies/*.php)
- **VehiclePolicy**: Users see own vehicles; admins see all
- **ServiceRecordPolicy**: Access controlled through parent vehicle
- **Enforcement**: `$this->authorize('view', $vehicle)` in all API endpoints
- **Test coverage**: `tests/Feature/ApiAuthorizationTest.php` verifies unauthorized users cannot access others' data
- **Admin enforcement**: Admins bypass ownership checks

## 2. Input Validation & Sanitization

### Server-side Validation
- **Framework**: Form Requests (`app/Http/Requests/*.php`)
- **Fuel type**: Enum validation (Petrol, Diesel, Electric, Hybrid)
- **VIN number**: max 17 chars, alphanumeric, stored uppercase
- **Service date**: Must be past or today (`before_or_equal:today`)
- **Next service date**: Must be future (`after:today`)
- **File uploads**: 
  - MIME type validation (image/jpeg, image/png only)
  - Max 2MB file size
  - Filename randomized: `Str::random(20) . '.jpg'` (not user-provided)
  - Dimensions validation: min 100x100px
- **Number plate**: Unique per user + globally unique VIN

### Output Encoding
- **Blade templates**: Auto-escaped via `{{ }}` syntax
- **API responses**: JSON responses (automatically encoded)
- **File downloads**: `Content-Disposition: attachment` enforced

## 3. CSRF & XSS Protection

### CSRF
- **Web routes**: `@csrf` tokens via `VerifyCsrfToken` middleware
- **API routes**: Sanctum bearer tokens (CSRF disabled by design)
- **Rationale**: API uses stateless token auth, not session cookies that are vulnerable to CSRF

### XSS
- **Input**: Server-side validation in Form Requests prevents dangerous input
- **Output**: Blade `{{ }}` auto-escapes HTML characters
- **Headers**: `X-Content-Type-Options: nosniff` prevents MIME sniffing
- **CSP**: `Content-Security-Policy: default-src 'self'` restricts script sources

## 4. SQL Injection Prevention
- **All queries use Eloquent ORM** (parameterized statements)
- **Example**: `Vehicle::where('name', 'like', '%'.$term.'%')` compiles to `WHERE name LIKE ?` with value bound separately
- **No raw queries**: String concatenation avoided entirely
- **LIKE wildcard escaping**: `str_replace('%', '\%', $search)` to prevent wildcard injection
- **Prepared statements**: Framework handles all binding

## 5. Sensitive Data Protection
- **Encryption**: VIN numbers encrypted at rest (using `protected $encrypted = ['vin_number']`)
- **Password hashing**: bcrypt with 12 rounds (never stored in plain text)
- **Token storage**: 
  - Hashed in `personal_access_tokens` table
  - Plain text returned only once at creation
  - Never logged or exposed in error messages
- **Logs**: Sensitive fields sanitized (no passwords, tokens, PII)
- **Configuration**: OAuth secrets in `.env` only, never committed to git
- **.gitignore**: `.env`, `.env.production`, `*.key` properly ignored

## 6. API Security

### Rate Limiting
- **Auth endpoints** (login, register): 10 requests/1 minute per IP (`throttle:10,1` in `routes/api.php`)
- **Login attempts**: 5 attempts/minute per email (configured in `FortifyServiceProvider`)
- **Password reset**: 3 requests/hour per email (configured in `config/fortify.php`)
- **CSV export**: 30 requests/minute to prevent DOS attacks
- **Standard endpoints**: 60 requests/minute per authenticated user

### Response Security
- **X-Content-Type-Options**: `nosniff` — prevent MIME sniffing attacks
- **X-Frame-Options**: `DENY` — prevent clickjacking (remove from iframe)
- **Content-Security-Policy**: `default-src 'self'` — restrict script sources to prevent XSS
- **No stack traces**: Disabled in production (`APP_DEBUG=false`)
- **Error responses**: Generic messages in production; detailed messages in development only

### API Versioning
- **Version path**: `/api/v1/*` (supports future breaking changes without disrupting v1 clients)
- **Migration path**: Documented strategy for v1 → v2 migration
- **Backward compatibility**: All v1 endpoints maintain consistent request/response format

## 7. File Upload Security
- **File storage**: `storage/app/public` (outside webroot for production)
- **Filename**: Randomized `Str::random(20) . '.jpg'` — not user-provided to prevent path traversal
- **Access**: Served via `Storage::url()` (authenticated users only)
- **Size limit**: 2MB per file; configurable per endpoint
- **Type validation**: 
  - MIME type check (image/jpeg, image/png)
  - Dimension check (min 100x100px)
  - Extension whitelist
- **Validation**: All checks enforced in Form Request rules

## 8. Authentication Token Security

### Token Lifecycle
1. **Creation**: User logs in via `POST /api/v1/login`
   - Optional 2FA step if enabled
   - Token issued with name `vehiclepro-token`
   - Returned once in response `{ data: { token, user } }`

2. **Storage**: Frontend stores in localStorage as `vehiclepro_api_token`
   - Not in HTTP-only cookie (by design for SPA)
   - Frontend responsible for sanitization

3. **Usage**: Sent in header `Authorization: Bearer {token}`
   - All protected endpoints require valid token
   - Invalid/expired tokens return 401 Unauthorized

4. **Expiration**: 
   - Default 60 minutes (configurable in `config/sanctum.php`)
   - Expired tokens cannot be used; user must re-login
   - No refresh token endpoint (by design for simplicity)

5. **Revocation**: 
   - `POST /api/v1/logout` deletes token immediately
   - User session invalidated
   - Frontend must clear localStorage

## 9. Authorization & Access Control

### Policy-Based Authorization
- **Vehicle access**: 
  - Regular users see only their own vehicles
  - Admins see all vehicles
  - Ownership verified via `vehicle->user_id === auth()->id()`
  
- **Service records**: 
  - Access controlled through parent vehicle
  - Cannot create service for another user's vehicle
  
- **Enforcement**: 
  ```php
  $this->authorize('view', $vehicle); // Check before returning data
  $this->authorize('update', $vehicle); // Check before modifying
  ```

### Soft Deletes
- **Implementation**: All models use `SoftDeletes`
- **API behavior**: `->withoutTrashed()` explicitly excludes soft-deleted records
- **Trash management**: Separate `/api/v1/trash/*` endpoints for restore/force-delete
- **Auto-exclusion**: Eloquent auto-excludes soft-deleted records (manual `withTrashed()` for admin view)

## 10. Database Security

### Indexes & Query Optimization
- **Foreign key indexes**: 
  - `vehicles.user_id` indexed
  - `service_records.vehicle_id` indexed
  - `activity_logs.user_id` indexed
  
- **Composite indexes**: `[user_id, created_at]` for efficient scoped queries
- **Prevents N+1 queries**: Eager loading via `->with()` in all API endpoints
- **Query scopes**: Common filters encapsulated in model scopes (`scopeForUser()`, `scopeByFuelType()`)

### Data Constraints
- **Foreign key constraints**: `->constrained()->onDelete('cascade')`
  - Deleting user cascades delete to vehicles
  - Deleting vehicle cascades delete to service records
- **Unique constraints**: 
  - `vin_number` globally unique
  - `(user_id, number_plate)` composite unique (user can't have duplicate plates)
- **NOT NULL constraints**: Critical fields enforced (`name`, `make`, `model`, `number_plate`, `user_id`)

## 11. Testing & Compliance

### Authorization Testing
- **File**: `tests/Feature/ApiAuthorizationTest.php`
- **Coverage**: 
  - Unauthorized users cannot access protected endpoints
  - User A cannot access User B's vehicles
  - Admins can access all vehicles
  - Deleted records properly hidden
  
### Security Test Checklist
- [ ] All API endpoints have authorization checks
- [ ] Soft-deleted records excluded from API responses
- [ ] File uploads pass validation
- [ ] Rate limits enforced (test 429 response)
- [ ] Password reset tokens expire after 24 hours
- [ ] Session tokens regenerated after re-auth
- [ ] CSRF tokens validated on web routes
- [ ] XSS payloads properly escaped in responses

### Email Verification
- **Requirement**: All API endpoints require `verified` middleware
- **Enforcement**: `Route::middleware(['auth:sanctum', 'verified'])->group(...)`
- **Token expiry**: Password reset tokens expire after 24 hours (`config/auth.php`)

## 12. Deployment Security

### Environment Configuration
- **Development** (`.env`):
  - `APP_DEBUG=true` (detailed errors)
  - `LOG_LEVEL=debug`
  - `CACHE_STORE=database`
  
- **Production** (`.env.production`, not committed):
  - `APP_DEBUG=false` (generic errors)
  - `LOG_LEVEL=error` (only errors logged)
  - `CACHE_STORE=redis` (fast caching)
  - `APP_ENV=production`

### Secrets Management
- **OAuth secrets**: In `.env` only, never in `.env.example`
- **Database credentials**: Environment variables only
- **API keys**: Never in code or version control
- **Keys rotation**: Review quarterly

### HTTPS Enforcement
- **Web**: Redirect HTTP → HTTPS via middleware
- **Cookies**: `secure` flag set (HTTPS only)
- **API**: Bearer tokens only over HTTPS in production
- **Certificate**: Let's Encrypt (auto-renewal)

### Logging & Monitoring
- **Log channel**: `stack` (combines multiple handlers)
- **Log level**: ERROR in production (reduce noise)
- **Retention**: Delete logs older than 30 days
- **Monitoring**: External error tracking (recommended: Sentry, Rollbar)
- **Alerts**: Critical errors trigger email notifications

## 13. Threat Model & Mitigations

| Threat | Likelihood | Severity | Mitigation |
|--------|-----------|----------|-----------|
| Brute force login | High | High | Rate limiting (5/min per email) + 2FA |
| Session hijacking | Medium | High | HTTPS only, session regeneration on auth |
| Unauthorized vehicle access | Low | Critical | Policy checks tested in `ApiAuthorizationTest` |
| CSV injection | Medium | Medium | Escape `=`, `+`, `@` in exported data |
| File upload DOS | Medium | High | Rate limiting (30/min) + 2MB size limit |
| SQL injection | Low | Critical | Eloquent ORM (parameterized queries only) |
| XSS via stored data | Low | Medium | Blade `{{ }}` auto-escaping + CSP header |
| CSRF on forms | Low | Medium | Web routes require `@csrf` tokens |

## 14. Security Headers

The following headers are recommended for production:

```
X-Content-Type-Options: nosniff
X-Frame-Options: DENY
Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'
X-Content-Security-Policy-Report-Only: <same as CSP>
Referrer-Policy: strict-origin-when-cross-origin
Permissions-Policy: geolocation=(), microphone=(), camera=()
```

## 15. Incident Response

### Security Vulnerability Reporting
- **Process**: Email security team with details (if deployed)
- **Response time**: 24 hours for critical vulnerabilities
- **User notification**: Within 48 hours if data compromised
- **Fix timeline**: 1 week for critical, 2 weeks for high-severity

### Incident Checklist
- [ ] Identify vulnerability
- [ ] Assess impact (data, users affected)
- [ ] Create fix + tests
- [ ] Deploy with monitoring
- [ ] Document root cause
- [ ] Notify users if needed
- [ ] Post-incident review

## 16. Future Improvements

- [ ] Implement API key-based authentication for external integrations
- [ ] Add CORS for mobile app access
- [ ] Implement refresh tokens for longer-lived sessions
- [ ] Add audit logging for sensitive data exports
- [ ] Implement encryption for vehicle data at rest
- [ ] Add IP whitelisting for admin endpoints
- [ ] Implement device fingerprinting for 2FA
- [ ] Add webhook signature verification

## 17. Security Resources

- [Laravel Security Best Practices](https://laravel.com/docs/security)
- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [CWE/SANS Top 25](https://cwe.mitre.org/top25/)
- [Laravel Sanctum Documentation](https://laravel.com/docs/sanctum)
- [Laravel Fortify Documentation](https://laravel.com/docs/fortify)

---

**Last Updated**: May 26, 2026  
**Review Frequency**: Quarterly  
**Next Review**: August 26, 2026
