@extends('layouts.dashboard-sidebar')

@section('title', 'Solicitudes de Alquiler')

@section('dashboard-content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Solicitudes de Alquiler</h1>

    <!-- Filtro por Piso -->
    <div class="bg-white rounded-lg shadow-md mb-6 p-4">
        <form method="GET" action="{{ route('propietario.solicitudes.index') }}" class="flex items-center">
            <label for="piso-filter" class="mr-3 font-medium text-gray-700">Filtrar por piso:</label>
            <select id="piso-filter" name="piso" onchange="this.form.submit()" class="border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                <option value="todos" {{ request('piso') == 'todos' || !request('piso') ? 'selected' : '' }}>Todos los pisos</option>
                @foreach($pisos as $piso)
                <option value="{{ $piso }}" {{ request('piso') == $piso ? 'selected' : '' }}>Piso {{ $piso }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <!-- Resumen de solicitudes por apartamento -->
    @if(empty($resumen))
    <div class="bg-white rounded-lg shadow-md p-8 text-center">
        <p class="text-gray-500">No hay apartamentos registrados.</p>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
        @php $currentPiso = null; @endphp

        @foreach($resumen as $stats)
            @if($currentPiso !== $stats['piso'])
                @php $currentPiso = $stats['piso']; @endphp
                <div class="md:col-span-2 lg:col-span-3">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-layer-group mr-2 text-indigo-500"></i>
                        Piso {{ $currentPiso }}
                    </h2>
                </div>
            @endif

            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-200">
                <div class="p-4 bg-indigo-50 border-b border-indigo-100 flex justify-between items-center">
                    <div>
                        <h3 class="font-semibold text-indigo-800">Apartamento {{ $stats['apartamento'] }}</h3>
                        <p class="text-xs text-indigo-600">{{ $stats['edificio'] }}</p>
                    </div>
                    <div>
                        @if($stats['estado'] == 'Disponible')
                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Disponible</span>
                        @elseif($stats['estado'] == 'Ocupado')
                        <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">Ocupado</span>
                        @elseif($stats['estado'] == 'En mantenimiento')
                        <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">En mantenimiento</span>
                        @endif
                    </div>
                </div>
                <div class="p-4">
                    <div class="flex justify-between mb-3">
                        <div>
                            <span class="text-lg font-bold text-indigo-700">{{ $stats['total'] }}</span>
                            <span class="text-sm text-gray-600">solicitudes</span>
                        </div>
                        <div class="flex space-x-2">
                            <div class="flex items-center">
                                <span class="h-3 w-3 bg-yellow-400 rounded-full mr-1"></span>
                                <span class="text-sm text-gray-600">{{ $stats['pendientes'] }}</span>
                            </div>
                            <div class="flex items-center">
                                <span class="h-3 w-3 bg-green-500 rounded-full mr-1"></span>
                                <span class="text-sm text-gray-600">{{ $stats['aprobadas'] }}</span>
                            </div>
                            <div class="flex items-center">
                                <span class="h-3 w-3 bg-red-500 rounded-full mr-1"></span>
                                <span class="text-sm text-gray-600">{{ $stats['rechazadas'] }}</span>
                            </div>
                        </div>
                    </div>

                    @if($stats['pendientes'] > 0)
                    <div class="bg-yellow-50 text-yellow-800 text-xs font-medium px-2.5 py-1.5 rounded mb-3">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        {{ $stats['pendientes'] }} solicitudes pendientes de revisión
                    </div>
                    @endif

                    <div class="flex justify-between items-center">
                        <a href="{{ route('propietario.apartamentos.show', $stats['id']) }}" class="text-gray-600 hover:text-indigo-600 text-sm font-medium flex items-center">
                            <i class="fas fa-home mr-1"></i> Ver apartamento
                        </a>
                        <a href="{{ route('propietario.solicitudes.apartamento', $stats['id']) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-semibold flex items-center">
                            Ver solicitudes <i class="fas fa-chevron-right ml-1 text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
