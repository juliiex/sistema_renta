@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center mb-6">
        <a href="{{ route('usuario.quejas.mis-quejas') }}" class="flex items-center text-blue-600 hover:underline mr-4">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Volver a mis quejas
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Presentar una Queja</h1>
    </div>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
            <div class="bg-orange-50 px-6 py-4 border-b border-orange-100">
                <h2 class="text-lg font-medium text-orange-800">Formulario de Queja</h2>
                <p class="mt-1 text-sm text-orange-600">
                    Completa el formulario para enviar una queja. Nuestro equipo la revisará y tomará las acciones correspondientes.
                </p>
            </div>

            <form action="{{ route('usuario.quejas.store') }}" method="POST" class="p-6">
                @csrf

                @if($errors->any())
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 text-red-700">
                        <p class="font-bold">Por favor corrige los siguientes errores:</p>
                        <ul class="mt-1 list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-6">
                    <label for="tipo" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Queja *</label>
                    <select id="tipo" name="tipo" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500">
                        <option value="">Selecciona el tipo de queja</option>
                        @foreach($tipoOpciones as $value => $label)
                            <option value="{{ $value }}" {{ old('tipo') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('tipo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">Descripción *</label>
                    <textarea id="descripcion" name="descripcion" rows="6" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500" placeholder="Describe tu queja de manera detallada...">{{ old('descripcion') }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">Mínimo 10 caracteres, máximo 1000.</p>
                    @error('descripcion')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-between items-center">
                    <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white font-medium py-2 px-4 rounded-md shadow-sm">
                        Enviar Queja
                    </button>
                    <a href="{{ route('usuario.quejas.mis-quejas') }}" class="text-gray-600 hover:text-gray-800">
                        Cancelar
                    </a>
                </div>
            </form>

            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <div class="flex items-center text-sm text-gray-600">
                    <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p>Tu queja será revisada por la administración. Te contactaremos si necesitamos información adicional.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
