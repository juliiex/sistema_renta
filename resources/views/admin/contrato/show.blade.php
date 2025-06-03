<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Contrato</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Detalle del Contrato #{{ $contrato->id }}</h2>
            <div class="flex space-x-2">
                <a href="{{ route('contrato.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">Volver a la lista</a>
                <a href="{{ route('contrato.trashed') }}" class="bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600 transition">Ver Eliminados</a>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <div class="mb-6">
                    <h3 class="text-lg font-semibold border-b pb-2">Información del Usuario</h3>
                    <div class="mt-4 space-y-3">
                        <div class="flex items-center">
                            @if($contrato->usuario && $contrato->usuario->avatar)
                                <img src="{{ asset('storage/avatars/' . $contrato->usuario->avatar) }}" class="h-12 w-12 rounded-full mr-3" alt="Avatar">
                            @endif
                            <div>
                                <p class="font-medium">{{ $contrato->usuario->nombre ?? 'N/A' }}</p>
                                <p class="text-gray-600">{{ $contrato->usuario->correo ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <p><span class="text-gray-600">Teléfono:</span> {{ $contrato->usuario->telefono ?? 'No especificado' }}</p>
                        <p><span class="text-gray-600">ID Usuario:</span> {{ $contrato->usuario_id ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold border-b pb-2">Información del Apartamento</h3>
                    <div class="mt-4 space-y-3">
                        <p><span class="text-gray-600">Número:</span> {{ $contrato->apartamento->numero_apartamento ?? 'N/A' }}</p>
                        <p><span class="text-gray-600">Edificio:</span> {{ $contrato->apartamento->edificio->nombre ?? 'N/A' }}</p>
                        <p><span class="text-gray-600">Dirección:</span> {{ $contrato->apartamento->edificio->direccion ?? 'No especificada' }}</p>
                        <p><span class="text-gray-600">Piso:</span> {{ $contrato->apartamento->piso ?? 'N/A' }}</p>
                        <p><span class="text-gray-600">Precio:</span> ${{ number_format($contrato->apartamento->precio ?? 0, 2) }}</p>
                    </div>
                </div>
            </div>

            <div>
                <div class="mb-6">
                    <h3 class="text-lg font-semibold border-b pb-2">Detalles del Contrato</h3>
                    <div class="mt-4 space-y-3">
                        <p><span class="text-gray-600">Fecha de Inicio:</span> {{ $contrato->fecha_inicio->format('d/m/Y') }}</p>
                        <p><span class="text-gray-600">Fecha de Fin:</span> {{ $contrato->fecha_fin->format('d/m/Y') }}</p>
                        <p>
                            <span class="text-gray-600">Estado:</span>
                            @if($contrato->estado == 'activo')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Activo</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Inactivo</span>
                            @endif
                        </p>
                        <p>
                            <span class="text-gray-600">Estado de Firma:</span>
                            @if($contrato->estado_firma == 'firmado')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Firmado</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pendiente</span>
                            @endif
                        </p>
                        <p><span class="text-gray-600">Fecha de Creación:</span> {{ $contrato->created_at->format('d/m/Y H:i') }}</p>
                        <p><span class="text-gray-600">Última Actualización:</span> {{ $contrato->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold border-b pb-2">Firma</h3>
                    <div class="mt-4">
                        @if($contrato->firma_imagen && Storage::disk('public')->exists($contrato->firma_imagen))
                            <img src="{{ asset('storage/' . $contrato->firma_imagen) }}" alt="Firma del contrato" class="max-w-full h-auto border rounded shadow-sm">
                        @else
                            <p class="text-gray-500">No hay firma disponible.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-2 mt-6">
            <a href="{{ route('contrato.edit', $contrato->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition">
                Editar Contrato
            </a>

            <form action="{{ route('contrato.destroy', $contrato->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este contrato?');">
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
