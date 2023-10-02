import { createRouter, createWebHistory } from 'vue-router'
import App from './App.vue' // Adjust the path to match your project structure

const router = createRouter({
   history: createWebHistory(import.meta.env.BASE_URL),
   routes: [
      {
         path: '/',
         name: 'home',
         component: App, // Use the imported App component
      },
   ],
});

export default router
