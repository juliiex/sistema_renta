<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Edificios</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Lista de Edificios</h2>
            <a href="{{ route('menu') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">Volver al Menú</a>
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

        <a href="{{ route('edificio.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block hover:bg-blue-600 transition">Crear Edificio</a>

        <table class="min-w-full bg-white mt-4">
            <thead>
                <tr class="bg-gray-200 text-left">
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Imagen</th>
                    <th class="px-4 py-2">Nombre</th>
                    <th class="px-4 py-2">Dirección</th>
                    <th class="px-4 py-2">Pisos</th>
                    <th class="px-4 py-2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($edificios as $edificio)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-2">{{ $edificio->id }}</td>
                    <td class="px-4 py-2">
                        @if($edificio->imagen)
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
                    <td class="px-4 py-2 flex space-x-2">
                        <a href="{{ route('edificio.show', $edificio->id) }}" class="text-blue-500 hover:underline">Ver</a>
                        <a href="{{ route('edificio.edit', $edificio->id) }}" class="text-yellow-500 hover:underline">Editar</a>
                        <form action="{{ route('edificio.destroy', $edificio->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este edificio?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:underline">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-2 text-center text-gray-500">No hay edificios registrados</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
