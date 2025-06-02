@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center mb-6">
        <a href="{{ route('propietario.estados-alquiler.index') }}" class="flex items-center text-blue-600 hover:underline mr-4">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Volver a la lista
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Historial de Pagos: Apartamento {{ $contrato->apartamento->numero_apartamento }}</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Información del Contrato -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden col-span-1">
            <div class="bg-blue-600 text-white px-6 py-4">
                <h2 class="text-lg font-semibold">Información del Contrato</h2>
            </div>
            <div class="p-6">
                <div class="mb-4">
                    <h3 class="text-sm font-medium text-gray-500">Inquilino</h3>
                    <p class="mt-1 text-lg font-medium text-gray-800">{{ $contrato->usuario->nombre }}</p>
                </div>
                <div class="mb-4">
                    <h3 class="text-sm font-medium text-gray-500">Apartamento</h3>
                    <p class="mt-1 text-lg font-medium text-gray-800">
                        {{ $contrato->apartamento->numero_apartamento }} -
                        {{ $contrato->apartamento->edificio->nombre }}
                    </p>
                </div>
                <div class="mb-4">
                    <h3 class="text-sm font-medium text-gray-500">Período</h3>
                    <p class="mt-1 text-sm text-gray-800">
                        {{ $contrato->fecha_inicio->format('d/m/Y') }} al {{ $contrato->fecha_fin->format('d/m/Y') }}
                    </p>
                </div>
                <div class="mb-4">
                    <h3 class="text-sm font-medium text-gray-500">Monto Mensual</h3>
                    <p class="mt-1 text-lg font-medium text-gray-800">${{ number_format($contrato->apartamento->precio, 0) }}</p>
                </div>
            </div>
        </div>

        <!-- Actualizar Estado de Pago -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden col-span-2">
            <div class="bg-blue-600 text-white px-6 py-4">
                <h2 class="text-lg font-semibold">Actualizar Estado de Pago</h2>
            </div>
            <div class="p-6">
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('propietario.estados-alquiler.actualizar', $contrato->id) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="estado_pago" class="block text-sm font-medium text-gray-700 mb-1">Estado de pago:</label>
                        <select name="estado_pago" id="estado_pago" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="pendiente">Pendiente</option>
                            <option value="pagado">Pagado</option>
                            <option value="atrasado">Atrasado</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Actualizar estado
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Historial de Estados -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="bg-blue-600 text-white px-6 py-4 flex justify-between items-center">
            <h2 class="text-lg font-semibold">Historial de Estados de Pago</h2>
            <span class="px-2 py-1 bg-white text-blue-800 rounded-full text-xs">{{ count($estados) }}</span>
        </div>

        @if(count($estados) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Fecha de Reporte
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estado
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Registrado por
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($estados as $estado)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $estado->fecha_reporte->format('d/m/Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $estado->fecha_reporte->diffForHumans() }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $estado_pago = strtolower($estado->estado_pago);
                                    @endphp
                                    @if($estado_pago == 'pagado')
                                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Pagado</span>
                                    @elseif($estado_pago == 'atrasado')
                                        <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Atrasado</span>
                                    @else
                                        <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Pendiente</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $estado->usuario->nombre }}</div>
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
                <h3 class="text-lg font-medium text-gray-900 mb-1">No hay registros de pago</h3>
                <p class="text-gray-500">Este contrato no tiene ningún registro de estado de pago.</p>
            </div>
        @endif
    </div>
</div>
@endsection
