@extends('layouts.dashboard-sidebar')

@section('title', 'Evaluaciones de Apartamentos')

@section('dashboard-content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Evaluaciones de mis Apartamentos</h1>

    <!-- Filtro por Piso -->
    <div class="bg-white rounded-lg shadow-md mb-6 p-4">
        <form method="GET" action="{{ route('propietario.evaluaciones.index') }}" class="flex items-center">
            <label for="piso-filter" class="mr-3 font-medium text-gray-700">Filtrar por piso:</label>
            <select id="piso-filter" name="piso" onchange="this.form.submit()" class="border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                <option value="todos" {{ request('piso') == 'todos' || !request('piso') ? 'selected' : '' }}>Todos los pisos</option>
                @foreach($pisos as $piso)
                <option value="{{ $piso }}" {{ request('piso') == $piso ? 'selected' : '' }}>Piso {{ $piso }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <!-- Resumen de evaluaciones por apartamento -->
    @if(empty($estadisticas))
    <div class="bg-white rounded-lg shadow-md p-8 text-center">
        <p class="text-gray-500">No hay apartamentos registrados.</p>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
        @php $currentPiso = null; @endphp

        @foreach($estadisticas as $stats)
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
                    @if($stats['total'] > 0)
                    <div class="flex items-center bg-white px-3 py-1 rounded-full shadow-sm border border-indigo-200">
                        <span class="text-xl font-bold text-indigo-700 mr-1">{{ $stats['promedio'] }}</span>
                        <i class="fas fa-star text-yellow-400"></i>
                    </div>
                    @else
                    <div class="bg-gray-100 px-3 py-1 rounded-full shadow-sm border border-gray-200 text-gray-500 text-sm">
                        Sin evaluaciones
                    </div>
                    @endif
                </div>
                <div class="p-4">
                    @if($stats['total'] > 0)
                    <div class="flex items-center mb-3">
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ ($stats['promedio'] / 5) * 100 }}%"></div>
                        </div>
                        <span class="ml-2 text-sm text-gray-600">{{ $stats['total'] }} evaluaciones</span>
                    </div>
                    @else
                    <div class="flex items-center mb-3">
                        <div class="w-full bg-gray-200 rounded-full h-2"></div>
                        <span class="ml-2 text-sm text-gray-400">0 evaluaciones</span>
                    </div>
                    @endif
                    <div class="flex justify-between items-center">
                        <a href="{{ route('propietario.apartamentos.show', $stats['id']) }}" class="text-gray-600 hover:text-indigo-600 text-sm font-medium flex items-center">
                            <i class="fas fa-home mr-1"></i> Ver apartamento
                        </a>
                        <a href="{{ route('propietario.apartamentos.evaluaciones', $stats['id']) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-semibold flex items-center">
                            Ver evaluaciones <i class="fas fa-chevron-right ml-1 text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
