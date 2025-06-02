@extends('layouts.dashboard-sidebar')

@section('title', 'Mis Apartamentos')

@section('dashboard-content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Mis Apartamentos</h1>
        <a href="{{ route('propietario.apartamentos.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-md shadow-sm flex items-center">
            <i class="fas fa-plus mr-2"></i> Añadir apartamento
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-md mb-6 overflow-hidden">
        <div class="p-4 bg-gray-50 border-b border-gray-200">
            <h2 class="font-semibold text-gray-800">Resumen</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 divide-x divide-gray-200">
            <div class="p-4 text-center">
                <span class="text-2xl font-bold text-gray-700">{{ $apartamentos->count() }}</span>
                <p class="text-gray-500 text-sm">Apartamentos Totales</p>
            </div>
            <div class="p-4 text-center">
                <span class="text-2xl font-bold text-green-600">{{ $apartamentos->where('estado', 'Disponible')->count() }}</span>
                <p class="text-gray-500 text-sm">Disponibles</p>
            </div>
            <div class="p-4 text-center">
                <span class="text-2xl font-bold text-red-600">{{ $apartamentos->where('estado', 'Ocupado')->count() }}</span>
                <p class="text-gray-500 text-sm">Ocupados</p>
            </div>
        </div>
    </div>

    <div class="flex mb-4 justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-800">Listado de apartamentos</h2>
        <div class="flex">
            <form method="GET" action="{{ route('propietario.apartamentos.index') }}" class="flex">
                <select id="estado-filter" name="estado" onchange="this.form.submit()" class="border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                    <option value="todos" {{ request('estado') == 'todos' || !request('estado') ? 'selected' : '' }}>Todos los estados</option>
                    <option value="disponible" {{ request('estado') == 'disponible' ? 'selected' : '' }}>Disponible</option>
                    <option value="ocupado" {{ request('estado') == 'ocupado' ? 'selected' : '' }}>Ocupado</option>
                    <option value="mantenimiento" {{ request('estado') == 'mantenimiento' ? 'selected' : '' }}>En mantenimiento</option>
                </select>
            </form>
            <!-- Eliminado el filtro de edificios ya que solo habrá uno -->
        </div>
    </div>

    @if($apartamentos->isEmpty())
    <div class="bg-white rounded-lg shadow-md p-6 text-center">
        <p class="text-gray-500">No tienes apartamentos registrados.</p>
        <a href="{{ route('propietario.apartamentos.create') }}" class="mt-4 inline-block bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-md shadow-sm">
            <i class="fas fa-plus mr-2"></i> Añadir apartamento
        </a>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($apartamentos as $apartamento)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 apartamento-card">
            <div class="relative h-48 bg-gray-200">
                @if($apartamento->imagen)
                <img src="{{ asset('storage/' . $apartamento->imagen) }}" alt="Apartamento {{ $apartamento->numero_apartamento }}" class="w-full h-full object-cover">
                @else
                <div class="flex items-center justify-center h-full bg-gray-200 text-gray-400">
                    <i class="fas fa-home text-5xl"></i>
                </div>
                @endif
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-4">
                    <h3 class="text-white font-bold text-xl">Apartamento {{ $apartamento->numero_apartamento }}</h3>
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
                    <span class="text-gray-600 text-sm">{{ $apartamento->edificio ? $apartamento->edificio->nombre : 'Sin edificio' }} - Piso {{ $apartamento->piso }}</span>
                    <span class="text-indigo-600 font-bold">${{ number_format($apartamento->precio, 0, '.', ',') }}</span>
                </div>
                <div class="flex items-center text-sm text-gray-600 mb-3">
                    <i class="fas fa-ruler-combined mr-2"></i>
                    <span>{{ $apartamento->tamaño }} m²</span>
                </div>
                <p class="text-gray-700 text-sm mb-4 line-clamp-2">
                    {{ $apartamento->descripcion ?: 'Sin descripción disponible.' }}
                </p>
                <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                    <a href="{{ route('propietario.apartamentos.edit', $apartamento->id) }}" class="text-gray-600 hover:text-indigo-600 text-sm font-medium flex items-center">
                        <i class="fas fa-edit mr-1"></i> Editar
                    </a>
                    <a href="{{ route('propietario.apartamentos.show', $apartamento->id) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-semibold flex items-center">
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
