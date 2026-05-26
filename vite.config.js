import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import fs from 'fs';
import path from 'path';

const pageFiles = fs
    .readdirSync(path.resolve(__dirname, 'resources/js/pages'))
    .map(file => `resources/js/pages/${file}`);

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/pages/dashboard.js',
                'resources/js/pages/vehicles-index.js',
                'resources/js/pages/vehicle-show.js',
                'resources/js/pages/vehicle-form.js',
                'resources/js/pages/service-form.js',
                'resources/js/pages/trash-index.js',
                'resources/js/pages/profile-forms.js',
            ],
            refresh: true,
        }),
    ],
});