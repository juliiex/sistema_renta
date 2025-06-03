<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recordatorios de Pago Eliminados</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Recordatorios de Pago Eliminados</h2>
            <a href="{{ route('recordatorio_pago.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">Volver a la lista</a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @if($recordatorios->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white mt-4 border border-gray-200">
                    <thead>
                        <tr class="bg-gray-200 text-gray-700">
                            <th class="px-4 py-2 text-left">ID</th>
                            <th class="px-4 py-2 text-left">Usuario</th>
                            <th class="px-4 py-2 text-left">Método</th>
                            <th class="px-4 py-2 text-left">Fecha de Envío</th>
                            <th class="px-4 py-2 text-left">Eliminado el</th>
                            <th class="px-4 py-2 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recordatorios as $recordatorio)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $recordatorio->id }}</td>
                            <td class="px-4 py-2">{{ $recordatorio->usuario ? $recordatorio->usuario->nombre : 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $recordatorio->metodo }}</td>
                            <td class="px-4 py-2">
                                {{ $recordatorio->fecha_envio ? $recordatorio->fecha_envio->format('d/m/Y') : 'N/A' }}
                            </td>
                            <td class="px-4 py-2">{{ $recordatorio->deleted_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-2">
                                <div class="flex justify-center space-x-2">
                                    <form action="{{ route('recordatorio_pago.restore', $recordatorio->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-green-500 hover:underline">Restaurar</button>
                                    </form>
                                    <form action="{{ route('recordatorio_pago.force-delete', $recordatorio->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar permanentemente este recordatorio de pago? Esta acción no se puede deshacer.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:underline">Eliminar permanentemente</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                <div class="flex">
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            No hay recordatorios de pago eliminados.
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</body>
</html>
