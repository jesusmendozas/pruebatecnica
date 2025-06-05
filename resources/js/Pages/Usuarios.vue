<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import axios from 'axios';

const props = defineProps({
    usuarios: Array
});

const showModal = ref(false);
const editingUsuario = ref(null);
const formData = ref({
    usuario: '',
    password: '',
    password_confirmation: ''
});

const openCreateModal = () => {
    formData.value = {
        usuario: '',
        password: '',
        password_confirmation: ''
    };
    editingUsuario.value = null;
    showModal.value = true;
};

const openEditModal = (usuario) => {
    formData.value = {
        usuario: usuario.usuario,
        password: '',
        password_confirmation: ''
    };
    editingUsuario.value = usuario;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    formData.value = {
        usuario: '',
        password: '',
        password_confirmation: ''
    };
    editingUsuario.value = null;
};

const saveUsuario = async () => {
    try {
        if (editingUsuario.value) {
            const response = await axios.put(`/api/usuarios/${editingUsuario.value.id}`, formData.value);
            if (response.data.success) {
                alert('Usuario actualizado exitosamente');
                window.location.reload();
            }
        } else {
            const response = await axios.post('/api/usuarios', formData.value);
            if (response.data.success) {
                alert('Usuario creado exitosamente');
                window.location.reload();
            }
        }
        closeModal();
    } catch (error) {
        alert('Error al guardar el usuario: ' + (error.response?.data?.message || error.message || 'Error desconocido'));
    }
};

const deleteUsuario = async (usuario) => {
    if (confirm('¿Estás seguro de que deseas eliminar este usuario? Esta acción no se puede deshacer.')) {
        try {
            const response = await axios.delete(`/api/usuarios/${usuario.id}`);
            if (response.data.success) {
                alert('Usuario eliminado exitosamente');
                window.location.reload();
            }
        } catch (error) {
            alert('Error al eliminar el usuario: ' + (error.response?.data?.message || error.message || 'Error desconocido'));
        }
    }
};
</script>

<template>
    <AppLayout title="Usuarios">
        <div class="min-h-screen bg-gradient-to-br from-slate-50 to-teal-50 py-8">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header Section -->
                <div class="mb-8 animate-fade-in">
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-teal-100 p-6 transition-all duration-300 hover:shadow-2xl">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div>
                                <h1 class="text-3xl font-bold bg-gradient-to-r from-teal-800 to-teal-600 bg-clip-text text-transparent">
                                    Gestión de Usuarios
                                </h1>
                            </div>
                            <button @click="openCreateModal"
                                class="group relative px-6 py-3 bg-gradient-to-r from-teal-700 to-teal-800 text-white rounded-xl font-medium shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 hover:from-teal-800 hover:to-teal-900 focus:outline-none focus:ring-4 focus:ring-teal-300">
                                <span class="flex items-center gap-2">
                                    <svg class="w-5 h-5 transition-transform group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Nuevo Usuario
                                </span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Users Table Section -->
                <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-xl border border-teal-100 overflow-hidden transition-all duration-300 hover:shadow-2xl animate-slide-up">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gradient-to-r from-teal-800 to-teal-700">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider">
                                        Usuario
                                    </th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider">
                                        Fecha de Creación
                                    </th>
                                    <th class="px-6 py-4 text-right text-sm font-semibold text-white uppercase tracking-wider">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-teal-100">
                                <tr v-for="(usuario, index) in usuarios" :key="usuario.id" 
                                    class="hover:bg-gradient-to-r hover:from-teal-50 hover:to-white transition-all duration-300 group animate-fade-in-up"
                                    :style="{ animationDelay: `${index * 100}ms` }">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-gradient-to-br from-teal-500 to-teal-600 rounded-full flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300">
                                                <span class="text-white font-bold text-sm">{{ usuario.usuario.charAt(0).toUpperCase() }}</span>
                                            </div>
                                            <div>
                                                <div class="text-sm font-semibold text-teal-900">{{ usuario.usuario }}</div>
                                                <div class="text-xs text-teal-600">ID: {{ usuario.id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 text-teal-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span class="text-sm text-teal-700">{{ new Date(usuario.created_at).toLocaleDateString('es-ES', { 
                                                year: 'numeric', 
                                                month: 'long', 
                                                day: 'numeric' 
                                            }) }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            <button @click="openEditModal(usuario)"
                                                class="group relative px-4 py-2 bg-gradient-to-r from-teal-600 to-teal-700 text-white rounded-lg text-sm font-medium shadow-md hover:shadow-lg transition-all duration-300 transform hover:scale-105 hover:from-teal-700 hover:to-teal-800 focus:outline-none focus:ring-3 focus:ring-teal-300">
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-4 h-4 transition-transform group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                    Editar
                                                </span>
                                            </button>
                                            <button @click="deleteUsuario(usuario)"
                                                class="group relative px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg text-sm font-medium shadow-md hover:shadow-lg transition-all duration-300 transform hover:scale-105 hover:from-red-600 hover:to-red-700 focus:outline-none focus:ring-3 focus:ring-red-300">
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                    Eliminar
                                                </span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <Transition 
            enter-active-class="transition-all duration-300 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-all duration-200 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0">
            <div v-if="showModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-50">
                <Transition 
                    enter-active-class="transition-all duration-300 ease-out"
                    enter-from-class="opacity-0 scale-95 translate-y-8"
                    enter-to-class="opacity-100 scale-100 translate-y-0"
                    leave-active-class="transition-all duration-200 ease-in"
                    leave-from-class="opacity-100 scale-100 translate-y-0"
                    leave-to-class="opacity-0 scale-95 translate-y-8">
                    <div class="bg-white rounded-2xl shadow-2xl border border-teal-100 w-full max-w-md mx-4 overflow-hidden">
                        <div class="bg-gradient-to-r from-teal-800 to-teal-700 px-6 py-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    {{ editingUsuario ? 'Editar Usuario' : 'Nuevo Usuario' }}
                                </h3>
                                <button @click="closeModal" 
                                    class="text-white/80 hover:text-white hover:bg-white/20 rounded-full p-1 transition-all duration-200">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <form @submit.prevent="saveUsuario" class="p-6 space-y-6">
                            <div class="space-y-4">
                                <div class="form-group">
                                    <label class="block text-sm font-semibold text-teal-800 mb-2" for="usuario">
                                        Nombre de Usuario
                                    </label>
                                    <div class="relative">
                                        <input v-model="formData.usuario" type="text" id="usuario"
                                            class="w-full px-4 py-3 bg-gray-50 border border-teal-200 rounded-xl text-teal-900 placeholder-teal-400 focus:outline-none focus:ring-3 focus:ring-teal-300 focus:border-teal-500 transition-all duration-300"
                                            placeholder="Ingresa el nombre de usuario"
                                            required>
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                            <svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="block text-sm font-semibold text-teal-800 mb-2" for="password">
                                        Contraseña {{ editingUsuario ? '(opcional)' : '' }}
                                    </label>
                                    <div class="relative">
                                        <input v-model="formData.password" type="password" id="password"
                                            class="w-full px-4 py-3 bg-gray-50 border border-teal-200 rounded-xl text-teal-900 placeholder-teal-400 focus:outline-none focus:ring-3 focus:ring-teal-300 focus:border-teal-500 transition-all duration-300"
                                            placeholder="Ingresa la contraseña"
                                            :required="!editingUsuario">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                            <svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="block text-sm font-semibold text-teal-800 mb-2" for="password_confirmation">
                                        Confirmar Contraseña
                                    </label>
                                    <div class="relative">
                                        <input v-model="formData.password_confirmation" type="password" id="password_confirmation"
                                            class="w-full px-4 py-3 bg-gray-50 border border-teal-200 rounded-xl text-teal-900 placeholder-teal-400 focus:outline-none focus:ring-3 focus:ring-teal-300 focus:border-teal-500 transition-all duration-300"
                                            placeholder="Confirma la contraseña"
                                            :required="!editingUsuario">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                            <svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex gap-3 pt-4">
                                <button type="button" @click="closeModal"
                                    class="flex-1 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-medium hover:bg-gray-200 focus:outline-none focus:ring-3 focus:ring-gray-300 transition-all duration-300 transform hover:scale-105">
                                    Cancelar
                                </button>
                                <button type="submit"
                                    class="flex-1 px-6 py-3 bg-gradient-to-r from-teal-700 to-teal-800 text-white rounded-xl font-medium shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 hover:from-teal-800 hover:to-teal-900 focus:outline-none focus:ring-3 focus:ring-teal-300">
                                    {{ editingUsuario ? 'Actualizar' : 'Crear' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </Transition>
            </div>
        </Transition>
    </AppLayout>
</template>

<style scoped>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(-10px);
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
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.6s ease-out;
}

.animate-slide-up {
    animation: slide-up 0.8s ease-out;
}

.animate-fade-in-up {
    animation: fade-in-up 0.5s ease-out forwards;
    opacity: 0;
}

.form-group {
    transform: translateY(0);
    transition: transform 0.2s ease;
}

.form-group:focus-within {
    transform: translateY(-2px);
}
</style>