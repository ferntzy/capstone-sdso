import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/assets/js/user.js',
        'resources/assets/scss/app.scss'
      ],
      refresh: true,
    }),
  ],
});
