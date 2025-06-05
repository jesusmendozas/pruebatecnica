<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, onMounted } from 'vue';
import { Chart, registerables } from 'chart.js';

// Registrar todos los componentes necesarios
Chart.register(...registerables);

const props = defineProps({
    piezasPendientes: Array,
    estadosPorProyecto: Array
});

const chartRef = ref(null);
const expandedProyectos = ref({});
const expandedBloques = ref({});

const toggleProyecto = (proyectoId) => {
    expandedProyectos.value[proyectoId] = !expandedProyectos.value[proyectoId];
};

const toggleBloque = (bloqueId) => {
    expandedBloques.value[bloqueId] = !expandedBloques.value[bloqueId];
};

const getPriorityColor = (cantidad) => {
    if (cantidad > 10) return 'bg-red-100 text-red-800';
    if (cantidad > 5) return 'bg-yellow-100 text-yellow-800';
    return 'bg-green-100 text-green-800';
};

onMounted(() => {
    const ctx = chartRef.value.getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: props.estadosPorProyecto.map(p => p.nombre_proyecto),
            datasets: [
                {
                    label: 'Piezas Pendientes',
                    data: props.estadosPorProyecto.map(p => p.pendientes),
                    backgroundColor: 'rgba(239, 68, 68, 0.5)',
                    borderColor: 'rgb(239, 68, 68)',
                    borderWidth: 1
                },
                {
                    label: 'Piezas Fabricadas',
                    data: props.estadosPorProyecto.map(p => p.fabricadas),
                    backgroundColor: 'rgba(34, 197, 94, 0.5)',
                    borderColor: 'rgb(34, 197, 94)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Cantidad de Piezas'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Proyectos'
                    }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Estado de Piezas por Proyecto',
                    font: {
                        size: 16
                    }
                },
                legend: {
                    position: 'top'
                }
            }
        }
    });
});
</script>

<template>
    <AppLayout title="Reportes">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Reportes de Producción</h2>

                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-700 mb-4">Gráfico de Estado por Proyecto</h3>
                            <div class="bg-white p-4 rounded-lg shadow">
                                <canvas ref="chartRef"></canvas>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-700 mb-4">Detalle de Piezas Pendientes</h3>
                            <div class="space-y-4">
                                <div v-for="proyecto in piezasPendientes" :key="proyecto.IDproyecto"
                                    class="bg-white rounded-lg shadow overflow-hidden">
                                    <div class="p-4 bg-gray-50 border-b flex justify-between items-center cursor-pointer"
                                        @click="toggleProyecto(proyecto.IDproyecto)">
                                        <div>
                                            <h4 class="text-lg font-semibold text-gray-800">
                                                {{ proyecto.nombre_proyecto }}
                                            </h4>
                                            <p class="text-sm text-gray-600">
                                                Total de piezas pendientes: {{ proyecto.total_pendientes }}
                                            </p>
                                        </div>
                                        <div class="flex items-center">
                                            <span :class="getPriorityColor(proyecto.total_pendientes)"
                                                class="px-3 py-1 rounded-full text-sm font-medium">
                                                {{ proyecto.total_pendientes }} pendientes
                                            </span>
                                            <svg class="w-5 h-5 ml-2 transform transition-transform"
                                                :class="{ 'rotate-180': expandedProyectos[proyecto.IDproyecto] }"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                    </div>

                                    <div v-if="expandedProyectos[proyecto.IDproyecto]" class="p-4">
                                        <div v-for="bloque in proyecto.bloques" :key="bloque.IDBloque"
                                            class="mb-4 last:mb-0">
                                            <div class="flex justify-between items-center cursor-pointer p-2 hover:bg-gray-50 rounded"
                                                @click="toggleBloque(bloque.IDBloque)">
                                                <h5 class="font-medium text-gray-700">
                                                    Bloque {{ bloque.nombre_bloque }}
                                                </h5>
                                                <div class="flex items-center">
                                                    <span :class="getPriorityColor(bloque.piezas.length)"
                                                        class="px-2 py-1 rounded-full text-xs font-medium">
                                                        {{ bloque.piezas.length }} piezas
                                                    </span>
                                                    <svg class="w-4 h-4 ml-2 transform transition-transform"
                                                        :class="{ 'rotate-180': expandedBloques[bloque.IDBloque] }"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M19 9l-7 7-7-7" />
                                                    </svg>
                                                </div>
                                            </div>

                                            <div v-if="expandedBloques[bloque.IDBloque]"
                                                class="mt-2 pl-4 border-l-2 border-gray-200">
                                                <div v-for="pieza in bloque.piezas" :key="pieza.IDPieza"
                                                    class="py-2 text-sm text-gray-600">
                                                    <div class="flex justify-between items-center">
                                                        <span>{{ pieza.nombre_pieza }}</span>
                                                        <span class="text-gray-500">
                                                            Peso teórico: {{ pieza.peso_teorico }} kg
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.transition-transform {
    transition: transform 0.2s ease-in-out;
}

.hover\:bg-gray-50:hover {
    background-color: rgba(249, 250, 251, 1);
}
</style>
