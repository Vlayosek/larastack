import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'

import Users from '../views/Users/Users.vue'
import UserForm from '../views/Users/UserForm.vue'

const routes = [
  {
    path: '/login',
    name: 'Login',
    component: () => import('../views/Login.vue'),
    meta: { guest: true }  // Define que esta vista es solo para usuarios sin sesion
  },
  {
    path: '/',
    component: () => import('../layouts/AdminLayout.vue'),
    meta: { requiresAuth: true }, // Define barrera protectora general a las vistas hijas
    children: [
      {
        path: '',
        name: 'Dashboard',
        component: () => import('../views/Dashboard.vue')
      },
      {
        path: '/users',
        name: 'Users',
        component: Users
      },
      {
        path: '/users/create',
        name: 'UserCreate',
        component: UserForm
      },
      {
        path: '/users/:id/edit',
        name: 'UserEdit',
        component: UserForm,
        props: true
      }
    ]
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

// Guardia general inyectable de estado
router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore()
  
  if (authStore.token && !authStore.user) {
    await authStore.fetchUser()
  }

  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next('/login')
  } else if (to.meta.guest && authStore.isAuthenticated) {
    next('/')
  } else {
    next()
  }
})

export default router
