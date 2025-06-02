@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center mb-6">
        <a href="{{ route('usuario.reportes.lista') }}" class="flex items-center text-blue-600 hover:underline mr-4">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Volver a la lista
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Reporte #{{ $reporte->id }}</h1>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200 mb-6">
        <div class="p-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">{{ $reporte->tipo }}</h2>
                    <p class="text-gray-600">Reportado el {{ $reporte->fecha_reporte->format('d/m/Y \a \l\a\s H:i') }}</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                    @if($reporte->estado == 'pendiente') bg-yellow-100 text-yellow-800
                    @elseif($reporte->estado == 'en_proceso') bg-blue-100 text-blue-800
                    @elseif($reporte->estado == 'resuelto') bg-green-100 text-green-800
                    @else bg-red-100 text-red-800 @endif">
                    {{ ucfirst(str_replace('_', ' ', $reporte->estado)) }}
                </span>
            </div>

            <div class="mb-6">
                <h3 class="text-md font-medium text-gray-700 mb-2">Descripción del problema</h3>
                <div class="bg-gray-50 p-4 rounded-md">
                    <p class="text-gray-800 whitespace-pre-line">{{ $reporte->descripcion }}</p>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-md font-medium text-gray-700 mb-2">Información del apartamento</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-4 rounded-md">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mr-4">
                                <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-sm text-gray-500">Apartamento</h4>
                                <p class="mt-1 text-md font-medium text-gray-800">{{ $reporte->apartamento->numero_apartamento }} - {{ $reporte->apartamento->edificio->nombre }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-md">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mr-4">
                                <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-sm text-gray-500">Dirección</h4>
                                <p class="mt-1 text-md font-medium text-gray-800">{{ $reporte->apartamento->edificio->direccion }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($reporte->respuesta)
                <div class="border-t border-gray-200 pt-6 mt-6">
                    <h3 class="text-md font-medium text-gray-700 mb-2">Respuesta de la administración</h3>
                    <div class="bg-blue-50 p-4 rounded-md">
                        <div class="mb-2 text-sm text-gray-500">
                            Respondido el {{ $reporte->fecha_respuesta->format('d/m/Y \a \l\a\s H:i') }}
                        </div>
                        <p class="text-gray-800 whitespace-pre-line">{{ $reporte->respuesta }}</p>
                    </div>
                </div>
            @endif

            @if($reporte->estado == 'resuelto')
                <div class="border-t border-gray-200 pt-6 mt-6">
                    <div class="bg-green-50 p-4 rounded-md flex items-start">
                        <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h4 class="text-md font-medium text-green-800">¡Problema resuelto!</h4>
                            <p class="text-sm text-green-700 mt-1">Este problema ha sido resuelto. Si tienes alguna otra consulta o el problema persiste, puedes crear un nuevo reporte.</p>
                        </div>
                    </div>
                </div>
            @elseif($reporte->estado == 'en_proceso')
                <div class="border-t border-gray-200 pt-6 mt-6">
                    <div class="bg-blue-50 p-4 rounded-md flex items-start">
                        <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        <div>
                            <h4 class="text-md font-medium text-blue-800">Problema en proceso de solución</h4>
                            <p class="text-sm text-blue-700 mt-1">Estamos trabajando para resolver este problema. Te informaremos cuando esté solucionado.</p>
                        </div>
                    </div>
                </div>
            @elseif($reporte->estado == 'cancelado')
                <div class="border-t border-gray-200 pt-6 mt-6">
                    <div class="bg-red-50 p-4 rounded-md flex items-start">
                        <svg class="w-6 h-6 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h4 class="text-md font-medium text-red-800">Reporte cancelado</h4>
                            <p class="text-sm text-red-700 mt-1">Este reporte ha sido cancelado. Si necesitas reportar el problema nuevamente, puedes crear un nuevo reporte.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="flex justify-between">
        <a href="{{ route('usuario.reportes.lista') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md transition duration-150">
            Volver a mis reportes
        </a>

        @if($reporte->estado == 'pendiente')
            <a href="{{ route('usuario.reportes.nuevo') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-150">
                Reportar otro problema
            </a>
        @endif
    </div>
</div>
@endsection
