<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Edificio</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Detalles del Edificio</h2>
            <a href="{{ route('edificio.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">Volver a la lista</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <div class="mb-3">
                    <strong class="block text-sm font-medium text-gray-700">ID:</strong>
                    <p>{{ $edificio->id }}</p>
                </div>

                <div class="mb-3">
                    <strong class="block text-sm font-medium text-gray-700">Nombre:</strong>
                    <p>{{ $edificio->nombre }}</p>
                </div>

                <div class="mb-3">
                    <strong class="block text-sm font-medium text-gray-700">Dirección:</strong>
                    <p>{{ $edificio->direccion }}</p>
                </div>

                <div class="mb-3">
                    <strong class="block text-sm font-medium text-gray-700">Cantidad de Pisos:</strong>
                    <p>{{ $edificio->cantidad_pisos }}</p>
                </div>

                <div class="mb-3">
                    <strong class="block text-sm font-medium text-gray-700">Descripción:</strong>
                    <p>{{ $edificio->descripcion ?? 'Sin descripción' }}</p>
                </div>
            </div>

            <div>
                <div class="mb-3">
                    <strong class="block text-sm font-medium text-gray-700">Imagen:</strong>
                    @if($edificio->imagen)
                        <img src="{{ asset('storage/' . $edificio->imagen) }}" class="mt-2 max-w-full rounded shadow" style="max-height: 300px;">
                    @else
                        <p>No hay imagen disponible.</p>
                    @endif
                </div>

                <div class="flex justify-end space-x-2 mt-6">
                    <a href="{{ route('edificio.edit', $edificio->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition">Editar</a>
                    <form action="{{ route('edificio.destroy', $edificio->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Está seguro que desea eliminar este edificio?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
