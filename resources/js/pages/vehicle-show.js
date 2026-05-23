import { servicesApi } from '../api/services.js';
import { downloadCsv } from '../api/export.js';
import { handleApiError } from '../api/errors.js';
import { unwrapList } from '../api/response.js';
import { showToast } from '../api/toast.js';

const root = document.getElementById('vehicle-services-app');
if (root) {
    const vehicleId = root.dataset.vehicleId;
    const tbody = root.querySelector('[data-services-body]');
    const loading = root.querySelector('[data-services-loading]');
    const empty = root.querySelector('[data-services-empty]');
    const totalEl = root.querySelector('[data-services-total]');
    const searchInput = root.querySelector('[data-service-search]');
    const typeInput = root.querySelector('[data-service-type]');
    const routes = JSON.parse(root.dataset.routes || '{}');

    let debounce;

    async function load() {
        loading?.classList.remove('hidden');
        try {
            const response = await servicesApi.list(vehicleId, {
                search: searchInput?.value || undefined,
                type: typeInput?.value || undefined,
                per_page: 50,
            });
            const { items } = unwrapList(response);
            render(items);
        } catch (e) {
            handleApiError(e);
        } finally {
            loading?.classList.add('hidden');
        }
    }

    function render(services) {
        if (!tbody) return;
        tbody.innerHTML = '';
        let total = 0;

        if (!services.length) {
            empty?.classList.remove('hidden');
            if (totalEl) totalEl.textContent = '0.00';
            return;
        }

        empty?.classList.add('hidden');

        services.forEach((s) => {
            total += parseFloat(s.cost) || 0;
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="whitespace-nowrap font-medium text-gray-900 dark:text-white">${s.service_date}</td>
                <td><div class="font-semibold text-gray-900 dark:text-white">${esc(s.service_type)}</div><div class="text-sm text-gray-500">${esc(s.service_provider)}</div></td>
                <td class="font-semibold">$${Number(s.cost).toFixed(2)}</td>
                <td>${Number(s.mileage).toLocaleString()}</td>
                <td class="text-right space-x-2 whitespace-nowrap">
                    <a href="${routes.invoice.replace('__SID__', s.id)}" class="text-xs text-cyan-600 dark:text-cyan-400 hover:underline">PDF</a>
                    <a href="${routes.edit.replace('__SID__', s.id)}" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">Edit</a>
                    <button type="button" data-delete-service="${s.id}" class="text-xs text-red-600 dark:text-red-400 hover:underline bg-transparent border-0 cursor-pointer">Delete</button>
                </td>`;
            tbody.appendChild(tr);
        });

        if (totalEl) totalEl.textContent = total.toFixed(2);

        tbody.querySelectorAll('[data-delete-service]').forEach((btn) => {
            btn.addEventListener('click', async () => {
                if (!confirm('Move this service record to trash?')) return;
                try {
                    await servicesApi.delete(vehicleId, btn.dataset.deleteService);
                    showToast('Service record moved to trash.', 'success');
                    load();
                } catch (e) {
                    handleApiError(e);
                }
            });
        });
    }

    function esc(v) {
        const d = document.createElement('div');
        d.textContent = v ?? '';
        return d.innerHTML;
    }

    [searchInput, typeInput].forEach((el) => {
        el?.addEventListener('input', () => {
            clearTimeout(debounce);
            debounce = setTimeout(load, 300);
        });
    });

    root.querySelector('[data-export-services-csv]')?.addEventListener('click', () => {
        downloadCsv(
            `/export/vehicles/${vehicleId}/services`,
            `vehicle-${vehicleId}-services-${new Date().toISOString().slice(0, 10)}.csv`
        );
    });

    load();
}
