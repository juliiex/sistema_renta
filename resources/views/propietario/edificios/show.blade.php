@extends('layouts.dashboard-sidebar')

@section('title', $edificio->nombre)

@section('dashboard-content')
<div class="container mx-auto px-4">
    <div class="flex items-center mb-6">
        <a href="{{ route('propietario.edificios.index') }}" class="text-gray-600 hover:text-indigo-600 mr-2">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">{{ $edificio->nombre }}</h1>
        <a href="{{ route('propietario.edificios.edit', $edificio->id) }}" class="ml-auto bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-md shadow-sm flex items-center">
            <i class="fas fa-edit mr-2"></i> Editar
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="md:flex">
            <div class="md:w-1/3 bg-gray-200">
                @if($edificio->imagen)
                <img src="{{ asset('storage/' . $edificio->imagen) }}" alt="{{ $edificio->nombre }}" class="w-full h-full object-cover">
                @else
                <div class="flex items-center justify-center h-full min-h-[300px] bg-gray-200 text-gray-400">
                    <i class="fas fa-building text-6xl"></i>
                </div>
                @endif
            </div>
            <div class="md:w-2/3 p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Dirección</h3>
                        <p class="text-gray-900 flex items-center">
                            <i class="fas fa-map-marker-alt mr-2 text-indigo-500"></i>
                            {{ $edificio->direccion }}
                        </p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Pisos</h3>
                        <p class="text-gray-900 flex items-center">
                            <i class="fas fa-layers mr-2 text-indigo-500"></i>
                            {{ $edificio->cantidad_pisos }}
                        </p>
                    </div>
                    <div class="md:col-span-2">
                        <h3 class="text-sm font-medium text-gray-500">Descripción</h3>
                        <p class="text-gray-900">{{ $edificio->descripcion ?: 'Sin descripción disponible.' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold text-gray-800 flex items-center">
            <i class="fas fa-home mr-2 text-indigo-500"></i>
            Apartamentos destacados
        </h2>

        @if($totalApartamentos > 3)
        <a href="{{ route('propietario.apartamentos.index') }}" class="text-indigo-600 hover:text-indigo-800 flex items-center">
            Ver todos los apartamentos ({{ $totalApartamentos }})
            <i class="fas fa-arrow-right ml-2"></i>
        </a>
        @endif
    </div>

    @if($apartamentos->isEmpty())
    <div class="bg-white rounded-lg shadow-md p-6 text-center">
        <p class="text-gray-500">No hay apartamentos registrados en este edificio.</p>
        <a href="{{ route('propietario.apartamentos.create') }}" class="mt-4 inline-block bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-md shadow-sm">
            <i class="fas fa-plus mr-2"></i> Añadir apartamento
        </a>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @foreach($apartamentos as $apartamento)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
            <div class="relative h-40 bg-gray-200">
                @if($apartamento->imagen)
                <img src="{{ asset('storage/' . $apartamento->imagen) }}" alt="Apartamento {{ $apartamento->numero_apartamento }}" class="w-full h-full object-cover">
                @else
                <div class="flex items-center justify-center h-full bg-gray-200 text-gray-400">
                    <i class="fas fa-home text-4xl"></i>
                </div>
                @endif
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-4">
                    <h3 class="text-white font-bold text-lg">Apartamento {{ $apartamento->numero_apartamento }}</h3>
                </div>
                <div class="absolute top-0 right-0 p-2">
                    @if($apartamento->estado == 'Disponible')
                    <span class="bg-green-500 text-white text-xs px-2 py-1 rounded-full">Disponible</span>
                    @elseif($apartamento->estado == 'Ocupado')
                    <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">Ocupado</span>
                    @elseif($apartamento->estado == 'En mantenimiento')
                    <span class="bg-yellow-500 text-white text-xs px-2 py-1 rounded-full">En mantenimiento</span>
                    @endif
                </div>
            </div>
            <div class="p-4">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-600 text-sm">Piso {{ $apartamento->piso }}</span>
                    <span class="text-indigo-600 font-bold">${{ number_format($apartamento->precio, 0, '.', ',') }}</span>
                </div>
                <div class="flex items-center text-sm text-gray-600 mb-3">
                    <i class="fas fa-ruler-combined mr-2"></i>
                    <span>{{ $apartamento->tamaño }} m²</span>
                </div>
                <div class="flex justify-end pt-3 border-t border-gray-200">
                    <a href="{{ route('propietario.apartamentos.show', $apartamento->id) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-semibold flex items-center">
                        Ver detalles <i class="fas fa-chevron-right ml-1 text-xs"></i>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if($totalApartamentos > 3)
    <div class="flex justify-center mb-6">
        <a href="{{ route('propietario.apartamentos.index') }}" class="bg-white border border-indigo-600 text-indigo-600 hover:bg-indigo-50 py-2 px-4 rounded-md shadow-sm flex items-center">
            Ver todos los apartamentos ({{ $totalApartamentos }}) <i class="fas fa-arrow-right ml-2"></i>
        </a>
    </div>
    @endif

    <div class="flex justify-center">
        <a href="{{ route('propietario.apartamentos.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-md shadow-sm flex items-center">
            <i class="fas fa-plus mr-2"></i> Añadir nuevo apartamento
        </a>
    </div>
    @endif
</div>
@endsection
