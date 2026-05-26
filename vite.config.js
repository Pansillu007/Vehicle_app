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
                ...pageFiles,
            ],
            refresh: true,
        }),
    ],
});