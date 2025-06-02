@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Reportar un problema</h1>
        <a href="{{ route('usuario.reportes.lista') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-md transition duration-150 flex items-center">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Volver a mis reportes
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200">
        <div class="p-6">
            <form action="{{ route('usuario.reportes.guardar') }}" method="POST" class="space-y-6">
                @csrf

                @if(session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                        <p>{{ session('error') }}</p>
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
                        <p class="font-bold">Por favor corrige los siguientes errores:</p>
                        <ul class="list-disc pl-5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-6">
                    <h3 class="text-sm font-medium text-gray-700 mb-1">Apartamento</h3>
                    @if(isset($apartamentoSeleccionado) && $apartamentoSeleccionado)
                        @php
                            $apartamentoInfo = $apartamentosContratados->firstWhere('id', $apartamentoSeleccionado);
                        @endphp
                        <div class="bg-gray-50 p-4 rounded-lg flex items-start">
                            <svg class="w-6 h-6 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <div>
                                <p class="text-gray-800 font-medium">Apartamento {{ $apartamentoInfo->numero_apartamento }}</p>
                                <p class="text-gray-600 text-sm">{{ $apartamentoInfo->edificio->nombre }}</p>
                            </div>
                        </div>
                        <input type="hidden" name="apartamento_id" value="{{ $apartamentoSeleccionado }}">
                    @else
                        <select id="apartamento_id" name="apartamento_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="" disabled selected>Selecciona el apartamento</option>
                            @foreach($apartamentosContratados as $apartamento)
                                <option value="{{ $apartamento->id }}">
                                    Apartamento {{ $apartamento->numero_apartamento }} - {{ $apartamento->edificio->nombre }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                </div>

                <div class="mb-6">
                    <label for="tipo" class="block text-sm font-medium text-gray-700 mb-1">Tipo de problema</label>
                    <select id="tipo" name="tipo" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="" disabled selected>Selecciona el tipo de problema</option>
                        <option value="plomeria">Plomería</option>
                        <option value="electricidad">Electricidad</option>
                        <option value="estructura">Estructura</option>
                        <option value="seguridad">Seguridad</option>
                        <option value="limpieza">Limpieza</option>
                        <option value="otro">Otro</option>
                    </select>
                </div>

                <div>
                    <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">Descripción detallada del problema</label>
                    <textarea id="descripcion" name="descripcion" rows="6" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Describe el problema con la mayor cantidad de detalles posible. Incluye cuándo comenzó, qué has observado y cualquier otra información relevante..." required>{{ old('descripcion') }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">Máximo 500 caracteres</p>
                </div>

                <div class="border-t border-gray-200 pt-6">
                    <div class="flex justify-between">
                        <a href="{{ route('usuario.reportes.lista') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md transition duration-150">Cancelar</a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-150">Enviar reporte</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
