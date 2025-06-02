@extends('layouts.dashboard-sidebar')

@section('title', 'Editar Solicitud')

@section('dashboard-content')
<div class="container mx-auto px-4">
    <div class="flex items-center mb-6">
        <a href="{{ route('propietario.solicitudes.show', $solicitud->id) }}" class="text-gray-600 hover:text-indigo-600 mr-2">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">
            Editar Solicitud #{{ $solicitud->id }}
        </h1>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-4 bg-gray-50 border-b border-gray-200">
            <h2 class="font-semibold text-gray-800">Modificar estado de la solicitud</h2>
        </div>
        <form action="{{ route('propietario.solicitudes.update', $solicitud->id) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <div class="flex items-center mb-4">
                    <div class="h-16 w-16 rounded-full bg-gray-200 flex items-center justify-center">
                        <i class="fas fa-user text-gray-500 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ $solicitud->usuario->nombre }}</h3>
                        <p class="text-gray-600">{{ $solicitud->usuario->email }}</p>
                    </div>
                </div>

                <div class="flex items-center mb-4">
                    <div class="h-10 w-10 bg-gray-200 rounded-lg flex items-center justify-center text-gray-400">
                        <i class="fas fa-home"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-gray-900">Apartamento {{ $solicitud->apartamento->numero_apartamento }}</p>
                        <p class="text-sm text-gray-500">{{ $solicitud->apartamento->edificio->nombre }} - Piso {{ $solicitud->apartamento->piso }}</p>
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <label for="estado_solicitud" class="block text-gray-700 font-medium mb-2">Estado de la solicitud</label>
                <select id="estado_solicitud" name="estado_solicitud" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                    <option value="pendiente" {{ $solicitud->estado_solicitud == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="aprobada" {{ $solicitud->estado_solicitud == 'aprobada' ? 'selected' : '' }}>Aprobada</option>
                    <option value="rechazada" {{ $solicitud->estado_solicitud == 'rechazada' ? 'selected' : '' }}>Rechazada</option>
                </select>
                @error('estado_solicitud')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror

                <div class="mt-4 bg-yellow-50 border-l-4 border-yellow-400 p-4 text-yellow-700">
                    <p class="font-medium">Importante:</p>
                    <ul class="list-disc list-inside text-sm">
                        <li>Si apruebas esta solicitud, el apartamento se marcará como "Ocupado".</li>
                        <li>Esto puede afectar a otras solicitudes pendientes para este apartamento.</li>
                        <li>Asegúrate de haber revisado toda la información del solicitante.</li>
                    </ul>
                </div>
            </div>

            <div class="flex justify-end mt-6 pt-6 border-t border-gray-200">
                <a href="{{ route('propietario.solicitudes.show', $solicitud->id) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 px-4 rounded-md mr-2">
                    Cancelar
                </a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-md">
                    Guardar cambios
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
