<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Apartamentos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-4">Lista de Apartamentos</h2>

        @if (session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('apartamento.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Crear Apartamento</a>

        <table class="min-w-full bg-white mt-4">
            <thead>
                <tr class="bg-gray-200 text-left">
                    <th class="px-4 py-2">Número</th>
                    <th class="px-4 py-2">Piso</th>
                    <th class="px-4 py-2">Tamaño (m²)</th>
                    <th class="px-4 py-2">Estado</th>
                    <th class="px-4 py-2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($apartamentos as $apt)
                <tr class="border-b">
                    <td class="px-4 py-2">{{ $apt->numero_apartamento }}</td>
                    <td class="px-4 py-2">{{ $apt->piso }}</td>
                    <td class="px-4 py-2">{{ $apt->tamaño }} m²</td>
                    <td class="px-4 py-2">{{ ucfirst($apt->estado) }}</td>
                    <td class="px-4 py-2 flex space-x-2">
                        <a href="{{ route('apartamento.show', $apt->id) }}" class="text-blue-500">Ver</a>
                        <a href="{{ route('apartamento.edit', $apt->id) }}" class="text-yellow-500">Editar</a>
                        <form action="{{ route('apartamento.destroy', $apt->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este apartamento?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>

