@extends('layouts.dashboard-sidebar')

@section('title', 'Reportes por Apartamento')

@section('dashboard-content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center mb-6">
        <a href="{{ route('propietario.reportes.index') }}" class="flex items-center text-blue-600 hover:underline mr-4">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Volver a la lista
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Reportes de Problemas: Apartamento {{ $apartamento->numero_apartamento }}</h1>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
        <div class="bg-blue-600 text-white px-6 py-4 flex justify-between items-center">
            <h2 class="text-lg font-semibold">Información del Apartamento</h2>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="p-4 bg-gray-50 rounded-lg">
                <h3 class="text-sm font-medium text-gray-500 mb-1">Número</h3>
                <p class="text-xl font-medium text-gray-800">{{ $apartamento->numero_apartamento }}</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-lg">
                <h3 class="text-sm font-medium text-gray-500 mb-1">Edificio</h3>
                <p class="text-xl font-medium text-gray-800">{{ $apartamento->edificio->nombre }}</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-lg">
                <h3 class="text-sm font-medium text-gray-500 mb-1">Piso</h3>
                <p class="text-xl font-medium text-gray-800">{{ $apartamento->piso }}</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-lg">
                <h3 class="text-sm font-medium text-gray-500 mb-1">Inquilino Actual</h3>
                <p class="text-xl font-medium text-gray-800">
                    @if($inquilino)
                        {{ $inquilino->nombre }}
                    @else
                        Sin inquilino
                    @endif
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="bg-blue-600 text-white px-6 py-4 flex justify-between items-center">
            <h2 class="text-lg font-semibold">Historial de Reportes de Problemas</h2>
            <span class="px-2 py-1 bg-white text-blue-800 rounded-full text-xs">{{ count($reportes) }}</span>
        </div>

        @if(count($reportes) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Fecha
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tipo
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estado
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Descripción
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
                                    <div class="text-sm text-gray-900">{{ $reporte->fecha_reporte->format('d/m/Y H:i') }}</div>
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
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 line-clamp-2">{{ $reporte->descripcion }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <a href="{{ route('propietario.reportes.show', $reporte->id) }}" class="text-indigo-600 hover:text-indigo-900">
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
                <p class="text-gray-500">Este apartamento no tiene reportes de problemas registrados.</p>
            </div>
        @endif
    </div>
</div>
@endsection
