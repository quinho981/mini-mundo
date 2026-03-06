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
    component: Login
  },
  {
    path: '/auth/register',
    name: 'register',
    component: Register
  },
]

export const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach((to, from, next) => {
  const token = localStorage.getItem("token")

  if (!token && to.path.startsWith("/projects")) {
      return next("/auth/login")
  }

  next()
})