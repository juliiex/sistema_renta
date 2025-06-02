<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de la Queja</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Detalles de la Queja</h2>
            <a href="{{ route('queja.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">Volver a la lista</a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="text-lg font-semibold mb-3 text-gray-700">Información de la Queja</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">ID:</strong>
                        <p>{{ $queja->id }}</p>
                    </div>

                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">Usuario:</strong>
                        <p>{{ $queja->usuario->nombre ?? 'No disponible' }}</p>
                    </div>

                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">Correo:</strong>
                        <p>{{ $queja->usuario->correo ?? 'No disponible' }}</p>
                    </div>

                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">Tipo:</strong>
                        <div class="mt-1">
                            @switch($queja->tipo)
                                @case('Aplicativo')
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full">{{ $queja->tipo }}</span>
                                    @break
                                @case('Servicio')
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full">{{ $queja->tipo }}</span>
                                    @break
                                @case('Mantenimiento')
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full">{{ $queja->tipo }}</span>
                                    @break
                                @case('Seguridad')
                                    <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full">{{ $queja->tipo }}</span>
                                    @break
                                @default
                                    <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full">{{ $queja->tipo }}</span>
                            @endswitch
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-semibold mb-3 text-gray-700">Detalles Adicionales</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">Fecha de Envío:</strong>
                        <p>
                            {{ $queja->fecha_envio ? $queja->fecha_envio->format('d/m/Y') : 'No disponible' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-3 text-gray-700">Descripción de la Queja</h3>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="whitespace-pre-line">{{ $queja->descripcion }}</p>
            </div>
        </div>

        @if(auth()->user()->hasRole(['admin', 'propietario']) || auth()->id() === $queja->usuario_id)
        <div class="flex justify-end space-x-2">
            <a href="{{ route('queja.edit', $queja->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition">Editar</a>
            <form action="{{ route('queja.destroy', $queja->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Está seguro que desea eliminar esta queja?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">Eliminar</button>
            </form>
        </div>
        @endif
    </div>
</body>
</html>
