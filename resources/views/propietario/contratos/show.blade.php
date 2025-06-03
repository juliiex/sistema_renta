@extends('layouts.dashboard-sidebar')

@section('title', 'Detalle de Contrato')

@section('dashboard-content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center mb-6">
        <a href="javascript:history.back()" class="flex items-center text-blue-600 hover:underline mr-4">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Volver
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Detalles del Contrato</h1>

        <!-- Botón de descarga corregido -->
        <a href="{{ route('propietario.contratos.descargar', $contrato->id) }}" target="_blank" class="ml-auto inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Descargar Contrato
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <div class="flex flex-wrap justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold text-gray-700">Contrato #{{ $contrato->id }}</h2>
                    <p class="text-gray-600">Apartamento {{ $contrato->apartamento->numero_apartamento }} - {{ $contrato->apartamento->edificio->nombre }}</p>
                </div>
                <div class="mt-2 md:mt-0">
                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $contrato->estado == 'activo' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ ucfirst($contrato->estado) }}
                    </span>
                </div>
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="text-lg font-semibold mb-4">Información del Inquilino</h3>
                    <dl class="grid grid-cols-1 gap-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nombre</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $contrato->usuario->nombre }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Correo Electrónico</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $contrato->usuario->correo }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $contrato->usuario->telefono ?? 'No especificado' }}</dd>
                        </div>
                    </dl>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4">Información del Contrato</h3>
                    <dl class="grid grid-cols-1 gap-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Fecha de Inicio</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $contrato->fecha_inicio->format('d/m/Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Fecha de Fin</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $contrato->fecha_fin->format('d/m/Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Duración</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $contrato->fecha_inicio->diffInMonths($contrato->fecha_fin) }} meses</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Estado de Firma</dt>
                            <dd class="mt-1 text-sm">
                                @if($contrato->estado_firma == 'firmado')
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Firmado</span>
                                @else
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Pendiente de firma</span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            @if($contrato->estado_firma == 'firmado' && $contrato->firma_imagen)
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-semibold mb-4">Firma del Contrato</h3>
                    <div class="max-w-sm mx-auto bg-gray-100 p-4 rounded-lg">
                        <img src="{{ asset('storage/' . $contrato->firma_imagen) }}" alt="Firma del contrato" class="w-full max-h-24 object-contain">
                    </div>
                </div>
            @endif

            <div class="border-t border-gray-200 pt-6 mt-6">
                <h3 class="text-lg font-semibold mb-4">Términos y Condiciones</h3>
                <div class="prose max-w-none">
                    <p>Por el presente documento, <strong>{{ $contrato->usuario->nombre }}</strong>, acepta alquilar el apartamento {{ $contrato->apartamento->numero_apartamento }} ubicado en {{ $contrato->apartamento->edificio->nombre }}, de acuerdo a las siguientes condiciones:</p>

                    <ol>
                        <li>El periodo de alquiler será desde <strong>{{ $contrato->fecha_inicio->format('d/m/Y') }}</strong> hasta <strong>{{ $contrato->fecha_fin->format('d/m/Y') }}</strong>.</li>
                        <li>El monto mensual de alquiler será de <strong>${{ number_format($contrato->apartamento->precio, 0) }}</strong>, pagadero dentro de los primeros 5 días de cada mes.</li>
                        <li>El apartamento será utilizado única y exclusivamente como vivienda.</li>
                        <li>El inquilino se compromete a mantener el apartamento en buen estado.</li>
                        <li>Cualquier modificación al inmueble deberá ser consultada previamente con el propietario.</li>
                        <li>El contrato podrá ser renovado previo acuerdo entre las partes.</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
