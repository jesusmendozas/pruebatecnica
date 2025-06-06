<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, computed, onMounted } from 'vue';
import { ChevronDown, Package, Building, Layers, Calendar, User, Weight, CheckCircle, Clock, Search, Filter, Plus, Edit, Trash2 } from 'lucide-vue-next';
import { useForm, router } from '@inertiajs/vue3';

const props = defineProps({
    piezas: Array,
    bloques: Array
});

const expandedPiezas = ref({});
const searchTerm = ref('');
const selectedEstado = ref('');
const showCreateModal = ref(false);
const showEditModal = ref(false);
const showDeleteModal = ref(false);
const selectedPieza = ref(null);

const createForm = useForm({
    IdPieza: '',
    pieza: '',
    peso_teorico: '',
    IDBloque: '',
});

const editForm = useForm({
    pieza: '',
    peso_teorico: '',
    peso_real: '',
    estado: '',
    IDBloque: '',
});

const openCreateModal = () => {
    createForm.reset();
    showCreateModal.value = true;
};

const openEditModal = (pieza) => {
    selectedPieza.value = pieza;
    editForm.pieza = pieza.pieza;
    editForm.peso_teorico = pieza.peso_teorico;
    editForm.peso_real = pieza.peso_real;
    editForm.estado = pieza.estado;
    editForm.IDBloque = pieza.IDBloque;
    showEditModal.value = true;
};

const openDeleteModal = (pieza) => {
    selectedPieza.value = pieza;
    showDeleteModal.value = true;
};

const submitCreate = () => {
    createForm.post(route('piezas.store'), {
        onSuccess: () => {
            showCreateModal.value = false;
            createForm.reset();
        },
    });
};

const submitEdit = () => {
    editForm.put(route('piezas.update', selectedPieza.value.IdPieza), {
        onSuccess: () => {
            showEditModal.value = false;
            selectedPieza.value = null;
        },
    });
};

const submitDelete = () => {
    router.delete(route('piezas.destroy', selectedPieza.value.IdPieza), {
        onSuccess: () => {
            showDeleteModal.value = false;
            selectedPieza.value = null;
        },
    });
};

const togglePieza = (piezaId) => {
    expandedPiezas.value[piezaId] = !expandedPiezas.value[piezaId];
};

const getEstadoConfig = (estado) => {
    switch (estado) {
        case 'Pendiente':
            return {
                color: 'bg-navy-100 text-navy-700 border-navy-200',
                icon: Clock,
                iconColor: 'text-navy-600'
            };
        case 'Fabricada':
            return {
                color: 'bg-navy-300 text-navy-900 border-navy-200',
                icon: CheckCircle,
                iconColor: 'text-navy-600'
            };
        default:
            return {
                color: 'bg-navy-100 text-navy-700 border-navy-200',
                icon: Clock,
                iconColor: 'text-navy-500'
            };
    }
};

const filteredPiezas = computed(() => {
    let filtered = props.piezas || [];

    if (searchTerm.value) {
        filtered = filtered.filter(pieza =>
            pieza.pieza.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
            pieza.bloque?.nombre_bloque.toLowerCase().includes(searchTerm.value.toLowerCase())
        );
    }

    if (selectedEstado.value) {
        filtered = filtered.filter(pieza => pieza.estado === selectedEstado.value);
    }

    return filtered;
});

const estadosUnicos = computed(() => {
    const estados = [...new Set((props.piezas || []).map(pieza => pieza.estado))];
    return estados;
});

const formatDate = (dateString) => {
    if (!dateString) return 'No registrado';
    return new Date(dateString).toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};

onMounted(() => {
    // Remove debug console.log
});
</script>

<template>
    <AppLayout title="Piezas">
        <div class="min-h-screen bg-gradient-to-br from-navy-50 to-navy-100 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header Section -->
                <div class="mb-8 animate-slide-down">
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-slate-200/50 p-8">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                            <div class="flex-1">
                                <h1 class="text-4xl font-bold bg-gradient-to-r from-slate-900 to-blue-900 bg-clip-text text-transparent mb-3">
                                    Gestión de Piezas
                                </h1>
                                <p class="text-slate-600 text-lg">
                                    Administra y supervisa el estado de todas las piezas del proyecto
                                </p>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-xl shadow-lg">
                                    <span class="font-semibold text-lg">{{ filteredPiezas.length }}</span>
                                    <span class="ml-2 text-blue-100">piezas</span>
                                </div>
                                <button @click="openCreateModal"
                                    class="group bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 ease-out transform hover:scale-105 hover:shadow-xl shadow-lg">
                                    <Plus class="w-5 h-5 inline-block mr-2 transition-transform group-hover:rotate-90 duration-300" />
                                    Nueva Pieza
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters Section -->
                <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg border border-slate-200/50 p-6 mb-8 animate-slide-up">
                    <div class="flex flex-col lg:flex-row gap-6">
                        <!-- Search -->
                        <div class="flex-1">
                            <div class="relative group">
                                <Search class="absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400 w-5 h-5 transition-colors group-focus-within:text-blue-600" />
                                <input
                                    v-model="searchTerm"
                                    type="text"
                                    placeholder="Buscar por nombre o bloque..."
                                    class="w-full pl-12 pr-6 py-4 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-slate-700 placeholder-slate-400 shadow-sm hover:shadow-md"
                                />
                            </div>
                        </div>

                        <!-- Estado Filter -->
                        <div class="lg:w-64">
                            <div class="relative group">
                                <Filter class="absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400 w-5 h-5 transition-colors group-focus-within:text-blue-600" />
                                <select
                                    v-model="selectedEstado"
                                    class="w-full pl-12 pr-6 py-4 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-slate-700 shadow-sm hover:shadow-md appearance-none"
                                >
                                    <option value="">Todos los estados</option>
                                    <option v-for="estado in estadosUnicos" :key="estado" :value="estado">
                                        {{ estado }}
                                    </option>
                                </select>
                                <ChevronDown class="absolute right-4 top-1/2 transform -translate-y-1/2 text-slate-400 w-5 h-5 pointer-events-none" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Piezas Grid -->
                <div class="space-y-6">
                    <div v-for="(pieza, index) in filteredPiezas" :key="pieza.IdPieza"
                        class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg border border-slate-200/50 hover:shadow-xl transition-all duration-500 ease-out transform hover:-translate-y-1 animate-fade-in-up"
                        :style="{ animationDelay: `${index * 100}ms` }">

                        <!-- Card Header -->
                        <div class="p-6">
                            <div class="flex items-start justify-between">
                                <div class="flex-1 cursor-pointer group" @click="togglePieza(pieza.IdPieza)">
                                    <div class="flex items-center gap-4 mb-4">
                                        <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg group-hover:shadow-xl transition-all duration-300">
                                            <Package class="w-6 h-6 text-white" />
                                        </div>
                                        <div>
                                            <h3 class="text-2xl font-bold text-slate-900 group-hover:text-blue-900 transition-colors duration-300">
                                                {{ pieza.pieza }}
                                            </h3>
                                            <div class="flex items-center gap-2 text-slate-600 mt-1">
                                                <Layers class="w-4 h-4" />
                                                <span class="font-medium">{{ pieza.bloque?.nombre_bloque }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3 ml-6">
                                    <!-- Action Buttons -->
                                    <button @click="openEditModal(pieza)"
                                        class="p-3 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all duration-300 hover:scale-110">
                                        <Edit class="w-5 h-5" />
                                    </button>
                                    <button @click="openDeleteModal(pieza)"
                                        class="p-3 text-slate-500 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all duration-300 hover:scale-110">
                                        <Trash2 class="w-5 h-5" />
                                    </button>

                                    <!-- Estado Badge -->
                                    <div :class="getEstadoConfig(pieza.estado).color"
                                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold border shadow-sm">
                                        <component :is="getEstadoConfig(pieza.estado).icon"
                                            :class="getEstadoConfig(pieza.estado).iconColor"
                                            class="w-4 h-4" />
                                        {{ pieza.estado }}
                                    </div>

                                    <!-- Expand Icon -->
                                    <button @click="togglePieza(pieza.IdPieza)"
                                        class="p-3 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all duration-300">
                                        <ChevronDown class="w-5 h-5 transform transition-transform duration-300"
                                            :class="{ 'rotate-180': expandedPiezas[pieza.IdPieza] }" />
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Expanded Content -->
                        <Transition name="expand">
                            <div v-if="expandedPiezas[pieza.IdPieza]"
                                class="border-t border-slate-100 bg-gradient-to-br from-slate-50 to-blue-50 p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                                    <!-- Peso Teórico -->
                                    <div class="bg-white rounded-xl p-6 border border-slate-200 shadow-sm hover:shadow-md transition-all duration-300 hover:scale-105">
                                        <div class="flex items-center gap-3 mb-3">
                                            <div class="p-2 bg-blue-100 rounded-lg">
                                                <Weight class="w-5 h-5 text-blue-600" />
                                            </div>
                                            <span class="text-sm font-semibold text-slate-700">Peso Teórico</span>
                                        </div>
                                        <p class="text-3xl font-bold text-slate-900">
                                            {{ pieza.peso_teorico }}
                                            <span class="text-sm font-medium text-slate-500 ml-1">kg</span>
                                        </p>
                                    </div>

                                    <!-- Peso Real -->
                                    <div class="bg-white rounded-xl p-6 border border-slate-200 shadow-sm hover:shadow-md transition-all duration-300 hover:scale-105">
                                        <div class="flex items-center gap-3 mb-3">
                                            <div class="p-2 bg-emerald-100 rounded-lg">
                                                <Weight class="w-5 h-5 text-emerald-600" />
                                            </div>
                                            <span class="text-sm font-semibold text-slate-700">Peso Real</span>
                                        </div>
                                        <p class="text-3xl font-bold text-slate-900">
                                            {{ pieza.peso_real || '--' }}
                                            <span v-if="pieza.peso_real" class="text-sm font-medium text-slate-500 ml-1">kg</span>
                                            <span v-else class="text-sm font-medium text-slate-400 ml-1">No registrado</span>
                                        </p>
                                    </div>

                                    <!-- Fecha de Registro -->
                                    <div class="bg-white rounded-xl p-6 border border-slate-200 shadow-sm hover:shadow-md transition-all duration-300 hover:scale-105">
                                        <div class="flex items-center gap-3 mb-3">
                                            <div class="p-2 bg-purple-100 rounded-lg">
                                                <Calendar class="w-5 h-5 text-purple-600" />
                                            </div>
                                            <span class="text-sm font-semibold text-slate-700">Fecha de Registro</span>
                                        </div>
                                        <p class="text-xl font-bold text-slate-900">
                                            {{ formatDate(pieza.fecha_registro) }}
                                        </p>
                                    </div>

                                    <!-- Registrado Por -->
                                    <div class="bg-white rounded-xl p-6 border border-slate-200 shadow-sm hover:shadow-md transition-all duration-300 hover:scale-105">
                                        <div class="flex items-center gap-3 mb-3">
                                            <div class="p-2 bg-indigo-100 rounded-lg">
                                                <User class="w-5 h-5 text-indigo-600" />
                                            </div>
                                            <span class="text-sm font-semibold text-slate-700">Registrado Por</span>
                                        </div>
                                        <p class="text-xl font-bold text-slate-900">
                                            {{ pieza.registrado_por }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Diferencia de Peso -->
                                <div v-if="pieza.peso_real" class="mt-6 p-6 bg-white rounded-xl border border-slate-200 shadow-sm">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="p-2 bg-blue-100 rounded-lg">
                                                <Weight class="w-5 h-5 text-blue-600" />
                                            </div>
                                            <span class="text-lg font-semibold text-slate-800">Diferencia de Peso:</span>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-2xl font-bold"
                                                :class="(pieza.peso_real - pieza.peso_teorico) >= 0 ? 'text-red-600' : 'text-emerald-600'">
                                                {{ (pieza.peso_real - pieza.peso_teorico) >= 0 ? '+' : '' }}{{ (pieza.peso_real - pieza.peso_teorico).toFixed(2) }} kg
                                            </span>
                                            <p v-if="(pieza.peso_real - pieza.peso_teorico) >= 0" class="text-sm text-red-500">Exceso de peso</p>
                                            <p v-else class="text-sm text-emerald-500">Ahorro de peso</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </Transition>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-if="filteredPiezas.length === 0" class="text-center py-16 animate-fade-in">
                    <div class="max-w-md mx-auto">
                        <div class="p-6 bg-gradient-to-br from-slate-100 to-blue-100 rounded-full w-32 h-32 mx-auto mb-6 flex items-center justify-center">
                            <Package class="w-16 h-16 text-slate-400" />
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-3">No se encontraron piezas</h3>
                        <p class="text-slate-600 text-lg">
                            {{ searchTerm || selectedEstado ? 'Intenta ajustar los filtros de búsqueda' : 'No hay piezas registradas en el sistema' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modals (Create, Edit, Delete) with improved styling -->
        <!-- Create Modal -->
        <Transition name="modal">
            <div v-if="showCreateModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4 z-50">
                <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
                    <div class="p-8">
                        <h3 class="text-2xl font-bold text-slate-900 mb-6">Nueva Pieza</h3>
                        <form @submit.prevent="submitCreate" class="space-y-6">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">ID Pieza</label>
                                <input v-model="createForm.IdPieza" type="text" required
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Nombre</label>
                                <input v-model="createForm.pieza" type="text" required
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Peso Teórico (kg)</label>
                                <input v-model="createForm.peso_teorico" type="number" step="0.01" required
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Bloque</label>
                                <select v-model="createForm.IDBloque" required
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300">
                                    <option value="">Seleccione un bloque</option>
                                    <option v-for="bloque in bloques" :key="bloque.IDBloque" :value="bloque.IDBloque">
                                        {{ bloque.nombre_bloque }}
                                    </option>
                                </select>
                            </div>
                            <div class="flex justify-end gap-4 pt-4">
                                <button type="button" @click="showCreateModal = false"
                                    class="px-6 py-3 text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl font-semibold transition-all duration-300">
                                    Cancelar
                                </button>
                                <button type="submit"
                                    class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-xl font-semibold transition-all duration-300 transform hover:scale-105">
                                    Crear Pieza
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </Transition>

        <!-- Edit Modal -->
        <Transition name="modal">
            <div v-if="showEditModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4 z-50">
                <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
                    <div class="p-8">
                        <h3 class="text-2xl font-bold text-slate-900 mb-6">Editar Pieza</h3>
                        <form @submit.prevent="submitEdit" class="space-y-6">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Nombre</label>
                                <input v-model="editForm.pieza" type="text" required
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Peso Teórico (kg)</label>
                                    <input v-model="editForm.peso_teorico" type="number" step="0.01" required
                                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Peso Real (kg)</label>
                                    <input v-model="editForm.peso_real" type="number" step="0.01"
                                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Estado</label>
                                    <select v-model="editForm.estado" required
                                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300">
                                        <option value="Pendiente">Pendiente</option>
                                        <option value="Fabricada">Fabricada</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Bloque</label>
                                    <select v-model="editForm.IDBloque" required
                                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300">
                                        <option value="">Seleccione un bloque</option>
                                        <option v-for="bloque in bloques" :key="bloque.IDBloque" :value="bloque.IDBloque">
                                            {{ bloque.nombre_bloque }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="flex justify-end gap-4 pt-4">
                                <button type="button" @click="showEditModal = false"
                                    class="px-6 py-3 text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl font-semibold transition-all duration-300">
                                    Cancelar
                                </button>
                                <button type="submit"
                                    class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-xl font-semibold transition-all duration-300 transform hover:scale-105">
                                    Guardar Cambios
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </Transition>

        <!-- Delete Modal -->
        <Transition name="modal">
            <div v-if="showDeleteModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4 z-50">
                <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full">
                    <div class="p-8">
                        <div class="text-center mb-6">
                            <div class="p-4 bg-red-100 rounded-full w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                                <Trash2 class="w-8 h-8 text-red-600" />
                            </div>
                            <h3 class="text-2xl font-bold text-slate-900 mb-2">Eliminar Pieza</h3>
                            <p class="text-slate-600 text-lg">
                                ¿Estás seguro de que deseas eliminar la pieza
                                <span class="font-semibold text-slate-900">"{{ selectedPieza?.pieza }}"</span>?
                            </p>
                            <p class="text-sm text-red-600 mt-2">Esta acción no se puede deshacer.</p>
                        </div>
                        <div class="flex justify-center gap-4">
                            <button type="button" @click="showDeleteModal = false"
                                class="px-6 py-3 text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl font-semibold transition-all duration-300">
                                Cancelar
                            </button>
                            <button type="button" @click="submitDelete"
                                class="px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white rounded-xl font-semibold transition-all duration-300 transform hover:scale-105">
                                Eliminar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </AppLayout>
</template>

<style scoped>
/* Animations */
@keyframes fade-in {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes slide-down {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slide-up {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fade-in-up {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.6s ease-out;
}

.animate-slide-down {
    animation: slide-down 0.8s ease-out;
}

.animate-slide-up {
    animation: slide-up 0.6s ease-out;
}

.animate-fade-in-up {
    animation: fade-in-up 0.8s ease-out;
    animation-fill-mode: both;
}

/* Expand transition */
.expand-enter-active,
.expand-leave-active {
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden;
}

.expand-enter-from,
.expand-leave-to {
    max-height: 0;
    opacity: 0;
    transform: translateY(-10px);
}

.expand-enter-to,
.expand-leave-from {
    max-height: 1000px;
    opacity: 1;
    transform: translateY(0);
}

/* Modal transitions */
.modal-enter-active,
.modal-leave-active {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
    transform: scale(0.95);
}

.modal-enter-to,
.modal-leave-from {
    opacity: 1;
    transform: scale(1);
}

/* Smooth transitions */
.transition-all {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Hover effects */
.hover\:scale-105:hover {
    transform: scale(1.05);
}

.hover\:scale-110:hover {
    transform: scale(1.1);
}

/* Glass morphism effects */
.backdrop-blur-sm {
    backdrop-filter: blur(4px);
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(to bottom, #0070c2, #005a9e);
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(to bottom, #005a9e, #004b83);
}

/* Focus styles */
.focus\:ring-2:focus {
    box-shadow: 0 0 0 2px rgba(0, 112, 194, 0.2);
}

/* Selection styles */
::selection {
    background-color: rgba(0, 112, 194, 0.2);
    color: #005a9e;
}

/* Responsive grid improvements */
@media (max-width: 768px) {
    .grid-cols-1.md\:grid-cols-2.xl\:grid-cols-4 {
        grid-template-columns: repeat(1, minmax(0, 1fr));
        gap: 1rem;
    }
}

@media (min-width: 768px) and (max-width: 1279px) {
    .grid-cols-1.md\:grid-cols-2.xl\:grid-cols-4 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1.25rem;
    }
}

@media (min-width: 1280px) {
    .grid-cols-1.md\:grid-cols-2.xl\:grid-cols-4 {
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1.5rem;
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

/* Gradientes y fondos */
.bg-navy-gradient {
    background: linear-gradient(135deg, #0070c2 0%, #005a9e 100%);
}

.bg-navy-light {
    background-color: #f0f7ff;
}

/* Botones */
.btn-navy {
    @apply bg-navy-600 hover:bg-navy-700 text-white transition-colors duration-200;
}

/* Bordes y sombras */
.border-navy {
    border-color: #0070c2;
}

.shadow-navy {
    box-shadow: 0 4px 6px -1px rgba(0, 112, 194, 0.1), 0 2px 4px -1px rgba(0, 112, 194, 0.06);
}
</style>
