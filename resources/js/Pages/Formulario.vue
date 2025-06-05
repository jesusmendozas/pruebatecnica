<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, computed, watch } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    proyectos: Array,
    bloques: Array,
    piezas: Array,
    usuario: Object,
});

const selectedProyecto = ref('');
const selectedBloque = ref('');
const selectedPieza = ref('');
const pesoReal = ref('');
const isSaving = ref(false);
const showAlert = ref(false);

const bloquesFiltrados = computed(() => {
    if (!selectedProyecto.value) return [];
    return props.bloques.filter(bloque => String(bloque.IDproyecto) === String(selectedProyecto.value));
});

const piezasFiltradas = computed(() => {
    if (!selectedBloque.value) return [];
    return props.piezas.filter(pieza => String(pieza.IDBloque) === String(selectedBloque.value));
});

const piezaSeleccionada = computed(() => {
    if (!selectedPieza.value) return null;
    const pieza = props.piezas.find(pieza => String(pieza.IdPieza) === String(selectedPieza.value));
    if (pieza && pieza.peso_real) {
        pesoReal.value = pieza.peso_real;
    }
    return pieza;
});

const diferenciaPeso = computed(() => {
    if (!piezaSeleccionada.value || !pesoReal.value) return null;
    const teorico = parseFloat(piezaSeleccionada.value.peso_teorico);
    const real = parseFloat(pesoReal.value);
    if (isNaN(teorico) || isNaN(real)) return null;
    return (real - teorico).toFixed(2);
});

const handleProyectoChange = () => {
    selectedBloque.value = '';
    selectedPieza.value = '';
    pesoReal.value = '';
};

const handleBloqueChange = () => {
    selectedPieza.value = '';
    pesoReal.value = '';
};

watch(selectedPieza, (newValue) => {
    if (newValue && piezaSeleccionada.value) {
        pesoReal.value = piezaSeleccionada.value.peso_real || '';
    } else {
        pesoReal.value = '';
    }
});

const registrarPieza = async () => {
    try {
        if (!piezaSeleccionada.value) {
            alert('Por favor seleccione una pieza');
            return;
        }

        if (!pesoReal.value) {
            alert('Por favor ingrese el peso real');
            return;
        }

        isSaving.value = true;

        const data = {
            id_pieza: piezaSeleccionada.value.IdPieza,
            peso_real: pesoReal.value
        };

        await router.post(route('formulario.store'), data);

        // Show success alert
        showAlert.value = true;
        setTimeout(() => {
            showAlert.value = false;
        }, 3000);

        // Limpiar el formulario
        selectedProyecto.value = '';
        selectedBloque.value = '';
        selectedPieza.value = '';
        pesoReal.value = '';
    } catch (error) {
        console.error('Error completo:', error);
        alert('Error al registrar la pieza: ' + (error.message || 'Error desconocido'));
    } finally {
        isSaving.value = false;
    }
};
</script>

<template>
    <AppLayout>
        <!-- Success Alert -->
        <Transition
            enter-active-class="transition duration-500 ease-out"
            enter-from-class="transform translate-x-full opacity-0"
            enter-to-class="transform translate-x-0 opacity-100"
            leave-active-class="transition duration-300 ease-in"
            leave-from-class="transform translate-x-0 opacity-100"
            leave-to-class="transform translate-x-full opacity-0"
        >
            <div v-if="showAlert" class="fixed top-4 right-4 z-50">
                <div class="bg-white rounded-xl shadow-2xl p-6 max-w-sm transform transition-all border-l-4 border-green-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 rounded-full bg-gradient-to-r from-green-400 to-emerald-500 flex items-center justify-center">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">¡Registro Exitoso!</h3>
                            <p class="text-sm text-gray-600">Los datos han sido registrados correctamente</p>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>

        <div class="min-h-screen bg-gradient-to-br from-slate-50 to-cyan-50 py-8 px-4">
            <div class="max-w-6xl mx-auto">


                <div class="bg-white rounded-2xl shadow-xl overflow-hidden transform transition-all duration-500 hover:shadow-2xl">
                    <div class="bg-gradient-to-r from-cyan-600 to-teal-700 px-8 py-6">
                        <h2 class="text-2xl font-semibold text-white flex items-center">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Selección de Componentes
                        </h2>
                    </div>

                    <!-- Selection Form -->
                    <div class="p-8">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            <!-- Proyecto Select -->
                            <div class="transform transition-all duration-300 hover:scale-105">
                                <label for="proyecto" class="block text-sm font-semibold text-slate-700 mb-3">
                                    Proyecto
                                </label>
                                <div class="relative">
                                    <select
                                        id="proyecto"
                                        v-model="selectedProyecto"
                                        @change="handleProyectoChange"
                                        class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-cyan-100 focus:border-cyan-500 transition-all duration-300 bg-white hover:border-cyan-300 text-slate-700 font-medium"
                                    >
                                        <option value="">Seleccione un proyecto</option>
                                        <option
                                            v-for="proyecto in proyectos"
                                            :key="proyecto.IDproyecto"
                                            :value="proyecto.IDproyecto"
                                        >
                                            {{ proyecto.nombre }}
                                        </option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Bloque Select -->
                            <div class="transform transition-all duration-300 hover:scale-105">
                                <label for="bloque" class="block text-sm font-semibold text-slate-700 mb-3">
                                    Bloque
                                </label>
                                <div class="relative">
                                    <select
                                        id="bloque"
                                        v-model="selectedBloque"
                                        @change="handleBloqueChange"
                                        :disabled="!selectedProyecto"
                                        class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-cyan-100 focus:border-cyan-500 transition-all duration-300 bg-white hover:border-cyan-300 text-slate-700 font-medium disabled:bg-slate-100 disabled:text-slate-400 disabled:cursor-not-allowed"
                                    >
                                        <option value="">Seleccione un bloque</option>
                                        <option
                                            v-for="bloque in bloquesFiltrados"
                                            :key="bloque.IDBloque"
                                            :value="bloque.IDBloque"
                                        >
                                            {{ bloque.nombre_bloque }}
                                        </option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Pieza Select -->
                            <div class="transform transition-all duration-300 hover:scale-105">
                                <label for="pieza" class="block text-sm font-semibold text-slate-700 mb-3">
                                    Pieza
                                </label>
                                <div class="relative">
                                    <select
                                        id="pieza"
                                        v-model="selectedPieza"
                                        :disabled="!selectedBloque"
                                        class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-cyan-100 focus:border-cyan-500 transition-all duration-300 bg-white hover:border-cyan-300 text-slate-700 font-medium disabled:bg-slate-100 disabled:text-slate-400 disabled:cursor-not-allowed"
                                    >
                                        <option value="">Seleccione una pieza</option>
                                        <option
                                            v-for="pieza in piezasFiltradas"
                                            :key="pieza.IdPieza"
                                            :value="pieza.IdPieza"
                                        >
                                            {{ pieza.pieza }}
                                        </option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Details Card -->
                <div v-if="piezaSeleccionada" class="mt-8 bg-white rounded-2xl shadow-xl overflow-hidden transform transition-all duration-500 ease-out animate-slide-up">
                    <!-- Details Header -->
                    <div class="bg-gradient-to-r from-teal-600 to-cyan-700 px-8 py-6">
                        <h3 class="text-2xl font-semibold text-white flex items-center">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Detalles de la Pieza
                        </h3>
                    </div>

                    <div class="p-8">
                        <!-- Weight Information Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8 mb-8">
                            <!-- Peso Teórico -->
                            <div class="bg-gradient-to-br from-slate-50 to-slate-100 rounded-xl p-6 transform transition-all duration-300 hover:scale-105 hover:shadow-lg">
                                <label class="block text-sm font-semibold text-slate-700 mb-3 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                    Peso Teórico (kg)
                                </label>
                                <div class="text-3xl font-bold text-slate-800">
                                    {{ piezaSeleccionada.peso_teorico || 'N/A' }}
                                </div>
                                <div class="text-sm text-slate-500 mt-1">Peso calculado</div>
                            </div>

                            <!-- Peso Real Input -->
                            <div class="bg-gradient-to-br from-cyan-50 to-teal-50 rounded-xl p-6 transform transition-all duration-300 hover:scale-105 hover:shadow-lg">
                                <label class="block text-sm font-semibold text-slate-700 mb-3 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16l3-1m-3 1l-3-1"></path>
                                    </svg>
                                    Peso Real (kg)
                                </label>
                                <input
                                    type="number"
                                    v-model="pesoReal"
                                    step="0.01"
                                    min="0"
                                    class="w-full px-4 py-3 text-2xl font-bold border-2 border-cyan-200 rounded-lg focus:outline-none focus:ring-4 focus:ring-cyan-100 focus:border-cyan-500 transition-all duration-300 bg-white hover:border-cyan-300 text-slate-800"
                                    :placeholder="piezaSeleccionada.peso_real ? 'Actual: ' + piezaSeleccionada.peso_real : 'Ingrese peso'"
                                />
                                <div class="text-sm text-slate-500 mt-1">Medición actual</div>
                            </div>

                            <!-- Diferencia -->
                            <div class="md:col-span-2 xl:col-span-1">
                                <div :class="[
                                    'rounded-xl p-6 transform transition-all duration-300 hover:scale-105 hover:shadow-lg',
                                    diferenciaPeso !== null ?
                                        (parseFloat(diferenciaPeso) > 0 ? 'bg-gradient-to-br from-emerald-50 to-green-50' :
                                         parseFloat(diferenciaPeso) < 0 ? 'bg-gradient-to-br from-red-50 to-rose-50' :
                                         'bg-gradient-to-br from-slate-50 to-gray-50')
                                        : 'bg-gradient-to-br from-slate-50 to-gray-50'
                                ]">
                                    <label class="block text-sm font-semibold text-slate-700 mb-3 flex items-center">
                                        <svg class="w-4 h-4 mr-2" :class="[
                                            diferenciaPeso !== null ?
                                                (parseFloat(diferenciaPeso) > 0 ? 'text-emerald-600' :
                                                 parseFloat(diferenciaPeso) < 0 ? 'text-red-600' :
                                                 'text-slate-500')
                                                : 'text-slate-500'
                                        ]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                        </svg>
                                        Diferencia (kg)
                                    </label>
                                    <div :class="[
                                        'text-3xl font-bold',
                                        diferenciaPeso !== null ?
                                            (parseFloat(diferenciaPeso) > 0 ? 'text-emerald-700' :
                                             parseFloat(diferenciaPeso) < 0 ? 'text-red-700' :
                                             'text-slate-700')
                                            : 'text-slate-700'
                                    ]">
                                        {{ diferenciaPeso !== null ? (parseFloat(diferenciaPeso) > 0 ? '+' : '') + diferenciaPeso : 'N/A' }}
                                    </div>
                                    <div :class="[
                                        'text-sm mt-1',
                                        diferenciaPeso !== null ?
                                            (parseFloat(diferenciaPeso) > 0 ? 'text-emerald-600' :
                                             parseFloat(diferenciaPeso) < 0 ? 'text-red-600' :
                                             'text-slate-500')
                                            : 'text-slate-500'
                                    ]">
                                        {{ diferenciaPeso !== null ?
                                            (parseFloat(diferenciaPeso) > 0 ? 'Sobrepeso' :
                                             parseFloat(diferenciaPeso) < 0 ? 'Peso inferior' :
                                             'Peso exacto')
                                            : 'Calculando...' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <div class="flex justify-center">
                            <button
                                @click="registrarPieza"
                                :disabled="isSaving || !pesoReal"
                                class="group relative px-8 py-4 bg-gradient-to-r from-cyan-600 to-teal-700 text-white font-semibold rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-cyan-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                            >
                                <span class="flex items-center">
                                    <svg v-if="!isSaving" class="w-5 h-5 mr-2 transition-transform duration-300 group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <svg v-else class="w-5 h-5 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    {{ isSaving ? 'Registrando...' : 'Registrar Pieza' }}
                                </span>
                                <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-teal-600 to-cyan-700 opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
@keyframes fade-in {
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
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.7s ease-out;
}

.animate-slide-up {
    animation: slide-up 0.5s ease-out;
}

.animate-bounce {
    animation: bounce 1s infinite;
}

@keyframes bounce {
    0%, 100% {
        transform: translateY(-5%);
        animation-timing-function: cubic-bezier(0.8, 0, 1, 1);
    }
    50% {
        transform: translateY(0);
        animation-timing-function: cubic-bezier(0, 0, 0.2, 1);
    }
}
</style>
