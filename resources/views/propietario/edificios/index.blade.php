@extends('layouts.dashboard-sidebar')

@section('title', 'Mis Edificios')

@section('dashboard-content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Mis Edificios</h1>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    @if($edificios->isEmpty())
    <div class="bg-white rounded-lg shadow-md p-6 text-center">
        <p class="text-gray-500">No tienes edificios registrados.</p>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($edificios as $edificio)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
            <div class="relative h-48 bg-gray-200">
                @if($edificio->imagen)
                <img src="{{ asset('storage/' . $edificio->imagen) }}" alt="{{ $edificio->nombre }}" class="w-full h-full object-cover">
                @else
                <div class="flex items-center justify-center h-full bg-gray-200 text-gray-400">
                    <i class="fas fa-building text-5xl"></i>
                </div>
                @endif
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-4">
                    <h3 class="text-white font-bold text-xl">{{ $edificio->nombre }}</h3>
                </div>
            </div>
            <div class="p-4">
                <div class="flex items-center text-sm text-gray-600 mb-2">
                    <i class="fas fa-map-marker-alt mr-2"></i>
                    <span>{{ $edificio->direccion }}</span>
                </div>
                <div class="flex items-center text-sm text-gray-600 mb-3">
                    <i class="fas fa-building mr-2"></i>
                    <span>{{ $edificio->cantidad_pisos }} pisos</span>
                </div>
                <p class="text-gray-700 text-sm mb-4 line-clamp-2">
                    {{ $edificio->descripcion ?: 'Sin descripción disponible.' }}
                </p>
                <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                    <span class="text-sm font-medium text-indigo-600">
                        {{ $edificio->apartamentos->count() }} apartamentos
                    </span>
                    <a href="{{ route('propietario.edificios.show', $edificio->id) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-semibold flex items-center">
                        Ver detalles <i class="fas fa-chevron-right ml-1 text-xs"></i>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
