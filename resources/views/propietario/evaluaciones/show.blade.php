@extends('layouts.dashboard-sidebar')

@section('title', 'Detalle de Evaluación')

@section('dashboard-content')
<div class="container mx-auto px-4">
    <div class="flex items-center mb-6">
        <a href="{{ route('propietario.evaluaciones.index') }}" class="text-gray-600 hover:text-indigo-600 mr-2">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Detalle de Evaluación</h1>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="p-6">
            <div class="flex flex-col md:flex-row md:items-start">
                <!-- Información del apartamento -->
                <div class="md:w-1/3 mb-6 md:mb-0 md:pr-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-home text-indigo-500 mr-2"></i>
                        Información del apartamento
                    </h2>
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="mb-3">
                            <h3 class="text-sm font-medium text-gray-500">Apartamento</h3>
                            <p class="text-gray-900">{{ $evaluacion->apartamento->numero_apartamento }}</p>
                        </div>
                        <div class="mb-3">
                            <h3 class="text-sm font-medium text-gray-500">Edificio</h3>
                            <p class="text-gray-900">{{ $evaluacion->apartamento->edificio->nombre }}</p>
                        </div>
                        <div class="mb-3">
                            <h3 class="text-sm font-medium text-gray-500">Dirección</h3>
                            <p class="text-gray-900">{{ $evaluacion->apartamento->edificio->direccion }}</p>
                        </div>
                        <div>
                            <a href="{{ route('propietario.apartamentos.show', $evaluacion->apartamento->id) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium flex items-center">
                                <i class="fas fa-external-link-alt mr-1 text-xs"></i>
                                Ver apartamento
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Evaluación -->
                <div class="md:w-2/3 md:border-l md:border-gray-200 md:pl-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-star text-indigo-500 mr-2"></i>
                            Evaluación
                        </h2>
                        <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded">
                            {{ $evaluacion->fecha_evaluacion ? $evaluacion->fecha_evaluacion->format('d/m/Y') : 'Fecha no disponible' }}
                        </span>
                    </div>

                    <div class="mb-4">
                        <div class="flex items-center">
                            <div class="flex">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $evaluacion->calificacion)
                                        <i class="fas fa-star text-yellow-400 text-xl"></i>
                                    @else
                                        <i class="far fa-star text-gray-300 text-xl"></i>
                                    @endif
                                @endfor
                            </div>
                            <span class="ml-2 text-gray-600 font-medium">{{ $evaluacion->calificacion }}/5</span>
                        </div>
                    </div>

                    <div class="flex items-start mb-6">
                        <div class="mr-4">
                            <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600">
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-md font-medium text-gray-900">{{ $evaluacion->usuario->nombre }}</h3>
                            <span class="text-sm text-gray-500">Inquilino</span>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
                        <h3 class="text-md font-semibold text-gray-800 mb-2">Comentario:</h3>
                        <p class="text-gray-700">{{ $evaluacion->comentario }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
