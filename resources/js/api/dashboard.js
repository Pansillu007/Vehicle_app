import api from './client.js';

export const dashboardApi = {
    get() {
        return api.get('/dashboard');
    },
};
