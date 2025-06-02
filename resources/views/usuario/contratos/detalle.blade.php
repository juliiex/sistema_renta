@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center mb-6">
        <a href="{{ route('usuario.contratos.lista') }}" class="flex items-center text-blue-600 hover:underline mr-4">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Volver a mis contratos
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Contrato #{{ $contrato->id }}</h1>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
        <!-- Información general del contrato -->
        <div class="p-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Apartamento {{ $contrato->apartamento->numero_apartamento }}</h2>
                    <p class="text-gray-600">{{ $contrato->apartamento->edificio->nombre }}</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $contrato->estado == 'activo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ ucfirst($contrato->estado) }}
                </span>
            </div>

            <!-- Sección de firma digital -->
            @if($contrato->firma_imagen)
            <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <h3 class="text-md font-medium text-gray-800 mb-2">Firma Digital</h3>
                <div class="border border-gray-300 rounded-md p-2 bg-white">
                    <img src="{{ asset('storage/' . $contrato->firma_imagen) }}" alt="Firma digital" class="max-h-32 mx-auto">
                </div>
                <p class="text-xs text-gray-500 mt-2 text-right">Firmado el {{ $contrato->updated_at->format('d/m/Y') }}</p>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Fecha de inicio</h3>
                    <p class="mt-1 text-lg font-medium text-gray-800">{{ $contrato->fecha_inicio->format('d/m/Y') }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Fecha de fin</h3>
                    <p class="mt-1 text-lg font-medium text-gray-800">{{ $contrato->fecha_fin->format('d/m/Y') }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Duración</h3>
                    <p class="mt-1 text-lg font-medium text-gray-800">{{ $contrato->fecha_inicio->diffInMonths($contrato->fecha_fin) }} meses</p>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-800">Detalles del apartamento</h3>
                    <a href="{{ route('usuario.mi-apartamento.detalle', $contrato->id) }}" class="text-blue-600 hover:underline text-sm">
                        Ver apartamento
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Edificio</h4>
                        <p class="mt-1 font-medium text-gray-800">{{ $contrato->apartamento->edificio->nombre }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Tamaño</h4>
                        <p class="mt-1 font-medium text-gray-800">{{ $contrato->apartamento->tamaño }} m²</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Precio mensual</h4>
                        <p class="mt-1 font-medium text-gray-800">${{ number_format($contrato->apartamento->precio, 0) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historial completo de pagos -->
        <div class="border-t border-gray-200 px-6 py-5">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Historial de pagos</h3>

            @if(count($pagos) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mes</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha de reporte</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monto</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reportado por</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($pagos as $pago)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            @if(isset($pago->mes_reportado))
                                                {{ $pago->mes_reportado }}
                                            @else
                                                {{ $pago->fecha_reporte->format('F Y') }}
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            {{ $pago->estado_pago == 'pagado' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ ucfirst($pago->estado_pago) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $pago->fecha_reporte->format('d/m/Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if(isset($pago->monto))
                                            ${{ number_format($pago->monto, 0) }}
                                        @else
                                            ${{ number_format($contrato->apartamento->precio, 0) }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $pago->usuario->nombre ?? 'Administrador' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-6 bg-gray-50 rounded-lg">
                    <p class="text-gray-500">No hay registros de pagos disponibles para este contrato.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
