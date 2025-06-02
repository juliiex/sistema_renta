@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-wrap items-center gap-3 mb-6">
        <a href="{{ route('propietario.contratos.index') }}" class="inline-flex items-center text-blue-600 hover:underline">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Volver a la lista
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Contratos del Apartamento {{ $apartamento->numero_apartamento }}</h1>
        <span class="ml-auto px-3 py-1 text-sm rounded-full {{ $apartamento->estado == 'disponible' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
            {{ ucfirst($apartamento->estado) }}
        </span>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
        <div class="p-6">
            <div class="flex flex-wrap md:flex-nowrap gap-6 mb-6">
                <div class="w-full md:w-1/3">
                    <h2 class="text-lg font-semibold mb-4">Información del Apartamento</h2>
                    <dl class="grid grid-cols-1 gap-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Número</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $apartamento->numero_apartamento }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Edificio</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $apartamento->edificio->nombre }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Piso</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $apartamento->piso }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Precio</dt>
                            <dd class="mt-1 text-sm text-gray-900">${{ number_format($apartamento->precio, 0) }}/mes</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tamaño</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $apartamento->tamaño }} m²</dd>
                        </div>
                    </dl>
                </div>
                <div class="w-full md:w-2/3">
                    @if($apartamento->imagen)
                        <img src="{{ asset('storage/' . $apartamento->imagen) }}" alt="Apartamento {{ $apartamento->numero_apartamento }}" class="w-full h-64 object-cover rounded-lg">
                    @else
                        <div class="w-full h-64 bg-gray-200 flex items-center justify-center rounded-lg">
                            <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                    @endif
                    <div class="mt-2">
                        <h3 class="text-lg font-medium mb-2">Descripción</h3>
                        <p class="text-gray-600">{{ $apartamento->descripcion ?? 'Sin descripción disponible' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contrato activo -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
        <div class="bg-blue-600 text-white px-6 py-3">
            <h2 class="text-lg font-semibold">Contrato Actual</h2>
        </div>
        <div class="p-6">
            @if($tieneContratoActivo)
                <div class="flex justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Inquilino: {{ $contratoActivo->usuario->nombre }}</h3>
                        <p class="text-gray-600">{{ $contratoActivo->usuario->correo }}</p>
                    </div>
                    <div class="flex flex-col items-end">
                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-sm">{{ ucfirst($contratoActivo->estado) }}</span>
                        <span class="text-sm text-gray-600 mt-1">{{ $contratoActivo->estado_firma === 'firmado' ? 'Firmado' : 'Pendiente de firma' }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Fecha de inicio</h4>
                        <p class="mt-1 text-gray-900">{{ $contratoActivo->fecha_inicio->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Fecha de fin</h4>
                        <p class="mt-1 text-gray-900">{{ $contratoActivo->fecha_fin->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Duración</h4>
                        <p class="mt-1 text-gray-900">{{ $contratoActivo->fecha_inicio->diffInMonths($contratoActivo->fecha_fin) }} meses</p>
                    </div>
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('propietario.contratos.show', $contratoActivo->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        Ver detalles completos
                    </a>
                </div>
            @else
                <div class="py-6 text-center">
                    <svg class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">Sin contrato activo</h3>
                    <p class="text-gray-500">Este apartamento no tiene contrato activo actualmente.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Contratos anteriores -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
        <div class="bg-gray-600 text-white px-6 py-3 flex justify-between items-center">
            <h2 class="text-lg font-semibold">Contratos Anteriores</h2>
            <span class="px-2 py-1 bg-gray-800 text-white rounded-full text-xs">{{ $contratosAnteriores->count() }}</span>
        </div>
        <div class="p-6">
            @if($tieneContratosAnteriores)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Inquilino
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Período
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Duración
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Estado
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($contratosAnteriores as $contrato)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $contrato->usuario->nombre }}</div>
                                        <div class="text-sm text-gray-500">{{ $contrato->usuario->correo }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $contrato->fecha_inicio->format('d/m/Y') }} al {{ $contrato->fecha_fin->format('d/m/Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $contrato->fecha_inicio->diffInMonths($contrato->fecha_fin) }} meses
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            {{ ucfirst($contrato->estado) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                        <a href="{{ route('propietario.contratos.show', $contrato->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                            Ver detalles
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="py-6 text-center">
                    <svg class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">Sin contratos anteriores</h3>
                    <p class="text-gray-500">Este apartamento no tiene contratos anteriores registrados.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
