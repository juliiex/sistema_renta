@extends('layouts.app')

@section('title', 'Editar Perfil')

@section('content')
<div class="bg-gray-100 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Encabezado -->
            <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-blue-500 to-indigo-600">
                <h2 class="text-xl font-bold text-white">Editar Perfil</h2>
            </div>

            <div class="p-6">
                {{-- Mostrar mensajes de estado --}}
                @if(session('status'))
                    <div class="mb-6 p-3 bg-green-100 text-green-800 rounded-md flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                {{-- Mostrar errores de validación --}}
                @if ($errors->any())
                    <div class="mb-6 p-3 bg-red-100 text-red-800 rounded-md">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <span class="font-medium">Por favor corrige los siguientes errores:</span>
                        </div>
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Avatar -->
                    <div class="flex flex-col items-center mb-6">
                        <div class="mb-4">
                            @if($user->avatar)
                                <img src="{{ asset('storage/avatars/' . $user->avatar) }}"
                                     alt="Avatar"
                                     class="w-32 h-32 rounded-full object-cover border-4 border-gray-200 shadow-md">
                            @else
                                <div class="w-32 h-32 rounded-full border-4 border-gray-200 shadow-md bg-gradient-to-r from-blue-400 to-indigo-500 flex items-center justify-center text-white text-2xl font-bold">
                                    {{ substr($user->nombre, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <label class="block">
                            <span class="sr-only">Seleccionar nueva imagen</span>
                            <input type="file"
                                   name="avatar"
                                   accept="image/*"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                        </label>
                        <p class="text-sm text-gray-500 mt-1">JPG, PNG o GIF. Máximo 1MB.</p>
                    </div>

                    <div class="space-y-6">
                        <!-- Nombre -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-user text-gray-400 mr-1"></i> Nombre
                            </label>
                            <input
                                type="text"
                                name="name"
                                id="name"
                                value="{{ old('name', $user->nombre) }}"
                                required
                                class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                placeholder="Tu nombre completo"
                            >
                        </div>

                        <!-- Correo electrónico (solo mostrar, no editable) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-envelope text-gray-400 mr-1"></i> Correo electrónico
                            </label>
                            <div class="w-full p-3 bg-gray-50 border border-gray-300 rounded-md text-gray-600">
                                {{ $user->correo }}
                                <input type="hidden" name="email" value="{{ $user->correo }}">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">El correo electrónico no se puede modificar.</p>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4 border-t border-gray-200">
                        <a href="{{ route('profile.show') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-3 transition-all duration-200">
                            <i class="fas fa-arrow-left mr-2"></i> Volver
                        </a>
                        <button
                            type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200"
                        >
                            <i class="fas fa-save mr-2"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Consejos adicionales -->
        <div class="mt-6 bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-blue-50">
                <h3 class="text-md font-medium text-blue-800">
                    <i class="fas fa-info-circle mr-1"></i> Consejos para tu perfil
                </h3>
            </div>
            <div class="px-6 py-4">
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                        <span>Utiliza una foto de perfil clara para que los propietarios puedan identificarte.</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                        <span>Un nombre completo y correcto ayuda a generar confianza en tus interacciones.</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                        <span>Para cambiar tu correo electrónico o contraseña, contacta con el administrador del sistema.</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
