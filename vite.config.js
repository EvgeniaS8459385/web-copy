import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import prism from 'vite-plugin-prismjs';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
                'resources/css/app.scss',
            ],
            refresh: true,
        }),
        prism({
            languages: ['javascript', 'css', 'html', 'typescript'],
            plugins: ['line-numbers'],
            theme: 'tomorrow',
            css: true,
        }),
    ],
    resolve: {
        alias: {
            '~fontawesome': path.resolve(__dirname, 'node_modules/@fortawesome/fontawesome-free'),
        },
    },
});
