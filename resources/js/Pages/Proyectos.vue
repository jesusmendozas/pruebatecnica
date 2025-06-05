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
        <!-- Fondo con gradiente -->
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-teal-50 to-cyan-100">
            <div class="container mx-auto px-4 py-8">
                <div class="max-w-6xl mx-auto">
                    <!-- Header con animación de entrada -->
                    <div class="mb-8 animate-fadeInDown">
                        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-teal-100 p-6">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                <div>
                                    <h1 class="text-3xl font-bold bg-gradient-to-r from-teal-600 to-cyan-600 bg-clip-text text-transparent">
                                        Gestión de Proyectos
                                    </h1>
                                    <p class="text-slate-600 mt-1">Administra tus proyectos de forma eficiente</p>
                                </div>
                                <button @click="openCreateModal"
                                    class="group relative px-6 py-3 bg-gradient-to-r from-teal-500 to-cyan-600 text-white rounded-xl font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 ease-out hover:from-teal-600 hover:to-cyan-700">
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

                    <!-- Lista de proyectos con grid responsivo -->
                    <div class="grid gap-6 animate-fadeInUp">
                        <div v-for="(proyecto, index) in proyectos" :key="proyecto.IDproyecto"
                            class="group bg-white/90 backdrop-blur-sm rounded-2xl shadow-md hover:shadow-xl border border-teal-100 hover:border-teal-200 transition-all duration-500 ease-out hover:-translate-y-1"
                            :style="{ animationDelay: `${index * 100}ms` }">

                            <!-- Header del proyecto -->
                            <div class="p-6 border-b border-teal-50">
                                <div class="flex justify-between items-start gap-4">
                                    <div class="flex-1 cursor-pointer" @click="toggleProyecto(proyecto.IDproyecto)">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="w-3 h-3 bg-gradient-to-r from-teal-400 to-cyan-400 rounded-full animate-pulse"></div>
                                            <h3 class="text-xl font-semibold text-slate-800 group-hover:text-teal-700 transition-colors duration-300">
                                                {{ proyecto.nombre }}
                                            </h3>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm text-slate-500">ID:</span>
                                            <span class="px-3 py-1 bg-gradient-to-r from-teal-100 to-cyan-100 text-teal-700 rounded-full text-sm font-medium">
                                                {{ proyecto.IDproyecto }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Botones de acción -->
                                    <div class="flex items-center gap-2">
                                        <button @click="openEditModal(proyecto)"
                                            class="p-2.5 text-teal-600 hover:text-white hover:bg-teal-500 rounded-xl transition-all duration-300 transform hover:scale-110 hover:rotate-3">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button @click="deleteProyecto(proyecto)"
                                            class="p-2.5 text-rose-500 hover:text-white hover:bg-rose-500 rounded-xl transition-all duration-300 transform hover:scale-110 hover:-rotate-3">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                        <button @click="toggleProyecto(proyecto.IDproyecto)"
                                            class="p-2.5 text-slate-500 hover:text-teal-600 hover:bg-teal-50 rounded-xl transition-all duration-300">
                                            <svg class="w-6 h-6 transform transition-transform duration-500 ease-out"
                                                :class="{ 'rotate-180': expandedProyecto === proyecto.IDproyecto }"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Contenido expandible -->
                            <transition name="expand" mode="out-in">
                                <div v-if="expandedProyecto === proyecto.IDproyecto" class="overflow-hidden">
                                    <div class="p-6 space-y-4">
                                        <div v-for="(bloque, bloqueIndex) in proyecto.bloques" :key="bloque.IDBloque"
                                            class="animate-slideInLeft border-l-4 border-gradient-to-b from-teal-400 to-cyan-500 bg-gradient-to-r from-teal-50/50 to-cyan-50/30 rounded-r-xl p-4"
                                            :style="{ animationDelay: `${bloqueIndex * 150}ms` }">

                                            <h4 class="font-semibold text-slate-700 mb-3 flex items-center gap-2">
                                                <div class="w-2 h-2 bg-teal-400 rounded-full"></div>
                                                {{ bloque.nombre_bloque }}
                                            </h4>

                                            <div v-if="bloque.piezas && bloque.piezas.length > 0"
                                                class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                                                <div v-for="(pieza, piezaIndex) in bloque.piezas" :key="pieza.IdPieza"
                                                    class="bg-white/70 backdrop-blur-sm p-4 rounded-xl border border-teal-100 hover:border-teal-200 hover:shadow-md transition-all duration-300 transform hover:-translate-y-0.5"
                                                    :style="{ animationDelay: `${(bloqueIndex * 150) + (piezaIndex * 100)}ms` }">
                                                    <div class="space-y-2">
                                                        <p class="text-sm">
                                                            <span class="font-medium text-slate-600">Pieza:</span>
                                                            <span class="text-slate-800 ml-1">{{ pieza.pieza }}</span>
                                                        </p>
                                                        <div class="flex items-center gap-2">
                                                            <span class="font-medium text-slate-600 text-sm">Peso Teórico:</span>
                                                            <span class="px-2 py-1 bg-gradient-to-r from-cyan-100 to-teal-100 text-teal-700 rounded-lg text-sm font-medium">
                                                                {{ pieza.peso_teorico }} kg
                                                            </span>
                                                        </div>
                                                        <div class="flex items-center gap-2">
                                                            <span class="font-medium text-slate-600 text-sm">Peso Real:</span>
                                                            <span class="px-2 py-1 bg-gradient-to-r from-cyan-100 to-teal-100 text-teal-700 rounded-lg text-sm font-medium">
                                                                {{ pieza.peso_real || 'No registrado' }} kg
                                                            </span>
                                                        </div>
                                                        <div class="flex items-center gap-2">
                                                            <span class="font-medium text-slate-600 text-sm">Estado:</span>
                                                            <span :class="[
                                                                'px-2 py-1 rounded-lg text-sm font-medium',
                                                                pieza.estado === 'Fabricado'
                                                                    ? 'bg-green-100 text-green-700'
                                                                    : 'bg-yellow-100 text-yellow-700'
                                                            ]">
                                                                {{ pieza.estado || 'Pendiente' }}
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

        <!-- Modal mejorado -->
        <transition name="modal" mode="out-in">
            <div v-if="showModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">
                <div class="bg-white rounded-2xl shadow-2xl border border-teal-100 w-full max-w-md transform transition-all duration-300">
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-gradient-to-r from-teal-500 to-cyan-600 rounded-xl flex items-center justify-center">
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
                                    class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-300 bg-slate-50 focus:bg-white"
                                    :readonly="editingProyecto"
                                    :class="{ 'bg-slate-100 cursor-not-allowed': editingProyecto }"
                                    required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">
                                    Nombre del Proyecto
                                </label>
                                <input v-model="formData.nombre" type="text"
                                    class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-300 bg-slate-50 focus:bg-white"
                                    required>
                            </div>
                            <div class="flex gap-3 pt-4">
                                <button type="button" @click="closeModal"
                                    class="flex-1 px-4 py-3 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition-all duration-300 font-medium">
                                    Cancelar
                                </button>
                                <button type="submit"
                                    class="flex-1 px-4 py-3 bg-gradient-to-r from-teal-500 to-cyan-600 text-white rounded-xl hover:from-teal-600 hover:to-cyan-700 transition-all duration-300 font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                    {{ editingProyecto ? 'Actualizar' : 'Crear' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </transition>
    </AppLayout>
</template>

<style scoped>
/* Animaciones personalizadas */
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

/* Transiciones para el contenido expandible */
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

/* Transiciones para el modal */
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

/* Efectos de hover suaves */
.group:hover .group-hover\:rotate-90 {
    transform: rotate(90deg);
}

/* Gradientes personalizados */
.border-gradient-to-b {
    border-image: linear-gradient(to bottom, #14b8a6, #0891b2) 1;
}
</style>