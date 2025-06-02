<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Apartamento</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Editar Apartamento</h2>
            <a href="{{ route('apartamento.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">Volver a la lista</a>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('apartamento.update', $apartamento->id) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="numero_apartamento" class="block text-sm font-medium text-gray-700 mb-1">Número de apartamento:</label>
                    <input type="text" id="numero_apartamento" name="numero_apartamento" value="{{ old('numero_apartamento', $apartamento->numero_apartamento) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div>
                    <label for="piso" class="block text-sm font-medium text-gray-700 mb-1">Piso:</label>
                    <input type="number" id="piso" name="piso" value="{{ old('piso', $apartamento->piso) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div>
                    <label for="tamaño" class="block text-sm font-medium text-gray-700 mb-1">Tamaño (m²):</label>
                    <input type="number" id="tamaño" name="tamaño" value="{{ old('tamaño', $apartamento->tamaño) }}" step="0.01"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div>
                    <label for="precio" class="block text-sm font-medium text-gray-700 mb-1">Precio:</label>
                    <input type="number" id="precio" name="precio" value="{{ old('precio', $apartamento->precio) }}" step="0.01"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div>
                    <label for="edificio_id" class="block text-sm font-medium text-gray-700 mb-1">Edificio:</label>
                    <select id="edificio_id" name="edificio_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Seleccione un edificio</option>
                        @foreach($edificios as $edificio)
                            <option value="{{ $edificio->id }}" {{ old('edificio_id', $apartamento->edificio_id) == $edificio->id ? 'selected' : '' }}>
                                {{ $edificio->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="estado" class="block text-sm font-medium text-gray-700 mb-1">Estado:</label>
                    <select id="estado" name="estado"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="Disponible" {{ old('estado', $apartamento->estado) == 'Disponible' ? 'selected' : '' }}>Disponible</option>
                        <option value="Ocupado" {{ old('estado', $apartamento->estado) == 'Ocupado' ? 'selected' : '' }}>Ocupado</option>
                        <option value="En mantenimiento" {{ old('estado', $apartamento->estado) == 'En mantenimiento' ? 'selected' : '' }}>En mantenimiento</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">Descripción:</label>
                <textarea id="descripcion" name="descripcion" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('descripcion', $apartamento->descripcion) }}</textarea>
            </div>

            <div>
                <label for="imagen" class="block text-sm font-medium text-gray-700 mb-1">Imagen:</label>
                <input type="file" id="imagen" name="imagen"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">

                @if($apartamento->imagen)
                    <div class="mt-2">
                        <p class="text-sm text-gray-600 mb-1">Imagen actual:</p>
                        <img src="{{ asset('storage/' . $apartamento->imagen) }}"
                             class="max-w-full h-auto rounded-lg shadow-sm" style="max-height: 200px;" alt="Imagen del apartamento">
                    </div>
                @endif
                <p class="text-sm text-gray-500 mt-1">Formatos aceptados: JPG, PNG, GIF. Tamaño máximo: 2MB</p>
            </div>

            <div class="flex justify-end mt-6">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">Actualizar Apartamento</button>
            </div>
        </form>
    </div>
</body>
</html>

