import api from './client.js';

export const vehiclesApi = {
    list(params = {}) {
        return api.get('/vehicles', { params });
    },

    get(id) {
        return api.get(`/vehicles/${id}`);
    },

    create(data) {
        return api.post('/vehicles', data, data instanceof FormData ? { headers: { 'Content-Type': 'multipart/form-data' } } : {});
    },

    update(id, data) {
        if (data instanceof FormData) {
            data.append('_method', 'PUT');
            return api.post(`/vehicles/${id}`, data, { headers: { 'Content-Type': 'multipart/form-data' } });
        }
        return api.put(`/vehicles/${id}`, data);
    },

    delete(id) {
        return api.delete(`/vehicles/${id}`);
    },
};
