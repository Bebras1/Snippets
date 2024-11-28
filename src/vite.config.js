import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
  plugins: [
    react(),
    laravel({
      input: [
        'resources/js/components/SnippetList.jsx',
        'resources/js/components/CreateSnippetForm.jsx',
        'resources/js/components/Home.jsx',
        'resources/js/reactApp.jsx',
        'resources/js/app.js',
        'resources/css/app.css',
        'resources/css/suggestions.css',
        'resources/css/CreateSnippetForm.css',
      ],
      refresh: true,
    }),
  ],
  build: {
    rollupOptions: {
      external: ['react-icons'],
    },
  },
  optimizeDeps: {
    include: ['react', 'react-dom', 'react-icons'],
  },
});
