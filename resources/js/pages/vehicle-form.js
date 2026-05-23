import { vehiclesApi } from '../api/vehicles.js';
import { bindApiForm } from '../api/forms.js';

const form = document.getElementById('vehicle-api-form');
if (form) {
    const vehicleId = form.dataset.vehicleId;
    const redirectUrl = form.dataset.redirectUrl;

    bindApiForm(form, {
        submit: (payload) => (vehicleId ? vehiclesApi.update(vehicleId, payload) : vehiclesApi.create(payload)),
        onSuccess: async () => {
            window.location.href = redirectUrl;
        },
    });
}
