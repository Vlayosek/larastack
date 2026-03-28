<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white dark:bg-gray-800 p-10 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700">
      <div>
        <h2 class="text-center text-3xl font-extrabold text-gray-900 dark:text-white">
          Larastack Login
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
          Usa admin@larastack.com / password
        </p>
      </div>
      <form class="mt-8 space-y-6" @submit.prevent="handleLogin">
        <div class="rounded-md shadow-sm space-y-4">
          <div>
            <label for="email-address" class="sr-only">Rola o Correo Electrónico</label>
            <input v-model="form.email" id="email-address" name="email" type="email" autocomplete="email" required class="appearance-none rounded relative block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm transition-all" placeholder="Correo Electrónico">
          </div>
          <div>
            <label for="password" class="sr-only">Password</label>
            <input v-model="form.password" id="password" name="password" type="password" autocomplete="current-password" required class="appearance-none rounded relative block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm transition-all" placeholder="Contraseña">
          </div>
        </div>
        <div v-if="errorMsg" class="text-red-500 text-sm text-center font-medium bg-red-50 p-2 rounded">{{ errorMsg }}</div>
        <div>
          <button type="submit" :disabled="loading" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 transition-colors">
            {{ loading ? 'Ingresando al sistema...' : 'Iniciar Sesión' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const form = reactive({ email: '', password: '' })
const errorMsg = ref('')
const loading = ref(false)
const router = useRouter()
const authStore = useAuthStore()

const handleLogin = async () => {
    loading.value = true
    errorMsg.value = ''
    try {
        await authStore.login(form.email, form.password)
        router.push('/')
    } catch (error) {
        errorMsg.value = error.response?.data?.message || 'Identidad no verificada. Verifica tus credenciales.'
    } finally {
        loading.value = false
    }
}
</script>
