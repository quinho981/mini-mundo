import { createRouter, createWebHistory } from 'vue-router'
import Hello from '@/views/Hello.vue'

const routes = [
  {
    path: '/',
    name: 'hello',
    component: Hello
  },
]

export const router = createRouter({
  history: createWebHistory(),
  routes
})