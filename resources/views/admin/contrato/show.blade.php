<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Contrato</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Detalles del Contrato</h2>
            <a href="{{ route('contrato.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">Volver a la lista</a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 p-5 rounded-lg shadow-sm space-y-4">
                <div class="mb-4">
                    <h3 class="text-lg font-semibold border-b pb-2 mb-3">Información del Contrato</h3>

                    <div class="grid grid-cols-1 gap-3">
                        <div>
                            <p class="text-sm text-gray-500">ID del Contrato</p>
                            <p class="font-medium">{{ $contrato->id }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">Estado del Contrato</p>
                            <p>
                                @if($contrato->estado == 'activo')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Activo</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Inactivo</span>
                                @endif
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">Estado de Firma</p>
                            <p>
                                @if($contrato->estado_firma == 'firmado')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Firmado</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pendiente de firma</span>
                                @endif
                            </p>
                        </div>

                        @if($contrato->firma_imagen)
                        <div>
                            <p class="text-sm text-gray-500">Firma Digital</p>
                            <div class="mt-1 border border-gray-300 p-2 bg-white rounded-md">
                                <img src="{{ asset('storage/' . $contrato->firma_imagen) }}" alt="Firma" class="max-w-full h-auto">
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="mb-4">
                    <h3 class="text-lg font-semibold border-b pb-2 mb-3">Período</h3>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <p class="text-sm text-gray-500">Fecha de Inicio</p>
                            <p class="font-medium">{{ $contrato->fecha_inicio->format('d/m/Y') }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">Fecha de Fin</p>
                            <p class="font-medium">{{ $contrato->fecha_fin->format('d/m/Y') }}</p>
                        </div>

                        <div class="col-span-2">
                            <p class="text-sm text-gray-500">Duración</p>
                            <p class="font-medium">
                                {{ $contrato->fecha_inicio->diffInMonths($contrato->fecha_fin) }} meses
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div class="bg-gray-50 p-5 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold border-b pb-2 mb-3">Información del Usuario</h3>

                    <div class="flex items-center mb-4">
                        @if($contrato->usuario && $contrato->usuario->avatar)
                            <img src="{{ asset('storage/avatars/' . $contrato->usuario->avatar) }}"
                                 class="h-16 w-16 rounded-full mr-4 object-cover border-2 border-gray-200" alt="Avatar">
                        @else
                            <div class="h-16 w-16 rounded-full mr-4 bg-blue-100 flex items-center justify-center text-blue-500">
                                <span class="text-xl font-bold">{{ substr($contrato->usuario->nombre ?? '?', 0, 1) }}</span>
                            </div>
                        @endif

                        <div>
                            <p class="font-medium text-lg">{{ $contrato->usuario->nombre ?? 'N/A' }}</p>
                            <p class="text-gray-500">{{ $contrato->usuario->correo ?? 'N/A' }}</p>
                            <p class="text-gray-500">{{ $contrato->usuario->telefono ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 p-5 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold border-b pb-2 mb-3">Información del Apartamento</h3>

                    <div class="grid grid-cols-1 gap-3">
                        <div>
                            <p class="text-sm text-gray-500">Número de Apartamento</p>
                            <p class="font-medium">{{ $contrato->apartamento->numero_apartamento ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">Piso</p>
                            <p class="font-medium">{{ $contrato->apartamento->piso ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">Edificio</p>
                            <p class="font-medium">{{ $contrato->apartamento->edificio->nombre ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">Precio</p>
                            <p class="font-medium">${{ number_format($contrato->apartamento->precio ?? 0, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('contrato.edit', $contrato->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition">
                Editar Contrato
            </a>

            <form action="{{ route('contrato.destroy', $contrato->id) }}" method="POST"
                  onsubmit="return confirm('¿Estás seguro de eliminar este contrato? Esta acción liberará el apartamento.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">
                    Eliminar Contrato
                </button>
            </form>
        </div>
    </div>
</body>
</html>
