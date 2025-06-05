<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, computed } from 'vue';
import { ChevronDown, Package, Building, Layers, Calendar, User, Weight, CheckCircle, Clock, Search, Filter, Plus, Edit, Trash2 } from 'lucide-vue-next';
import { useForm } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { route } from '@inertiajs/vue3';

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
                color: 'bg-amber-50 text-amber-700 border-amber-200',
                icon: Clock,
                iconColor: 'text-amber-500'
            };
        case 'Fabricada':
            return {
                color: 'bg-emerald-50 text-emerald-700 border-emerald-200',
                icon: CheckCircle,
                iconColor: 'text-emerald-500'
            };
        default:
            return {
                color: 'bg-gray-50 text-gray-700 border-gray-200',
                icon: Clock,
                iconColor: 'text-gray-500'
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
</script>

<template>
    <AppLayout title="Piezas">
        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header Section -->
                <div class="mb-8">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">Gestión de Piezas</h1>
                            <p class="mt-2 text-sm text-gray-600">
                                Administra y supervisa el estado de todas las piezas del proyecto
                            </p>
                        </div>
                        <div class="mt-4 sm:mt-0 flex items-center gap-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                {{ filteredPiezas.length }} piezas
                            </span>
                            <button @click="openCreateModal"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <Plus class="w-4 h-4 mr-2" />
                                Nueva Pieza
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Filters Section -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <!-- Search -->
                        <div class="flex-1">
                            <div class="relative">
                                <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5" />
                                <input
                                    v-model="searchTerm"
                                    type="text"
                                    placeholder="Buscar por nombre o bloque..."
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                />
                            </div>
                        </div>

                        <!-- Estado Filter -->
                        <div class="sm:w-48">
                            <div class="relative">
                                <Filter class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5" />
                                <select
                                    v-model="selectedEstado"
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent appearance-none bg-white"
                                >
                                    <option value="">Todos los estados</option>
                                    <option v-for="estado in estadosUnicos" :key="estado" :value="estado">
                                        {{ estado }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Piezas Grid -->
                <div class="grid gap-6">
                    <div v-for="pieza in filteredPiezas" :key="pieza.IdPieza"
                        class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-all duration-200">
                        <!-- Card Header -->
                        <div class="p-6">
                            <div class="flex items-start justify-between">
                                <div class="flex-1 cursor-pointer" @click="togglePieza(pieza.IdPieza)">
                                    <div class="flex items-center gap-3 mb-3">
                                        <Package class="w-6 h-6 text-blue-600" />
                                        <h3 class="text-xl font-semibold text-gray-900">
                                            {{ pieza.pieza }}
                                        </h3>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                                        <div class="flex items-center gap-2 text-sm text-gray-600">
                                            <Layers class="w-4 h-4" />
                                            <span class="font-medium">Bloque:</span>
                                            <span>{{ pieza.bloque?.nombre_bloque }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3 ml-4">
                                    <!-- Action Buttons -->
                                    <button @click="openEditModal(pieza)"
                                        class="p-2 text-gray-600 hover:text-blue-600 transition-colors">
                                        <Edit class="w-5 h-5" />
                                    </button>
                                    <button @click="openDeleteModal(pieza)"
                                        class="p-2 text-gray-600 hover:text-red-600 transition-colors">
                                        <Trash2 class="w-5 h-5" />
                                    </button>
                                    <!-- Estado Badge -->
                                    <div :class="getEstadoConfig(pieza.estado).color"
                                        class="inline-flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium border">
                                        <component :is="getEstadoConfig(pieza.estado).icon"
                                            :class="getEstadoConfig(pieza.estado).iconColor"
                                            class="w-4 h-4" />
                                        {{ pieza.estado }}
                                    </div>

                                    <!-- Expand Icon -->
                                    <ChevronDown class="w-5 h-5 text-gray-400 transform transition-transform duration-200"
                                        :class="{ 'rotate-180': expandedPiezas[pieza.IdPieza] }" />
                                </div>
                            </div>
                        </div>

                        <!-- Expanded Content -->
                        <div v-if="expandedPiezas[pieza.IdPieza]"
                            class="border-t border-gray-100 bg-gray-50 p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                <!-- Peso Teórico -->
                                <div class="bg-white rounded-lg p-4 border border-gray-200">
                                    <div class="flex items-center gap-2 mb-2">
                                        <Weight class="w-5 h-5 text-blue-600" />
                                        <span class="text-sm font-medium text-gray-700">Peso Teórico</span>
                                    </div>
                                    <p class="text-2xl font-bold text-gray-900">
                                        {{ pieza.peso_teorico }}
                                        <span class="text-sm font-normal text-gray-500">kg</span>
                                    </p>
                                </div>

                                <!-- Peso Real -->
                                <div class="bg-white rounded-lg p-4 border border-gray-200">
                                    <div class="flex items-center gap-2 mb-2">
                                        <Weight class="w-5 h-5 text-green-600" />
                                        <span class="text-sm font-medium text-gray-700">Peso Real</span>
                                    </div>
                                    <p class="text-2xl font-bold text-gray-900">
                                        {{ pieza.peso_real || '--' }}
                                        <span v-if="pieza.peso_real" class="text-sm font-normal text-gray-500">kg</span>
                                        <span v-else class="text-sm font-normal text-gray-400">No registrado</span>
                                    </p>
                                </div>

                                <!-- Fecha de Registro -->
                                <div class="bg-white rounded-lg p-4 border border-gray-200">
                                    <div class="flex items-center gap-2 mb-2">
                                        <Calendar class="w-5 h-5 text-purple-600" />
                                        <span class="text-sm font-medium text-gray-700">Fecha de Registro</span>
                                    </div>
                                    <p class="text-lg font-semibold text-gray-900">
                                        {{ formatDate(pieza.fecha_registro) }}
                                    </p>
                                </div>

                                <!-- Registrado Por -->
                                <div class="bg-white rounded-lg p-4 border border-gray-200">
                                    <div class="flex items-center gap-2 mb-2">
                                        <User class="w-5 h-5 text-indigo-600" />
                                        <span class="text-sm font-medium text-gray-700">Registrado Por</span>
                                    </div>
                                    <p class="text-lg font-semibold text-gray-900">
                                        {{ pieza.registrado_por }}
                                    </p>
                                </div>
                            </div>

                            <!-- Diferencia de Peso (si existe peso real) -->
                            <div v-if="pieza.peso_real" class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-blue-800">Diferencia de Peso:</span>
                                    <span class="text-lg font-bold"
                                        :class="(pieza.peso_real - pieza.peso_teorico) >= 0 ? 'text-red-600' : 'text-green-600'">
                                        {{ (pieza.peso_real - pieza.peso_teorico) >= 0 ? '+' : '' }}{{ (pieza.peso_real - pieza.peso_teorico).toFixed(2) }} kg
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-if="filteredPiezas.length === 0" class="text-center py-12">
                    <Package class="w-16 h-16 text-gray-300 mx-auto mb-4" />
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No se encontraron piezas</h3>
                    <p class="text-gray-500">
                        {{ searchTerm || selectedEstado ? 'Intenta ajustar los filtros de búsqueda' : 'No hay piezas registradas en el sistema' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Create Modal -->
        <div v-if="showCreateModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center">
            <div class="bg-white rounded-lg p-6 max-w-md w-full">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Nueva Pieza</h3>
                <form @submit.prevent="submitCreate">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">ID Pieza</label>
                            <input v-model="createForm.IdPieza" type="text" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre</label>
                            <input v-model="createForm.pieza" type="text" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Peso Teórico (kg)</label>
                            <input v-model="createForm.peso_teorico" type="number" step="0.01" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Bloque</label>
                            <select v-model="createForm.IDBloque" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Seleccione un bloque</option>
                                <option v-for="bloque in bloques" :key="bloque.IDBloque" :value="bloque.IDBloque">
                                    {{ bloque.nombre_bloque }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" @click="showCreateModal = false"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Crear Pieza
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Modal -->
        <div v-if="showEditModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center">
            <div class="bg-white rounded-lg p-6 max-w-md w-full">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Editar Pieza</h3>
                <form @submit.prevent="submitEdit">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre</label>
                            <input v-model="editForm.pieza" type="text" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Peso Teórico (kg)</label>
                            <input v-model="editForm.peso_teorico" type="number" step="0.01" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Peso Real (kg)</label>
                            <input v-model="editForm.peso_real" type="number" step="0.01"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Estado</label>
                            <select v-model="editForm.estado" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="Pendiente">Pendiente</option>
                                <option value="Fabricada">Fabricada</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Bloque</label>
                            <select v-model="editForm.IDBloque" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Seleccione un bloque</option>
                                <option v-for="bloque in bloques" :key="bloque.IDBloque" :value="bloque.IDBloque">
                                    {{ bloque.nombre_bloque }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" @click="showEditModal = false"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Modal -->
        <div v-if="showDeleteModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center">
            <div class="bg-white rounded-lg p-6 max-w-md w-full">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Eliminar Pieza</h3>
                <p class="text-sm text-gray-500 mb-4">
                    ¿Estás seguro de que deseas eliminar la pieza "{{ selectedPieza?.pieza }}"? Esta acción no se puede deshacer.
                </p>
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" @click="showDeleteModal = false"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancelar
                    </button>
                    <button type="button" @click="submitDelete"
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.transition-all {
    transition: all 0.2s ease-in-out;
}
</style>
