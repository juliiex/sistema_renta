<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Recordatorios de Pago</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Lista de Recordatorios de Pago</h2>
            <div class="flex space-x-2">
                @if(auth()->user()->hasRole(['admin', 'propietario']))
                <a href="{{ route('recordatorio_pago.trashed') }}" class="bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600 transition">Ver Eliminados</a>
                @endif
                <a href="{{ route('home') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">Volver al Dashboard</a>
            </div>
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

        @if(auth()->user()->hasRole(['admin', 'propietario']))
        <a href="{{ route('recordatorio_pago.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block hover:bg-blue-600 transition">Crear Recordatorio de Pago</a>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white mt-4 border border-gray-200">
                <thead>
                    <tr class="bg-gray-200 text-gray-700">
                        <th class="px-4 py-2 text-left">ID</th>
                        <th class="px-4 py-2 text-left">Usuario</th>
                        <th class="px-4 py-2 text-left">Método</th>
                        <th class="px-4 py-2 text-left">Fecha de Envío</th>
                        <th class="px-4 py-2 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recordatorios as $recordatorio)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $recordatorio->id }}</td>
                        <td class="px-4 py-2">{{ $recordatorio->usuario ? $recordatorio->usuario->nombre : 'N/A' }}</td>
                        <td class="px-4 py-2">{{ $recordatorio->metodo }}</td>
                        <td class="px-4 py-2">
                            {{ $recordatorio->fecha_envio ? $recordatorio->fecha_envio->format('d/m/Y') : 'N/A' }}
                        </td>
                        <td class="px-4 py-2">
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route('recordatorio_pago.show', $recordatorio->id) }}" class="text-blue-500 hover:underline">Ver</a>
                                @if(auth()->user()->hasRole(['admin', 'propietario']))
                                <a href="{{ route('recordatorio_pago.edit', $recordatorio->id) }}" class="text-yellow-500 hover:underline">Editar</a>
                                <form action="{{ route('recordatorio_pago.destroy', $recordatorio->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Está seguro que desea eliminar este recordatorio de pago?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline">Eliminar</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-2 text-center text-gray-500">No hay recordatorios de pago registrados</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
