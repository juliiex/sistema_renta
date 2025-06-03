@extends('layouts.dashboard-sidebar')

@section('title', 'Detalle de Queja')

@section('dashboard-content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center mb-6">
        <a href="{{ route('propietario.quejas.index') }}" class="flex items-center text-blue-600 hover:underline mr-4">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Volver a la lista
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Detalle de la Queja o Sugerencia</h1>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
        <div class="bg-blue-600 text-white px-6 py-4">
            <h2 class="text-lg font-semibold">Información de la Queja</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-gray-500">Usuario</h3>
                        <p class="mt-1 text-lg font-medium text-gray-800">{{ $queja->usuario->nombre }}</p>
                    </div>
                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-gray-500">Tipo</h3>
                        <p class="mt-1 text-lg font-medium text-gray-800">{{ ucfirst($queja->tipo) }}</p>
                    </div>
                </div>
                <div>
                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-gray-500">Fecha de envío</h3>
                        <p class="mt-1 text-lg font-medium text-gray-800">{{ $queja->fecha_envio->format('d/m/Y') }}</p>
                    </div>
                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-gray-500">Correo electrónico</h3>
                        <p class="mt-1 text-lg font-medium text-gray-800">{{ $queja->usuario->correo ?? 'No disponible' }}</p>
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Descripción completa</h3>
                <div class="p-4 bg-gray-50 rounded-md text-gray-800 whitespace-pre-line">
                    {{ $queja->descripcion }}
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Opciones adicionales</h2>
        </div>
        <div class="p-6">
            <div class="flex justify-center">
                <a href="mailto:{{ $queja->usuario->correo ?? '' }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Contactar al usuario
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
