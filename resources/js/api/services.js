import api from './client.js';

export const servicesApi = {
    list(vehicleId, params = {}) {
        return api.get(`/vehicles/${vehicleId}/services`, { params });
    },

    get(vehicleId, serviceId) {
        return api.get(`/vehicles/${vehicleId}/services/${serviceId}`);
    },

    create(vehicleId, data) {
        return api.post(`/vehicles/${vehicleId}/services`, data);
    },

    update(vehicleId, serviceId, data) {
        return api.put(`/vehicles/${vehicleId}/services/${serviceId}`, data);
    },

    delete(vehicleId, serviceId) {
        return api.delete(`/vehicles/${vehicleId}/services/${serviceId}`);
    },
};
