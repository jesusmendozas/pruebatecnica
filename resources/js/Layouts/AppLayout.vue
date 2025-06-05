<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import Banner from '@/Components/Banner.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

defineProps({
    title: String,
});

const showingNavigationDropdown = ref(false);
const scrolled = ref(false);
const mousePosition = ref({ x: 0, y: 0 });

const switchToTeam = (team) => {
    router.put(route('current-team.update'), {
        team_id: team.id,
    }, {
        preserveState: false,
    });
};

const logout = () => {
    router.post(route('logout'));
};

const handleScroll = () => {
    scrolled.value = window.scrollY > 20;
};

const handleMouseMove = (e) => {
    mousePosition.value = {
        x: (e.clientX / window.innerWidth) * 100,
        y: (e.clientY / window.innerHeight) * 100
    };
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
    window.addEventListener('mousemove', handleMouseMove);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
    window.removeEventListener('mousemove', handleMouseMove);
});
</script>

<template>
    <div>
        <Head :title="title" />

        <Banner />

        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100">
            <!-- Ultra Modern Navbar -->
            <nav
                :class="[
                    'fixed top-0 left-0 right-0 z-50 transition-all duration-500 ease-out',
                    scrolled
                        ? 'backdrop-blur-xl bg-white/80 shadow-2xl shadow-indigo-500/10 border-b border-white/20'
                        : 'backdrop-blur-sm bg-white/60 border-b border-white/10'
                ]"
            >
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-6 lg:px-8 relative">
                    <div class="flex justify-between h-20">
                        <div class="flex items-center">
                            <!-- Logo with glow effect -->
                            <div class="shrink-0 flex items-center group">
                                <Link
                                    :href="route('formulario')"
                                    class="relative flex items-center space-x-3 transition-transform duration-300 hover:scale-105"
                                >
                                    <div class="relative">
                                        <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl blur-md opacity-0 group-hover:opacity-40 transition-opacity duration-300"></div>
                                        <ApplicationMark class="relative block h-10 w-auto filter drop-shadow-lg" />
                                    </div>
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden lg:flex items-center space-x-2 ml-16">
                                <NavLink
                                    :href="route('formulario')"
                                    :active="route().current('formulario')"
                                    class="relative group px-4 py-2 rounded-xl font-medium transition-all duration-300 hover:scale-105 hover:shadow-lg"
                                    :class="route().current('formulario')
                                        ? 'text-white bg-gradient-to-r from-blue-600 to-purple-600 shadow-lg shadow-blue-500/25'
                                        : 'text-gray-700 hover:text-gray-900 hover:bg-white/60'"
                                >
                                    <span class="relative z-10 flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <span>Formulario</span>
                                    </span>
                                </NavLink>

                                <NavLink
                                    :href="route('proyectos')"
                                    :active="route().current('proyectos')"
                                    class="relative group px-4 py-2 rounded-xl font-medium transition-all duration-300 hover:scale-105 hover:shadow-lg"
                                    :class="route().current('proyectos')
                                        ? 'text-white bg-gradient-to-r from-blue-600 to-purple-600 shadow-lg shadow-blue-500/25'
                                        : 'text-gray-700 hover:text-gray-900 hover:bg-white/60'"
                                >
                                    <span class="relative z-10 flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                        </svg>
                                        <span>Proyectos</span>
                                    </span>
                                </NavLink>

                                <NavLink
                                    :href="route('bloques')"
                                    :active="route().current('bloques')"
                                    class="relative group px-4 py-2 rounded-xl font-medium transition-all duration-300 hover:scale-105 hover:shadow-lg"
                                    :class="route().current('bloques')
                                        ? 'text-white bg-gradient-to-r from-blue-600 to-purple-600 shadow-lg shadow-blue-500/25'
                                        : 'text-gray-700 hover:text-gray-900 hover:bg-white/60'"
                                >
                                    <span class="relative z-10 flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                        </svg>
                                        <span>Bloques</span>
                                    </span>
                                </NavLink>

                                <NavLink
                                    :href="route('piezas')"
                                    :active="route().current('piezas')"
                                    class="relative group px-4 py-2 rounded-xl font-medium transition-all duration-300 hover:scale-105 hover:shadow-lg"
                                    :class="route().current('piezas')
                                        ? 'text-white bg-gradient-to-r from-blue-600 to-purple-600 shadow-lg shadow-blue-500/25'
                                        : 'text-gray-700 hover:text-gray-900 hover:bg-white/60'"
                                >
                                    <span class="relative z-10 flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                        <span>Piezas</span>
                                    </span>
                                </NavLink>

                                <NavLink
                                    :href="route('usuarios')"
                                    :active="route().current('usuarios')"
                                    class="relative group px-4 py-2 rounded-xl font-medium transition-all duration-300 hover:scale-105 hover:shadow-lg"
                                    :class="route().current('usuarios')
                                        ? 'text-white bg-gradient-to-r from-blue-600 to-purple-600 shadow-lg shadow-blue-500/25'
                                        : 'text-gray-700 hover:text-gray-900 hover:bg-white/60'"
                                >
                                    <span class="relative z-10 flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                        </svg>
                                        <span>Usuarios</span>
                                    </span>
                                </NavLink>

                                <NavLink
                                    :href="route('reportes')"
                                    :active="route().current('reportes')"
                                    class="relative group px-4 py-2 rounded-xl font-medium transition-all duration-300 hover:scale-105 hover:shadow-lg"
                                    :class="route().current('reportes')
                                        ? 'text-white bg-gradient-to-r from-blue-600 to-purple-600 shadow-lg shadow-blue-500/25'
                                        : 'text-gray-700 hover:text-gray-900 hover:bg-white/60'"
                                >
                                    <span class="relative z-10 flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                        <span>Reportes</span>
                                    </span>
                                </NavLink>
                            </div>
                        </div>

                        <!-- Right side with user dropdown -->
                        <div class="hidden lg:flex lg:items-center lg:space-x-4">
                            <!-- Settings Dropdown with modern design -->
                            <div class="relative">
                                <Dropdown align="right" width="56">
                                    <template #trigger>
                                        <button
                                            v-if="$page.props.jetstream.managesProfilePhotos"
                                            class="group relative flex items-center space-x-3 p-2 rounded-2xl transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-blue-500/20 hover:bg-white/60"
                                        >
                                            <div class="relative">
                                                <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full blur-md opacity-0 group-hover:opacity-40 transition-opacity duration-300"></div>
                                                <img
                                                    class="relative h-10 w-10 rounded-full object-cover ring-2 ring-white/20 group-hover:ring-blue-500/50 transition-all duration-300"
                                                    :src="$page.props.auth.user.profile_photo_url"
                                                    :alt="$page.props.auth.user.name"
                                                >
                                                <div class="absolute -bottom-1 -right-1 h-4 w-4 bg-green-400 rounded-full ring-2 ring-white"></div>
                                            </div>
                                            <div class="hidden xl:block text-left">
                                                <div class="text-sm font-semibold text-gray-800">{{ $page.props.auth.user.name }}</div>
                                                <div class="text-xs text-gray-500">Online</div>
                                            </div>
                                        </button>

                                        <button
                                            v-else
                                            class="group relative flex items-center space-x-3 px-4 py-2 rounded-2xl font-medium transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-blue-500/20 text-gray-700 hover:text-gray-900 hover:bg-white/60"
                                        >
                                            <div class="flex items-center space-x-2">
                                                <div class="h-8 w-8 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                                    {{ $page.props.auth.user.usuario.charAt(0).toUpperCase() }}
                                                </div>
                                                <span>{{ $page.props.auth.user.usuario }}</span>
                                                <svg class="h-4 w-4 transition-transform duration-200 group-hover:rotate-180" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                                </svg>
                                            </div>
                                        </button>
                                    </template>

                                    <template #content>
                                        <div class="bg-white/90 backdrop-blur-xl border border-white/20 rounded-2xl shadow-2xl overflow-hidden">
                                            <!-- Account Management -->
                                            <div class="px-6 py-3 bg-gradient-to-r from-gray-50 to-blue-50 border-b border-gray-100">
                                                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Manage Account</div>
                                            </div>

                                            <div class="p-2">
                                                <DropdownLink
                                                    :href="route('profile.show')"
                                                    class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700 hover:text-gray-900 hover:bg-gradient-to-r hover:from-blue-50 hover:to-purple-50 transition-all duration-200"
                                                >
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                    <span>Profile</span>
                                                </DropdownLink>
                                            </div>

                                            <div class="border-t border-gray-100 p-2">
                                                <!-- Authentication -->
                                                <form @submit.prevent="logout">
                                                    <DropdownLink
                                                        as="button"
                                                        class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl text-red-600 hover:text-red-700 hover:bg-red-50 transition-all duration-200"
                                                    >
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                                        </svg>
                                                        <span>Log Out</span>
                                                    </DropdownLink>
                                                </form>
                                            </div>
                                        </div>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Mobile menu button -->
                        <div class="flex items-center lg:hidden">
                            <button
                                class="relative p-3 rounded-2xl text-gray-600 hover:text-gray-900 hover:bg-white/60 transition-all duration-300 hover:scale-110 hover:shadow-lg"
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                            >
                                <div class="relative w-6 h-6">
                                    <span
                                        :class="[
                                            'absolute left-0 top-1 w-6 h-0.5 bg-current transition-all duration-300 ease-in-out',
                                            showingNavigationDropdown ? 'top-3 rotate-45' : 'top-1'
                                        ]"
                                    ></span>
                                    <span
                                        :class="[
                                            'absolute left-0 top-3 w-6 h-0.5 bg-current transition-all duration-300 ease-in-out',
                                            showingNavigationDropdown ? 'opacity-0' : 'opacity-100'
                                        ]"
                                    ></span>
                                    <span
                                        :class="[
                                            'absolute left-0 top-5 w-6 h-0.5 bg-current transition-all duration-300 ease-in-out',
                                            showingNavigationDropdown ? 'top-3 -rotate-45' : 'top-5'
                                        ]"
                                    ></span>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Mobile Navigation Menu -->
                <div
                    :class="[
                        'lg:hidden transition-all duration-500 ease-out overflow-hidden',
                        showingNavigationDropdown ? 'max-h-screen opacity-100' : 'max-h-0 opacity-0'
                    ]"
                >
                    <div class="backdrop-blur-xl bg-white/90 border-t border-white/20 mx-4 rounded-2xl mb-4 shadow-xl">
                        <div class="p-4 space-y-2">
                            <ResponsiveNavLink
                                :href="route('formulario')"
                                :active="route().current('formulario')"
                                class="flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition-all duration-300"
                                :class="route().current('formulario')
                                    ? 'text-white bg-gradient-to-r from-blue-600 to-purple-600 shadow-lg'
                                    : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50'"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span>Formulario</span>
                            </ResponsiveNavLink>

                            <ResponsiveNavLink
                                :href="route('proyectos')"
                                :active="route().current('proyectos')"
                                class="flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition-all duration-300"
                                :class="route().current('proyectos')
                                    ? 'text-white bg-gradient-to-r from-blue-600 to-purple-600 shadow-lg'
                                    : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50'"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                                <span>Proyectos</span>
                            </ResponsiveNavLink>

                            <ResponsiveNavLink
                                :href="route('bloques')"
                                :active="route().current('bloques')"
                                class="flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition-all duration-300"
                                :class="route().current('bloques')
                                    ? 'text-white bg-gradient-to-r from-blue-600 to-purple-600 shadow-lg'
                                    : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50'"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                                <span>Bloques</span>
                            </ResponsiveNavLink>

                            <ResponsiveNavLink
                                :href="route('piezas')"
                                :active="route().current('piezas')"
                                class="flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition-all duration-300"
                                :class="route().current('piezas')
                                    ? 'text-white bg-gradient-to-r from-blue-600 to-purple-600 shadow-lg'
                                    : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50'"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <span>Piezas</span>
                            </ResponsiveNavLink>

                            <ResponsiveNavLink
                                :href="route('usuarios')"
                                :active="route().current('usuarios')"
                                class="flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition-all duration-300"
                                :class="route().current('usuarios')
                                    ? 'text-white bg-gradient-to-r from-blue-600 to-purple-600 shadow-lg'
                                    : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50'"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                                <span>Usuarios</span>
                            </ResponsiveNavLink>

                            <ResponsiveNavLink
                                :href="route('reportes')"
                                :active="route().current('reportes')"
                                class="flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition-all duration-300"
                                :class="route().current('reportes')
                                    ? 'text-white bg-gradient-to-r from-blue-600 to-purple-600 shadow-lg'
                                    : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50'"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                <span>Reportes</span>
                            </ResponsiveNavLink>
                        </div>

                        <!-- Mobile User Info -->
                        <div class="border-t border-gray-100 p-4">
                            <div class="flex items-center space-x-4 mb-4">
                                <div v-if="$page.props.jetstream.managesProfilePhotos" class="relative">
                                    <img
                                        class="h-12 w-12 rounded-full object-cover ring-2 ring-blue-500/20"
                                        :src="$page.props.auth.user.profile_photo_url"
                                        :alt="$page.props.auth.user.name"
                                    >
                                    <div class="absolute -bottom-1 -right-1 h-4 w-4 bg-green-400 rounded-full ring-2 ring-white"></div>
                                </div>
                                <div v-else class="h-12 w-12 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center">
                                    <span class="text-white font-bold text-lg">{{ $page.props.auth.user.usuario.charAt(0).toUpperCase() }}</span>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-800">{{ $page.props.auth.user.name }}</div>
                                    <div class="text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <ResponsiveNavLink
                                    :href="route('profile.show')"
                                    :active="route().current('profile.show')"
                                    class="flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition-all duration-300 text-gray-700 hover:text-gray-900 hover:bg-gray-50"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span>Profile</span>
                                </ResponsiveNavLink>

                                <ResponsiveNavLink
                                    v-if="$page.props.jetstream.hasApiFeatures"
                                    :href="route('api-tokens.index')"
                                    :active="route().current('api-tokens.index')"
                                    class="flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition-all duration-300 text-gray-700 hover:text-gray-900 hover:bg-gray-50"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                    </svg>
                                    <span>API Tokens</span>
                                </ResponsiveNavLink>

                                <!-- Authentication -->
                                <form method="POST" @submit.prevent="logout">
                                    <ResponsiveNavLink
                                        as="button"
                                        class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition-all duration-300 text-red-600 hover:text-red-700 hover:bg-red-50"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        <span>Log Out</span>
                                    </ResponsiveNavLink>
                                </form>
                            </div>

                            <!-- Team Management -->
                            <template v-if="$page.props.jetstream.hasTeamFeatures">
                                <div class="border-t border-gray-100 mt-4 pt-4">
                                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">
                                        Manage Team
                                    </div>

                                    <div class="space-y-2">
                                        <!-- Team Settings -->
                                        <ResponsiveNavLink
                                            :href="route('teams.show', $page.props.auth.user.current_team)"
                                            :active="route().current('teams.show')"
                                            class="flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition-all duration-300 text-gray-700 hover:text-gray-900 hover:bg-gray-50"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            <span>Team Settings</span>
                                        </ResponsiveNavLink>

                                        <ResponsiveNavLink
                                            v-if="$page.props.jetstream.canCreateTeams"
                                            :href="route('teams.create')"
                                            :active="route().current('teams.create')"
                                            class="flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition-all duration-300 text-gray-700 hover:text-gray-900 hover:bg-gray-50"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            <span>Create New Team</span>
                                        </ResponsiveNavLink>
                                    </div>

                                    <!-- Team Switcher -->
                                    <template v-if="$page.props.auth.user.all_teams.length > 1">
                                        <div class="border-t border-gray-100 mt-4 pt-4">
                                            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">
                                                Switch Teams
                                            </div>

                                            <div class="space-y-2">
                                                <template v-for="team in $page.props.auth.user.all_teams" :key="team.id">
                                                    <form @submit.prevent="switchToTeam(team)">
                                                        <ResponsiveNavLink
                                                            as="button"
                                                            class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition-all duration-300 text-gray-700 hover:text-gray-900 hover:bg-gray-50"
                                                        >
                                                            <svg
                                                                v-if="team.id == $page.props.auth.user.current_team_id"
                                                                class="w-5 h-5 text-green-500"
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                fill="none"
                                                                viewBox="0 0 24 24"
                                                                stroke-width="1.5"
                                                                stroke="currentColor"
                                                            >
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            <div class="w-5 h-5 bg-gray-300 rounded-full" v-else></div>
                                                            <span>{{ team.name }}</span>
                                                        </ResponsiveNavLink>
                                                    </form>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Main content with proper spacing for fixed navbar -->
            <main class="pt-24">
                <slot />
            </main>
        </div>
    </div>
</template>

<style scoped>
/* Glassmorphism effects */
.backdrop-blur-xl {
    backdrop-filter: blur(16px);
}

/* Smooth scrolling for better UX */
html {
    scroll-behavior: smooth;
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-track {
    background: transparent;
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(45deg, #6366f1, #8b5cf6);
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(45deg, #4f46e5, #7c3aed);
}
</style>
