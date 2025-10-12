import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

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
    ],
    build: {
        minify: false,
        cssMinify: true
    }
});
