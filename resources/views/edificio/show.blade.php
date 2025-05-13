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
        <h2 class="text-2xl font-bold mb-4">Detalles del Edificio</h2>

        <div class="mb-3">
            <strong>Nombre:</strong>
            <p>{{ $edificio->nombre }}</p>
        </div>

        <div class="mb-3">
            <strong>Dirección:</strong>
            <p>{{ $edificio->direccion }}</p>
        </div>

        <div class="mb-3">
            <strong>Cantidad de Pisos:</strong>
            <p>{{ $edificio->cantidad_pisos }}</p>
        </div>

        <div class="mb-3">
            <strong>Imagen:</strong>
            @if($edificio->imagen)
                <img src="{{ asset('storage/' . $edificio->imagen) }}" class="mt-2 w-100 rounded" style="max-height: 300px;">
            @else
                <p>No hay imagen disponible.</p>
            @endif
        </div>

        <div class="mb-3">
            <strong>Descripción:</strong>
            <p>{{ $edificio->descripcion ?? 'Sin descripción' }}</p>
        </div>

        <div class="text-end">
            <a href="{{ route('edificio.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Volver a la lista</a>
            <a href="{{ route('edificio.edit', $edificio->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded">Editar Edificio</a>
        </div>
    </div>
</body>
</html>
