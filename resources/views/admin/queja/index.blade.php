<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Quejas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Lista de Quejas</h2>
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

        <a href="{{ route('queja.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block hover:bg-blue-600 transition">Crear Queja</a>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white mt-4 border border-gray-200">
                <thead>
                    <tr class="bg-gray-200 text-gray-700">
                        <th class="px-4 py-2 text-left">ID</th>
                        <th class="px-4 py-2 text-left">Usuario</th>
                        <th class="px-4 py-2 text-left">Tipo</th>
                        <th class="px-4 py-2 text-left">Fecha de Envío</th>
                        <th class="px-4 py-2 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($quejas as $queja)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $queja->id }}</td>
                        <td class="px-4 py-2">{{ $queja->usuario->nombre ?? 'N/A' }}</td>
                        <td class="px-4 py-2">
                            @switch($queja->tipo)
                                @case('Aplicativo')
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">{{ $queja->tipo }}</span>
                                    @break
                                @case('Servicio')
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">{{ $queja->tipo }}</span>
                                    @break
                                @case('Mantenimiento')
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">{{ $queja->tipo }}</span>
                                    @break
                                @case('Seguridad')
                                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">{{ $queja->tipo }}</span>
                                    @break
                                @default
                                    <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">{{ $queja->tipo }}</span>
                            @endswitch
                        </td>
                        <td class="px-4 py-2">{{ $queja->fecha_envio->format('d/m/Y') }}</td>
                        <td class="px-4 py-2">
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route('queja.show', $queja->id) }}" class="text-blue-500 hover:underline">Ver</a>
                                @if(auth()->user()->hasRole(['admin', 'propietario']) || auth()->id() === $queja->usuario_id)
                                <a href="{{ route('queja.edit', $queja->id) }}" class="text-yellow-500 hover:underline">Editar</a>
                                <form action="{{ route('queja.destroy', $queja->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Está seguro que desea eliminar esta queja?');">
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
                        <td colspan="5" class="px-4 py-2 text-center text-gray-500">No hay quejas registradas</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
