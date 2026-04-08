import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: { // Tambahkan blok server ini
        host: '0.0.0.0',
        hmr: {
            host: 'mandy-unstoic-venessa.ngrok-free.dev', // Sesuaikan dengan link Ngrok kamu
        },
    },
});
