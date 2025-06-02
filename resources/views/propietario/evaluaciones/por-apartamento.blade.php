@extends('layouts.dashboard-sidebar')

@section('title', 'Evaluaciones del Apartamento ' . $apartamento->numero_apartamento)

@section('dashboard-content')
<div class="container mx-auto px-4">
    <div class="flex items-center mb-6">
        <a href="{{ route('propietario.evaluaciones.index') }}" class="text-gray-600 hover:text-indigo-600 mr-2">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">
            Evaluaciones del Apartamento {{ $apartamento->numero_apartamento }}
        </h1>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="p-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-gray-800">Detalles del apartamento</h2>
                <p class="text-sm text-gray-500">{{ $apartamento->edificio->nombre }} - Piso {{ $apartamento->piso }}</p>
            </div>
            <a href="{{ route('propietario.apartamentos.show', $apartamento->id) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                Ver apartamento <i class="fas fa-external-link-alt ml-1"></i>
            </a>
        </div>
        <div class="p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="mb-4 md:mb-0">
                    <div class="flex items-center mb-2">
                        <div class="w-20 h-20 bg-gray-200 rounded-lg overflow-hidden">
                            @if($apartamento->imagen)
                            <img src="{{ asset('storage/' . $apartamento->imagen) }}" alt="{{ $apartamento->numero_apartamento }}" class="w-full h-full object-cover">
                            @else
                            <div class="flex items-center justify-center h-full bg-gray-200 text-gray-400">
                                <i class="fas fa-home text-4xl"></i>
                            </div>
                            @endif
                        </div>
                        <div class="ml-4">
                            <h3 class="font-semibold">Apartamento {{ $apartamento->numero_apartamento }}</h3>
                            <div class="flex items-center mt-1">
                                @if($apartamento->estado == 'Disponible')
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Disponible</span>
                                @elseif($apartamento->estado == 'Ocupado')
                                <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">Ocupado</span>
                                @elseif($apartamento->estado == 'En mantenimiento')
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">En mantenimiento</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if($evaluaciones->count() > 0)
                <div class="bg-indigo-50 p-4 rounded-lg text-center">
                    <div class="text-3xl font-bold text-indigo-700 mb-1">{{ number_format($promedioCalificacion, 1) }}</div>
                    <div class="flex justify-center mb-1">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= round($promedioCalificacion))
                                <i class="fas fa-star text-yellow-400"></i>
                            @else
                                <i class="far fa-star text-gray-300"></i>
                            @endif
                        @endfor
                    </div>
                    <p class="text-sm text-gray-600">{{ $evaluaciones->count() }} evaluaciones</p>
                </div>
                @else
                <div class="bg-gray-100 p-4 rounded-lg text-center">
                    <div class="text-gray-500">Sin evaluaciones</div>
                    <div class="flex justify-center my-1">
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="far fa-star text-gray-300"></i>
                        @endfor
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
        <i class="fas fa-star mr-2 text-indigo-500"></i>
        Evaluaciones recibidas
    </h2>

    @if($evaluaciones->isEmpty())
    <div class="bg-white rounded-lg shadow-md p-8 text-center">
        <p class="text-gray-500">Este apartamento aún no tiene evaluaciones.</p>
    </div>
    @else
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="divide-y divide-gray-200">
            @foreach($evaluaciones as $evaluacion)
            <div class="p-6">
                <div class="flex items-start">
                    <div class="mr-4">
                        <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="text-md font-medium text-gray-900">{{ $evaluacion->usuario->nombre }}</h3>
                            <span class="text-sm text-gray-500">
                                {{ $evaluacion->fecha_evaluacion ? $evaluacion->fecha_evaluacion->format('d/m/Y') : 'Fecha no disponible' }}
                            </span>
                        </div>
                        <div class="flex items-center mb-3">
                            <div class="flex">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $evaluacion->calificacion)
                                        <i class="fas fa-star text-yellow-400"></i>
                                    @else
                                        <i class="far fa-star text-gray-300"></i>
                                    @endif
                                @endfor
                            </div>
                            <span class="ml-2 text-sm text-gray-600">{{ $evaluacion->calificacion }}/5</span>
                        </div>
                        <p class="text-gray-700">{{ $evaluacion->comentario }}</p>
                    </div>
                    <div class="ml-4">
                        <a href="{{ route('propietario.evaluaciones.show', $evaluacion->id) }}" class="text-indigo-600 hover:text-indigo-900 text-xs font-semibold">
                            Ver detalle
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
