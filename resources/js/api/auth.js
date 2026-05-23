import api from './client.js';

export const TOKEN_KEY = 'vehiclepro_api_token';

export function getApiToken() {
    return localStorage.getItem(TOKEN_KEY) || '';
}

export function setApiToken(token) {
    if (token) {
        localStorage.setItem(TOKEN_KEY, token);
    }
}

export function clearApiToken() {
    localStorage.removeItem(TOKEN_KEY);
}

/** Sync Sanctum token from Blade meta (after Fortify session login) into localStorage. */
export function bootstrapApiTokenFromMeta() {
    const meta = document.querySelector('meta[name="api-token"]');
    if (meta?.content) {
        setApiToken(meta.content);
    }
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

export function bindLogoutTokenClear() {
    document.querySelectorAll('form[data-clear-api-token]').forEach((form) => {
        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            await revokeApiToken();
            clearApiToken();
            form.submit();
        });
    });
}
