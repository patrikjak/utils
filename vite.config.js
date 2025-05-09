import { defineConfig } from 'vite';
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/assets/css/main.scss",
                "resources/assets/css/general/buttons.scss",
                "resources/assets/css/general/general.scss",
                "resources/assets/css/general/animations.scss",
                "resources/assets/css/table/table.scss",
                "resources/assets/css/form/inputs.scss",
                "resources/assets/css/utils/dropdown.scss",
                "resources/assets/css/utils/modal.scss",
                "resources/assets/css/utils/notification.scss",
                "resources/assets/css/utils/file-uploader.scss",
            ],
            refresh: true,
        }),
    ],
    build: {
        rollupOptions: {
            output: {
                assetFileNames: '[name].[ext]',
            }
        },
        manifest: false,
        emptyOutDir: true,
        outDir: 'public/assets',
    },
    server: {
        host: '0.0.0.0',
        port: 5173,
        hmr: {
            host: '0.0.0.0',
        }
    },
})