<template>
  <div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
        {{ isEdit ? 'Editar Usuario' : 'Nuevo Usuario' }}
      </h1>
      <router-link
        to="/users"
        class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500"
      >
        &larr; Volver al listado
      </router-link>
    </div>

    <form @submit.prevent="handleSubmit" class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-100 dark:border-gray-700 p-6 space-y-6">
      <div v-if="loading" class="text-center py-4 text-gray-500 dark:text-gray-400">
        Cargando formulario...
      </div>
      
      <template v-else>
        <!-- Información General -->
        <div class="grid grid-cols-1 gap-6">
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre Completo</label>
            <input
              v-model="form.name"
              type="text"
              id="name"
              required
              class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
              :class="{ 'border-red-500': errors.name }"
            />
            <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name[0] }}</p>
          </div>

          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Correo Electrónico</label>
            <input
              v-model="form.email"
              type="email"
              id="email"
              required
              class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
              :class="{ 'border-red-500': errors.email }"
            />
            <p v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email[0] }}</p>
          </div>
        </div>

        <!-- Contraseña -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4 border-t border-gray-100 dark:border-gray-700">
          <div class="col-span-full mb-2">
            <p class="text-sm text-gray-500 dark:text-gray-400" v-if="isEdit">
              Deja las contraseñas en blanco si no deseas cambiarlas.
            </p>
          </div>
          <div>
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contraseña</label>
            <input
              v-model="form.password"
              type="password"
              id="password"
              :required="!isEdit"
              class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
              :class="{ 'border-red-500': errors.password }"
            />
            <p v-if="errors.password" class="mt-1 text-sm text-red-600">{{ errors.password[0] }}</p>
          </div>

          <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirmar Contraseña</label>
            <input
              v-model="form.password_confirmation"
              type="password"
              id="password_confirmation"
              :required="!isEdit"
              class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            />
          </div>
        </div>

        <!-- Roles -->
        <div class="pt-4 border-t border-gray-100 dark:border-gray-700">
          <span class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Asignar Roles</span>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div v-for="role in availableRoles" :key="role.id" class="flex items-center">
              <input
                v-model="form.roles"
                :id="`role-${role.id}`"
                type="checkbox"
                :value="role.name"
                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
              />
              <label :for="`role-${role.id}`" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                {{ role.name }}
              </label>
            </div>
          </div>
          <p v-if="errors.roles" class="mt-1 text-sm text-red-600">{{ errors.roles[0] }}</p>
        </div>

        <div class="pt-6 flex justify-end">
          <button
            type="submit"
            :disabled="submitting"
            class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 transition-colors"
          >
            {{ submitting ? 'Guardando...' : 'Guardar Usuario' }}
          </button>
        </div>
      </template>
    </form>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import api from '../../plugins/axios'

const router = useRouter()
const route = useRoute()
const isEdit = computed(() => !!route.params.id)

const loading = ref(true)
const submitting = ref(false)
const availableRoles = ref([])
const errors = ref({})

const form = reactive({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    roles: []
})

const fetchData = async () => {
    loading.value = true
    try {
        const rolesRes = await api.get('/roles')
        availableRoles.value = rolesRes.data

        if (isEdit.value) {
            const userRes = await api.get(`/users/${route.params.id}`)
            const user = userRes.data
            form.name = user.name
            form.email = user.email
            form.roles = user.roles.map(r => r.name)
        }
    } catch (error) {
        console.error('Error fetching form data:', error)
    } finally {
        loading.value = false
    }
}

const handleSubmit = async () => {
    submitting.value = true
    errors.value = {}
    try {
        if (isEdit.value) {
            await api.put(`/users/${route.params.id}`, form)
        } else {
            await api.post('/users', form)
        }
        router.push('/users')
    } catch (error) {
        if (error.response?.status === 422) {
            errors.value = error.response.data.errors
        } else {
            alert('Error al guardar el usuario')
        }
    } finally {
        submitting.value = false
    }
}

onMounted(fetchData)
</script>
