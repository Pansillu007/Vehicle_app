<<<<<<< HEAD
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();
=======
// ========================================
// VehiclePro — Dark Mode Manager
// ========================================

// Initialize theme from localStorage before page renders (prevents flash)
(function() {
    const theme = localStorage.getItem('vehiclepro-theme');
    if (theme === 'dark' || (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
})();

// Theme toggle function (globally available)
window.toggleDarkMode = function() {
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

window.isDarkMode = function() {
    return document.documentElement.classList.contains('dark');
};
>>>>>>> ec6237d (Third Week of Assignment small changes)
