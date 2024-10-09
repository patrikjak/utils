import { defineConfig } from 'vite';
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/assets/css/table/table.scss",
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
    }
})