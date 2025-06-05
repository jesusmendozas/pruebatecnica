<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import axios from 'axios';

// Configurar axios
axios.defaults.withCredentials = true;
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const props = defineProps({
    bloques: Array,
    proyectos: Array
});

const showModal = ref(false);
const editingBloque = ref(null);
const formData = ref({
    nombre_bloque: '',
    IDBloque: '',
    IDproyecto: ''
});

const openCreateModal = () => {
    formData.value = {
        nombre_bloque: '',
        IDBloque: '',
        IDproyecto: ''
    };
    editingBloque.value = null;
    showModal.value = true;
};

const openEditModal = (bloque) => {
    formData.value = {
        nombre_bloque: bloque.nombre_bloque,
        IDBloque: bloque.IDBloque,
        IDproyecto: bloque.IDproyecto
    };
    editingBloque.value = bloque;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    formData.value = {
        nombre_bloque: '',
        IDBloque: '',
        IDproyecto: ''
    };
    editingBloque.value = null;
};

const saveBloque = async () => {
    try {
        if (editingBloque.value) {
            const response = await axios.put(`/api/bloques/${editingBloque.value.IDBloque}`, formData.value);
            if (response.data.success) {
                alert('Bloque actualizado exitosamente');
                window.location.reload();
            }
        } else {
            const response = await axios.post('/api/bloques', formData.value);
            if (response.data.success) {
                alert('Bloque creado exitosamente');
                window.location.reload();
            }
        }
        closeModal();
    } catch (error) {
        alert('Error al guardar el bloque: ' + (error.response?.data?.message || error.message || 'Error desconocido'));
    }
};

const deleteBloque = async (bloque) => {
    if (confirm('¿Estás seguro de que deseas eliminar este bloque? Esta acción no se puede deshacer.')) {
        try {
            const response = await axios.delete(`/api/bloques/${bloque.IDBloque}`);
            if (response.data.success) {
                alert('Bloque eliminado exitosamente');
                window.location.reload();
            }
        } catch (error) {
            alert('Error al eliminar el bloque: ' + (error.response?.data?.message || error.message || 'Error desconocido'));
        }
    }
};
</script>

<template>
    <AppLayout title="Bloques">
        <div class="container mx-auto p-6">
            <div class="max-w-7xl mx-auto">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-semibold text-gray-800">Lista de Bloques</h2>
                            <button @click="openCreateModal"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Nuevo Bloque
                            </button>
                        </div>

                        <div class="space-y-6">
                            <div v-for="bloque in bloques" :key="bloque.IDBloque"
                                class="bg-white border rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                                <div class="p-6">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-800">
                                                {{ bloque.nombre_bloque }}
                                            </h3>
                                            <p class="text-sm text-gray-600">ID: {{ bloque.IDBloque }}</p>
                                            <p class="text-sm text-gray-600">
                                                Proyecto: {{ bloque.proyecto?.nombre || 'Sin proyecto' }}
                                            </p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <button @click="openEditModal(bloque)"
                                                class="p-2 text-blue-600 hover:text-blue-800 focus:outline-none">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <button @click="deleteBloque(bloque)"
                                                class="p-2 text-red-600 hover:text-red-800 focus:outline-none">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Piezas -->
                                    <div class="mt-4">
                                        <h4 class="font-medium text-gray-700 mb-2">Piezas asociadas:</h4>
                                        <div v-if="bloque.piezas && bloque.piezas.length > 0" class="space-y-2">
                                            <div v-for="pieza in bloque.piezas" :key="pieza.IdPieza"
                                                class="bg-gray-50 p-3 rounded-md">
                                                <p class="text-sm text-gray-600">
                                                    <span class="font-medium">Pieza:</span> {{ pieza.pieza }}
                                                </p>
                                                <p class="text-sm text-gray-600">
                                                    <span class="font-medium">Peso Teórico:</span> {{ pieza.peso_teorico }} kg
                                                </p>
                                                <p class="text-sm text-gray-600">
                                                    <span class="font-medium">Estado:</span> {{ pieza.estado }}
                                                </p>
                                            </div>
                                        </div>
                                        <p v-else class="text-sm text-gray-500 italic">No hay piezas asociadas</p>
                                    </div>
                                </div>
                            </div>
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
                        {{ editingBloque ? 'Editar Bloque' : 'Nuevo Bloque' }}
                    </h3>
                    <form @submit.prevent="saveBloque">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="IDBloque">
                                ID del Bloque
                            </label>
                            <input v-model="formData.IDBloque" type="text"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="nombre_bloque">
                                Nombre del Bloque
                            </label>
                            <input v-model="formData.nombre_bloque" type="text"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="IDproyecto">
                                Proyecto
                            </label>
                            <select v-model="formData.IDproyecto"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                required>
                                <option value="">Seleccione un proyecto</option>
                                <option v-for="proyecto in proyectos" :key="proyecto.IDproyecto" :value="proyecto.IDproyecto">
                                    {{ proyecto.nombre }}
                                </option>
                            </select>
                        </div>
                        <div class="flex justify-end space-x-2">
                            <button type="button" @click="closeModal"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                {{ editingBloque ? 'Actualizar' : 'Crear' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
