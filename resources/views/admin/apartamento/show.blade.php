<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Apartamento</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Detalles del Apartamento</h2>
            <a href="{{ route('apartamento.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">Volver a la lista</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">Imagen</h3>
                    @if($apartamento->imagen)
                        <img src="{{ asset('storage/' . $apartamento->imagen) }}"
                             class="mt-2 w-full h-auto rounded-lg shadow-sm object-cover" style="max-height: 300px;" alt="Imagen del apartamento">
                    @else
                        <div class="mt-2 bg-gray-200 rounded-lg w-full h-48 flex items-center justify-center text-gray-400">
                            Sin imagen disponible
                        </div>
                    @endif
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-700">Descripción</h3>
                    <p class="mt-1 text-gray-600">{{ $apartamento->descripcion ?? 'Sin descripción disponible' }}</p>
                </div>
            </div>

            <div class="space-y-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Número de apartamento</h4>
                            <p class="mt-1 font-semibold">{{ $apartamento->numero_apartamento }}</p>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Piso</h4>
                            <p class="mt-1 font-semibold">{{ $apartamento->piso }}</p>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Tamaño</h4>
                            <p class="mt-1 font-semibold">{{ $apartamento->tamaño }} m²</p>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Precio</h4>
                            <p class="mt-1 font-semibold">${{ number_format($apartamento->precio, 2) }}</p>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Estado</h4>
                            <p class="mt-1">
                                @if($apartamento->estado == 'Disponible')
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Disponible</span>
                                @elseif($apartamento->estado == 'Ocupado')
                                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Ocupado</span>
                                @else
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">{{ $apartamento->estado }}</span>
                                @endif
                            </p>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Edificio</h4>
                            <p class="mt-1 font-semibold">{{ $apartamento->edificio->nombre }}</p>
                        </div>

                        <div class="col-span-2">
                            <h4 class="text-sm font-medium text-gray-500">Dirección del edificio</h4>
                            <p class="mt-1 font-semibold">{{ $apartamento->edificio->direccion ?? 'No especificada' }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-2 pt-4">
                    <a href="{{ route('apartamento.edit', $apartamento->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition">
                        Editar
                    </a>

                    <form action="{{ route('apartamento.destroy', $apartamento->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este apartamento?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">
                            Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
