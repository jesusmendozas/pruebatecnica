<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import Banner from '@/Components/Banner.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

defineProps({
    title: String,
});

const showingNavigationDropdown = ref(false);
const scrolled = ref(false);

const logout = () => {
    router.post(route('logout'));
};

const handleScroll = () => {
    scrolled.value = window.scrollY > 20;
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});
</script>

<template>
    <div>
        <Head :title="title" />

        <Banner />

        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100">
            <!-- Navbar simplificado -->
            <nav
                :class="[
                    'fixed top-0 left-0 right-0 z-50 transition-all duration-300',
                    scrolled
                        ? 'bg-white shadow-md border-b border-gray-200'
                        : 'bg-white/90 border-b border-gray-200'
                ]"
            >
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-6 lg:px-8">
                    <div class="flex justify-between h-20">
                        <div class="flex items-center">
                            <!-- Logo simplificado -->
                            <div class="shrink-0 flex items-center">
                                <Link
                                    :href="route('formulario')"
                                    class="flex items-center"
                                >
                                    <ApplicationMark class="block h-12 w-auto" />
                                </Link>
                            </div>

                            <!-- Navigation Links simplificados -->
                            <div class="hidden lg:flex items-center space-x-1 ml-20">
                                <Link
                                    :href="route('formulario')"
                                    class="px-6 py-3 font-medium text-gray-700 hover:text-blue-700 transition-colors"
                                    :class="route().current('formulario') ? 'text-blue-700 border-b-2 border-blue-700' : ''"
                                >
                                    <span class="flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        <span>Formulario</span>
                                    </span>
                                </Link>

                                <Link
                                    :href="route('proyectos')"
                                    class="px-6 py-3 font-medium text-gray-700 hover:text-blue-700 transition-colors"
                                    :class="route().current('proyectos') ? 'text-blue-700 border-b-2 border-blue-700' : ''"
                                >
                                    <span class="flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15"></path>
                                        </svg>
                                        <span>Proyectos</span>
                                    </span>
                                </Link>

                                <Link
                                    :href="route('bloques')"
                                    class="px-6 py-3 font-medium text-gray-700 hover:text-blue-700 transition-colors"
                                    :class="route().current('bloques') ? 'text-blue-700 border-b-2 border-blue-700' : ''"
                                >
                                    <span class="flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path>
                                        </svg>
                                        <span>Bloques</span>
                                    </span>
                                </Link>

                                <Link
                                    :href="route('piezas')"
                                    class="px-6 py-3 font-medium text-gray-700 hover:text-blue-700 transition-colors"
                                    :class="route().current('piezas') ? 'text-blue-700 border-b-2 border-blue-700' : ''"
                                >
                                    <span class="flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 7.5l-2.25-1.313M21 7.5v2.25m0-2.25l-2.25 1.313M3 7.5l2.25-1.313M3 7.5l2.25 1.313M3 7.5v2.25m9 3l2.25-1.313M12 12.75l-2.25-1.313M12 12.75V15m0 6.75l2.25-1.313M12 21.75V19.5m0 2.25l-2.25-1.313m0-16.875L12 2.25l2.25 1.313M21 14.25v2.25l-2.25 1.313m-13.5 0L3 16.5v-2.25"></path>
                                        </svg>
                                        <span>Piezas</span>
                                    </span>
                                </Link>

                                <Link
                                    :href="route('usuarios')"
                                    class="px-6 py-3 font-medium text-gray-700 hover:text-blue-700 transition-colors"
                                    :class="route().current('usuarios') ? 'text-blue-700 border-b-2 border-blue-700' : ''"
                                >
                                    <span class="flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"></path>
                                        </svg>
                                        <span>Usuarios</span>
                                    </span>
                                </Link>

                                <Link
                                    :href="route('reportes')"
                                    class="px-6 py-3 font-medium text-gray-700 hover:text-blue-700 transition-colors"
                                    :class="route().current('reportes') ? 'text-blue-700 border-b-2 border-blue-700' : ''"
                                >
                                    <span class="flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3v1.5M3 21v-6m0 0l2.77-.693a9 9 0 016.208.682l.108.054a9 9 0 006.086.71l3.114-.732a48.524 48.524 0 01-.005-10.499l-3.11.732a9 9 0 01-6.085-.711l-.108-.054a9 9 0 00-6.208-.682L3 4.5M3 15V4.5"></path>
                                        </svg>
                                        <span>Reportes</span>
                                    </span>
                                </Link>
                            </div>
                        </div>

                        <!-- Right side with simplified user dropdown -->
                        <div class="hidden lg:flex lg:items-center">
                            <div class="relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <button
                                            class="flex items-center space-x-3 px-5 py-2.5 rounded-lg font-medium text-gray-700 hover:text-blue-700 hover:bg-gray-50 transition-colors"
                                        >
                                            <div class="flex items-center space-x-3">
                                                <div class="h-9 w-9 bg-blue-700 rounded-lg flex items-center justify-center text-white font-bold text-sm">
                                                    {{ $page.props.auth.user.usuario.charAt(0).toUpperCase() }}
                                                </div>
                                                <span>{{ $page.props.auth.user.name || $page.props.auth.user.usuario }}</span>
                                            </div>
                                        </button>
                                    </template>

                                    <template #content>
                                        <div class="p-2">
                                            <form method="POST" @submit.prevent="logout">
                                                <DropdownLink as="button" class="w-full text-left text-red-600 hover:text-red-700 hover:bg-red-50">
                                                    <div class="flex items-center space-x-2">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                                        </svg>
                                                        <span>Cerrar Sesión</span>
                                                    </div>
                                                </DropdownLink>
                                            </form>
                                        </div>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Mobile menu button simplificado -->
                        <div class="flex items-center lg:hidden">
                            <button
                                class="p-3 rounded-lg text-gray-600 hover:text-blue-700 hover:bg-gray-50 transition-colors"
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                            >
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path v-if="!showingNavigationDropdown" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                    <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Mobile Navigation Menu simplificado -->
                <div
                    :class="[
                        'lg:hidden transition-all duration-300',
                        showingNavigationDropdown ? 'max-h-screen opacity-100' : 'max-h-0 opacity-0'
                    ]"
                >
                    <div class="bg-white border-t border-gray-200">
                        <div class="p-4 space-y-2">
                            <ResponsiveNavLink
                                :href="route('formulario')"
                                :active="route().current('formulario')"
                                class="flex items-center space-x-3 px-4 py-3 rounded-lg font-medium"
                                :class="route().current('formulario')
                                    ? 'text-blue-700 bg-blue-50'
                                    : 'text-gray-700 hover:text-blue-700 hover:bg-gray-50'"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                <span>Formulario</span>
                            </ResponsiveNavLink>

                            <ResponsiveNavLink
                                :href="route('proyectos')"
                                :active="route().current('proyectos')"
                                class="flex items-center space-x-3 px-4 py-3 rounded-lg font-medium"
                                :class="route().current('proyectos')
                                    ? 'text-blue-700 bg-blue-50'
                                    : 'text-gray-700 hover:text-blue-700 hover:bg-gray-50'"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15"></path>
                                </svg>
                                <span>Proyectos</span>
                            </ResponsiveNavLink>

                            <ResponsiveNavLink
                                :href="route('bloques')"
                                :active="route().current('bloques')"
                                class="flex items-center space-x-3 px-4 py-3 rounded-lg font-medium"
                                :class="route().current('bloques')
                                    ? 'text-blue-700 bg-blue-50'
                                    : 'text-gray-700 hover:text-blue-700 hover:bg-gray-50'"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path>
                                </svg>
                                <span>Bloques</span>
                            </ResponsiveNavLink>

                            <ResponsiveNavLink
                                :href="route('piezas')"
                                :active="route().current('piezas')"
                                class="flex items-center space-x-3 px-4 py-3 rounded-lg font-medium"
                                :class="route().current('piezas')
                                    ? 'text-blue-700 bg-blue-50'
                                    : 'text-gray-700 hover:text-blue-700 hover:bg-gray-50'"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 7.5l-2.25-1.313M21 7.5v2.25m0-2.25l-2.25 1.313M3 7.5l2.25-1.313M3 7.5l2.25 1.313M3 7.5v2.25m9 3l2.25-1.313M12 12.75l-2.25-1.313M12 12.75V15m0 6.75l2.25-1.313M12 21.75V19.5m0 2.25l-2.25-1.313m0-16.875L12 2.25l2.25 1.313M21 14.25v2.25l-2.25 1.313m-13.5 0L3 16.5v-2.25"></path>
                                </svg>
                                <span>Piezas</span>
                            </ResponsiveNavLink>

                            <ResponsiveNavLink
                                :href="route('usuarios')"
                                :active="route().current('usuarios')"
                                class="flex items-center space-x-3 px-4 py-3 rounded-lg font-medium"
                                :class="route().current('usuarios')
                                    ? 'text-blue-700 bg-blue-50'
                                    : 'text-gray-700 hover:text-blue-700 hover:bg-gray-50'"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"></path>
                                </svg>
                                <span>Usuarios</span>
                            </ResponsiveNavLink>

                            <ResponsiveNavLink
                                :href="route('reportes')"
                                :active="route().current('reportes')"
                                class="flex items-center space-x-3 px-4 py-3 rounded-lg font-medium"
                                :class="route().current('reportes')
                                    ? 'text-blue-700 bg-blue-50'
                                    : 'text-gray-700 hover:text-blue-700 hover:bg-gray-50'"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3v1.5M3 21v-6m0 0l2.77-.693a9 9 0 016.208.682l.108.054a9 9 0 006.086.71l3.114-.732a48.524 48.524 0 01-.005-10.499l-3.11.732a9 9 0 01-6.085-.711l-.108-.054a9 9 0 00-6.208-.682L3 4.5M3 15V4.5"></path>
                                </svg>
                                <span>Reportes</span>
                            </ResponsiveNavLink>
                        </div>

                        <!-- Mobile User Info simplificado -->
                        <div class="border-t border-gray-200 p-4">
                            <div class="flex items-center space-x-4 mb-4">
                                <div class="h-12 w-12 bg-blue-700 rounded-lg flex items-center justify-center text-white font-bold text-lg">
                                    {{ $page.props.auth.user.usuario.charAt(0).toUpperCase() }}
                                </div>
                                <div>
                                    <div class="font-medium text-gray-800">{{ $page.props.auth.user.name || $page.props.auth.user.usuario }}</div>
                                    <div class="text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
                                </div>
                            </div>

                            <div>
                                <form method="POST" @submit.prevent="logout">
                                    <ResponsiveNavLink
                                        as="button"
                                        class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg font-medium text-red-600 hover:text-red-700 hover:bg-red-50"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        <span>Cerrar Sesión</span>
                                    </ResponsiveNavLink>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <main class="pt-24">
                <slot />
            </main>
        </div>
    </div>
</template>

<style scoped>
/* Estilos simplificados */
.transition-all {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 300ms;
}

/* Estilos de impresión */
@media print {
    nav, .no-print {
        display: none !important;
    }

    main {
        margin: 0 !important;
        padding: 0 !important;
    }
}
</style>
