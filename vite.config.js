import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import svelte from '@sveltejs/vite-plugin-svelte';

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/js/app.js'],
      refresh: true,
    }),
    svelte(),
  ],
  server: {
    host: 'localhost',
    port: 5173,
    hmr: {
      host: 'localhost',
      port: 5173,
    },
  },
});
