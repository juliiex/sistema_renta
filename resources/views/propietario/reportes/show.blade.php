@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center mb-6">
        <a href="{{ route('propietario.reportes.index') }}" class="flex items-center text-blue-600 hover:underline mr-4">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Volver a la lista
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Detalle del Reporte de Problema</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Información del Apartamento -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden col-span-1">
            <div class="bg-blue-600 text-white px-6 py-4">
                <h2 class="text-lg font-semibold">Información del Apartamento</h2>
            </div>
            <div class="p-6">
                <div class="mb-4">
                    <h3 class="text-sm font-medium text-gray-500">Apartamento</h3>
                    <p class="mt-1 text-lg font-medium text-gray-800">{{ $reporte->apartamento->numero_apartamento }}</p>
                </div>
                <div class="mb-4">
                    <h3 class="text-sm font-medium text-gray-500">Edificio</h3>
                    <p class="mt-1 text-lg font-medium text-gray-800">{{ $reporte->apartamento->edificio->nombre }}</p>
                </div>
                <div class="mb-4">
                    <h3 class="text-sm font-medium text-gray-500">Piso</h3>
                    <p class="mt-1 text-lg font-medium text-gray-800">{{ $reporte->apartamento->piso }}</p>
                </div>
                <div class="mb-4">
                    <h3 class="text-sm font-medium text-gray-500">Inquilino</h3>
                    <p class="mt-1 text-lg font-medium text-gray-800">{{ $reporte->usuario->nombre }}</p>
                </div>
            </div>
        </div>

        <!-- Detalles del Reporte -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden col-span-2">
            <div class="bg-blue-600 text-white px-6 py-4">
                <h2 class="text-lg font-semibold">Detalles del Problema</h2>
            </div>
            <div class="p-6">
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="mb-4">
                    <h3 class="text-sm font-medium text-gray-500">Tipo de problema</h3>
                    <p class="mt-1 text-lg font-medium text-gray-800">{{ ucfirst($reporte->tipo) }}</p>
                </div>

                <div class="mb-4">
                    <h3 class="text-sm font-medium text-gray-500">Estado actual</h3>
                    <div class="mt-2">
                        @php
                            $estado = strtolower($reporte->estado);
                        @endphp
                        @if($estado == 'cerrado')
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Cerrado</span>
                        @elseif($estado == 'atendido')
                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">Atendido</span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Pendiente</span>
                        @endif
                    </div>
                </div>

                <div class="mb-4">
                    <h3 class="text-sm font-medium text-gray-500">Fecha del reporte</h3>
                    <p class="mt-1 text-md text-gray-800">{{ $reporte->fecha_reporte->format('d/m/Y H:i:s') }}</p>
                </div>

                <div class="mb-6">
                    <h3 class="text-sm font-medium text-gray-500">Descripción del problema</h3>
                    <div class="mt-2 p-4 bg-gray-50 rounded-md text-gray-800">
                        {{ $reporte->descripcion }}
                    </div>
                </div>

                <!-- Formulario para actualizar el estado -->
                <form action="{{ route('propietario.reportes.actualizar-estado', $reporte->id) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="estado" class="block text-sm font-medium text-gray-700 mb-1">Actualizar estado:</label>
                        <select name="estado" id="estado" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="pendiente" {{ $estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="atendido" {{ $estado == 'atendido' ? 'selected' : '' }}>Atendido</option>
                            <option value="cerrado" {{ $estado == 'cerrado' ? 'selected' : '' }}>Cerrado</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Actualizar estado
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
