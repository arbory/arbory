import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';
import { viteStaticCopy } from 'vite-plugin-static-copy'
import { globSync } from "glob";
import inject from '@rollup/plugin-inject';
import resolve from '@rollup/plugin-node-resolve';
import commonjs from '@rollup/plugin-commonjs';
import amd from 'rollup-plugin-amd';


export default defineConfig({
    plugins: [
        laravel({
            buildDirectory: 'dist',
            input: [
                'resources/assets/js/application.js',
                'resources/assets/js/admin.js',
                'resources/assets/js/controllers/nodes.js',
                'resources/assets/js/controllers/roles.js',
                'resources/assets/js/controllers/sessions.js',
                'resources/assets/js/modules/UrlBuilder.js',
                'resources/assets/stylesheets/application.scss',
                'resources/assets/stylesheets/material-icons.scss',
                'resources/assets/stylesheets/controllers/sessions.scss',
                ...globSync("resources/assets/js/Admin/*.js"),
                ...globSync("resources/assets/js/include/*.js")
            ],
            refresh: true,
            manifest: true
        }),
        viteStaticCopy({
            targets: [
                { src: 'node_modules/ckeditor4/*', dest: 'ckeditor' },
                { src: 'resources/assets/js/ckeditor_plugins/**/*', dest: 'ckeditor/plugins' },
                { src: 'resources/assets/images/*', dest: 'images' },
                { src: 'resources/assets/fonts/*', dest: 'fonts' },
                { src: 'node_modules/material-icons/iconfont/*.{woff,woff2,eot,ttf}', dest: 'fonts' },
                { src: 'node_modules/jquery/dist/jquery.min.js', dest: 'jquery' },
                { src: 'node_modules/jquery-ui/dist/jquery-ui.min.js', dest: 'jquery' }
            ]
        })
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources')
        },
    },
    build: {
        outDir: 'dist',
        rollupOptions: {
            output: {
                chunkFileNames: 'js/[name]-[hash].js',
                entryFileNames: 'js/[name]-[hash].js',
                assetFileNames: assetInfo => {
                    if (assetInfo.name.endsWith('.css')) return 'css/[name]-[hash][extname]';
                    return 'assets/[name]-[hash][extname]';
                }
            }
        },
    },
    server: {
        host: true
    }
});
