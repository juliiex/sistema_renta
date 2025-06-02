@extends('layouts.dashboard-sidebar')

@section('title', 'Editar Apartamento')

@section('dashboard-content')
<div class="container mx-auto px-4">
    <div class="flex items-center mb-6">
        <a href="{{ route('propietario.apartamentos.show', $apartamento->id) }}" class="text-gray-600 hover:text-indigo-600 mr-2">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Editar Apartamento {{ $apartamento->numero_apartamento }}</h1>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <form action="{{ route('propietario.apartamentos.update', $apartamento->id) }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Edificio -->
                <div>
                    <label for="edificio_id" class="block text-gray-700 font-medium mb-2">Edificio</label>
                    <select id="edificio_id" name="edificio_id" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        @foreach($edificios as $edificio)
                        <option value="{{ $edificio->id }}" {{ old('edificio_id', $apartamento->edificio_id) == $edificio->id ? 'selected' : '' }}>
                            {{ $edificio->nombre }} ({{ $edificio->direccion }})
                        </option>
                        @endforeach
                    </select>
                    @error('edificio_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Número de apartamento -->
                <div>
                    <label for="numero_apartamento" class="block text-gray-700 font-medium mb-2">Número de apartamento</label>
                    <input type="text" id="numero_apartamento" name="numero_apartamento" value="{{ old('numero_apartamento', $apartamento->numero_apartamento) }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                    @error('numero_apartamento')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Piso -->
                <div>
                    <label for="piso" class="block text-gray-700 font-medium mb-2">Piso</label>
                    <input type="number" id="piso" name="piso" value="{{ old('piso', $apartamento->piso) }}" min="1" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                    @error('piso')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Estado -->
                <div>
                    <label for="estado" class="block text-gray-700 font-medium mb-2">Estado</label>
                    <select id="estado" name="estado" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        <option value="Disponible" {{ old('estado', $apartamento->estado) == 'Disponible' ? 'selected' : '' }}>Disponible</option>
                        <option value="Ocupado" {{ old('estado', $apartamento->estado) == 'Ocupado' ? 'selected' : '' }}>Ocupado</option>
                        <option value="En mantenimiento" {{ old('estado', $apartamento->estado) == 'En mantenimiento' ? 'selected' : '' }}>En mantenimiento</option>
                    </select>
                    @error('estado')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Precio -->
                <div>
                    <label for="precio" class="block text-gray-700 font-medium mb-2">Precio (COP)</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">$</span>
                        </div>
                        <input type="number" id="precio" name="precio" value="{{ old('precio', $apartamento->precio) }}" min="0" step="1" class="block w-full pl-7 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                    </div>
                    @error('precio')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tamaño -->
                <div>
                    <label for="tamaño" class="block text-gray-700 font-medium mb-2">Tamaño (m²)</label>
                    <input type="number" id="tamaño" name="tamaño" value="{{ old('tamaño', $apartamento->tamaño) }}" min="1" step="0.01" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                    @error('tamaño')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Descripción -->
                <div class="md:col-span-2">
                    <label for="descripcion" class="block text-gray-700 font-medium mb-2">Descripción</label>
                    <textarea id="descripcion" name="descripcion" rows="4" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('descripcion', $apartamento->descripcion) }}</textarea>
                    @error('descripcion')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Imagen actual y nueva -->
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-medium mb-2">Imagen del apartamento</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Imagen actual -->
                        <div class="bg-gray-50 p-4 rounded-md">
                            <p class="text-sm text-gray-500 mb-2">Imagen actual</p>
                            <div class="flex items-center justify-center border rounded-lg p-4 h-40">
                                @if($apartamento->imagen)
                                <img src="{{ asset('storage/' . $apartamento->imagen) }}" alt="Imagen actual del apartamento" class="max-h-full">
                                @else
                                <div class="flex flex-col items-center text-gray-400">
                                    <i class="fas fa-image text-3xl mb-2"></i>
                                    <span>Sin imagen</span>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Nueva imagen -->
                        <div>
                            <p class="text-sm text-gray-500 mb-2">Subir nueva imagen (opcional)</p>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                <div class="space-y-1 text-center">
                                    <div class="mb-3">
                                        <img src="" id="preview-image" class="max-h-24 mx-auto hidden">
                                    </div>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="imagen" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                            <span>Subir una imagen</span>
                                            <input id="imagen" name="imagen" type="file" class="sr-only" accept="image/*" onchange="previewImage()">
                                        </label>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF hasta 2MB</p>
                                </div>
                            </div>
                            @error('imagen')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end mt-6 pt-6 border-t border-gray-200">
                <a href="{{ route('propietario.apartamentos.show', $apartamento->id) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 px-4 rounded-md mr-2">
                    Cancelar
                </a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-md">
                    Guardar cambios
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function previewImage() {
        const input = document.getElementById('imagen');
        const preview = document.getElementById('preview-image');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
