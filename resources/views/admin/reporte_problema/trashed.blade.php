<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes de Problemas Eliminados</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Reportes de Problemas Eliminados</h2>
            <a href="{{ route('reporte_problema.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">Volver a la lista</a>
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

        @if($reportes->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white mt-4 border border-gray-200">
                    <thead>
                        <tr class="bg-gray-200 text-gray-700">
                            <th class="px-4 py-2 text-left">ID</th>
                            <th class="px-4 py-2 text-left">Apartamento</th>
                            <th class="px-4 py-2 text-left">Usuario</th>
                            <th class="px-4 py-2 text-left">Tipo</th>
                            <th class="px-4 py-2 text-left">Estado</th>
                            <th class="px-4 py-2 text-left">Fecha de Reporte</th>
                            <th class="px-4 py-2 text-left">Eliminado el</th>
                            <th class="px-4 py-2 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reportes as $reporte)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $reporte->id }}</td>
                            <td class="px-4 py-2">{{ $reporte->apartamento ? $reporte->apartamento->numero_apartamento : 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $reporte->usuario ? $reporte->usuario->nombre : 'N/A' }}</td>
                            <td class="px-4 py-2">
                                @if($reporte->tipo)
                                    <span class="px-2 py-1 {{
                                        $reporte->tipo == 'plomeria' ? 'bg-blue-100 text-blue-800' :
                                        ($reporte->tipo == 'electricidad' ? 'bg-yellow-100 text-yellow-800' :
                                        ($reporte->tipo == 'estructura' ? 'bg-orange-100 text-orange-800' :
                                        ($reporte->tipo == 'seguridad' ? 'bg-red-100 text-red-800' :
                                        ($reporte->tipo == 'limpieza' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800')))) }} rounded-full text-xs">
                                        {{ ucfirst($reporte->tipo) }}
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                @if($reporte->estado == 'pendiente')
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Pendiente</span>
                                @elseif($reporte->estado == 'atendido')
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Atendido</span>
                                @elseif($reporte->estado == 'cerrado')
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Cerrado</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                {{ $reporte->fecha_reporte ? $reporte->fecha_reporte->format('d/m/Y') : 'N/A' }}
                            </td>
                            <td class="px-4 py-2">{{ $reporte->deleted_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-2">
                                <div class="flex justify-center space-x-2">
                                    @if(auth()->user()->hasRole(['admin', 'propietario']) ||
                                        (auth()->id() === $reporte->usuario_id && $reporte->estado === 'pendiente'))
                                        <form action="{{ route('reporte_problema.restore', $reporte->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-green-500 hover:underline">Restaurar</button>
                                        </form>
                                        <form action="{{ route('reporte_problema.force-delete', $reporte->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar permanentemente este reporte de problema? Esta acción no se puede deshacer.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:underline">Eliminar permanentemente</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                <div class="flex">
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            No hay reportes de problemas eliminados.
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</body>
</html>
