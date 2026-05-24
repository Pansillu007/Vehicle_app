import axios from 'axios';
import {
    clearApiToken,
    getResolvedApiToken,
    isPublicApiPath,
    performFullLogout,
    setApiToken,
} from './auth.js';

const api = axios.create({
    baseURL: '/api',
    headers: {
        Accept: 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    },
});

let handlingUnauthorized = false;

api.interceptors.request.use((config) => {
    const url = config.url || '';

    if (!isPublicApiPath(url)) {
        const token = getResolvedApiToken();
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
            setApiToken(token);
        } else {
            delete config.headers.Authorization;
        }
    } else {
        delete config.headers.Authorization;
    }

    const csrf = document.querySelector('meta[name="csrf-token"]')?.content?.trim();
    if (csrf) {
        config.headers['X-CSRF-TOKEN'] = csrf;
    }

    return config;
});

api.interceptors.response.use(
    (response) => response,
    async (error) => {
        const status = error.response?.status;
        const requestUrl = error.config?.url || '';

        if (status === 401 && !isPublicApiPath(requestUrl)) {
            const onAuthPage = ['/login', '/register', '/two-factor-challenge'].some((path) =>
                window.location.pathname.includes(path)
            );

            if (!onAuthPage && !handlingUnauthorized) {
                const resolvedToken = getResolvedApiToken();

                if (resolvedToken && error.config.headers.Authorization !== `Bearer ${resolvedToken}`) {
                    error.config.headers.Authorization = `Bearer ${resolvedToken}`;
                    setApiToken(resolvedToken);
                    return api.request(error.config);
                }

                if (resolvedToken && !error.config.__sessionRetried) {
                    try {
                        const { bootstrapWebSession, verifyWebSession } = await import('./auth.js');
                        await bootstrapWebSession(resolvedToken);
                        if (await verifyWebSession()) {
                            error.config.__sessionRetried = true;
                            return api.request(error.config);
                        }
                    } catch {
                        // Fall through to full logout below.
                    }
                }

                handlingUnauthorized = true;
                clearApiToken();
                await performFullLogout({ redirect: false });
                window.dispatchEvent(new CustomEvent('api:unauthorized'));
            }
        }

        return Promise.reject(error);
    }
);

export function resetUnauthorizedGuard() {
    handlingUnauthorized = false;
}

export default api;
