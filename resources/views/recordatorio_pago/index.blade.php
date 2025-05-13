<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Reportes de Problemas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-5xl mx-auto bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-4">Lista de Reportes de Problemas</h2>

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

        <a href="{{ route('reporte_problema.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Crear Reporte de Problema</a>

        <table class="min-w-full bg-white mt-4 border border-gray-200">
            <thead>
                <tr class="bg-gray-200 text-left">
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Apartamento</th>
                    <th class="px-4 py-2">Usuario</th>
                    <th class="px-4 py-2">Descripción</th>
                    <th class="px-4 py-2">Estado</th>
                    <th class="px-4 py-2">Fecha de Reporte</th>
                    <th class="px-4 py-2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reportes as $reporte)
                <tr class="border-b">
                    <td class="px-4 py-2">{{ $reporte->id }}</td>
                    <td class="px-4 py-2">{{ $reporte->apartamento->nombre ?? 'N/A' }}</td>
                    <td class="px-4 py-2">{{ $reporte->usuario->nombre ?? 'N/A' }}</td>
                    <td class="px-4 py-2">{{ $reporte->descripcion }}</td>
                    <td class="px-4 py-2">{{ ucfirst($reporte->estado) }}</td>
                    <td class="px-4 py-2">{{ $reporte->fecha_reporte->format('d/m/Y') }}</td>
                    <td class="px-4 py-2 flex space-x-2">
                        <a href="{{ route('reporte_problema.show', $reporte->id) }}" class="text-blue-500">Ver</a>
                        <a href="{{ route('reporte_problema.edit', $reporte->id) }}" class="text-yellow-500">Editar</a>
                        <form action="{{ route('reporte_problema.destroy', $reporte->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este reporte de problema?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>

