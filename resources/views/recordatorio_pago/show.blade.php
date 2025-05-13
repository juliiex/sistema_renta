<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Recordatorio de Pago</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">

    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-4">Detalles del Recordatorio de Pago</h2>

        <div class="mb-4">
            <strong>Usuario:</strong>
            <p>{{ $recordatorio->usuario->nombre ?? 'No asignado' }}</p>
        </div>

        <div class="mb-4">
            <strong>Método de Envío:</strong>
            <p>{{ $recordatorio->metodo ?? 'No asignado' }}</p>
        </div>

        <div class="mb-4">
            <strong>Fecha de Envío:</strong>
            <p>{{ $recordatorio->fecha_envio->format('d/m/Y') ?? 'No asignada' }}</p>
        </div>

        <div class="flex justify-end space-x-4">
            <a href="{{ route('recordatorio_pago.edit', $recordatorio->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded">Editar</a>
            <form action="{{ route('recordatorio_pago.destroy', $recordatorio->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este recordatorio de pago?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Eliminar</button>
            </form>
            <a href="{{ route('recordatorio_pago.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Volver a la lista</a>
        </div>
    </div>

</body>
</html>

