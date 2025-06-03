<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edificios Eliminados</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Edificios Eliminados</h2>
            <a href="{{ route('edificio.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">Volver a la lista</a>
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

        @if ($edificios->count() > 0)
            <table class="min-w-full bg-white mt-4">
                <thead>
                    <tr class="bg-gray-200 text-left">
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Imagen</th>
                        <th class="px-4 py-2">Nombre</th>
                        <th class="px-4 py-2">Dirección</th>
                        <th class="px-4 py-2">Pisos</th>
                        <th class="px-4 py-2">Eliminado el</th>
                        <th class="px-4 py-2">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($edificios as $edificio)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $edificio->id }}</td>
                        <td class="px-4 py-2">
                            @if($edificio->imagen && Storage::disk('public')->exists($edificio->imagen))
                                <img src="{{ asset('storage/' . $edificio->imagen) }}" alt="{{ $edificio->nombre }}" class="w-16 h-16 object-cover rounded">
                            @else
                                <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center text-gray-500">
                                    <span class="text-xs">Sin imagen</span>
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-2">{{ $edificio->nombre }}</td>
                        <td class="px-4 py-2">{{ $edificio->direccion }}</td>
                        <td class="px-4 py-2">{{ $edificio->cantidad_pisos }}</td>
                        <td class="px-4 py-2">{{ $edificio->deleted_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-2 flex space-x-2">
                            <form action="{{ route('edificio.restore', $edificio->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-green-500 hover:underline">Restaurar</button>
                            </form>
                            <form action="{{ route('edificio.force-delete', $edificio->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar permanentemente este edificio? Esta acción no se puede deshacer.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline">Eliminar permanentemente</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                <div class="flex">
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            No hay edificios eliminados.
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</body>
</html>
