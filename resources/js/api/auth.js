import api from './client.js';

export const TOKEN_KEY = 'vehiclepro_api_token';

const PUBLIC_API_PATHS = ['/login', '/register'];

function getCsrfToken() {
    const meta = document.querySelector('meta[name="csrf-token"]')?.content?.trim();
    if (meta) {
        return meta;
    }

    const match = document.cookie.match(/(?:^|;\s*)XSRF-TOKEN=([^;]*)/);
    return match ? decodeURIComponent(match[1]) : '';
}

export function getApiToken() {
    return localStorage.getItem(TOKEN_KEY) || '';
}

/** Prefer Blade meta token (server truth) over localStorage. */
export function getResolvedApiToken() {
    const meta = document.querySelector('meta[name="api-token"]')?.content?.trim();
    if (meta) {
        return meta;
    }

    return getApiToken();
}

export function setApiToken(token) {
    if (token) {
        localStorage.setItem(TOKEN_KEY, token);
    }
}

export function clearApiToken() {
    localStorage.removeItem(TOKEN_KEY);
}

export function isPublicApiPath(url = '') {
    const path = String(url).replace(/^\/api/, '');
    return PUBLIC_API_PATHS.some((segment) => path === segment || path.endsWith(segment));
}

/** Prefer server-issued token on authenticated Blade pages. */
export function bootstrapApiTokenFromMeta() {
    const token = getResolvedApiToken();
    if (token) {
        setApiToken(token);
    }
    return token;
}

export async function verifyWebSession() {
    const response = await fetch('/auth/session/check', {
        method: 'GET',
        headers: {
            Accept: 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
        credentials: 'same-origin',
    });

    return response.ok;
}

export async function loginApi(credentials) {
    const response = await api.post('/login', credentials);
    const token = response.data?.data?.token;
    if (token) {
        setApiToken(token);
    }
    return response;
}

export async function registerApi(payload) {
    const response = await api.post('/register', payload);
    const token = response.data?.data?.token;
    if (token) {
        setApiToken(token);
    }
    return response;
}

/** Establish a web session after API login so Blade routes work. */
export async function bootstrapWebSession(token = getApiToken()) {
    if (!token) {
        throw new Error('Missing API token.');
    }

    const csrf = getCsrfToken();
    const response = await fetch('/auth/session/bootstrap', {
        method: 'POST',
        headers: {
            Accept: 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf,
            'X-Requested-With': 'XMLHttpRequest',
        },
        credentials: 'same-origin',
        body: JSON.stringify({ token }),
    });

    const body = await response.json().catch(() => ({}));

    if (!response.ok) {
        const error = new Error(body.message || 'Could not establish session.');
        error.status = response.status;
        error.data = body;
        throw error;
    }

    if (body.data?.token) {
        setApiToken(body.data.token);
    } else {
        setApiToken(token);
    }

    return body;
}

/** Revoke the current Sanctum token on the server (best-effort). */
export async function revokeApiToken() {
    const token = getApiToken();
    if (!token) {
        return;
    }

    try {
        await api.post('/logout');
    } catch {
        // Session logout should still proceed if the token is already invalid.
    }
}

/** Invalidate the Jetstream/Fortify web session (best-effort). */
export async function logoutWebSession() {
    const csrf = getCsrfToken();
    if (!csrf) {
        return;
    }

    try {
        await fetch('/logout', {
            method: 'POST',
            headers: {
                Accept: 'application/json',
                'X-CSRF-TOKEN': csrf,
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
        });
    } catch {
        // Redirect to login even if the session was already gone.
    }
}

/** Full sign-out: revoke token, clear storage, destroy web session. */
export async function performFullLogout({ redirect = true } = {}) {
    await revokeApiToken().catch(() => {});
    clearApiToken();
    await logoutWebSession().catch(() => {});

    if (redirect && !window.location.pathname.includes('/login')) {
        window.location.href = '/login?session=expired';
    }
}

export async function handleLogoutSubmit(event) {
    event.preventDefault();
    const form = event.currentTarget;

    form.querySelectorAll('[type="submit"]').forEach((btn) => {
        btn.disabled = true;
    });

    await performFullLogout({ redirect: true });
}

export function bindLogoutTokenClear() {
    document.querySelectorAll('form[data-clear-api-token]').forEach((form) => {
        form.addEventListener('submit', handleLogoutSubmit);
    });
}
