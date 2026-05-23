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

bootstrapApiTokenFromMeta();
bindLogoutTokenClear();

// API-driven CRUD modules (depend on routes/api.php)
import './pages/dashboard.js';
import './pages/vehicles-index.js';
import './pages/vehicle-form.js';
import './pages/vehicle-show.js';
import './pages/service-form.js';
import './pages/profile-forms.js';
import './pages/trash-index.js';
