<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    usuario: '',
    password: '',
    remember: false,
});

const isLoaded = ref(false);

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

onMounted(() => {
    setTimeout(() => isLoaded.value = true, 200);
});
</script>

<template>
    <Head title="COTECMAR - Sistema de Acceso" />

    <div class="min-h-screen bg-slate-50">
        <!-- Header con patrón naval -->
        <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-blue-900 via-blue-800 to-blue-900"></div>
        
        <!-- Patrón de fondo sutil -->
        <div class="absolute inset-0 opacity-30">
            <svg width="60" height="60" viewBox="0 0 60 60" class="absolute inset-0 w-full h-full">
                <defs>
                    <pattern id="naval-pattern" x="0" y="0" width="60" height="60" patternUnits="userSpaceOnUse">
                        <circle cx="30" cy="30" r="1" fill="#1e293b" opacity="0.1"/>
                        <circle cx="0" cy="0" r="1" fill="#1e293b" opacity="0.1"/>
                        <circle cx="60" cy="60" r="1" fill="#1e293b" opacity="0.1"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#naval-pattern)"/>
            </svg>
        </div>

        <div class="relative min-h-screen flex items-center justify-center px-4">
            <div class="w-full max-w-6xl">
                <div class="bg-white shadow-2xl border border-slate-200 overflow-hidden">
                    <div class="flex min-h-[600px]">
                        <!-- Panel izquierdo - Branding COTECMAR -->
                        <div class="hidden lg:flex lg:w-2/3 bg-gradient-to-br from-slate-800 to-slate-900 relative">
                            <!-- Elemento decorativo naval -->
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-900/20 to-transparent"></div>
                            
                            <!-- Líneas decorativas -->
                            <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-blue-400 to-transparent"></div>
                            <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-blue-400 to-transparent"></div>
                            
                            <div class="relative z-10 flex flex-col justify-center px-16 py-12 text-white">
                                <!-- Logo COTECMAR -->
                                <div class="mb-12">
                                    <div class="w-20 h-20 bg-white rounded-lg shadow-xl flex items-center justify-center mb-6">
                                        <img 
                                            src="https://www.cotecmar.com/sites/default/files/media/imagenes/2023-12/CotecmarLogo.png" 
                                            alt="COTECMAR" 
                                            class="w-16 h-16 object-contain"
                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='block'"
                                        />
                                        <div class="hidden w-16 h-16 bg-blue-900 rounded flex items-center justify-center">
                                            <span class="text-white font-bold text-xl">C</span>
                                        </div>
                                    </div>
                                    <h1 class="text-4xl font-light mb-4 tracking-wide">
                                        COTECMAR
                                    </h1>
                                    <div class="w-24 h-1 bg-blue-400 mb-6"></div>
                                    <p class="text-slate-300 text-lg leading-relaxed">
                                        Corporación de Ciencia y Tecnología para el Desarrollo 
                                        de la Industria Naval, Marítima y Fluvial
                                    </p>
                                </div>

                                <!-- Características del sistema -->
                                
                            </div>
                        </div>

                        <!-- Panel derecho - Formulario de acceso -->
                        <div class="w-full lg:w-1/3 bg-white flex flex-col">
                            <!-- Header del formulario -->
                            <div class="px-8 py-6 bg-slate-50 border-b border-slate-200">
                                <div class="lg:hidden mb-4">
                                    <img 
                                        src="https://www.cotecmar.com/sites/default/files/media/imagenes/2023-12/CotecmarLogo.png" 
                                        alt="COTECMAR" 
                                        class="w-12 h-12 object-contain mx-auto"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='block'"
                                    />
                                    <div class="hidden w-12 h-12 bg-blue-900 rounded mx-auto flex items-center justify-center">
                                        <span class="text-white font-bold">C</span>
                                    </div>
                                </div>
                                <h2 class="text-2xl font-bold text-slate-900 text-center lg:text-left">
                                    Acceso al Sistema
                                </h2>
                                <p class="text-slate-600 text-sm mt-1 text-center lg:text-left">
                                    Ingrese sus credenciales corporativas
                                </p>
                            </div>

                            <!-- Formulario -->
                            <div class="flex-1 px-8 py-8">
                                <!-- Mensaje de estado -->
                                <div v-if="status" class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-4 h-4 bg-emerald-500 rounded-full mr-3"></div>
                                        <p class="text-emerald-800 text-sm font-medium">{{ status }}</p>
                                    </div>
                                </div>

                                <form @submit.prevent="submit" class="space-y-6">
                                    <!-- Campo Usuario -->
                                    <div>
                                        <label for="usuario" class="block text-sm font-semibold text-slate-700 mb-2 uppercase tracking-wide">
                                            Usuario Corporativo
                                        </label>
                                        <div class="relative">
                                            <input
                                                id="usuario"
                                                v-model="form.usuario"
                                                type="text"
                                                required
                                                autofocus
                                                autocomplete="username"
                                                class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg bg-white focus:border-blue-600 focus:outline-none transition-colors duration-200 text-slate-900 placeholder-slate-400"
                                                placeholder="Ingrese su usuario"
                                            />
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <InputError class="mt-2 text-sm text-red-600" :message="form.errors.usuario" />
                                    </div>

                                    <!-- Campo Contraseña -->
                                    <div>
                                        <label for="password" class="block text-sm font-semibold text-slate-700 mb-2 uppercase tracking-wide">
                                            Contraseña
                                        </label>
                                        <div class="relative">
                                            <input
                                                id="password"
                                                v-model="form.password"
                                                type="password"
                                                required
                                                autocomplete="current-password"
                                                class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg bg-white focus:border-blue-600 focus:outline-none transition-colors duration-200 text-slate-900 placeholder-slate-400"
                                                placeholder="Ingrese su contraseña"
                                            />
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <InputError class="mt-2 text-sm text-red-600" :message="form.errors.password" />
                                    </div>

                                    <!-- Opciones adicionales -->
                                    <div class="flex items-center justify-between">
                                        <label class="flex items-center">
                                            <input
                                                v-model="form.remember"
                                                type="checkbox"
                                                class="h-4 w-4 text-blue-600 border-2 border-slate-300 rounded focus:ring-blue-500"
                                            />
                                            <span class="ml-2 text-sm text-slate-600">Recordar sesión</span>
                                        </label>

                                        <Link
                                            v-if="canResetPassword"
                                            :href="route('password.request')"
                                            class="text-sm text-blue-600 hover:text-blue-800 font-medium"
                                        >
                                            ¿Olvidó su contraseña?
                                        </Link>
                                    </div>

                                    <!-- Botón de acceso -->
                                    <button
                                        type="submit"
                                        :disabled="form.processing"
                                        class="w-full bg-slate-900 hover:bg-slate-800 text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed uppercase tracking-wide"
                                    >
                                        <span v-if="!form.processing" class="flex items-center justify-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                            </svg>
                                            Acceder al Sistema
                                        </span>
                                        <span v-else class="flex items-center justify-center">
                                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Verificando credenciales...
                                        </span>
                                    </button>
                                </form>
                            </div>

                            <!-- Footer -->
                            <div class="px-8 py-4 bg-slate-50 border-t border-slate-200">
                                <div class="flex items-center justify-between text-xs text-slate-500">
                                    <span>© 2025 COTECMAR</span>
                                    <span>Versión 3.0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Animaciones específicas para el contexto militar/naval */
@keyframes secure-fade-in {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.secure-entrance {
    animation: secure-fade-in 0.6s ease-out;
}

/* Efectos de focus más definidos para ambiente corporativo */
input:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Estilos específicos para el logo */
.logo-container {
    background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%);
}
</style>