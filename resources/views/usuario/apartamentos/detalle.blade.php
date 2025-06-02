@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center mb-6">
        <a href="{{ route('usuario.apartamentos.explorar') }}" class="flex items-center text-blue-600 hover:underline mr-4">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Volver a la lista
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Apartamento {{ $apartamento->numero_apartamento }}</h1>
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

    <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200">
        <!-- Información del apartamento -->
        <div class="md:flex">
            <!-- Galería de imágenes -->
            <div class="md:w-1/2">
                <div class="h-96 bg-gray-200 relative overflow-hidden">
                    @if($apartamento->imagen)
                        <img src="{{ asset('storage/' . $apartamento->imagen) }}" alt="Apartamento {{ $apartamento->numero_apartamento }}" class="w-full h-full object-cover">
                    @else
                        <div class="flex items-center justify-center w-full h-full bg-gray-200 text-gray-500">
                            <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                        </div>
                    @endif
                    <div class="absolute top-0 right-0 bg-green-500 text-white px-3 py-1 text-sm font-bold uppercase">
                        {{ $apartamento->estado }}
                    </div>
                </div>
            </div>

            <!-- Información detallada -->
            <div class="md:w-1/2 p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Apartamento {{ $apartamento->numero_apartamento }}</h2>
                        <p class="text-gray-600 mb-4">{{ $apartamento->edificio->nombre }}</p>

                        <!-- Calificación -->
                        <div class="flex items-center mb-4">
                            <div class="flex items-center mr-2">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $apartamento->calificacion_promedio)
                                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @elseif($i - 0.5 <= $apartamento->calificacion_promedio)
                                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @endif
                                @endfor
                            </div>
                            <span class="text-sm text-gray-600">
                                {{ $apartamento->calificacion_promedio }} ({{ $apartamento->total_evaluaciones }} evaluaciones)
                            </span>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="bg-blue-600 text-white px-4 py-2 rounded-lg text-xl font-bold">
                            ${{ number_format($apartamento->precio, 0) }} <span class="text-sm font-normal">/mes</span>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-4 mt-4">
                    <div class="grid grid-cols-2 gap-x-4 gap-y-6 mb-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Edificio</h3>
                            <p class="mt-1 text-lg font-medium text-gray-800">{{ $apartamento->edificio->nombre }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Piso</h3>
                            <p class="mt-1 text-lg font-medium text-gray-800">{{ $apartamento->piso }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Tamaño</h3>
                            <p class="mt-1 text-lg font-medium text-gray-800">{{ $apartamento->tamaño }} m²</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Estado</h3>
                            <p class="mt-1 text-lg font-medium text-gray-800 capitalize">{{ $apartamento->estado }}</p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Dirección</h3>
                        <div class="bg-gray-50 rounded-md p-3 flex items-start">
                            <svg class="w-5 h-5 text-gray-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <p class="text-gray-700">{{ $apartamento->edificio->direccion }}</p>
                        </div>
                    </div>

                    @if($apartamento->descripcion)
                        <div class="mb-6">
                            <h3 class="text-sm font-medium text-gray-500 mb-2">Descripción</h3>
                            <div class="bg-gray-50 rounded-md p-3">
                                <p class="text-gray-700 whitespace-pre-line">{{ $apartamento->descripcion }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Botón de Solicitar -->
                    <div class="mt-8">
                        @if(isset($solicitudExistente))
                            <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4 text-center">
                                <p class="text-yellow-800 mb-2">
                                    Ya has enviado una solicitud para este apartamento.
                                </p>
                                <p class="text-yellow-800 font-medium">
                                    @if($solicitudExistente->estado_solicitud === 'pendiente')
                                        Estado actual: <span class="font-bold text-yellow-600">Pendiente</span>
                                    @elseif($solicitudExistente->estado_solicitud === 'aprobada')
                                        Estado actual: <span class="font-bold text-green-600">Aprobada</span>
                                    @endif
                                </p>
                                <a href="{{ route('usuario.solicitudes.detalle', $solicitudExistente->id) }}" class="text-blue-600 hover:underline mt-2 inline-block">
                                    Ver detalles de la solicitud
                                </a>
                            </div>
                        @else
                            <form action="{{ route('usuario.apartamentos.solicitar', $apartamento->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-4 rounded-md text-center flex items-center justify-center transition duration-150">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Solicitar este apartamento
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Información adicional del edificio -->
        <div class="border-t border-gray-200 px-6 py-5">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Acerca del edificio</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="text-sm font-medium text-gray-500 mb-2">Nombre del Edificio</h4>
                    <p class="text-gray-800 font-medium">{{ $apartamento->edificio->nombre }}</p>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-500 mb-2">Cantidad de Pisos</h4>
                    <p class="text-gray-800 font-medium">{{ $apartamento->edificio->cantidad_pisos }} pisos</p>
                </div>
                @if($apartamento->edificio->descripcion)
                    <div class="md:col-span-2">
                        <h4 class="text-sm font-medium text-gray-500 mb-2">Descripción</h4>
                        <p class="text-gray-700">{{ $apartamento->edificio->descripcion }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sección de Evaluaciones y Comentarios -->
        <div class="border-t border-gray-200 px-6 py-5">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Evaluaciones de inquilinos anteriores</h3>

            @if(isset($evaluaciones) && $evaluaciones->count() > 0)
                <div class="space-y-4">
                    @foreach($evaluaciones as $evaluacion)
                        <div class="bg-gray-50 rounded-md p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="flex items-center mb-2">
                                        <span class="font-medium text-gray-700 mr-2">{{ $evaluacion->usuario->nombre }}</span>
                                        <div class="flex">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $evaluacion->calificacion)
                                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="text-gray-600">{{ $evaluacion->comentario }}</p>
                                </div>
                                <span class="text-sm text-gray-500">{{ $evaluacion->fecha_evaluacion->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    @endforeach

                    <!-- Paginación de evaluaciones -->
                    <div class="mt-4">
                        {{ $evaluaciones->links() }}
                    </div>
                </div>
            @else
                <div class="bg-gray-50 rounded-md p-6 text-center">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <p class="text-gray-500">Este apartamento aún no tiene evaluaciones.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
