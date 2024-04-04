import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from "@vitejs/plugin-vue";
import vuetify from 'vite-plugin-vuetify'

export default defineConfig({
  resolve: {
    alias: {
      vue: "vue/dist/vue.esm-bundler.js"
    }
  },
  plugins: [
    laravel([
      //'resources/css/app.css',
      'resources/js/app.ts',
    ]),
    vue(),
    vuetify({ autoImport: true }),
  ],
});