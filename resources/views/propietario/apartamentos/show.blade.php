@extends('layouts.dashboard-sidebar')

@section('title', 'Apartamento ' . $apartamento->numero_apartamento)

@section('dashboard-content')
<div class="container mx-auto px-4">
    <div class="flex items-center mb-6">
        <a href="{{ route('propietario.apartamentos.index') }}" class="text-gray-600 hover:text-indigo-600 mr-2">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">
            Apartamento {{ $apartamento->numero_apartamento }}
            @if($apartamento->estado == 'Disponible')
            <span class="ml-2 bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Disponible</span>
            @elseif($apartamento->estado == 'Ocupado')
            <span class="ml-2 bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">Ocupado</span>
            @elseif($apartamento->estado == 'En mantenimiento')
            <span class="ml-2 bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">En mantenimiento</span>
            @endif
        </h1>
        <div class="ml-auto">
            <a href="{{ route('propietario.apartamentos.edit', $apartamento->id) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-md shadow-sm flex items-center">
                <i class="fas fa-edit mr-2"></i> Editar
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Información principal y foto -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="relative h-64 bg-gray-200">
                    @if($apartamento->imagen)
                    <img src="{{ asset('storage/' . $apartamento->imagen) }}" alt="Apartamento {{ $apartamento->numero_apartamento }}" class="w-full h-full object-cover">
                    @else
                    <div class="flex items-center justify-center h-full bg-gray-200 text-gray-400">
                        <i class="fas fa-home text-6xl"></i>
                    </div>
                    @endif
                </div>
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Detalles del apartamento</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Edificio</h3>
                            <p class="text-gray-900 flex items-center">
                                <i class="fas fa-building mr-2 text-indigo-500"></i>
                                {{ $apartamento->edificio ? $apartamento->edificio->nombre : 'Sin edificio' }}
                            </p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Piso</h3>
                            <p class="text-gray-900 flex items-center">
                                <i class="fas fa-layer-group mr-2 text-indigo-500"></i>
                                {{ $apartamento->piso }}
                            </p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Precio</h3>
                            <p class="text-gray-900 flex items-center">
                                <i class="fas fa-dollar-sign mr-2 text-indigo-500"></i>
                                ${{ number_format($apartamento->precio, 0, '.', ',') }} COP
                            </p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Tamaño</h3>
                            <p class="text-gray-900 flex items-center">
                                <i class="fas fa-ruler-combined mr-2 text-indigo-500"></i>
                                {{ $apartamento->tamaño }} m²
                            </p>
                        </div>
                        <div class="md:col-span-2">
                            <h3 class="text-sm font-medium text-gray-500">Descripción</h3>
                            <p class="text-gray-900">{{ $apartamento->descripcion ?: 'Sin descripción disponible.' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas de evaluaciones -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="p-4 bg-indigo-50 border-b border-indigo-100">
                    <h2 class="font-semibold text-indigo-800">Valoración general</h2>
                </div>
                <div class="p-6 flex flex-col items-center">
                    <div class="text-5xl font-bold text-indigo-600 mb-2">{{ number_format($promedioCalificacion, 1) }}</div>
                    <div class="flex mb-3">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= round($promedioCalificacion))
                                <i class="fas fa-star text-yellow-400"></i>
                            @else
                                <i class="far fa-star text-gray-300"></i>
                            @endif
                        @endfor
                    </div>
                    <p class="text-gray-500 text-sm">Basado en {{ $evaluaciones->count() }} opiniones</p>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-4 bg-indigo-50 border-b border-indigo-100">
                    <h2 class="font-semibold text-indigo-800">Estado del apartamento</h2>
                </div>
                <div class="p-6">
                    @if($apartamento->estado == 'Disponible')
                        <div class="bg-green-50 border-l-4 border-green-500 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-green-500"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-green-700">Este apartamento está disponible para alquilar</p>
                                </div>
                            </div>
                        </div>
                    @elseif($apartamento->estado == 'Ocupado')
                        <div class="bg-red-50 border-l-4 border-red-500 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-circle text-red-500"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-red-700">Este apartamento está actualmente ocupado</p>
                                </div>
                            </div>
                        </div>
                    @elseif($apartamento->estado == 'En mantenimiento')
                        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-tools text-yellow-500"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-yellow-700">Este apartamento está en mantenimiento</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Evaluaciones -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="p-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
            <h2 class="font-semibold text-gray-800">Evaluaciones del apartamento</h2>
            @if($evaluaciones->count() > 0)
                Ver todas <i class="fas fa-arrow-right ml-1"></i>
            </a>
            @endif
        </div>

        @if($evaluaciones->isEmpty())
        <div class="p-6 text-center text-gray-500">
            Este apartamento aún no ha recibido evaluaciones.
        </div>
        @else
        <div class="divide-y divide-gray-200">
            @foreach($evaluaciones->take(3) as $evaluacion)
            <div class="p-6">
                <div class="flex items-start">
                    <div class="mr-4">
                        <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center text-gray-600">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-sm font-medium text-gray-900">{{ $evaluacion->usuario->nombre }}</h3>
                        <div class="flex items-center mt-1">
                            <div class="flex">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $evaluacion->calificacion)
                                        <i class="fas fa-star text-yellow-400"></i>
                                    @else
                                        <i class="far fa-star text-gray-300"></i>
                                    @endif
                                @endfor
                            </div>
                            <span class="text-xs text-gray-500 ml-2">
                                {{ $evaluacion->fecha_evaluacion ? $evaluacion->fecha_evaluacion->format('d/m/Y') : 'Fecha no disponible' }}
                            </span>
                        </div>
                        <p class="mt-2 text-gray-800">{{ $evaluacion->comentario }}</p>
                    </div>
                    <div class="ml-4">
                        <a href="{{ route('propietario.evaluaciones.show', $evaluacion->id) }}" class="text-indigo-600 hover:text-indigo-900 text-xs font-semibold">
                            Ver detalles
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @if($evaluaciones->count() > 3)
        <div class="p-4 bg-gray-50 border-t border-gray-200 text-center">
            <a href="{{ route('propietario.apartamentos.evaluaciones', $apartamento->id) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                Ver todas las evaluaciones ({{ $evaluaciones->count() }}) <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        @endif
        @endif
    </div>
</div>
@endsection
