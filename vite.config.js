import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

// Configuration Vite pour Laravel + Tailwind.
export default defineConfig({
    plugins: [
        // Gere les points d'entree CSS/JS et le rafraichissement auto en dev.
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        // Active le plugin Tailwind dans le pipeline Vite.
        tailwindcss(),
    ],
    server: {
        watch: {
            // Ignore les vues compilees pour eviter des refresh inutiles en boucle.
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
