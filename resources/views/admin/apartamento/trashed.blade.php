<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apartamentos Eliminados</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Apartamentos Eliminados</h2>
            <a href="{{ route('apartamento.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">Volver a Apartamentos</a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if ($apartamentos->count() > 0)
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
                            <th class="px-4 py-2">Eliminado el</th>
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
                            <td class="px-4 py-2">{{ $apt->deleted_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-2 flex space-x-2">
                                <form action="{{ route('apartamento.restore', $apt->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-green-500 hover:text-green-700 transition">Restaurar</button>
                                </form>
                                <form action="{{ route('apartamento.force-delete', $apt->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar permanentemente este apartamento? Esta acción no se puede deshacer.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 transition">Eliminar permanentemente</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                <div class="flex">
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            No hay apartamentos eliminados.
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</body>
</html>
