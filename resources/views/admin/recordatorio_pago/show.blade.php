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
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Detalles del Recordatorio de Pago</h2>
            <a href="{{ route('recordatorio_pago.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">Volver a la lista</a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="text-lg font-semibold mb-3 text-gray-700">Información del Recordatorio</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">ID:</strong>
                        <p>{{ $recordatorio->id }}</p>
                    </div>

                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">Usuario (Inquilino):</strong>
                        <p>{{ $recordatorio->usuario ? $recordatorio->usuario->nombre : 'No asignado' }}</p>
                    </div>

                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">Correo:</strong>
                        <p>{{ $recordatorio->usuario ? $recordatorio->usuario->correo : 'No disponible' }}</p>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-semibold mb-3 text-gray-700">Detalles de Envío</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">Método de Envío:</strong>
                        <p>{{ $recordatorio->metodo }}</p>
                    </div>

                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">Fecha de Envío:</strong>
                        <p>{{ $recordatorio->fecha_envio ? $recordatorio->fecha_envio->format('d/m/Y') : 'No asignada' }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if(auth()->user()->hasRole(['admin', 'propietario']))
        <div class="flex justify-end space-x-2">
            <a href="{{ route('recordatorio_pago.edit', $recordatorio->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition">Editar</a>
            <form action="{{ route('recordatorio_pago.destroy', $recordatorio->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Está seguro que desea eliminar este recordatorio de pago?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">Eliminar</button>
            </form>
        </div>
        @endif
    </div>
</body>
</html>
