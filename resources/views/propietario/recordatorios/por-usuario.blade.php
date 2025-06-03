@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center mb-6">
        <a href="{{ route('propietario.recordatorios.index') }}" class="flex items-center text-blue-600 hover:underline mr-4">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Volver a la lista
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Historial de Recordatorios: {{ $usuario->nombre }}</h1>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
        <div class="bg-blue-600 text-white px-6 py-4">
            <h2 class="text-lg font-semibold">Información del Inquilino</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Nombre</h3>
                    <p class="mt-1 text-lg font-medium text-gray-800">{{ $usuario->nombre }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Correo Electrónico</h3>
                    <p class="mt-1 text-lg font-medium text-gray-800">{{ $usuario->correo }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Teléfono</h3>
                    <p class="mt-1 text-lg font-medium text-gray-800">{{ $usuario->telefono ?? 'No especificado' }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Apartamentos</h3>
                    <div class="mt-1">
                        @foreach($contratos as $contrato)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-2">
                                {{ $contrato->apartamento->numero_apartamento }} ({{ $contrato->apartamento->edificio->nombre }})
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="bg-blue-600 text-white px-6 py-4 flex justify-between items-center">
            <h2 class="text-lg font-semibold">Historial de Recordatorios</h2>
            <span class="px-2 py-1 bg-white text-blue-800 rounded-full text-xs">{{ count($recordatorios) }}</span>
        </div>

        @if(count($recordatorios) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Fecha de Envío
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Método
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($recordatorios as $recordatorio)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $recordatorio->fecha_envio->format('d/m/Y H:i') }}</div>
                                    <div class="text-xs text-gray-500">{{ $recordatorio->fecha_envio->diffForHumans() }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $recordatorio->metodo }}</div>
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
                <h3 class="text-lg font-medium text-gray-900 mb-1">No hay recordatorios</h3>
                <p class="text-gray-500">Este inquilino no tiene ningún recordatorio de pago registrado.</p>
            </div>
        @endif
    </div>
</div>
@endsection
