@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center mb-6">
        <a href="{{ route('usuario.solicitudes.lista') }}" class="flex items-center text-blue-600 hover:underline mr-4">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Volver a mis solicitudes
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Detalles de la Solicitud</h1>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Información de la solicitud -->
        <div class="md:col-span-2">
            <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h2 class="text-lg font-bold text-gray-800">Solicitud #{{ $solicitud->id }}</h2>
                        @if($solicitud->estado_solicitud == 'pendiente')
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Pendiente
                            </span>
                        @elseif($solicitud->estado_solicitud == 'aprobada')
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Aprobada
                            </span>
                        @elseif($solicitud->estado_solicitud == 'cancelada')
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                Cancelada
                            </span>
                        @else
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Rechazada
                            </span>
                        @endif
                    </div>
                </div>

                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-sm font-medium text-gray-500 mb-4">Detalles de la Solicitud</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Fecha de solicitud</p>
                            <p class="text-base font-medium">{{ $solicitud->fecha_solicitud->format('d/m/Y - H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Estado</p>
                            <p class="text-base font-medium capitalize">{{ $solicitud->estado_solicitud }}</p>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4">
                    <h3 class="text-sm font-medium text-gray-500 mb-4">Información del Solicitante</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Nombre</p>
                            <p class="text-base font-medium">{{ $solicitud->usuario->nombre }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Correo electrónico</p>
                            <p class="text-base font-medium">{{ $solicitud->usuario->correo }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Teléfono</p>
                            <p class="text-base font-medium">{{ $solicitud->usuario->telefono ?? 'No especificado' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Acciones -->
                <div class="px-6 py-4 bg-gray-50 flex justify-end">
                    @if($solicitud->estado_solicitud == 'pendiente')
                        <form action="{{ route('usuario.solicitudes.cancelar', $solicitud->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-md" onclick="return confirm('¿Estás seguro de que deseas cancelar esta solicitud?')">
                                Cancelar solicitud
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Información del apartamento -->
        <div class="md:col-span-1">
            <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
                <div class="h-40 bg-gray-200 relative">
                    @if($solicitud->apartamento->imagen)
                        <img src="{{ asset('storage/' . $solicitud->apartamento->imagen) }}" alt="Apartamento {{ $solicitud->apartamento->numero_apartamento }}" class="w-full h-full object-cover">
                    @else
                        <div class="flex items-center justify-center w-full h-full bg-gray-200 text-gray-500">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                        </div>
                    @endif
                </div>

                <div class="p-4">
                    <h3 class="font-bold text-lg mb-2">Apartamento {{ $solicitud->apartamento->numero_apartamento }}</h3>
                    <p class="text-gray-700 mb-4">{{ $solicitud->apartamento->edificio->nombre }}</p>

                    <ul class="space-y-2 mb-4">
                        <li class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            Piso {{ $solicitud->apartamento->piso }}
                        </li>
                        <li class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5v-4m0 4h-4m4 0l-5-5"></path>
                            </svg>
                            {{ $solicitud->apartamento->tamaño }} m²
                        </li>
                        <li class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            ${{ number_format($solicitud->apartamento->precio, 0) }} /mes
                        </li>
                    </ul>

                    <a href="{{ route('usuario.apartamentos.detalle', $solicitud->apartamento->id) }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center font-medium py-2 px-4 rounded-md">
                        Ver apartamento
                    </a>
                </div>
            </div>

            <!-- Información adicional si la solicitud fue aprobada -->
            @if($solicitud->estado_solicitud == 'aprobada')
                <div class="mt-4 bg-green-50 border border-green-200 rounded-lg p-4">
                    <h3 class="font-medium text-green-800 flex items-center mb-2">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        ¡Tu solicitud fue aprobada!
                    </h3>
                    <p class="text-sm text-green-700 mb-2">
                        Por favor, contacta con administración para formalizar el contrato de arrendamiento.
                    </p>

                    <div class="mt-3 flex justify-center">
                        <a href="#" class="text-sm text-green-700 hover:text-green-800 underline">
                            Contactar administración
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
