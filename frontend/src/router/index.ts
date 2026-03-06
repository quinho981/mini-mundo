import { createRouter, createWebHistory } from 'vue-router'
import Hello from '@/views/Hello.vue'
import Login from '@/views/Login.vue'
import Register from '@/views/Register.vue'

const routes = [
  {
    path: '/',
    name: 'hello',
    component: Hello
  },
  {
    path: '/auth/login',
    name: 'login',
    component: Login,
    meta: { guest: true }
  },
  {
    path: '/auth/register',
    name: 'register',
    component: Register,
    meta: { guest: true }
  },
]

export const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach((to, from, next) => {
  const token = localStorage.getItem("token")

  if (to.meta.requiresAuth && !token) {
    return next("/auth/login")
  }

  if (to.meta.guest && token) {
    return next("/projects")
  }

  next()
})