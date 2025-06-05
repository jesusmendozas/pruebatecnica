<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import axios from 'axios';

const props = defineProps({
    usuarios: Array
});

const showModal = ref(false);
const editingUsuario = ref(null);
const formData = ref({
    name: '',
    password: '',
    password_confirmation: ''
});

const openCreateModal = () => {
    formData.value = {
        name: '',
        password: '',
        password_confirmation: ''
    };
    editingUsuario.value = null;
    showModal.value = true;
};

const openEditModal = (usuario) => {
    formData.value = {
        name: usuario.name,
        password: '',
        password_confirmation: ''
    };
    editingUsuario.value = usuario;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    formData.value = {
        name: '',
        password: '',
        password_confirmation: ''
    };
    editingUsuario.value = null;
};

const saveUsuario = async () => {
    try {
        if (editingUsuario.value) {
            const response = await axios.put(`/api/usuarios/${editingUsuario.value.id}`, formData.value);
            if (response.data.success) {
                alert('Usuario actualizado exitosamente');
                window.location.reload();
            }
        } else {
            const response = await axios.post('/api/usuarios', formData.value);
            if (response.data.success) {
                alert('Usuario creado exitosamente');
                window.location.reload();
            }
        }
        closeModal();
    } catch (error) {
        alert('Error al guardar el usuario: ' + (error.response?.data?.message || error.message || 'Error desconocido'));
    }
};

const deleteUsuario = async (usuario) => {
    if (confirm('¿Estás seguro de que deseas eliminar este usuario? Esta acción no se puede deshacer.')) {
        try {
            const response = await axios.delete(`/api/usuarios/${usuario.id}`);
            if (response.data.success) {
                alert('Usuario eliminado exitosamente');
                window.location.reload();
            }
        } catch (error) {
            alert('Error al eliminar el usuario: ' + (error.response?.data?.message || error.message || 'Error desconocido'));
        }
    }
};
</script>

<template>
    <AppLayout title="Usuarios">
        <div class="container mx-auto p-6">
            <div class="max-w-7xl mx-auto">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-semibold text-gray-800">Lista de Usuarios</h2>
                            <button @click="openCreateModal"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Nuevo Usuario
                            </button>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Usuario
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Fecha de Creación
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="usuario in usuarios" :key="usuario.id">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ usuario.name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">{{ new Date(usuario.created_at).toLocaleDateString() }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button @click="openEditModal(usuario)"
                                                class="text-blue-600 hover:text-blue-900 mr-3">
                                                Editar
                                            </button>
                                            <button @click="deleteUsuario(usuario)"
                                                class="text-red-600 hover:text-red-900">
                                                Eliminar
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div v-if="showModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        {{ editingUsuario ? 'Editar Usuario' : 'Nuevo Usuario' }}
                    </h3>
                    <form @submit.prevent="saveUsuario">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                                Usuario
                            </label>
                            <input v-model="formData.name" type="text"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                                Contraseña {{ editingUsuario ? '(opcional)' : '' }}
                            </label>
                            <input v-model="formData.password" type="password"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                :required="!editingUsuario">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="password_confirmation">
                                Confirmar Contraseña
                            </label>
                            <input v-model="formData.password_confirmation" type="password"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                :required="!editingUsuario">
                        </div>
                        <div class="flex justify-end space-x-2">
                            <button type="button" @click="closeModal"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                {{ editingUsuario ? 'Actualizar' : 'Crear' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
