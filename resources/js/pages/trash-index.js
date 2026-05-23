import { trashApi } from '../api/trash.js';
import { handleApiError } from '../api/errors.js';
import { apiMessage } from '../api/response.js';
import { showToast } from '../api/toast.js';

const root = document.getElementById('trash-app');
if (root) {
    root.querySelectorAll('[data-restore-vehicle]').forEach((btn) => {
        btn.addEventListener('click', async () => {
            try {
                const response = await trashApi.restoreVehicle(btn.dataset.restoreVehicle);
                showToast(apiMessage(response, 'Vehicle restored.'), 'success');
                window.location.reload();
            } catch (e) {
                handleApiError(e);
            }
        });
    });

    root.querySelectorAll('[data-force-delete-vehicle]').forEach((btn) => {
        btn.addEventListener('click', async () => {
            if (!confirm('Permanently delete this vehicle?')) return;
            try {
                const response = await trashApi.forceDeleteVehicle(btn.dataset.forceDeleteVehicle);
                showToast(apiMessage(response, 'Vehicle permanently deleted.'), 'success');
                window.location.reload();
            } catch (e) {
                handleApiError(e);
            }
        });
    });

    root.querySelectorAll('[data-restore-service]').forEach((btn) => {
        btn.addEventListener('click', async () => {
            try {
                const response = await trashApi.restoreService(btn.dataset.restoreService);
                showToast(apiMessage(response, 'Service record restored.'), 'success');
                window.location.reload();
            } catch (e) {
                handleApiError(e);
            }
        });
    });

    root.querySelectorAll('[data-force-delete-service]').forEach((btn) => {
        btn.addEventListener('click', async () => {
            if (!confirm('Permanently delete this service record?')) return;
            try {
                const response = await trashApi.forceDeleteService(btn.dataset.forceDeleteService);
                showToast(apiMessage(response, 'Service permanently deleted.'), 'success');
                window.location.reload();
            } catch (e) {
                handleApiError(e);
            }
        });
    });
}
