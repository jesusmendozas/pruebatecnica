<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, onMounted, nextTick, watch, computed } from 'vue';
import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

const props = defineProps({
    piezasPendientes: Array,
    estadosPorProyecto: Array
});

const chartRef = ref(null);
const chartInstance = ref(null);
const expandedProyectos = ref({});
const expandedBloques = ref({});
const isChartReady = ref(false);
const searchTerm = ref('');
const currentPage = ref(1);
const itemsPerPage = 12;

// Computed para filtrar y paginar
const filteredProyectos = computed(() => {
    if (!props.piezasPendientes) return [];

    return props.piezasPendientes.filter(proyecto => {
        return proyecto.nombre_proyecto.toLowerCase().includes(searchTerm.value.toLowerCase());
    });
});

const paginatedProyectos = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return filteredProyectos.value.slice(start, end);
});

const totalPages = computed(() => {
    return Math.ceil(filteredProyectos.value.length / itemsPerPage);
});

const proyectoOptions = computed(() => {
    if (!props.piezasPendientes) return [];
    return props.piezasPendientes.map(p => ({
        value: p.IDproyecto,
        label: p.nombre_proyecto
    }));
});

const totalPiezasPendientes = computed(() => {
    return filteredProyectos.value.reduce((total, proyecto) => total + proyecto.total_pendientes, 0);
});

const totalPiezasFabricadas = computed(() => {
    if (!props.estadosPorProyecto) return 0;
    return props.estadosPorProyecto.reduce((total, proyecto) => total + (proyecto.fabricadas || 0), 0);
});

const toggleProyecto = (proyectoId) => {
    expandedProyectos.value[proyectoId] = !expandedProyectos.value[proyectoId];
};

const toggleBloque = (bloqueId) => {
    expandedBloques.value[bloqueId] = !expandedBloques.value[bloqueId];
};

const getPriorityColor = (cantidad) => {
    if (cantidad > 10) return 'bg-gradient-to-r from-red-100 to-red-200 text-red-800 border-red-300';
    if (cantidad > 5) return 'bg-gradient-to-r from-amber-100 to-amber-200 text-amber-800 border-amber-300';
    return 'bg-gradient-to-r from-emerald-100 to-emerald-200 text-emerald-800 border-emerald-300';
};

const clearFilters = () => {
    searchTerm.value = '';
    currentPage.value = 1;
};

const goToPage = (page) => {
    currentPage.value = page;
};

const initChart = async () => {
    if (!chartRef.value || !props.estadosPorProyecto?.length) return;

    await nextTick();

    const ctx = chartRef.value.getContext('2d');
    if (!ctx) return;

    if (chartInstance.value) {
        chartInstance.value.destroy();
    }

    try {
        chartInstance.value = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: props.estadosPorProyecto.map(p => p.nombre_proyecto || 'Sin nombre'),
                datasets: [
                    {
                        label: 'Piezas Pendientes',
                        data: props.estadosPorProyecto.map(p => p.pendientes || 0),
                        backgroundColor: 'rgba(255, 99, 132, 0.8)',
                        borderColor: 'rgb(255, 99, 132)',
                        borderWidth: 2,
                        borderRadius: 8,
                        borderSkipped: false,
                    },
                    {
                        label: 'Piezas Fabricadas',
                        data: props.estadosPorProyecto.map(p => p.fabricadas || 0),
                        backgroundColor: 'rgba(6, 182, 212, 0.8)',
                        borderColor: 'rgb(6, 182, 212)',
                        borderWidth: 2,
                        borderRadius: 8,
                        borderSkipped: false,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Estado de Piezas por Proyecto',
                        font: { size: 18, weight: 'bold' },
                        color: '#0f172a'
                    },
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            font: { size: 12 }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Cantidad de Piezas',
                            font: { weight: 'bold' }
                        },
                        grid: {
                            color: 'rgba(148, 163, 184, 0.3)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Proyectos',
                            font: { weight: 'bold' }
                        },
                        grid: {
                            display: false
                        }
                    }
                },
                animation: {
                    duration: 1500,
                    easing: 'easeOutQuart'
                }
            }
        });

        isChartReady.value = true;
    } catch (error) {
        console.error('Error creating chart:', error);
    }
};

watch(() => props.estadosPorProyecto, () => {
    if (props.estadosPorProyecto?.length) {
        initChart();
    }
}, { deep: true });

onMounted(() => {
    setTimeout(() => {
        initChart();
    }, 100);
});
</script>

<template>
    <AppLayout title="Reportes">
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-cyan-50 to-teal-100 py-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <transition
                    enter-active-class="transition-all ease-out duration-700"
                    enter-from-class="transform opacity-0 scale-95 -translate-y-8"
                    enter-to-class="transform opacity-100 scale-100 translate-y-0"
                    leave-active-class="transition-all ease-in duration-300"
                    leave-from-class="transform opacity-100 scale-100 translate-y-0"
                    leave-to-class="transform opacity-0 scale-95 -translate-y-8"
                >
                    <div class="space-y-6">
                        <!-- Header Compacto -->
                        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-cyan-100 p-6">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <div>
                                    <h1 class="text-2xl font-bold bg-gradient-to-r from-slate-900 to-cyan-700 bg-clip-text text-transparent">
                                        Dashboard de Producción
                                    </h1>
                                    <p class="text-slate-600 text-sm mt-1">Monitoreo en tiempo real del estado de proyectos</p>
                                </div>
                                
                                <!-- Búsqueda integrada en header -->
                                <div class="flex gap-3">
                                    <div class="relative">
                                        <input
                                            v-model="searchTerm"
                                            type="text"
                                            placeholder="Buscar proyecto..."
                                            class="w-64 pl-10 pr-4 py-2 bg-white/70 border border-cyan-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent transition-all duration-300"
                                        >
                                        <svg class="absolute left-3 top-2.5 w-4 h-4 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                    <button
                                        @click="clearFilters"
                                        class="px-4 py-2 bg-gradient-to-r from-slate-600 to-slate-700 text-white rounded-xl hover:from-slate-700 hover:to-slate-800 transition-all duration-300 transform hover:scale-105"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Estadísticas y Gráfico Optimizados -->
                        <div class="grid grid-cols-1 xl:grid-cols-4 gap-6">
                            <!-- Tarjetas de estadísticas mejoradas -->
                            <div class="xl:col-span-1 grid grid-cols-2 xl:grid-cols-1 gap-4">
                                <div class="bg-gradient-to-br from-white to-cyan-50 rounded-2xl shadow-lg border border-cyan-100 p-6 hover:shadow-xl transition-all duration-500 hover:-translate-y-1">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-slate-600">Total Proyectos</p>
                                            <p class="text-3xl font-bold text-slate-900 mt-1">{{ filteredProyectos.length }}</p>
                                        </div>
                                        <div class="p-3 bg-gradient-to-br from-cyan-400 to-teal-500 rounded-xl">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-gradient-to-br from-white to-red-50 rounded-2xl shadow-lg border border-red-100 p-6 hover:shadow-xl transition-all duration-500 hover:-translate-y-1">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-slate-600">Piezas Pendientes</p>
                                            <p class="text-3xl font-bold text-red-600 mt-1">{{ totalPiezasPendientes }}</p>
                                        </div>
                                        <div class="p-3 bg-gradient-to-br from-red-400 to-red-500 rounded-xl">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-gradient-to-br from-white to-emerald-50 rounded-2xl shadow-lg border border-emerald-100 p-6 hover:shadow-xl transition-all duration-500 hover:-translate-y-1 col-span-2 xl:col-span-1">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-slate-600">Piezas Fabricadas</p>
                                            <p class="text-3xl font-bold text-emerald-600 mt-1">{{ totalPiezasFabricadas }}</p>
                                        </div>
                                        <div class="p-3 bg-gradient-to-br from-emerald-400 to-emerald-500 rounded-xl">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Gráfico mejorado -->
                            <div class="xl:col-span-3">
                                <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg border border-cyan-100 p-6 hover:shadow-xl transition-all duration-500">
                                    <div class="h-80 relative">
                                        <canvas ref="chartRef" class="rounded-xl"></canvas>
                                        <div v-if="!isChartReady" class="absolute inset-0 flex items-center justify-center bg-white/80 rounded-xl">
                                            <div class="flex flex-col items-center">
                                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-cyan-600 mb-3"></div>
                                                <div class="text-slate-600 text-sm">Cargando visualización...</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Lista de Proyectos Optimizada -->
                        <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg border border-cyan-100 overflow-hidden">
                            <div class="bg-gradient-to-r from-slate-800 to-cyan-700 px-6 py-4">
                                <h3 class="text-lg font-semibold text-white flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    Detalle de Proyectos
                                    <span class="text-sm font-normal text-cyan-200 ml-2">
                                        ({{ filteredProyectos.length }} proyectos encontrados)
                                    </span>
                                </h3>
                            </div>

                            <div class="divide-y divide-slate-200">
                                <div v-for="(proyecto, index) in paginatedProyectos" :key="proyecto.IDproyecto"
                                     class="transition-all duration-300 hover:bg-gradient-to-r hover:from-slate-50 hover:to-cyan-50"
                                     :style="{ animationDelay: `${index * 100}ms` }"
                                >
                                    <!-- Header del Proyecto -->
                                    <div
                                        class="px-6 py-5 cursor-pointer group"
                                        @click="toggleProyecto(proyecto.IDproyecto)"
                                    >
                                        <div class="flex items-center justify-between">
                                            <div class="flex-1">
                                                <h4 class="text-lg font-semibold text-slate-800 group-hover:text-cyan-700 transition-colors duration-300">
                                                    {{ proyecto.nombre_proyecto }}
                                                </h4>
                                                <p class="text-sm text-slate-600 mt-1 flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                                    </svg>
                                                    {{ proyecto.bloques.length }} bloques • {{ proyecto.total_pendientes }} piezas pendientes
                                                </p>
                                            </div>
                                            <div class="flex items-center space-x-3">
                                                <span :class="getPriorityColor(proyecto.total_pendientes)"
                                                      class="px-4 py-2 rounded-xl text-sm font-semibold border transition-all duration-300 hover:scale-105">
                                                    {{ proyecto.total_pendientes }} pendientes
                                                </span>
                                                <svg
                                                    class="w-5 h-5 text-slate-400 transform transition-all duration-300 group-hover:text-cyan-600"
                                                    :class="{ 'rotate-180': expandedProyectos[proyecto.IDproyecto] }"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                >
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Contenido del Proyecto -->
                                    <transition
                                        enter-active-class="transition-all ease-out duration-500"
                                        enter-from-class="transform opacity-0 -translate-y-4 scale-95"
                                        enter-to-class="transform opacity-100 translate-y-0 scale-100"
                                        leave-active-class="transition-all ease-in duration-300"
                                        leave-from-class="transform opacity-100 translate-y-0 scale-100"
                                        leave-to-class="transform opacity-0 -translate-y-4 scale-95"
                                    >
                                        <div v-if="expandedProyectos[proyecto.IDproyecto]" class="px-6 pb-6 bg-gradient-to-r from-slate-50 to-cyan-50">
                                            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 pt-4">
                                                <div v-for="(bloque, bloqueIndex) in proyecto.bloques" :key="bloque.IDBloque"
                                                     class="bg-white rounded-xl p-4 shadow-md border border-cyan-100 hover:shadow-lg transition-all duration-300 hover:-translate-y-1"
                                                     :style="{ animationDelay: `${bloqueIndex * 150}ms` }"
                                                >
                                                    <!-- Header del Bloque -->
                                                    <div
                                                        class="flex items-center justify-between cursor-pointer mb-3 group"
                                                        @click="toggleBloque(bloque.IDBloque)"
                                                    >
                                                        <h5 class="font-semibold text-slate-800 group-hover:text-cyan-700 transition-colors duration-300">
                                                            Bloque {{ bloque.nombre_bloque }}
                                                        </h5>
                                                        <div class="flex items-center space-x-2">
                                                            <span :class="getPriorityColor(bloque.piezas.length)"
                                                                  class="px-3 py-1 rounded-lg text-xs font-semibold border transition-all duration-300 hover:scale-105">
                                                                {{ bloque.piezas.length }}
                                                            </span>
                                                            <svg
                                                                class="w-4 h-4 text-slate-400 transform transition-all duration-300 group-hover:text-cyan-600"
                                                                :class="{ 'rotate-180': expandedBloques[bloque.IDBloque] }"
                                                                fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                            >
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                            </svg>
                                                        </div>
                                                    </div>

                                                    <!-- Lista de Piezas -->
                                                    <transition
                                                        enter-active-class="transition-all ease-out duration-400"
                                                        enter-from-class="transform opacity-0 -translate-y-2 scale-95"
                                                        enter-to-class="transform opacity-100 translate-y-0 scale-100"
                                                        leave-active-class="transition-all ease-in duration-200"
                                                        leave-from-class="transform opacity-100 translate-y-0 scale-100"
                                                        leave-to-class="transform opacity-0 -translate-y-2 scale-95"
                                                    >
                                                        <div v-if="expandedBloques[bloque.IDBloque]" class="space-y-3">
                                                            <div v-for="(pieza, piezaIndex) in bloque.piezas" :key="pieza.IDPieza"
                                                                 class="bg-gradient-to-r from-slate-50 to-cyan-50 rounded-lg p-3 border border-slate-200 hover:border-cyan-300 transition-all duration-300"
                                                                 :style="{ animationDelay: `${piezaIndex * 100}ms` }"
                                                            >
                                                                <div class="flex justify-between items-start mb-2">
                                                                    <span class="font-medium text-slate-800">{{ pieza.nombre_pieza }}</span>
                                                                    <span v-if="pieza.estado === 'Pendiente'" class="inline-block px-2 py-1 rounded-lg bg-gradient-to-r from-amber-100 to-amber-200 text-amber-800 text-xs font-semibold border border-amber-300">Pendiente</span>
                                                                    <span v-else-if="pieza.estado === 'Fabricada'" class="inline-block px-2 py-1 rounded-lg bg-gradient-to-r from-emerald-100 to-emerald-200 text-emerald-800 text-xs font-semibold border border-emerald-300">Fabricada</span>
                                                                    <span v-else class="inline-block px-2 py-1 rounded-lg bg-gradient-to-r from-slate-100 to-slate-200 text-slate-800 text-xs font-semibold border border-slate-300">{{ pieza.estado }}</span>
                                                                </div>
                                                                
                                                                <div class="grid grid-cols-2 gap-3 text-xs text-slate-600">
                                                                    <div class="flex items-center">
                                                                        <svg class="w-3 h-3 mr-1 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                                        </svg>
                                                                        <span class="font-medium">Teórico:</span>
                                                                        <span class="ml-1">{{ pieza.peso_teorico }} kg</span>
                                                                    </div>
                                                                    <div class="flex items-center">
                                                                        <svg class="w-3 h-3 mr-1 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                                                                        </svg>
                                                                        <span class="font-medium">Real:</span>
                                                                        <span class="ml-1">{{ pieza.peso_real || 'N/A' }} kg</span>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="mt-2 text-xs text-slate-500 flex items-center">
                                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                                    </svg>
                                                                    <span>{{ pieza.registrado_por || 'Sin asignar' }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </transition>
                                                </div>
                                            </div>
                                        </div>
                                    </transition>
                                </div>
                            </div>

                            <!-- Paginación Mejorada -->
                            <div v-if="totalPages > 1" class="bg-gradient-to-r from-slate-50 to-cyan-50 px-6 py-4 border-t border-slate-200">
                                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                                    <div class="text-sm text-slate-700 font-medium">
                                        Mostrando {{ (currentPage - 1) * itemsPerPage + 1 }} - 
                                        {{ Math.min(currentPage * itemsPerPage, filteredProyectos.length) }}
                                        de {{ filteredProyectos.length }} proyectos
                                    </div>
                                    <div class="flex space-x-2">
                                        <button
                                            v-for="page in totalPages"
                                            :key="page"
                                            @click="goToPage(page)"
                                            class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-300 transform hover:scale-105"
                                            :class="page === currentPage 
                                                ? 'bg-gradient-to-r from-cyan-500 to-teal-600 text-white shadow-lg' 
                                                : 'bg-white text-slate-700 hover:bg-gradient-to-r hover:from-cyan-100 hover:to-teal-100 hover:text-cyan-700 border border-slate-300'"
                                        >
                                            {{ page }}
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Estado vacío mejorado -->
                            <div v-if="filteredProyectos.length === 0" class="px-6 py-16 text-center">
                                <div class="max-w-sm mx-auto">
                                    <div class="bg-gradient-to-br from-cyan-100 to-teal-200 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6">
                                        <svg class="w-10 h-10 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-slate-900 mb-2">No se encontraron proyectos</h3>
                                    <p class="text-slate-600 mb-6">Intenta ajustar los términos de búsqueda o verifica los filtros activos.</p>
                                    <button
                                        @click="clearFilters"
                                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-cyan-500 to-teal-600 text-white rounded-xl hover:from-cyan-600 hover:to-teal-700 transition-all duration-300 transform hover:scale-105 shadow-lg"
                                    >
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        Limpiar Filtros
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </transition>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
/* Animaciones personalizadas para elementos que aparecen gradualmente */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translate3d(0, 30px, 0);
    }
    to {
        opacity: 1;
        transform: translate3d(0, 0, 0);
    }
}

.animate-fadeInUp {
    animation: fadeInUp 0.6s ease-out;
}

/* Efectos de hover suaves para las tarjetas */
.card-hover {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.card-hover:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

/* Animación para el loading del gráfico */
@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Gradientes personalizados para los estados */
.status-gradient-pending {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    border-color: #f59e0b;
}

.status-gradient-completed {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    border-color: #10b981;
}

.status-gradient-error {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    border-color: #ef4444;
}

/* Mejoras en la transición de los elementos expandibles */
.expand-transition {
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    transform-origin: top;
}

/* Efecto glassmorphism para las tarjetas principales */
.glass-card {
    background: rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.18);
}

/* Scroll suave para la paginación */
html {
    scroll-behavior: smooth;
}

/* Estilos para mejorar la accesibilidad */
button:focus,
input:focus {
    outline: 2px solid #06b6d4;
    outline-offset: 2px;
}

/* Animación sutil para los iconos */
.icon-hover {
    transition: transform 0.2s ease-in-out;
}

.icon-hover:hover {
    transform: scale(1.1);
}
</style>