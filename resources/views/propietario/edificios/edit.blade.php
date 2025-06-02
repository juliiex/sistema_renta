@extends('layouts.dashboard-sidebar')

@section('title', 'Editar Edificio')

@section('dashboard-content')
<div class="container mx-auto px-4">
    <div class="flex items-center mb-6">
        <a href="{{ route('propietario.edificios.show', $edificio->id) }}" class="text-gray-600 hover:text-indigo-600 mr-2">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Editar {{ $edificio->nombre }}</h1>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <form action="{{ route('propietario.edificios.update', $edificio->id) }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <div class="mb-4">
                        <label for="descripcion" class="block text-gray-700 font-medium mb-2">Descripción</label>
                        <textarea id="descripcion" name="descripcion" rows="4" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('descripcion', $edificio->descripcion) }}</textarea>
                        @error('descripcion')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Información del edificio no modificable (solo para referencia) -->
                <div class="bg-gray-50 p-4 rounded-md border border-gray-200">
                    <h3 class="text-sm font-medium text-gray-500 mb-3">Información del edificio (no modificable)</h3>
                    <div class="mb-3">
                        <label class="block text-xs text-gray-500">Nombre</label>
                        <p class="text-gray-900">{{ $edificio->nombre }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="block text-xs text-gray-500">Dirección</label>
                        <p class="text-gray-900">{{ $edificio->direccion }}</p>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500">Número de pisos</label>
                        <p class="text-gray-900">{{ $edificio->cantidad_pisos }}</p>
                    </div>
                </div>

                <!-- Imagen del edificio -->
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Imagen del edificio</label>
                    <div class="mb-3">
                        <div class="flex items-center justify-center border-2 border-dashed border-gray-300 rounded-lg p-4 h-40">
                            @if($edificio->imagen)
                            <img src="{{ asset('storage/' . $edificio->imagen) }}" alt="Imagen del edificio" id="preview-image" class="max-h-full">
                            @else
                            <div id="no-image" class="flex flex-col items-center text-gray-400">
                                <i class="fas fa-image text-3xl mb-2"></i>
                                <span>Sin imagen</span>
                            </div>
                            <img src="" id="preview-image" class="max-h-full hidden">
                            @endif
                        </div>
                        <input type="file" name="imagen" id="imagen" class="hidden" accept="image/*" onchange="previewImage()">
                        <div class="mt-2 flex justify-center">
                            <label for="imagen" class="cursor-pointer bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 px-4 rounded-md text-sm flex items-center">
                                <i class="fas fa-upload mr-2"></i> Subir nueva imagen
                            </label>
                        </div>
                        @error('imagen')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-end mt-6 pt-6 border-t border-gray-200">
                <a href="{{ route('propietario.edificios.show', $edificio->id) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 px-4 rounded-md mr-2">
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
        const noImage = document.getElementById('no-image');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                if (noImage) noImage.classList.add('hidden');
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
