import api from './client.js';

export const trashApi = {
    restoreVehicle(id) {
        return api.post(`/trash/vehicles/${id}/restore`);
    },

    forceDeleteVehicle(id) {
        return api.delete(`/trash/vehicles/${id}`);
    },

    restoreService(id) {
        return api.post(`/trash/services/${id}/restore`);
    },

    forceDeleteService(id) {
        return api.delete(`/trash/services/${id}`);
    },
};
