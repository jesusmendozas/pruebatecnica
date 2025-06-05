<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    proyectos: Array
});

const expandedProyecto = ref(null);
const showModal = ref(false);
const editingProyecto = ref(null);
const formData = ref({
    nombre: '',
    IDproyecto: ''
});

const toggleProyecto = (proyectoId) => {
    expandedProyecto.value = expandedProyecto.value === proyectoId ? null : proyectoId;
};

const openCreateModal = () => {
    formData.value = {
        nombre: '',
        IDproyecto: ''
    };
    editingProyecto.value = null;
    showModal.value = true;
};

const openEditModal = (proyecto) => {
    formData.value = {
        nombre: proyecto.nombre,
        IDproyecto: proyecto.IDproyecto
    };
    editingProyecto.value = proyecto;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    formData.value = {
        nombre: '',
        IDproyecto: ''
    };
    editingProyecto.value = null;
};

const saveProyecto = async () => {
    try {
        if (editingProyecto.value) {
            await router.put(`/api/proyectos/${editingProyecto.value.IDproyecto}`, {
                nombre: formData.value.nombre
            });
            alert('Proyecto actualizado exitosamente');
        } else {
            await router.post('/api/proyectos', formData.value);
            alert('Proyecto creado exitosamente');
        }
        closeModal();
        window.location.reload();
    } catch (error) {
        alert('Error al guardar el proyecto: ' + (error.message || 'Error desconocido'));
    }
};

const deleteProyecto = async (proyecto) => {
    if (confirm('¿Estás seguro de que deseas eliminar este proyecto? Esta acción no se puede deshacer.')) {
        try {
            await router.delete(`/api/proyectos/${proyecto.IDproyecto}`);
            alert('Proyecto eliminado exitosamente');
            window.location.reload();
        } catch (error) {
            alert('Error al eliminar el proyecto: ' + (error.message || 'Error desconocido'));
        }
    }
};
</script>

<template>
    <AppLayout title="Proyectos">
        <div class="container mx-auto p-6">
            <div class="max-w-7xl mx-auto">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-semibold text-gray-800">Lista de Proyectos</h2>
                            <button @click="openCreateModal"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Nuevo Proyecto
                            </button>
                        </div>

                        <div class="space-y-6">
                            <div v-for="proyecto in proyectos" :key="proyecto.IDproyecto"
                                class="bg-white border rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                                <div class="p-6">
                                    <div class="flex justify-between items-center">
                                        <div class="flex-1 cursor-pointer" @click="toggleProyecto(proyecto.IDproyecto)">
                                            <h3 class="text-lg font-semibold text-gray-800">
                                                {{ proyecto.nombre }}
                                            </h3>
                                            <p class="text-sm text-gray-600">ID: {{ proyecto.IDproyecto }}</p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <button @click="openEditModal(proyecto)"
                                                class="p-2 text-blue-600 hover:text-blue-800 focus:outline-none">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <button @click="deleteProyecto(proyecto)"
                                                class="p-2 text-red-600 hover:text-red-800 focus:outline-none">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                            <svg @click="toggleProyecto(proyecto.IDproyecto)"
                                                class="w-6 h-6 text-gray-500 transform transition-transform duration-200 cursor-pointer"
                                                :class="{ 'rotate-180': expandedProyecto === proyecto.IDproyecto }"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                    </div>

                                    <div v-if="expandedProyecto === proyecto.IDproyecto" class="mt-4 space-y-4">
                                        <div v-for="bloque in proyecto.bloques" :key="bloque.IDBloque"
                                            class="ml-4 border-l-2 border-gray-200 pl-4">
                                            <h4 class="font-medium text-gray-700 mb-2">{{ bloque.nombre_bloque }}</h4>

                                            <div v-if="bloque.piezas && bloque.piezas.length > 0" class="ml-4 space-y-2">
                                                <div v-for="pieza in bloque.piezas" :key="pieza.IdPieza"
                                                    class="bg-gray-50 p-3 rounded-md">
                                                    <p class="text-sm text-gray-600">
                                                        <span class="font-medium">Pieza:</span> {{ pieza.pieza }}
                                                    </p>
                                                    <p class="text-sm text-gray-600">
                                                        <span class="font-medium">Peso Teórico:</span> {{ pieza.peso_teorico }} kg
                                                    </p>
                                                </div>
                                            </div>
                                            <p v-else class="text-sm text-gray-500 italic">No hay piezas pendientes</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="showModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        {{ editingProyecto ? 'Editar Proyecto' : 'Nuevo Proyecto' }}
                    </h3>
                    <form @submit.prevent="saveProyecto">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="IDproyecto">
                                ID del Proyecto
                            </label>
                            <input v-model="formData.IDproyecto" type="text"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                :readonly="editingProyecto"
                                required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="nombre">
                                Nombre del Proyecto
                            </label>
                            <input v-model="formData.nombre" type="text"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                required>
                        </div>
                        <div class="flex justify-end space-x-2">
                            <button type="button" @click="closeModal"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                {{ editingProyecto ? 'Actualizar' : 'Crear' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
