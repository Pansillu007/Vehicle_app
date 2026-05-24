// VehiclePro — Dark Mode Manager
(function () {
    const theme = localStorage.getItem('vehiclepro-theme');
    if (theme === 'dark' || (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
})();

window.toggleDarkMode = function () {
    const html = document.documentElement;
    if (html.classList.contains('dark')) {
        html.classList.remove('dark');
        localStorage.setItem('vehiclepro-theme', 'light');
    } else {
        html.classList.add('dark');
        localStorage.setItem('vehiclepro-theme', 'dark');
    }
    window.dispatchEvent(new CustomEvent('theme-toggled', {
        detail: { dark: html.classList.contains('dark') },
    }));
};

window.isDarkMode = function () {
    return document.documentElement.classList.contains('dark');
};

import { bootstrapApiTokenFromMeta, bindLogoutTokenClear } from './api/auth.js';
import { resetUnauthorizedGuard } from './api/client.js';

// Auth forms must register submit handlers before any async page imports finish.
import './pages/auth-forms.js';

// Sync Bearer token before any page module fires API requests.
bootstrapApiTokenFromMeta();
bindLogoutTokenClear();
resetUnauthorizedGuard();

const pageModules = [
    './pages/dashboard.js',
    './pages/vehicles-index.js',
    './pages/vehicle-form.js',
    './pages/vehicle-show.js',
    './pages/service-form.js',
    './pages/profile-forms.js',
    './pages/trash-index.js',
];

await Promise.all(pageModules.map((path) => import(path)));
