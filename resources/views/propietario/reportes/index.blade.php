@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Reportes de Problemas</h1>
        <a href="{{ route('dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-md transition">
            Volver al Dashboard
        </a>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <h2 class="text-lg font-semibold mb-3">Filtros</h2>
        <form action="{{ route('propietario.reportes.index') }}" method="GET" class="flex flex-wrap items-center gap-4">
            <div class="w-full md:w-auto">
                <label for="piso" class="block text-sm font-medium text-gray-700 mb-1">Filtrar por piso:</label>
                <select name="piso" id="piso" class="w-full md:w-48 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="this.form.submit()">
                    <option value="todos" {{ $pisofiltro == 'todos' || !$pisofiltro ? 'selected' : '' }}>Todos los pisos</option>
                    @foreach($pisos as $piso)
                        <option value="{{ $piso }}" {{ $pisofiltro == $piso ? 'selected' : '' }}>Piso {{ $piso }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full md:w-auto">
                <label for="estado" class="block text-sm font-medium text-gray-700 mb-1">Estado:</label>
                <select name="estado" id="estado" class="w-full md:w-48 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="this.form.submit()">
                    <option value="todos" {{ $estadofiltro == 'todos' || !$estadofiltro ? 'selected' : '' }}>Todos los estados</option>
                    <option value="pendiente" {{ $estadofiltro == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="atendido" {{ $estadofiltro == 'atendido' ? 'selected' : '' }}>Atendido</option>
                    <option value="cerrado" {{ $estadofiltro == 'cerrado' ? 'selected' : '' }}>Cerrado</option>
                </select>
            </div>
        </form>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
        <div class="bg-blue-600 text-white px-6 py-4">
            <h2 class="text-lg font-semibold">Reportes de Problemas por Apartamento</h2>
        </div>

        @if(count($reportes) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Apartamento
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Edificio
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Inquilino
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tipo
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estado
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Fecha
                            </th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($reportes as $reporte)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $reporte->apartamento->numero_apartamento }}</div>
                                    <div class="text-xs text-gray-500">Piso {{ $reporte->apartamento->piso }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $reporte->apartamento->edificio->nombre }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $reporte->usuario->nombre }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ ucfirst($reporte->tipo) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $estado = strtolower($reporte->estado);
                                    @endphp
                                    @if($estado == 'cerrado')
                                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Cerrado</span>
                                    @elseif($estado == 'atendido')
                                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">Atendido</span>
                                    @else
                                        <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Pendiente</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $reporte->fecha_reporte->format('d/m/Y') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <a href="{{ route('propietario.reportes.show', $reporte->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                        Ver detalle
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-6 text-center">
                <svg class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-1">No hay reportes de problemas</h3>
                <p class="text-gray-500">No se encontraron reportes de problemas con los filtros seleccionados.</p>
            </div>
        @endif
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Información sobre reportes de problemas</h2>
        </div>
        <div class="p-6">
            <p class="mb-4 text-gray-600">Los reportes de problemas permiten a los inquilinos informar sobre incidencias en sus apartamentos para su pronta atención.</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                <strong>Pendiente:</strong> El reporte ha sido recibido y está pendiente de atención.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                <strong>Atendido:</strong> El personal se encuentra trabajando en el problema reportado.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="bg-green-50 border-l-4 border-green-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">
                                <strong>Cerrado:</strong> El problema ha sido resuelto satisfactoriamente.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
