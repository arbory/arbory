import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';
import copy from 'vite-plugin-copy';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/assets/js/application.js',
                'resources/assets/stylesheets/application.scss',
                'resources/assets/stylesheets/material-icons.scss',
                'resources/assets/stylesheets/controllers/sessions.scss',
                'resources/assets/js/controllers/nodes.js',
                'resources/assets/js/controllers/roles.js',
                'resources/assets/js/controllers/sessions.js',
                'resources/assets/js/include/**/*.js',
            ],
            refresh: true,
        }),
        copy({
            targets: [
                { src: 'node_modules/ckeditor4/**/*', dest: 'public/ckeditor' },
                { src: 'resources/assets/js/ckeditor_plugins/**/*', dest: 'public/ckeditor/plugins' },
                { src: 'resources/assets/images/**/*', dest: 'public/images' },
                { src: 'resources/assets/fonts/**/*', dest: 'public/fonts' },
                { src: 'node_modules/material-icons/iconfont/*.{woff,woff2,eot,ttf}', dest: 'public/fonts/material-icons' },
            ],
            hook: 'writeBundle' // Use the `writeBundle` hook to copy assets after Vite build
        })
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources'),
        },
    },
    build: {
        rollupOptions: {
            output: {
                chunkFileNames: 'js/[name]-[hash].js',
                entryFileNames: 'js/[name]-[hash].js',
                assetFileNames: assetInfo => {
                    if (assetInfo.name.endsWith('.css')) return 'css/[name]-[hash][extname]';
                    return 'assets/[name]-[hash][extname]';
                },
            },
        },
    },
    server: {
        host: true
    }
});
