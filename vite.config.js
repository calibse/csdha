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
            targets: ['defaults', 'firefox < 4'],
        }),
    ],
    build: {
        minify: false,
        cssMinify: false
    },
    server: {
        cors: {
            origin: '*'
        },
        proxy: {
            '/font': 'http://127.0.0.1:8000',
            '/images': 'http://127.0.0.1:8000',
        }
    }
});
