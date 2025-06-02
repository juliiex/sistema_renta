<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitudes de Alquiler</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Solicitudes de Alquiler</h2>
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

        <a href="{{ route('solicitudes.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block hover:bg-blue-600 transition">Nueva Solicitud</a>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white mt-4 border border-gray-200">
                <thead>
                    <tr class="bg-gray-200 text-gray-700">
                        <th class="px-4 py-2 text-left">ID</th>
                        <th class="px-4 py-2 text-left">Usuario</th>
                        <th class="px-4 py-2 text-left">Apartamento</th>
                        <th class="px-4 py-2 text-left">Fecha Solicitud</th>
                        <th class="px-4 py-2 text-left">Estado</th>
                        <th class="px-4 py-2 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($solicitudes as $solicitud)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $solicitud->id }}</td>
                        <td class="px-4 py-2">{{ $solicitud->usuario->nombre ?? 'N/A' }}</td>
                        <td class="px-4 py-2">{{ $solicitud->apartamento->numero_apartamento ?? 'N/A' }}</td>
                        <td class="px-4 py-2">{{ $solicitud->fecha_solicitud ? $solicitud->fecha_solicitud->format('d/m/Y') : 'N/A' }}</td>
                        <td class="px-4 py-2">
                            @if($solicitud->estado_solicitud == 'pendiente')
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Pendiente</span>
                            @elseif($solicitud->estado_solicitud == 'aprobada')
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Aprobada</span>
                            @elseif($solicitud->estado_solicitud == 'rechazada')
                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Rechazada</span>
                            @else
                                {{ $solicitud->estado_solicitud }}
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route('solicitudes.show', $solicitud->id) }}" class="text-blue-500 hover:underline">Ver</a>

                                @if(auth()->user()->hasRole(['admin', 'propietario']) ||
                                    (auth()->id() == $solicitud->usuario_id && $solicitud->estado_solicitud == 'pendiente'))
                                    <a href="{{ route('solicitudes.edit', $solicitud->id) }}" class="text-yellow-500 hover:underline">Editar</a>
                                    <form action="{{ route('solicitudes.destroy', $solicitud->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Está seguro que desea eliminar esta solicitud?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:underline">Eliminar</button>
                                    </form>
                                @endif

                                {{-- Botones de aprobar/rechazar eliminados porque no usas esas rutas --}}
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-2 text-center text-gray-500">No hay solicitudes de alquiler registradas</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
