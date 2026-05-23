import { vehiclesApi } from '../api/vehicles.js';
import { downloadCsv } from '../api/export.js';
import { handleApiError } from '../api/errors.js';
import { unwrapList } from '../api/response.js';
import { showToast } from '../api/toast.js';

const root = document.getElementById('vehicles-app');
if (root) {
    const grid = root.querySelector('[data-vehicles-grid]');
    const loading = root.querySelector('[data-vehicles-loading]');
    const empty = root.querySelector('[data-vehicles-empty]');
    const searchInput = root.querySelector('[data-search]');
    const fuelSelect = root.querySelector('[data-fuel-filter]');
    const sortSelect = root.querySelector('[data-sort]');
    const pagination = root.querySelector('[data-pagination]');

    const routes = JSON.parse(root.dataset.routes || '{}');
    const isAdmin = root.dataset.isAdmin === '1';

    let debounce;

    async function load(page = 1) {
        loading?.classList.remove('hidden');
        grid?.classList.add('opacity-50');

        try {
            const response = await vehiclesApi.list({
                page,
                search: searchInput?.value || undefined,
                fuel_type: fuelSelect?.value || undefined,
                sort: sortSelect?.value || 'latest',
            });

            const { items, meta } = unwrapList(response);
            render(items, meta);
        } catch (e) {
            handleApiError(e);
        } finally {
            loading?.classList.add('hidden');
            grid?.classList.remove('opacity-50');
        }
    }

    function render(vehicles, meta) {
        if (!grid) return;
        grid.innerHTML = '';

        if (!vehicles.length) {
            empty?.classList.remove('hidden');
            pagination.innerHTML = '';
            return;
        }

        empty?.classList.add('hidden');

        vehicles.forEach((v) => {
            const card = document.createElement('div');
            card.className = 'glass-card rounded-3xl overflow-hidden hover:-translate-y-1 transition-all duration-300 group';
            card.innerHTML = `
                <div class="aspect-video bg-slate-800/50 overflow-hidden">
                    <img src="${v.image_url}" alt="${v.name}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </div>
                <div class="p-5">
                    <div class="flex justify-between items-start gap-2 mb-2">
                        <h3 class="font-bold text-gray-900 dark:text-white">${escapeHtml(v.name)}</h3>
                        <span class="text-xs px-2 py-1 rounded-full bg-blue-500/10 text-blue-600 dark:text-blue-400 font-mono">${escapeHtml(v.number_plate)}</span>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">${escapeHtml(v.make)} ${escapeHtml(v.model)} · ${v.year}</p>
                    ${v.fuel_type ? `<span class="badge-blue mb-2 inline-block">${escapeHtml(v.fuel_type)}</span>` : ''}
                    ${isAdmin && v.user ? `<p class="text-xs text-gray-400 mb-3">Owner: ${escapeHtml(v.user.name)}</p>` : ''}
                    <div class="flex flex-wrap gap-2 mt-3">
                        <a href="${routes.show.replace('__ID__', v.id)}" class="btn-primary text-xs py-2 px-3">View</a>
                        <a href="${routes.edit.replace('__ID__', v.id)}" class="btn-secondary text-xs py-2 px-3">Edit</a>
                        <button type="button" data-delete-id="${v.id}" class="btn-danger text-xs py-2 px-3">Delete</button>
                    </div>
                </div>`;
            grid.appendChild(card);
        });

        renderPagination(meta);
        bindDeleteButtons();
    }

    function renderPagination(meta) {
        if (!pagination || !meta) {
            return;
        }
        pagination.innerHTML = '';
        if (meta.last_page <= 1) return;

        for (let p = 1; p <= meta.last_page; p++) {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.textContent = p;
            btn.className = `px-3 py-1 rounded-lg text-sm ${p === meta.current_page ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-gray-300'}`;
            btn.addEventListener('click', () => load(p));
            pagination.appendChild(btn);
        }
    }

    function bindDeleteButtons() {
        grid.querySelectorAll('[data-delete-id]').forEach((btn) => {
            btn.addEventListener('click', async () => {
                if (!confirm('Move this vehicle to trash?')) return;
                try {
                    await vehiclesApi.delete(btn.dataset.deleteId);
                    showToast('Vehicle moved to trash.', 'success');
                    load();
                } catch (e) {
                    handleApiError(e);
                }
            });
        });
    }

    function escapeHtml(str) {
        const d = document.createElement('div');
        d.textContent = str ?? '';
        return d.innerHTML;
    }

    [searchInput, fuelSelect, sortSelect].forEach((el) => {
        el?.addEventListener('input', () => {
            clearTimeout(debounce);
            debounce = setTimeout(() => load(), 300);
        });
        el?.addEventListener('change', () => load());
    });

    root.querySelector('[data-export-vehicles-csv]')?.addEventListener('click', () => {
        downloadCsv('/export/vehicles', `vehicles-${new Date().toISOString().slice(0, 10)}.csv`, {
            search: searchInput?.value || undefined,
            fuel_type: fuelSelect?.value || undefined,
            sort: sortSelect?.value || 'latest',
        });
    });

    load();
}
