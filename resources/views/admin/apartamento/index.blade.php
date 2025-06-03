<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Apartamentos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Lista de Apartamentos</h2>
            <div class="flex space-x-2">
                <a href="{{ route('apartamento.trashed') }}" class="bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600 transition">Ver Eliminados</a>
                <a href="{{ route('home') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">Volver al Dashboard</a>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('apartamento.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block hover:bg-blue-600 transition">Crear Apartamento</a>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white mt-4 shadow rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-gray-200 text-left">
                        <th class="px-4 py-2">Número</th>
                        <th class="px-4 py-2">Edificio</th>
                        <th class="px-4 py-2">Piso</th>
                        <th class="px-4 py-2">Tamaño (m²)</th>
                        <th class="px-4 py-2">Precio</th>
                        <th class="px-4 py-2">Estado</th>
                        <th class="px-4 py-2">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($apartamentos as $apt)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $apt->numero_apartamento }}</td>
                        <td class="px-4 py-2">{{ $apt->edificio->nombre ?? 'N/A' }}</td>
                        <td class="px-4 py-2">{{ $apt->piso }}</td>
                        <td class="px-4 py-2">{{ $apt->tamaño }} m²</td>
                        <td class="px-4 py-2">${{ number_format($apt->precio, 2) }}</td>
                        <td class="px-4 py-2">
                            @if($apt->estado == 'Disponible')
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Disponible</span>
                            @elseif($apt->estado == 'Ocupado')
                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Ocupado</span>
                            @else
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">{{ $apt->estado }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 flex space-x-2">
                            <a href="{{ route('apartamento.show', $apt->id) }}" class="text-blue-500 hover:text-blue-700 transition">Ver</a>
                            <a href="{{ route('apartamento.edit', $apt->id) }}" class="text-yellow-500 hover:text-yellow-700 transition">Editar</a>
                            <form action="{{ route('apartamento.destroy', $apt->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este apartamento?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 transition">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
