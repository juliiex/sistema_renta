<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Edificio</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-4">Editar Edificio</h2>

        <form action="{{ route('edificio.update', $edificio->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="nombre" class="block text-gray-700">Nombre:</label>
                <input type="text" id="nombre" name="nombre" class="w-full p-2 border border-gray-300 rounded" value="{{ old('nombre', $edificio->nombre) }}" required>
            </div>

            <div class="mb-4">
                <label for="direccion" class="block text-gray-700">Dirección:</label>
                <input type="text" id="direccion" name="direccion" class="w-full p-2 border border-gray-300 rounded" value="{{ old('direccion', $edificio->direccion) }}" required>
            </div>

            <div class="mb-4">
                <label for="cantidad_pisos" class="block text-gray-700">Cantidad de Pisos:</label>
                <input type="number" id="cantidad_pisos" name="cantidad_pisos" class="w-full p-2 border border-gray-300 rounded" value="{{ old('cantidad_pisos', $edificio->cantidad_pisos) }}" required>
            </div>

            <div class="mb-4">
                <label for="imagen" class="block text-gray-700">Imagen (opcional):</label>
                <input type="file" id="imagen" name="imagen" class="w-full p-2 border border-gray-300 rounded">
            </div>

            <div class="mb-4">
                <label for="descripcion" class="block text-gray-700">Descripción (opcional):</label>
                <textarea id="descripcion" name="descripcion" class="w-full p-2 border border-gray-300 rounded" rows="4">{{ old('descripcion', $edificio->descripcion) }}</textarea>
            </div>

            <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded">Actualizar Edificio</button>
        </form>
    </div>
</body>
</html>
