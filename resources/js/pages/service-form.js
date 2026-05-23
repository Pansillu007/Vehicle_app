import { servicesApi } from '../api/services.js';
import { bindApiForm } from '../api/forms.js';

const form = document.getElementById('service-api-form');
if (form) {
    const vehicleId = form.dataset.vehicleId;
    const serviceId = form.dataset.serviceId;
    const redirectUrl = form.dataset.redirectUrl;

    bindApiForm(form, {
        submit: (payload) =>
            serviceId
                ? servicesApi.update(vehicleId, serviceId, payload)
                : servicesApi.create(vehicleId, payload),
        onSuccess: async () => {
            window.location.href = redirectUrl;
        },
    });
}
