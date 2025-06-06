<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    proyectos: Array
});

const expandedProyecto = ref(null);
const showModal = ref(false);
const showDeleteModal = ref(false);
const showAlert = ref(false);
const alertConfig = ref({
    type: 'success',
    message: '',
    show: false
});
const proyectoToDelete = ref(null);
const editingProyecto = ref(null);
const formData = ref({
    nombre: '',
    IDproyecto: ''
});

const showNotification = (type, message) => {
    alertConfig.value = {
        type,
        message,
        show: true
    };
    setTimeout(() => {
        alertConfig.value.show = false;
    }, 3000);
};

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

const openDeleteModal = (proyecto) => {
    proyectoToDelete.value = proyecto;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    proyectoToDelete.value = null;
};

const saveProyecto = async () => {
    try {
        if (editingProyecto.value) {
            await router.put(`/api/proyectos/${editingProyecto.value.IDproyecto}`, {
                nombre: formData.value.nombre
            });
            showNotification('success', 'Proyecto actualizado exitosamente');
        } else {
            await router.post('/api/proyectos', formData.value);
            showNotification('success', 'Proyecto creado exitosamente');
        }
        closeModal();
        window.location.reload();
    } catch (error) {
        showNotification('error', 'Error al guardar el proyecto: ' + (error.message || 'Error desconocido'));
    }
};

const deleteProyecto = async () => {
    try {
        await router.delete(`/api/proyectos/${proyectoToDelete.value.IDproyecto}`);
        showNotification('success', 'Proyecto eliminado exitosamente');
        closeDeleteModal();
        window.location.reload();
    } catch (error) {
        showNotification('error', 'Error al eliminar el proyecto: ' + (error.message || 'Error desconocido'));
    }
};
</script>

<template>
    <AppLayout title="Proyectos">
        <transition name="alert">
            <div v-if="alertConfig.show"
                class="fixed top-4 right-4 z-50 max-w-sm w-full bg-white rounded-lg shadow-lg border-l-4"
                :class="{
                    'border-green-500': alertConfig.type === 'success',
                    'border-red-500': alertConfig.type === 'error'
                }">
                <div class="p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg v-if="alertConfig.type === 'success'" class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <svg v-else class="h-6 w-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">{{ alertConfig.message }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </transition>

        <div class="min-h-screen bg-gradient-to-br from-navy-50 via-navy-100 to-navy-200">
            <div class="container mx-auto px-4 py-8">
                <div class="max-w-6xl mx-auto">
                    <div class="mb-8 animate-fadeInDown">
                        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-navy-100 p-6">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                <div>
                                    <h1 class="text-3xl font-bold bg-gradient-to-r from-navy-600 to-navy-800 bg-clip-text text-transparent">
                                        Gestión de Proyectos
                                    </h1>
                                </div>
                                <button @click="openCreateModal"
                                    class="group relative px-6 py-3 bg-gradient-to-r from-navy-500 to-navy-600 text-white rounded-xl font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 ease-out hover:from-navy-600 hover:to-navy-700">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Nuevo Proyecto
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="grid gap-6 animate-fadeInUp">
                        <div v-for="(proyecto, index) in proyectos" :key="proyecto.IDproyecto"
                            class="group bg-white/90 backdrop-blur-sm rounded-2xl shadow-md hover:shadow-xl border border-navy-100 hover:border-navy-200 transition-all duration-500 ease-out hover:-translate-y-1"
                            :style="{ animationDelay: `${index * 100}ms` }">

                            <div class="p-6 border-b border-navy-50">
                                <div class="flex justify-between items-start gap-4">
                                    <div class="flex-1 cursor-pointer" @click="toggleProyecto(proyecto.IDproyecto)">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="w-3 h-3 bg-gradient-to-r from-navy-400 to-navy-400 rounded-full animate-pulse"></div>
                                            <h3 class="text-xl font-semibold text-slate-800 group-hover:text-navy-700 transition-colors duration-300">
                                                {{ proyecto.nombre }}
                                            </h3>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm text-slate-500">ID:</span>
                                            <span class="px-3 py-1 bg-gradient-to-r from-navy-100 to-navy-100 text-navy-700 rounded-full text-sm font-medium">
                                                {{ proyecto.IDproyecto }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <button @click="openEditModal(proyecto)"
                                            class="p-2.5 text-navy-600 hover:text-white hover:bg-navy-500 rounded-xl transition-all duration-300 transform hover:scale-110 hover:rotate-3">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button @click="openDeleteModal(proyecto)"
                                            class="p-2.5 text-rose-500 hover:text-white hover:bg-rose-500 rounded-xl transition-all duration-300 transform hover:scale-110 hover:-rotate-3">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                        <button @click="toggleProyecto(proyecto.IDproyecto)"
                                            class="p-2.5 text-slate-500 hover:text-navy-600 hover:bg-navy-50 rounded-xl transition-all duration-300">
                                            <svg class="w-6 h-6 transform transition-transform duration-500 ease-out"
                                                :class="{ 'rotate-180': expandedProyecto === proyecto.IDproyecto }"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <transition name="expand" mode="out-in">
                                <div v-if="expandedProyecto === proyecto.IDproyecto" class="overflow-hidden">
                                    <div class="p-6 space-y-4">
                                        <div v-for="(bloque, bloqueIndex) in proyecto.bloques" :key="bloque.IDBloque"
                                            class="animate-slideInLeft border-l-4 border-gradient-to-b from-navy-400 to-navy-500 bg-gradient-to-r from-navy-50/50 to-navy-50/30 rounded-r-xl p-4"
                                            :style="{ animationDelay: `${bloqueIndex * 150}ms` }">

                                            <h4 class="font-semibold text-slate-700 mb-3 flex items-center gap-2">
                                                <div class="w-2 h-2 bg-navy-400 rounded-full"></div>
                                                {{ bloque.nombre_bloque }}
                                            </h4>

                                            <div v-if="bloque.piezas && bloque.piezas.length > 0"
                                                class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                                                <div v-for="(pieza, piezaIndex) in bloque.piezas" :key="pieza.IdPieza"
                                                    class="bg-white/70 backdrop-blur-sm p-4 rounded-xl border border-navy-100 hover:border-navy-200 hover:shadow-md transition-all duration-300 transform hover:-translate-y-0.5"
                                                    :style="{ animationDelay: `${(bloqueIndex * 150) + (piezaIndex * 100)}ms` }">
                                                    <div class="space-y-2">
                                                        <p class="text-sm">
                                                            <span class="font-medium text-slate-600">Pieza:</span>
                                                            <span class="text-slate-800 ml-1">{{ pieza.pieza }}</span>
                                                        </p>
                                                        <div class="flex items-center gap-2">
                                                            <span class="font-medium text-slate-600 text-sm">Peso Teórico:</span>
                                                            <span class="px-2 py-1 bg-gradient-to-r from-navy-100 to-navy-100 text-navy-700 rounded-lg text-sm font-medium">
                                                                {{ pieza.peso_teorico }} kg
                                                            </span>
                                                        </div>
                                                        <div class="flex items-center gap-2">
                                                            <span class="font-medium text-slate-600 text-sm">Peso Real:</span>
                                                            <span class="px-2 py-1 bg-gradient-to-r from-navy-100 to-navy-100 text-navy-700 rounded-lg text-sm font-medium">
                                                                {{ pieza.peso_real || 'No registrado' }} kg
                                                            </span>
                                                        </div>
                                                        <div class="flex items-center gap-2">
                                                            <span class="font-medium text-slate-600 text-sm">Estado:</span>
                                                            <span :class="[
                                                                'px-2 py-1 rounded-lg text-sm font-medium',
                                                                pieza.estado === 'Fabricada'
                                                                    ? 'bg-green-100 text-green-700'
                                                                    : 'bg-yellow-100 text-yellow-700'
                                                            ]">
                                                                {{ pieza.estado || 'Pendiente' }}
                                                            </span>
                                                        </div>
                                                        <div class="flex items-center gap-2">
                                                            <span class="font-medium text-slate-600 text-sm">Registrado por:</span>
                                                            <span class="px-2 py-1 bg-gradient-to-r from-navy-100 to-navy-100 text-navy-700 rounded-lg text-sm font-medium">
                                                                {{ pieza.registrado_por || 'No registrado' }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div v-else class="text-center py-8">
                                                <div class="inline-flex items-center gap-2 text-slate-500">
                                                    <svg class="w-5 h-5 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                                    </svg>
                                                    <span class="italic">No hay piezas disponibles</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </transition>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <transition name="modal" mode="out-in">
            <div v-if="showModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">
                <div class="bg-white rounded-2xl shadow-2xl border border-navy-100 w-full max-w-md transform transition-all duration-300">
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-gradient-to-r from-navy-500 to-navy-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        :d="editingProyecto ? 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z' : 'M12 4v16m8-8H4'" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-slate-800">
                                {{ editingProyecto ? 'Editar Proyecto' : 'Nuevo Proyecto' }}
                            </h3>
                        </div>

                        <form @submit.prevent="saveProyecto" class="space-y-5">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">
                                    ID del Proyecto
                                </label>
                                <input v-model="formData.IDproyecto" type="text"
                                    class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-navy-500 focus:border-navy-500 transition-all duration-300 bg-slate-50 focus:bg-white"
                                    :readonly="editingProyecto"
                                    :class="{ 'bg-slate-100 cursor-not-allowed': editingProyecto }"
                                    required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">
                                    Nombre del Proyecto
                                </label>
                                <input v-model="formData.nombre" type="text"
                                    class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-navy-500 focus:border-navy-500 transition-all duration-300 bg-slate-50 focus:bg-white"
                                    required>
                            </div>
                            <div class="flex gap-3 pt-4">
                                <button type="button" @click="closeModal"
                                    class="flex-1 px-4 py-3 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition-all duration-300 font-medium">
                                    Cancelar
                                </button>
                                <button type="submit"
                                    class="flex-1 px-4 py-3 bg-gradient-to-r from-navy-500 to-navy-600 text-white rounded-xl hover:from-navy-600 hover:to-navy-700 transition-all duration-300 font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                    {{ editingProyecto ? 'Actualizar' : 'Crear' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </transition>

        <transition name="modal" mode="out-in">
            <div v-if="showDeleteModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">
                <div class="bg-white rounded-2xl shadow-2xl border border-rose-100 w-full max-w-md transform transition-all duration-300">
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-gradient-to-r from-rose-500 to-red-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-slate-800">
                                Confirmar Eliminación
                            </h3>
                        </div>

                        <p class="text-slate-600 mb-6">
                            ¿Estás seguro de que deseas eliminar el proyecto "{{ proyectoToDelete?.nombre }}"? Esta acción no se puede deshacer.
                        </p>

                        <div class="flex gap-3">
                            <button @click="closeDeleteModal"
                                class="flex-1 px-4 py-3 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition-all duration-300 font-medium">
                                Cancelar
                            </button>
                            <button @click="deleteProyecto"
                                class="flex-1 px-4 py-3 bg-gradient-to-r from-rose-500 to-red-600 text-white rounded-xl hover:from-rose-600 hover:to-red-700 transition-all duration-300 font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                Eliminar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </AppLayout>
</template>

<style scoped>
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

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.animate-fadeInDown {
    animation: fadeInDown 0.8s ease-out;
}

.animate-fadeInUp {
    animation: fadeInUp 0.8s ease-out both;
}

.animate-slideInLeft {
    animation: slideInLeft 0.6s ease-out both;
}

.expand-enter-active, .expand-leave-active {
    transition: all 0.5s ease-out;
    overflow: hidden;
}

.expand-enter-from, .expand-leave-to {
    max-height: 0;
    opacity: 0;
    transform: translateY(-20px);
}

.expand-enter-to, .expand-leave-from {
    max-height: 1000px;
    opacity: 1;
    transform: translateY(0);
}

.modal-enter-active, .modal-leave-active {
    transition: all 0.3s ease-out;
}

.modal-enter-from, .modal-leave-to {
    opacity: 0;
    transform: scale(0.9);
}

.modal-enter-to, .modal-leave-from {
    opacity: 1;
    transform: scale(1);
}

.group:hover .group-hover\:rotate-90 {
    transform: rotate(90deg);
}

.border-gradient-to-b {
    border-image: linear-gradient(to bottom, #14b8a6, #0891b2) 1;
}

.alert-enter-active, .alert-leave-active {
    transition: all 0.3s ease-out;
}

.alert-enter-from, .alert-leave-to {
    opacity: 0;
    transform: translateX(100%);
}

.alert-enter-to, .alert-leave-from {
    opacity: 1;
    transform: translateX(0);
}
</style>