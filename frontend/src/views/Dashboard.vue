<template>
  <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-8 border border-gray-100 dark:border-gray-700">
    <div class="flex justify-between items-start">
        <div>
            <h3 class="text-2xl font-bold mb-2 text-gray-900 dark:text-white">Hola, {{ authStore.user?.name }} 👋</h3>
            <p class="text-gray-600 dark:text-gray-400 max-w-2xl">
              Bienvenido al panel central. Esta plantilla multi-rol construida en <strong>Laravel 12 / Vite</strong> 
              proporciona persistencia limpia a un API Desacoplada PostgreSQL 17.
            </p>
        </div>
        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">
            Conectado al API Real
        </span>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-10">
      
      <!-- Panel de Roles del Usuario logeado -->
      <div class="p-6 bg-gradient-to-br from-indigo-50 to-blue-50 dark:from-indigo-900/20 dark:to-blue-900/20 rounded-xl border border-indigo-100 dark:border-indigo-800/30">
        <h4 class="font-bold text-indigo-800 dark:text-indigo-300 flex items-center">
            <span class="text-xl mr-2">👑</span> Roles Asignados
        </h4>
        <ul class="mt-4 space-y-2 text-indigo-700 dark:text-indigo-400 text-sm font-medium">
          <li v-for="role in authStore.user?.roles" :key="role" class="bg-indigo-100/50 dark:bg-indigo-800/40 px-3 py-2 rounded">{{ role }}</li>
          <li v-if="!authStore.user?.roles?.length" class="opacity-50">Sin Roles Especiales</li>
        </ul>
      </div>
      
      <!-- Panel Permisos Detallados Spatie -->
      <div class="p-6 bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-xl border border-purple-100 dark:border-purple-800/30">
        <h4 class="font-bold text-purple-800 dark:text-purple-300 flex items-center">
            <span class="text-xl mr-2">🔑</span> Permisos Efectivos Absolutos
        </h4>
        <ul class="mt-4 space-y-2 text-purple-700 dark:text-purple-400 text-sm font-medium max-h-40 overflow-y-auto">
          <li v-for="permission in authStore.user?.permissions" :key="permission" class="bg-purple-100/50 dark:bg-purple-800/40 px-3 py-2 rounded inline-block w-full">{{ permission }}</li>
          <li v-if="!authStore.user?.permissions?.length" class="opacity-50">Ningún Permiso Superior</li>
        </ul>
      </div>

    </div>
  </div>
</template>

<script setup>
import { useAuthStore } from '../stores/auth'
const authStore = useAuthStore()
</script>
