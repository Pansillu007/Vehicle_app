import { dashboardApi } from '../api/dashboard.js';
import { unwrapData } from '../api/response.js';
import { handleApiError } from '../api/errors.js';

const root = document.getElementById('dashboard-app');
if (root) {
    const routes = JSON.parse(root.dataset.routes || '{}');
    let chartInstances = [];

    async function load() {
        root.classList.add('opacity-60');
        try {
            const response = await dashboardApi.get();
            const data = unwrapData(response);
            render(data);
        } catch (e) {
            handleApiError(e);
        } finally {
            root.classList.remove('opacity-60');
        }
    }

    function render(data) {
        const nameEl = root.querySelector('[data-user-name]');
        if (nameEl) nameEl.textContent = root.dataset.userName || 'User';

        const vehiclesCount = root.querySelector('[data-count-vehicles]');
        const servicesCount = root.querySelector('[data-count-services]');
        if (vehiclesCount) vehiclesCount.textContent = data.totalVehicles ?? 0;
        if (servicesCount) servicesCount.textContent = data.totalServiceRecords ?? 0;

        const costEl = root.querySelector('[data-stat-cost]');
        if (costEl) costEl.textContent = `$${Number(data.maintenanceCost ?? 0).toLocaleString(undefined, { minimumFractionDigits: 2 })}`;

        const topEl = root.querySelector('[data-stat-top-vehicle]');
        const topSub = root.querySelector('[data-stat-top-sub]');
        if (topEl) {
            topEl.textContent = data.mostExpensiveVehicle?.name ?? '—';
        }
        if (topSub) {
            if (data.mostExpensiveVehicle?.total_cost) {
                topSub.textContent = `$${Number(data.mostExpensiveVehicle.total_cost).toLocaleString()} total`;
                topSub.classList.remove('hidden');
            } else {
                topSub.classList.add('hidden');
            }
        }

        renderReminders(data.reminders || []);
        renderRecent(data.recentActivity || []);
        renderActivityLogs(data.activityLogs || []);
        renderCharts(data);
    }

    function renderReminders(reminders) {
        const container = root.querySelector('[data-reminders]');
        if (!container) return;

        container.querySelectorAll('[data-reminder-item]').forEach((el) => el.remove());

        if (!reminders.length) {
            container.classList.add('hidden');
            return;
        }

        container.classList.remove('hidden');
        reminders.forEach((r) => {
            const div = document.createElement('div');
            div.setAttribute('data-reminder-item', '');
            div.className = `glass-card rounded-2xl p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-l-4 ${r.status === 'overdue' ? 'border-red-500 bg-red-500/5' : 'border-amber-500 bg-amber-500/5'}`;
            div.innerHTML = `
                <div>
                    <p class="font-semibold text-gray-900 dark:text-white">${esc(r.vehicle_name)}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">${esc(r.message)}</p>
                </div>
                <span class="${r.status === 'overdue' ? 'badge-red' : 'badge-amber'} self-start sm:self-center">${r.status === 'overdue' ? 'OVERDUE' : 'DUE SOON'}</span>`;
            container.appendChild(div);
        });
    }

    function renderRecent(items) {
        const container = root.querySelector('[data-recent-services]');
        if (!container) return;
        container.innerHTML = '';

        if (!items.length) {
            container.innerHTML = `<p class="text-sm text-gray-500 dark:text-slate-400 py-6 text-center">No recent services. <a href="${routes.vehicles}" class="text-blue-500">View vehicles</a></p>`;
            return;
        }

        items.forEach((activity) => {
            const div = document.createElement('div');
            div.className = 'flex gap-3 items-start group';
            div.innerHTML = `
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center flex-shrink-0 ring-2 ring-white dark:ring-slate-900">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="min-w-0 flex-1">
                    <a href="${routes.vehicleShow.replace('__ID__', activity.vehicle.id)}" class="font-medium text-gray-900 dark:text-white group-hover:text-blue-500 transition-colors">${esc(activity.vehicle.name)}</a>
                    <span class="text-gray-500 dark:text-slate-400"> — ${esc(activity.service_type)}</span>
                    <p class="text-xs text-gray-400 mt-0.5">${esc(activity.created_at_human)} · $${Number(activity.cost).toFixed(2)}</p>
                </div>`;
            container.appendChild(div);
        });
    }

    function renderActivityLogs(logs) {
        const section = root.querySelector('[data-activity-section]');
        const container = root.querySelector('[data-activity-logs]');
        if (!section || !container) return;

        if (!logs.length) {
            section.classList.add('hidden');
            return;
        }

        section.classList.remove('hidden');
        container.innerHTML = '';
        logs.forEach((log) => {
            const div = document.createElement('div');
            div.className = 'flex justify-between items-start gap-4 py-3 border-b border-slate-100 dark:border-white/5 last:border-0';
            div.innerHTML = `
                <div class="flex gap-3">
                    <div class="w-2 h-2 mt-2 rounded-full bg-blue-500 shrink-0"></div>
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">${esc(log.description)}</p>
                        <p class="text-xs text-gray-500">${esc(log.user_name)} · ${esc(log.action)}</p>
                    </div>
                </div>
                <span class="text-xs text-gray-400 whitespace-nowrap">${esc(log.created_at_human)}</span>`;
            container.appendChild(div);
        });
    }

    function renderCharts(data) {
        if (typeof Chart === 'undefined') return;

        chartInstances.forEach((c) => c.destroy());
        chartInstances = [];

        const isDark = document.documentElement.classList.contains('dark');
        const gridColor = isDark ? 'rgba(148,163,184,0.12)' : 'rgba(148,163,184,0.2)';
        const labelColor = isDark ? '#94a3b8' : '#64748b';
        const defaults = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { labels: { color: labelColor, font: { family: 'Inter' } } } },
        };

        const costCtx = document.getElementById('costChart');
        if (costCtx) {
            chartInstances.push(new Chart(costCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Cost ($)',
                        data: data.chartData || [],
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.15)',
                        fill: true,
                        tension: 0.35,
                        pointRadius: 3,
                    }],
                },
                options: {
                    ...defaults,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: gridColor }, ticks: { color: labelColor } },
                        x: { ticks: { color: labelColor }, grid: { display: false } },
                    },
                },
            }));
        }

        const serviceLabels = Object.keys(data.serviceDistribution || {});
        const serviceDist = Object.values(data.serviceDistribution || {});
        const serviceCtx = document.getElementById('serviceChart');
        const serviceEmpty = root.querySelector('[data-service-chart-empty]');
        if (serviceCtx && serviceLabels.length) {
            serviceEmpty?.classList.add('hidden');
            serviceCtx.classList.remove('hidden');
            chartInstances.push(new Chart(serviceCtx, {
                type: 'doughnut',
                data: {
                    labels: serviceLabels,
                    datasets: [{ data: serviceDist, backgroundColor: ['#3b82f6', '#06b6d4', '#6366f1', '#8b5cf6', '#ec4899', '#f59e0b', '#10b981'] }],
                },
                options: { ...defaults, plugins: { legend: { position: 'bottom', labels: { color: labelColor, padding: 12 } } } },
            }));
        } else {
            serviceCtx?.classList.add('hidden');
            serviceEmpty?.classList.remove('hidden');
        }

        const fuelLabels = Object.keys(data.fuelDistribution || {});
        const fuelDist = Object.values(data.fuelDistribution || {});
        const fuelCtx = document.getElementById('fuelChart');
        const fuelEmpty = root.querySelector('[data-fuel-chart-empty]');
        if (fuelCtx && fuelLabels.length) {
            fuelEmpty?.classList.add('hidden');
            fuelCtx.classList.remove('hidden');
            chartInstances.push(new Chart(fuelCtx, {
                type: 'pie',
                data: {
                    labels: fuelLabels,
                    datasets: [{ data: fuelDist, backgroundColor: ['#2563eb', '#0891b2', '#4f46e5', '#7c3aed', '#db2777'] }],
                },
                options: { ...defaults, plugins: { legend: { position: 'bottom', labels: { color: labelColor } } } },
            }));
        } else {
            fuelCtx?.classList.add('hidden');
            fuelEmpty?.classList.remove('hidden');
        }
    }

    function esc(v) {
        const d = document.createElement('div');
        d.textContent = v ?? '';
        return d.innerHTML;
    }

    window.addEventListener('theme-toggled', () => load());

    load();
}
