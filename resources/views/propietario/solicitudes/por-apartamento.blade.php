@extends('layouts.dashboard-sidebar')

@section('title', 'Solicitudes para ' . $apartamento->numero_apartamento)

@section('dashboard-content')
<div class="container mx-auto px-4">
    <div class="flex items-center mb-6">
        <a href="{{ route('propietario.solicitudes.index') }}" class="text-gray-600 hover:text-indigo-600 mr-2">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">
            Solicitudes para Apartamento {{ $apartamento->numero_apartamento }}
        </h1>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
        <p>{{ session('error') }}</p>
    </div>
    @endif

    @if(session('info'))
    <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6" role="alert">
        <p>{{ session('info') }}</p>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="p-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-gray-800">Detalles del apartamento</h2>
                <p class="text-sm text-gray-500">{{ $apartamento->edificio->nombre }} - Piso {{ $apartamento->piso }}</p>
            </div>
            <a href="{{ route('propietario.apartamentos.show', $apartamento->id) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                Ver apartamento <i class="fas fa-external-link-alt ml-1"></i>
            </a>
        </div>
        <div class="p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="mb-4 md:mb-0">
                    <div class="flex items-center mb-2">
                        <div class="w-20 h-20 bg-gray-200 rounded-lg overflow-hidden">
                            @if($apartamento->imagen)
                            <img src="{{ asset('storage/' . $apartamento->imagen) }}" alt="{{ $apartamento->numero_apartamento }}" class="w-full h-full object-cover">
                            @else
                            <div class="flex items-center justify-center h-full bg-gray-200 text-gray-400">
                                <i class="fas fa-home text-4xl"></i>
                            </div>
                            @endif
                        </div>
                        <div class="ml-4">
                            <h3 class="font-semibold">Apartamento {{ $apartamento->numero_apartamento }}</h3>
                            <div class="flex items-center mt-1">
                                @if($apartamento->estado == 'Disponible')
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Disponible</span>
                                @elseif($apartamento->estado == 'Ocupado')
                                <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">Ocupado</span>
                                @elseif($apartamento->estado == 'En mantenimiento')
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">En mantenimiento</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="text-sm text-gray-500">Precio: <span class="font-medium text-gray-800">${{ number_format($apartamento->precio, 0, '.', ',') }}</span></div>
                        <div class="text-sm text-gray-500">Tamaño: <span class="font-medium text-gray-800">{{ $apartamento->tamaño }} m²</span></div>
                    </div>
                </div>

                <div class="bg-indigo-50 p-4 rounded-lg grid grid-cols-2 gap-4 text-center">
                    <div>
                        <div class="text-sm text-gray-600">Total</div>
                        <div class="text-2xl font-bold text-gray-700">{{ $estadisticas['total'] }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-yellow-600">Pendientes</div>
                        <div class="text-2xl font-bold text-yellow-600">{{ $estadisticas['pendientes'] }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-green-600">Aprobadas</div>
                        <div class="text-2xl font-bold text-green-600">{{ $estadisticas['aprobadas'] }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-red-600">Rechazadas</div>
                        <div class="text-2xl font-bold text-red-600">{{ $estadisticas['rechazadas'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
        <i class="fas fa-file-contract mr-2 text-indigo-500"></i>
        Todas las solicitudes
    </h2>

    @if($solicitudes->isEmpty())
    <div class="bg-white rounded-lg shadow-md p-8 text-center">
        <p class="text-gray-500">Este apartamento aún no tiene solicitudes de alquiler.</p>
    </div>
    @else
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Solicitante</th>
                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($solicitudes as $solicitud)
                    <tr>
                        <td class="py-4 px-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-user text-gray-500"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $solicitud->usuario->nombre }}</div>
                                    <div class="text-sm text-gray-500">{{ $solicitud->usuario->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $solicitud->fecha_solicitud ? $solicitud->fecha_solicitud->format('d/m/Y') : 'N/A' }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $solicitud->fecha_solicitud ? $solicitud->fecha_solicitud->format('H:i') : '' }}
                            </div>
                        </td>
                        <td class="py-4 px-4 whitespace-nowrap">
                            @if($solicitud->estado_solicitud == 'pendiente')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Pendiente
                            </span>
                            @elseif($solicitud->estado_solicitud == 'aprobada')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Aprobada
                            </span>
                            @elseif($solicitud->estado_solicitud == 'rechazada')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Rechazada
                            </span>
                            @endif
                        </td>
                        <td class="py-4 px-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('propietario.solicitudes.show', $solicitud->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                    Ver
                                </a>

                                @if($solicitud->estado_solicitud == 'pendiente')
                                <form action="{{ route('propietario.solicitudes.aprobar', $solicitud->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-900">
                                        Aprobar
                                    </button>
                                </form>

                                <form action="{{ route('propietario.solicitudes.rechazar', $solicitud->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        Rechazar
                                    </button>
                                </form>
                                @else
                                <a href="{{ route('propietario.solicitudes.edit', $solicitud->id) }}" class="text-gray-600 hover:text-gray-900">
                                    Editar
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
