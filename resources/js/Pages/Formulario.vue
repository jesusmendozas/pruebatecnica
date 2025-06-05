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

        const data = {
            pieza_id: piezaSeleccionada.value.IdPieza,
            peso_real: pesoReal.value,
            usuario: props.usuario
        };

        await router.post(route('formulario.registrar'), data);
        alert('Pieza registrada exitosamente');

        // Limpiar el formulario
        selectedProyecto.value = '';
        selectedBloque.value = '';
        selectedPieza.value = '';
        pesoReal.value = '';
    } catch (error) {
        alert('Error al registrar la pieza: ' + (error.message || 'Error desconocido'));
    }
};
</script>

<template>
    <AppLayout>
        <div class="container mx-auto p-6">
            <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md">
                <div class="px-6 py-4 bg-gray-50 border-b rounded-t-lg">
                    <h2 class="text-xl font-semibold text-gray-800">Formulario</h2>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Select de Proyectos -->
                        <div>
                            <label for="proyecto" class="block text-sm font-medium text-gray-700 mb-2">
                                Proyecto
                            </label>
                            <select
                                id="proyecto"
                                v-model="selectedProyecto"
                                @change="handleProyectoChange"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
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
                        </div>

                        <!-- Select de Bloques -->
                        <div>
                            <label for="bloque" class="block text-sm font-medium text-gray-700 mb-2">
                                Bloque
                            </label>
                            <select
                                id="bloque"
                                v-model="selectedBloque"
                                @change="handleBloqueChange"
                                :disabled="!selectedProyecto"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-gray-100"
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
                        </div>

                        <!-- Select de Piezas -->
                        <div>
                            <label for="pieza" class="block text-sm font-medium text-gray-700 mb-2">
                                Pieza
                            </label>
                            <select
                                id="pieza"
                                v-model="selectedPieza"
                                :disabled="!selectedBloque"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-gray-100"
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
                        </div>
                    </div>

                    <!-- Sección de Detalles de la Pieza -->
                    <div v-if="piezaSeleccionada" class="mt-8 p-6 bg-gray-50 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Detalles de la Pieza</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Peso Teórico -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Peso Teórico (kg)
                                </label>
                                <input
                                    type="text"
                                    :value="piezaSeleccionada.peso_teorico || 'No disponible'"
                                    disabled
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-700 font-medium"
                                />
                            </div>

                            <!-- Peso Real -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Peso Real (kg)
                                </label>
                                <input
                                    type="number"
                                    v-model="pesoReal"
                                    step="0.01"
                                    min="0"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    :placeholder="piezaSeleccionada.peso_real ? 'Peso real actual: ' + piezaSeleccionada.peso_real : 'Ingrese el peso real'"
                                />
                            </div>

                            <!-- Diferencia -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Diferencia (kg)
                                </label>
                                <input
                                    type="text"
                                    :value="diferenciaPeso !== null ? diferenciaPeso : 'No disponible'"
                                    disabled
                                    :class="[
                                        'w-full px-3 py-2 border rounded-md font-medium',
                                        diferenciaPeso !== null ?
                                            (parseFloat(diferenciaPeso) > 0 ? 'bg-green-100 text-green-700 border-green-300' :
                                             parseFloat(diferenciaPeso) < 0 ? 'bg-red-100 text-red-700 border-red-300' :
                                             'bg-gray-100 text-gray-700 border-gray-300')
                                            : 'bg-gray-100 text-gray-700 border-gray-300'
                                    ]"
                                />
                            </div>
                        </div>

                        <!-- Botón de Registro -->
                        <div class="mt-6 flex justify-end">
                            <button
                                @click="registrarPieza"
                                :disabled="isSaving || !pesoReal"
                                class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <span v-if="isSaving">Registrando...</span>
                                <span v-else>Registrar Pieza</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
