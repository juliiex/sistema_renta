<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Estado de Alquiler</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-4">Detalles del Estado de Alquiler</h2>

        <div class="mb-4">
            <strong>Contrato:</strong>
            <p>{{ $estadoAlquiler->contrato->id }} - {{ $estadoAlquiler->contrato->apartamento->numero_apartamento }}</p>
        </div>

        <div class="mb-4">
            <strong>Usuario:</strong>
            <p>{{ $estadoAlquiler->usuario->nombre }}</p>
        </div>

        <div class="mb-4">
            <strong>Estado de Pago:</strong>
            <p>{{ ucfirst($estadoAlquiler->estado_pago) }}</p>
        </div>

        <div class="mb-4">
            <strong>Fecha de Reporte:</strong>
            <p>{{ $estadoAlquiler->fecha_reporte->format('d/m/Y') }}</p>
        </div>

        <div class="flex justify-end space-x-4">
            <a href="{{ route('estado_alquiler.edit', $estadoAlquiler->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded">Editar</a>
            <form action="{{ route('estado_alquiler.destroy', $estadoAlquiler->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este estado de alquiler?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Eliminar</button>
            </form>
            <a href="{{ route('estado_alquiler.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Volver a la lista</a>
        </div>
    </div>
</body>
</html>


