<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import Swal from 'sweetalert2/dist/sweetalert2.js';
import 'sweetalert2/dist/sweetalert2.css';

// Configurar axios
axios.defaults.withCredentials = true;
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const props = defineProps({
    bloques: Array,
    proyectos: Array
});

const showModal = ref(false);
const editingBloque = ref(null);
const searchTerm = ref('');
const viewMode = ref('grid');
const isLoading = ref(false);
const formData = ref({
    nombre_bloque: '',
    IDBloque: '',
    IDproyecto: ''
});

// Computed property for filtered bloques
const filteredBloques = computed(() => {
    if (!searchTerm.value) return props.bloques;

    const search = searchTerm.value.toLowerCase();
    return props.bloques.filter(bloque =>
        bloque.nombre_bloque.toLowerCase().includes(search) ||
        bloque.IDBloque.toLowerCase().includes(search) ||
        (bloque.proyecto?.nombre || '').toLowerCase().includes(search)
    );
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
                await Swal.fire({
                    title: '¡Éxito!',
                    text: 'Bloque actualizado exitosamente',
                    icon: 'success',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#14b8a6',
                    timer: 3000,
                    timerProgressBar: true
                });
                window.location.reload();
            }
        } else {
            const response = await axios.post('/api/bloques', formData.value);
            if (response.data.success) {
                await Swal.fire({
                    title: '¡Éxito!',
                    text: 'Bloque creado exitosamente',
                    icon: 'success',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#14b8a6',
                    timer: 3000,
                    timerProgressBar: true
                });
                window.location.reload();
            }
        }
        closeModal();
    } catch (error) {
        await Swal.fire({
            title: 'Error',
            text: error.response?.data?.message || error.message || 'Error desconocido',
            icon: 'error',
            confirmButtonText: 'Aceptar',
            confirmButtonColor: '#ef4444'
        });
    }
};

const deleteBloque = async (bloque) => {
    const result = await Swal.fire({
        title: '¿Estás seguro?',
        text: `¿Deseas eliminar el bloque "${bloque.nombre_bloque}"? Esta acción no se puede deshacer.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true,
        customClass: {
            popup: 'rounded-2xl',
            confirmButton: 'rounded-xl',
            cancelButton: 'rounded-xl'
        }
    });

    if (result.isConfirmed) {
        try {
            const response = await axios.delete(`/api/bloques/${bloque.IDBloque}`);
            if (response.data.success) {
                await Swal.fire({
                    title: '¡Eliminado!',
                    text: 'El bloque ha sido eliminado exitosamente',
                    icon: 'success',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#14b8a6',
                    timer: 3000,
                    timerProgressBar: true
                });
                window.location.reload();
            }
        } catch (error) {
            await Swal.fire({
                title: 'Error',
                text: error.response?.data?.message || error.message || 'Error al eliminar el bloque',
                icon: 'error',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#ef4444'
            });
        }
    }
};
</script>

<template>
    <AppLayout title="Bloques">
        <div class="min-h-screen bg-gradient-to-br from-navy-50 to-navy-100">
            <!-- Add SweetAlert2 CSS -->
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
            <div class="container mx-auto px-4 py-8">
                <div class="max-w-7xl mx-auto">

                    <!-- Header con animación de entrada -->
                    <div class="flex flex-col md:flex-row gap-4 justify-center items-center mb-8 animate-fadeInDown">

                        <div class="relative group">
                            <input v-model="searchTerm" type="text" placeholder="Buscar bloques..."
                                class="w-80 px-6 py-4 rounded-full border-2 border-transparent bg-white/80 backdrop-blur-sm shadow-lg focus:border-navy-400 focus:shadow-2xl transition-all duration-500 transform group-hover:scale-105 focus:scale-105 text-gray-700">
                            <div class="absolute inset-0 rounded-full bg-gradient-to-r from-navy-400/20 to-navy-600/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                        </div>

                        <div class="flex bg-white/80 backdrop-blur-sm rounded-full p-1 shadow-lg">
                            <button @click="viewMode = 'grid'"
                                :class="viewMode === 'grid' ? 'bg-navy-500 text-white shadow-lg' : 'text-gray-600 hover:text-navy-600'"
                                class="px-4 py-2 rounded-full transition-all duration-300 transform hover:scale-110">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                </svg>
                            </button>
                            <button @click="viewMode = 'list'"
                                :class="viewMode === 'list' ? 'bg-navy-500 text-white shadow-lg' : 'text-gray-600 hover:text-navy-600'"
                                class="px-4 py-2 rounded-full transition-all duration-300 transform hover:scale-110">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>

                        <button @click="openCreateModal"
                            class="relative px-8 py-4 bg-gradient-to-r from-navy-500 to-navy-600 text-white font-bold rounded-full shadow-xl hover:shadow-2xl transform transition-all duration-500 hover:scale-110 hover:rotate-1 group overflow-hidden">
                            <span class="relative z-10 flex items-center gap-2">
                                <svg class="w-6 h-6 transform group-hover:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Crear Bloque
                            </span>
                            <div class="absolute inset-0 bg-gradient-to-r from-navy-600 to-navy-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </button>
                    </div>

                    <!-- Grid/lista de bloques con animación de entrada -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 animate-fadeInUp">
                        <div v-for="bloque in filteredBloques" :key="bloque.IDBloque"
                            :class="[
                                'group bg-white rounded-2xl shadow-md hover:shadow-xl transform transition-all duration-500 hover:scale-[1.02] border border-navy-100',
                                viewMode === 'list' ? 'col-span-full' : ''
                            ]">

                            <div class="bg-gradient-to-r from-navy-500 to-navy-600 rounded-t-2xl p-6 text-white">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h3 class="text-xl font-bold mb-2 group-hover:text-navy-100 transition-colors duration-300">
                                            {{ bloque.nombre_bloque }}
                                        </h3>
                                        <div class="flex flex-col gap-1">
                                            <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                                                <span class="bg-navy-100 px-3 py-1 rounded-full hover:bg-navy-200 transition-colors duration-300">ID: {{ bloque.IDBloque }}</span>
                                                <span class="bg-navy-100 px-3 py-1 rounded-full hover:bg-navy-200 transition-colors duration-300">{{ bloque.proyecto?.nombre || 'Sin proyecto' }}</span>
                                                <span class="bg-gray-100 px-3 py-1 rounded-full hover:bg-gray-200 transition-colors duration-300">{{ bloque.piezas?.length || 0 }} piezas</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Botones de acción -->
                                    <div class="flex gap-2 ml-4">
                                        <button @click="openEditModal(bloque)"
                                            class="p-2 bg-white/20 rounded-lg hover:bg-white/30 transform transition-all duration-300 hover:scale-110 backdrop-blur-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button @click="deleteBloque(bloque)"
                                            class="p-2 bg-red-500/80 rounded-lg hover:bg-red-600 transform transition-all duration-300 hover:scale-110 backdrop-blur-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Contenido del bloque -->
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="font-semibold text-gray-800 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-navy-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                        </svg>
                                        Piezas Asociadas
                                    </h4>
                                    <span class="px-3 py-1 bg-navy-100 text-navy-700 rounded-full text-sm font-medium">
                                        {{ bloque.piezas?.length || 0 }} piezas
                                    </span>
                                </div>

                                <!-- Lista de piezas -->
                                <div class="space-y-3 max-h-64 overflow-y-auto custom-scrollbar">
                                    <div v-if="bloque.piezas && bloque.piezas.length > 0">
                                        <div v-for="pieza in bloque.piezas" :key="pieza.IdPieza"
                                            class="bg-gradient-to-r from-slate-50 to-navy-50 p-4 rounded-xl border border-navy-100 hover:shadow-md transition-all duration-300 hover:border-navy-200">
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-700 mb-1">
                                                        {{ pieza.pieza }}
                                                    </p>
                                                    <div class="flex flex-col gap-2">
                                                        <div class="flex gap-2 text-xs text-gray-600">
                                                            <span class="bg-blue-50 px-2 py-1 rounded-full">
                                                                Teórico: {{ pieza.peso_teorico }} kg
                                                            </span>
                                                            <span class="bg-green-50 px-2 py-1 rounded-full">
                                                                Real: {{ pieza.peso_real || 'N/A' }} kg
                                                            </span>
                                                        </div>
                                                        <div class="text-xs text-gray-500 flex items-center gap-1">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                            </svg>
                                                            Registrado por: {{ pieza.registrado_por || 'No registrado' }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex justify-end items-center">
                                                    <span :class="{
                                                        'bg-navy-100 text-navy-700': pieza.estado === 'completado',
                                                        'bg-navy-200 text-navy-800': pieza.estado === 'en_proceso',
                                                        'bg-navy-100 text-navy-700': pieza.estado === 'pendiente',
                                                        'bg-red-100 text-red-700': pieza.estado === 'error'
                                                    }" class="px-3 py-1 rounded-full text-xs font-medium whitespace-nowrap">
                                                        {{ pieza.estado }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-else class="text-center py-8">
                                        <div class="text-gray-400 mb-2">
                                            <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm text-gray-500 italic">No hay piezas asociadas</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mensaje cuando no hay bloques -->
                    <div v-if="!bloques || bloques.length === 0" class="text-center py-16">
                        <div class="bg-white rounded-2xl shadow-lg p-12 max-w-md mx-auto">
                            <div class="text-navy-400 mb-4">
                                <svg class="w-20 h-20 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-700 mb-2">No hay bloques</h3>
                            <p class="text-gray-500 mb-6">Comienza creando tu primer bloque</p>
                            <button @click="openCreateModal"
                                class="px-6 py-3 bg-navy-600 text-white font-semibold rounded-xl hover:bg-navy-700 transform transition-all duration-300 hover:scale-105">
                                Crear Primer Bloque
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal mejorado -->
        <Transition
            enter-active-class="transition ease-out duration-300"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition ease-in duration-200"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0">
            <div v-if="showModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-50">
                <Transition
                    enter-active-class="transition ease-out duration-300"
                    enter-from-class="opacity-0 scale-95"
                    enter-to-class="opacity-100 scale-100"
                    leave-active-class="transition ease-in duration-200"
                    leave-from-class="opacity-100 scale-100"
                    leave-to-class="opacity-0 scale-95">
                    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform transition-all">
                        <!-- Header del modal -->
                        <div class="bg-gradient-to-r from-navy-600 to-navy-700 rounded-t-2xl p-6 text-white">
                            <h3 class="text-xl font-bold flex items-center gap-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                                {{ editingBloque ? 'Editar Bloque' : 'Nuevo Bloque' }}
                            </h3>
                        </div>

                        <!-- Contenido del modal -->
                        <form @submit.prevent="saveBloque" class="p-6 space-y-6">
                            <div class="space-y-4">
                                <div class="group">
                                    <label class="block text-sm font-bold text-gray-700 mb-3 transform group-hover:text-navy-600 transition-colors duration-300">
                                        ID del Bloque
                                    </label>
                                    <input v-model="formData.IDBloque" type="text"
                                        class="w-full px-6 py-4 border-2 border-gray-200 rounded-2xl focus:border-navy-500 focus:ring-4 focus:ring-navy-200 transition-all duration-500 transform focus:scale-105 bg-gradient-to-r from-white to-navy-50/30"
                                        placeholder="Ingrese el ID único del bloque"
                                        required>
                                </div>

                                <div class="group">
                                    <label class="block text-sm font-bold text-gray-700 mb-3 transform group-hover:text-navy-600 transition-colors duration-300">
                                        Nombre del Bloque
                                    </label>
                                    <input v-model="formData.nombre_bloque" type="text"
                                        class="w-full px-6 py-4 border-2 border-gray-200 rounded-2xl focus:border-navy-500 focus:ring-4 focus:ring-navy-200 transition-all duration-500 transform focus:scale-105 bg-gradient-to-r from-white to-navy-50/30"
                                        placeholder="Nombre descriptivo del bloque"
                                        required>
                                </div>

                                <div class="group">
                                    <label class="block text-sm font-bold text-gray-700 mb-3 transform group-hover:text-navy-600 transition-colors duration-300">
                                        Proyecto Asociado
                                    </label>
                                    <select v-model="formData.IDproyecto"
                                        class="w-full px-6 py-4 border-2 border-gray-200 rounded-2xl focus:border-navy-500 focus:ring-4 focus:ring-navy-200 transition-all duration-500 transform focus:scale-105 bg-gradient-to-r from-white to-navy-50/30"
                                        required>
                                        <option value="">Seleccione un proyecto</option>
                                        <option v-for="proyecto in proyectos" :key="proyecto.IDproyecto" :value="proyecto.IDproyecto">
                                            {{ proyecto.nombre }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <!-- Botones del modal -->
                            <div class="flex gap-3 pt-4">
                                <button type="button" @click="closeModal"
                                    class="flex-1 px-6 py-4 bg-gray-200 text-gray-700 font-bold rounded-2xl hover:bg-gray-300 transition-all duration-300 transform hover:scale-105 hover:-rotate-1">
                                    Cancelar
                                </button>
                                <button type="submit" :disabled="isLoading"
                                    class="flex-1 px-6 py-4 bg-gradient-to-r from-navy-500 to-navy-600 text-white font-bold rounded-2xl hover:from-navy-600 hover:to-navy-700 transition-all duration-300 transform hover:scale-105 hover:rotate-1 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span v-if="isLoading" class="flex items-center justify-center gap-2">
                                        <div class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                                        Guardando...
                                    </span>
                                    <span v-else>
                                        {{ editingBloque ? 'Actualizar' : 'Crear' }}
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </Transition>
            </div>
        </Transition>

        <!-- Loading overlay -->
        <Transition
            enter-active-class="transition ease-out duration-300"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition ease-in duration-200"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0">
            <div v-if="isLoading" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
                <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-8 shadow-2xl transform transition-all duration-500 animate-pulse">
                    <div class="flex flex-col items-center gap-4">
                        <div class="w-16 h-16 border-4 border-navy-500 border-t-transparent rounded-full animate-spin"></div>
                        <p class="text-xl font-semibold text-gray-700">Procesando...</p>
                    </div>
                </div>
            </div>
        </Transition>
    </AppLayout>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 2px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #0070c2;
    border-radius: 2px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #005a9e;
}

/* Animaciones personalizadas */
@keyframes float {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-10px);
    }
}

.animate-float {
    animation: float 3s ease-in-out infinite;
}

/* Animaciones de entrada */
@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fadeInDown {
    animation: fadeInDown 0.8s ease-out;
}

.animate-fadeInUp {
    animation: fadeInUp 0.8s ease-out both;
}

/* Efectos de hover */
.hover-lift {
    transition: transform 0.3s ease;
}

.hover-lift:hover {
    transform: translateY(-5px);
}

/* Gradientes animados */
.animate-gradient {
    background-size: 200% 200%;
    animation: gradient 15s ease infinite;
}

@keyframes gradient {
    0% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
    100% {
        background-position: 0% 50%;
    }
}

/* Estados de las piezas */
.status-pending {
    @apply bg-navy-100 text-navy-700;
}

.status-in-progress {
    @apply bg-navy-200 text-navy-800;
}

.status-completed {
    @apply bg-navy-300 text-navy-900;
}

.status-error {
    @apply bg-red-100 text-red-700;
}
</style>
