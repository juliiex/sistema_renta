<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Edificio</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Crear Edificio</h2>
            <a href="{{ route('edificio.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">Volver a la lista</a>
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

        <form action="{{ route('edificio.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre:</label>
                <input type="text" id="nombre" name="nombre" class="w-full p-2 border border-gray-300 rounded" value="{{ old('nombre') }}" required>
            </div>

            <div class="mb-4">
                <label for="direccion" class="block text-sm font-medium text-gray-700 mb-1">Dirección:</label>
                <input type="text" id="direccion" name="direccion" class="w-full p-2 border border-gray-300 rounded" value="{{ old('direccion') }}" required>
            </div>

            <div class="mb-4">
                <label for="cantidad_pisos" class="block text-sm font-medium text-gray-700 mb-1">Cantidad de Pisos:</label>
                <input type="number" id="cantidad_pisos" name="cantidad_pisos" class="w-full p-2 border border-gray-300 rounded" value="{{ old('cantidad_pisos') }}" min="1" required>
            </div>

            <div class="mb-4">
                <label for="imagen" class="block text-sm font-medium text-gray-700 mb-1">Imagen (opcional):</label>
                <input type="file" id="imagen" name="imagen" class="w-full p-2 border border-gray-300 rounded">
                <p class="text-xs text-gray-500 mt-1">Formatos permitidos: JPG, JPEG, PNG, GIF. Tamaño máximo: 2MB.</p>
            </div>

            <div class="mb-4">
                <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">Descripción (opcional):</label>
                <textarea id="descripcion" name="descripcion" class="w-full p-2 border border-gray-300 rounded" rows="4">{{ old('descripcion') }}</textarea>
            </div>

            <div class="flex justify-end pt-4">
                <a href="{{ route('edificio.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded mr-2 hover:bg-gray-600 transition">Cancelar</a>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">Crear Edificio</button>
            </div>
        </form>
    </div>
</body>
</html>
