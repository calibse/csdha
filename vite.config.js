import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import legacy from '@vitejs/plugin-legacy';
import { viteStaticCopy } from 'vite-plugin-static-copy';

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
        viteStaticCopy({
            targets: [
                {
                    src: 'node_modules/qr-scanner/qr-scanner.legacy.min.js',
                    dest: 'legacy',
                },
            ]
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
