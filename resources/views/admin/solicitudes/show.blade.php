<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de Solicitud de Alquiler</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Detalles de Solicitud de Alquiler</h2>
            <a href="{{ route('solicitudes.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">Volver a la lista</a>
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

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="text-lg font-semibold mb-3 text-gray-700">Información de la Solicitud</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">ID:</strong>
                        <p>{{ $solicitud->id }}</p>
                    </div>

                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">Usuario:</strong>
                        <p>{{ $solicitud->usuario->nombre ?? 'No disponible' }}</p>
                    </div>

                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">Correo:</strong>
                        <p>{{ $solicitud->usuario->correo ?? 'No disponible' }}</p>
                    </div>

                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">Fecha de Solicitud:</strong>
                        <p>{{ $solicitud->fecha_solicitud ? $solicitud->fecha_solicitud->format('d/m/Y') : 'No disponible' }}</p>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-semibold mb-3 text-gray-700">Información del Apartamento</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">Apartamento:</strong>
                        <p>{{ $solicitud->apartamento->numero_apartamento ?? 'No disponible' }}</p>
                    </div>

                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">Piso:</strong>
                        <p>{{ $solicitud->apartamento->piso ?? 'No disponible' }}</p>
                    </div>

                    @if(isset($solicitud->apartamento->edificio))
                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">Edificio:</strong>
                        <p>{{ $solicitud->apartamento->edificio->nombre ?? 'No disponible' }}</p>
                    </div>
                    @endif

                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">Estado de la Solicitud:</strong>
                        <div class="mt-1">
                            @if($solicitud->estado_solicitud == 'pendiente')
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full">Pendiente</span>
                            @elseif($solicitud->estado_solicitud == 'aprobada')
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full">Aprobada</span>
                            @elseif($solicitud->estado_solicitud == 'rechazada')
                                <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full">Rechazada</span>
                            @else
                                {{ $solicitud->estado_solicitud }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(auth()->user()->hasRole(['admin', 'propietario']) && $solicitud->estado_solicitud == 'pendiente')
        <div class="flex space-x-4 mb-6">
            <form action="{{ route('solicitudes.aprobar', $solicitud->id) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition">
                    Aprobar Solicitud
                </button>
            </form>

            <form action="{{ route('solicitudes.rechazar', $solicitud->id) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">
                    Rechazar Solicitud
                </button>
            </form>
        </div>
        @endif

        <div class="flex space-x-4 mt-6">
            @if(auth()->user()->hasRole(['admin', 'propietario']) ||
               (auth()->id() == $solicitud->usuario_id && $solicitud->estado_solicitud == 'pendiente'))
                <a href="{{ route('solicitudes.edit', $solicitud->id) }}"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded transition">
                    Editar
                </a>

                <form action="{{ route('solicitudes.destroy', $solicitud->id) }}" method="POST"
                    onsubmit="return confirm('¿Está seguro que desea eliminar esta solicitud?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded transition">
                        Eliminar
                    </button>
                </form>
            @endif

            <a href="{{ route('solicitudes.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded transition">
                Volver
            </a>
        </div>
    </div>
</body>
</html>
