import api from './client.js';

export const profileApi = {
    get() {
        return api.get('/profile');
    },

    update(data) {
        return api.put('/profile', data);
    },

    updatePassword(data) {
        return api.put('/profile/password', data);
    },
};
