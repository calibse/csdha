import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import legacy from '@vitejs/plugin-legacy';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/scss/app.scss', 
                'resources/scss/accom-report.scss',
                'resources/scss/gpoa-report.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        legacy({
            targets: ['defaults', 'ie >= 11'],
        }),
    ],
    build: {
        minify: false,
        cssMinify: true
    }
});
