import axios from 'axios';
import { getApiToken } from './auth.js';

const api = axios.create({
    baseURL: '/api',
    headers: {
        Accept: 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    },
});

api.interceptors.request.use((config) => {
    const token = getApiToken();
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }

    return config;
});

api.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 401) {
            const onLoginPage = window.location.pathname.includes('login');
            if (!onLoginPage) {
                console.warn('API authentication failed. Ensure api.php routes are active and a valid token is stored.');
            }
        }

        return Promise.reject(error);
    }
);

export default api;
