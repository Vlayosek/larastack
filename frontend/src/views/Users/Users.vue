<template>
  <div class="space-y-6">
    <div class="flex justify-between items-center">
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Gestión de Usuarios</h1>
      <router-link
        to="/users/create"
        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
      >
        ➕ Nuevo Usuario
      </router-link>
    </div>

    <!-- Filtros / Búsqueda (Opcional) -->
    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
      <input
        v-model="search"
        type="text"
        placeholder="Buscar por nombre o email..."
        class="block w-full sm:w-64 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm dark:bg-gray-700 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
      />
    </div>

    <!-- Tabla de Usuarios -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
      <div v-if="loading" class="p-8 text-center text-gray-500 dark:text-gray-400">
        Cargando usuarios...
      </div>
      <div v-else-if="filteredUsers.length === 0" class="p-8 text-center text-gray-500 dark:text-gray-400">
        No se encontraron usuarios.
      </div>
      <table v-else class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-700/50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Usuario</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Roles</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Creado</th>
            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
          <tr v-for="user in filteredUsers" :key="user.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex items-center">
                <div class="h-10 w-10 flex-shrink-0 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-700 dark:text-indigo-300 font-bold">
                  {{ user.name.charAt(0) }}
                </div>
                <div class="ml-4">
                  <div class="text-sm font-medium text-gray-900 dark:text-white">{{ user.name }}</div>
                  <div class="text-sm text-gray-500 dark:text-gray-400">{{ user.email }}</div>
                </div>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex flex-wrap gap-1">
                <span
                  v-for="role in user.roles"
                  :key="role.id"
                  class="px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300"
                >
                  {{ role.name }}
                </span>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
              {{ new Date(user.created_at).toLocaleDateString() }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
              <router-link
                :to="`/users/${user.id}/edit`"
                class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300"
              >
                Editar
              </router-link>
              <button
                @click="deleteUser(user)"
                class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 disabled:opacity-50"
                :disabled="isDeleting === user.id"
              >
                {{ isDeleting === user.id ? '...' : 'Eliminar' }}
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import api from '../../plugins/axios'

const users = ref([])
const loading = ref(true)
const search = ref('')
const isDeleting = ref(null)

const fetchUsers = async () => {
    loading.value = true
    try {
        const response = await api.get('/users')
        users.value = response.data
    } catch (error) {
        console.error('Error fetching users:', error)
    } finally {
        loading.value = false
    }
}

const deleteUser = async (user) => {
    if (!confirm(`¿Estás seguro de eliminar al usuario ${user.name}?`)) return

    isDeleting.value = user.id
    try {
        await api.delete(`/users/${user.id}`)
        users.value = users.value.filter(u => u.id !== user.id)
    } catch (error) {
        alert(error.response?.data?.message || 'Error al eliminar usuario')
    } finally {
        isDeleting.value = null
    }
}

const filteredUsers = computed(() => {
    if (!search.value) return users.value
    const s = search.value.toLowerCase()
    return users.value.filter(u => 
        u.name.toLowerCase().includes(s) || 
        u.email.toLowerCase().includes(s)
    )
})

onMounted(fetchUsers)
</script>
