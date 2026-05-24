import { dashboardApi } from '../api/dashboard.js';
import { unwrapData } from '../api/response.js';
import { handleApiError } from '../api/errors.js';
import { createRequestGuard } from '../api/request.js';

const root = document.getElementById('dashboard-app');
if (root) {
    const routes = JSON.parse(root.dataset.routes || '{}');
    let chartInstances = [];
    const guard = createRequestGuard();

    async function load() {
        const id = guard.next();
        root.classList.add('opacity-60');
        try {
            const response = await dashboardApi.get();
            if (!guard.isCurrent(id)) return;
            const data = unwrapData(response);
            render(data);
        } catch (e) {
            if (guard.isCurrent(id)) {
                handleApiError(e);
            }
        } finally {
            if (guard.isCurrent(id)) {
                root.classList.remove('opacity-60');
            }
        }
    }

    function render(data) {
        const nameEl = root.querySelector('[data-user-name]');
        if (nameEl) nameEl.textContent = root.dataset.userName || 'User';

        const vehiclesCount = root.querySelector('[data-count-vehicles]');
        const servicesCount = root.querySelector('[data-count-services]');
        if (vehiclesCount) vehiclesCount.textContent = data.totalVehicles ?? 0;
        if (servicesCount) servicesCount.textContent = data.totalServiceRecords ?? 0;

        const statVehicles = root.querySelector('[data-stat-vehicles]');
        const statServices = root.querySelector('[data-stat-services]');
        const statReminders = root.querySelector('[data-stat-reminders]');
        const statSpend = root.querySelector('[data-stat-spend]');
        if (statVehicles) statVehicles.textContent = data.totalVehicles ?? 0;
        if (statServices) statServices.textContent = data.totalServiceRecords ?? 0;
        if (statReminders) statReminders.textContent = data.pendingReminders ?? 0;
        if (statSpend) {
            statSpend.textContent = `$${Number(data.maintenanceCost ?? 0).toLocaleString(undefined, { minimumFractionDigits: 2 })}`;
        }

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
        renderUpcomingReminders(data.upcomingReminders || []);
        renderRecent(data.recentActivity || []);
        renderActivityLogs(data.activityLogs || []);
        renderCharts(data);
    }

    function renderUpcomingReminders(reminders) {
        const countEl = root.querySelector('[data-upcoming-count]');
        const listEl = root.querySelector('[data-upcoming-list]');
        if (!listEl) return;

        if (countEl) {
            countEl.textContent = `${reminders.length} Pending`;
        }

        listEl.innerHTML = '';

        if (!reminders.length) {
            listEl.innerHTML = `
                <div class="py-8 text-center">
                    <svg class="w-12 h-12 text-gray-300 dark:text-slate-700 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <p class="text-sm text-gray-500 dark:text-slate-400">All clear! No pending reminders.</p>
                </div>`;
            return;
        }

        reminders.forEach((r) => {
            const div = document.createElement('div');
            div.className = 'group flex items-center justify-between p-3 rounded-2xl bg-gray-50 dark:bg-slate-800/50 hover:bg-white dark:hover:bg-slate-800 transition-all border border-transparent hover:border-blue-100 dark:hover:border-blue-900/30';
            div.innerHTML = `
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white">${esc(r.title)}</h4>
                        <p class="text-xs text-gray-500 dark:text-slate-400">${esc(r.vehicle_name)} · Due ${esc(r.due_date)}</p>
                    </div>
                </div>`;
            listEl.appendChild(div);
        });
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

        container.innerHTML = '';

        if (!logs.length) {
            container.innerHTML = '<p class="text-sm text-gray-500 dark:text-slate-400 py-6 text-center">No recent activity found.</p>';
            return;
        }

        const timeline = document.createElement('div');
        timeline.className = 'relative space-y-6';
        timeline.innerHTML = '<div class="absolute left-[19px] top-2 bottom-2 w-0.5 bg-gray-100 dark:bg-slate-800"></div>';

        logs.forEach((log) => {
            const div = document.createElement('div');
            div.className = 'relative flex items-start gap-4';
            const action = (log.action || '').toLowerCase();
            const icon = action.includes('create')
                ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>'
                : action.includes('update')
                    ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>'
                    : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>';
            div.innerHTML = `
                <div class="z-10 w-10 h-10 rounded-full border-4 border-white dark:border-slate-900 bg-gray-50 dark:bg-slate-800 flex items-center justify-center text-blue-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">${icon}</svg>
                </div>
                <div class="flex-1 pt-1">
                    <div class="flex items-center justify-between gap-4">
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white capitalize">${esc(log.description)}</h4>
                        <span class="text-[10px] font-medium text-gray-400 uppercase tracking-tighter whitespace-nowrap">${esc(log.created_at_human)}</span>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-slate-400 mt-0.5">${esc(log.user_name)} · ${esc(log.action)}</p>
                </div>`;
            timeline.appendChild(div);
        });

        container.appendChild(timeline);
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
    }

    function esc(v) {
        const d = document.createElement('div');
        d.textContent = v ?? '';
        return d.innerHTML;
    }

    window.addEventListener('theme-toggled', () => load());

    load();
}
