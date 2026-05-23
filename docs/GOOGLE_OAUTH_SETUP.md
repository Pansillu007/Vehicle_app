# Google OAuth setup (VehiclePro)

## 1. Google Cloud Console

1. Open [Google Cloud Console](https://console.cloud.google.com/)
2. Create or select a project
3. Go to **APIs & Services → OAuth consent screen**
   - User type: **External** (or Internal for workspace)
   - Add app name, support email, developer contact
   - Scopes: `email`, `profile`, `openid` (default with Socialite)
4. Go to **Credentials → Create credentials → OAuth client ID**
   - Application type: **Web application**
   - Authorized redirect URIs (must match exactly):
     - Local: `http://127.0.0.1:8000/auth/google/callback`
     - Laragon: `http://vehiclepro.test/auth/google/callback` (your virtual host)
     - Production: `https://yourdomain.com/auth/google/callback`

## 2. Laravel `.env`

```env
GOOGLE_CLIENT_ID=your-client-id.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=your-client-secret
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"
```

Ensure `APP_URL` matches how you open the site in the browser.

## 3. Migrate database

```bash
php artisan migrate
```

Adds `google_id`, `avatar`, `provider` to `users`.

## 4. Test flow

1. Visit `/login` or `/register`
2. Click **Continue with Google**
3. Complete Google consent
4. You should land on `/dashboard`
5. DevTools → Network: API calls include `Authorization: Bearer …`

## Architecture notes

| Step | What happens |
|------|----------------|
| Redirect | `GET /auth/google/redirect` → Socialite → Google |
| Callback | `GET /auth/google/callback` → create/link user → `Auth::login()` |
| Sanctum | `FrontendTokenService` issues `frontend` token → `session('vehiclepro_api_token')` |
| Frontend | `layouts/app.blade.php` meta `api-token` → `bootstrapApiTokenFromMeta()` → Axios Bearer |

Email/password login and `POST /api/login` are unchanged.
