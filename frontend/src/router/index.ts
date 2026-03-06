import { createRouter, createWebHistory } from 'vue-router'
import Hello from '@/views/Hello.vue'
import Login from '@/views/auth/Login.vue'
import Register from '@/views/auth/Register.vue'

import Projects from '@/views/project/index.vue'
import ProjectShow from '@/views/project/show.vue'

const routes = [
  {
    path: '/',
    name: 'hello',
    component: Hello
  },
  {
    path: '/auth/login',
    name: 'auth.login',
    component: Login,
    meta: { guest: true }
  },
  {
    path: '/auth/register',
    name: 'auth.register',
    component: Register,
    meta: { guest: true }
  },
  {
    path: '/projects',
    name: 'projects.index',
    component: Projects,
    meta: { requiresAuth: true }
  },
  {
    path: '/projects/:id',
    name: 'projects.show',
    component: ProjectShow,
    meta: { requiresAuth: true }
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

  // if (to.meta.guest && token) {
  //   return next("/projects")
  // }

  next()
})