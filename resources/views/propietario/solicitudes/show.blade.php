@extends('layouts.dashboard-sidebar')

@section('title', 'Detalle de Solicitud')

@section('dashboard-content')
<div class="container mx-auto px-4">
    <div class="flex items-center mb-6">
        <a href="{{ route('propietario.solicitudes.apartamento', $solicitud->apartamento_id) }}" class="text-gray-600 hover:text-indigo-600 mr-2">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">
            Detalle de Solicitud #{{ $solicitud->id }}
        </h1>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Información del solicitante -->
        <div class="lg:col-span-1 bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-4 bg-indigo-50 border-b border-indigo-100">
                <h2 class="font-semibold text-indigo-800">Información del solicitante</h2>
            </div>
            <div class="p-6">
                <div class="flex items-center mb-6">
                    <div class="h-16 w-16 rounded-full bg-gray-200 flex items-center justify-center">
                        <i class="fas fa-user text-gray-500 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ $solicitud->usuario->nombre }}</h3>
                        <p class="text-gray-600">{{ $solicitud->usuario->email }}</p>
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Fecha de solicitud:</span>
                        <span class="text-gray-900">{{ $solicitud->fecha_solicitud ? $solicitud->fecha_solicitud->format('d/m/Y') : 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Estado actual:</span>
                        <span>
                            @if($solicitud->estado_solicitud == 'pendiente')
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">Pendiente</span>
                            @elseif($solicitud->estado_solicitud == 'aprobada')
                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Aprobada</span>
                            @elseif($solicitud->estado_solicitud == 'rechazada')
                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">Rechazada</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información del apartamento -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-4 bg-gray-50 border-b border-gray-200">
                <h2 class="font-semibold text-gray-800">Detalles del apartamento</h2>
            </div>
            <div class="p-6">
                <div class="flex flex-col md:flex-row">
                    <div class="md:w-1/3 mb-4 md:mb-0">
                        <div class="h-40 bg-gray-200 rounded-lg overflow-hidden">
                            @if($solicitud->apartamento->imagen)
                            <img src="{{ asset('storage/' . $solicitud->apartamento->imagen) }}" alt="Apartamento {{ $solicitud->apartamento->numero_apartamento }}" class="w-full h-full object-cover">
                            @else
                            <div class="flex items-center justify-center h-full bg-gray-200 text-gray-400">
                                <i class="fas fa-home text-4xl"></i>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="md:w-2/3 md:pl-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">
                            Apartamento {{ $solicitud->apartamento->numero_apartamento }}
                            <span class="ml-2">
                                @if($solicitud->apartamento->estado == 'Disponible')
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Disponible</span>
                                @elseif($solicitud->apartamento->estado == 'Ocupado')
                                <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">Ocupado</span>
                                @elseif($solicitud->apartamento->estado == 'En mantenimiento')
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">En mantenimiento</span>
                                @endif
                            </span>
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-500">Edificio</p>
                                <p class="font-medium">{{ $solicitud->apartamento->edificio->nombre }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Piso</p>
                                <p class="font-medium">{{ $solicitud->apartamento->piso }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Precio</p>
                                <p class="font-medium">${{ number_format($solicitud->apartamento->precio, 0, '.', ',') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Tamaño</p>
                                <p class="font-medium">{{ $solicitud->apartamento->tamaño }} m²</p>
                            </div>
                        </div>

                        <div class="mt-4">
                            <p class="text-gray-500 text-sm">Descripción</p>
                            <p class="text-gray-700">{{ $solicitud->apartamento->descripcion ?: 'Sin descripción disponible.' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones disponibles -->
    <div class="mt-6 bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-4 bg-gray-50 border-b border-gray-200">
            <h2 class="font-semibold text-gray-800">Acciones</h2>
        </div>
        <div class="p-6">
            @if($solicitud->estado_solicitud == 'pendiente')
            <p class="text-gray-700 mb-4">Esta solicitud está pendiente de revisión. Puede aprobarla o rechazarla.</p>
            <div class="flex space-x-4">
                <form action="{{ route('propietario.solicitudes.aprobar', $solicitud->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-md shadow-sm flex items-center">
                        <i class="fas fa-check mr-2"></i> Aprobar solicitud
                    </button>
                </form>

                <form action="{{ route('propietario.solicitudes.rechazar', $solicitud->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-md shadow-sm flex items-center">
                        <i class="fas fa-times mr-2"></i> Rechazar solicitud
                    </button>
                </form>
            </div>
            @else
            <div class="flex justify-between items-center">
                <div>
                    @if($solicitud->estado_solicitud == 'aprobada')
                    <p class="text-green-700"><i class="fas fa-check-circle mr-2"></i> Esta solicitud ha sido aprobada.</p>
                    @elseif($solicitud->estado_solicitud == 'rechazada')
                    <p class="text-red-700"><i class="fas fa-times-circle mr-2"></i> Esta solicitud ha sido rechazada.</p>
                    @endif
                </div>
                <a href="{{ route('propietario.solicitudes.edit', $solicitud->id) }}" class="bg-gray-600 hover:bg-gray-700 text-white py-2 px-4 rounded-md shadow-sm flex items-center">
                    <i class="fas fa-edit mr-2"></i> Cambiar estado
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
