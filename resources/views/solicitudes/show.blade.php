<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de la Solicitud de Alquiler</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-4">Detalles de la Solicitud de Alquiler</h2>

        <div class="mb-4">
            <strong>Usuario:</strong>
            <p>{{ $solicitud->usuario->nombre }}</p>
        </div>

        <div class="mb-4">
            <strong>Apartamento:</strong>
            <p>{{ $solicitud->apartamento->numero_apartamento }} - Piso {{ $solicitud->apartamento->piso }}</p>
        </div>

        <div class="mb-4">
            <strong>Fecha de Solicitud:</strong>
            <p>{{ $solicitud->fecha_solicitud->format('d/m/Y') }}</p>
        </div>

        <div class="mb-4">
            <strong>Estado de la Solicitud:</strong>
            <p>{{ $solicitud->estado_solicitud }}</p>
        </div>

        <div class="flex justify-end space-x-4">
            <a href="{{ route('solicitudes.edit', $solicitud->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded">Editar</a>
            <form action="{{ route('solicitudes.destroy', $solicitud->id) }}" method="POST" onsubmit="return confirm('¿Eliminar esta solicitud?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Eliminar</button>
            </form>
            <a href="{{ route('solicitudes.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Volver a la lista</a>
        </div>
    </div>
</body>
</html>
