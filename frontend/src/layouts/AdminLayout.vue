<template>
  <div class="flex h-screen bg-gray-100 dark:bg-gray-900 font-sans">
    <!-- Sidebar / Navigation Base-->
    <aside class="w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 flex flex-col shadow-sm z-10 transition-colors duration-200">
      <div class="h-16 flex items-center justify-center border-b border-gray-200 dark:border-gray-700">
        <h1 class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-indigo-500 to-purple-600">Larastack Admin</h1>
      </div>
      <nav class="flex-1 overflow-y-auto py-4">
        <ul class="space-y-1">
          <li>
            <router-link to="/" class="block px-6 py-2 text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/40 hover:text-indigo-600 font-medium transition-colors">
              📍 Inicio Dashboard
            </router-link>
          </li>
          
          <!-- Control de renderización RBAC basado en Roles/Permisos nativamente integrado -->
          <li v-if="hasRole('Superadministrador') || hasPermission('view users')">
            <a href="#" class="block px-6 py-2 text-gray-500 dark:text-gray-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/40 hover:text-indigo-600 font-medium transition-colors">
              👥 Gestión de Usuarios
            </a>
          </li>
          <li>
            <a href="#" class="block px-6 py-2 text-gray-500 dark:text-gray-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/40 hover:text-indigo-600 font-medium transition-colors">
              📄 Configurar Sistema
            </a>
          </li>
        </ul>
      </nav>
      <!-- Perfil Info Sidebar Abajo -->
      <div class="p-4 border-t border-gray-200 dark:border-gray-700">
        <div class="flex items-center space-x-3 mb-4">
            <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold">
                {{ authStore.user?.name?.charAt(0) }}
            </div>
            <div>
                <div class="text-sm font-medium text-gray-900 dark:text-white truncate max-w-[140px]">{{ authStore.user?.name }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400 capitalize">{{ authStore.user?.roles?.[0] || 'Miembro' }}</div>
            </div>
        </div>
        <button @click="logout" class="w-full flex items-center justify-center px-4 py-2 bg-red-50 hover:bg-red-100 dark:bg-red-900/30 dark:hover:bg-red-900/50 text-red-600 dark:text-red-400 rounded text-sm font-medium transition-colors border border-red-100 dark:border-red-900/30">
          Cerrar Sesión Segura
        </button>
      </div>
    </aside>

    <!-- Main Content Flow -->
    <main class="flex-1 flex flex-col overflow-hidden relative">
      <!-- Navbar superior opcional (Mobile o migajas) -->
      <header class="h-16 bg-white dark:bg-gray-800 shadow-sm border-b border-gray-100 dark:border-gray-700 flex items-center px-6">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-white capitalize">{{ $route.name }}</h2>
      </header>
      <div class="flex-1 overflow-auto p-6 bg-gray-50/50 dark:bg-gray-900">
        <!-- SPA Render -->
        <router-view v-slot="{ Component }">
            <transition name="fade" mode="out-in">
                <component :is="Component" />
            </transition>
        </router-view>
      </div>
    </main>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const router = useRouter()
const authStore = useAuthStore()

const logout = async () => {
    await authStore.logout()
    router.push('/login')
}

// Helpers globales para dibujar menú RBAC
const hasRole = (role) => authStore.user?.roles?.includes(role)
const hasPermission = (permission) => authStore.user?.permissions?.includes(permission)
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
