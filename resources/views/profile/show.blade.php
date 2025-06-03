@extends('layouts.app')

@section('title', 'Perfil de Usuario')

@section('content')
<div class="bg-gray-100 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Encabezado del perfil -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Banner de perfil -->
            <div class="h-32 bg-gradient-to-r from-blue-500 to-indigo-600"></div>

            <div class="relative px-4 sm:px-6 lg:px-8">
                <!-- Avatar -->
                <div class="absolute -mt-16">
                    @if (auth()->user()->avatar)
                        <img src="{{ asset('storage/avatars/' . auth()->user()->avatar) }}"
                             alt="Avatar de {{ auth()->user()->nombre }}"
                             class="w-32 h-32 rounded-full border-4 border-white shadow-md object-cover bg-white">
                    @else
                        <div class="w-32 h-32 rounded-full border-4 border-white shadow-md bg-gradient-to-r from-blue-400 to-indigo-500 flex items-center justify-center text-white text-xl font-bold">
                            {{ substr(auth()->user()->nombre, 0, 1) }}
                        </div>
                    @endif
                </div>

                <!-- Información de usuario -->
                <div class="pt-20 pb-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ auth()->user()->nombre }}</h1>
                            <div class="mt-1 flex items-center">
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                    {{ auth()->user()->roles->pluck('nombre')->join(', ') ?? 'Cliente' }}
                                </span>
                                <span class="ml-4 text-sm text-gray-500">
                                    Miembro desde {{ auth()->user()->created_at->format('d/m/Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detalles del perfil -->
            <div class="border-t border-gray-200">
                <div class="px-4 py-6 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Correo Electrónico</p>
                                <p class="mt-1 text-base font-medium text-gray-900">{{ auth()->user()->correo }}</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <i class="fas fa-user-shield text-gray-400"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Tipo de Usuario</p>
                                <p class="mt-1 text-base font-medium text-gray-900">
                                    {{ auth()->user()->roles->pluck('nombre')->join(', ') ?? 'Cliente' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <i class="fas fa-calendar-alt text-gray-400"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Fecha de Registro</p>
                                <p class="mt-1 text-base font-medium text-gray-900">{{ auth()->user()->created_at->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones de perfil -->
            <div class="bg-gray-50 px-4 py-4 sm:px-6 lg:px-8 border-t border-gray-200">
                <div class="flex justify-end">
                    <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                        <i class="fas fa-user-edit mr-2"></i> Editar Perfil
                    </a>
                </div>
            </div>
        </div>

        <!-- Sección adicional para más información (opcional) -->
        <div class="mt-6 bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Actividad Reciente</h3>
            </div>
            <div class="px-4 py-6 sm:px-6 lg:px-8">
                <div class="space-y-4">
                    <!-- Puedes añadir contenido real según tu aplicación -->
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 mt-1">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-home text-blue-500"></i>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Visita a apartamentos</p>
                            <p class="text-sm text-gray-500">Has visitado los apartamentos disponibles recientemente.</p>
                            <p class="text-xs text-gray-400 mt-1">Hace 2 días</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 mt-1">
                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                <i class="fas fa-check text-green-500"></i>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Perfil actualizado</p>
                            <p class="text-sm text-gray-500">Has actualizado tu información de perfil.</p>
                            <p class="text-xs text-gray-400 mt-1">Hace 1 semana</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
