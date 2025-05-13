<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitudes de Alquiler</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-5xl mx-auto bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-4">Solicitudes de Alquiler</h2>

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

        <a href="{{ route('solicitudes.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Nueva Solicitud</a>

        <table class="min-w-full bg-white mt-4 border border-gray-200">
            <thead>
                <tr class="bg-gray-200 text-left">
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Usuario</th>
                    <th class="px-4 py-2">Apartamento</th>
                    <th class="px-4 py-2">Fecha Solicitud</th>
                    <th class="px-4 py-2">Estado</th>
                    <th class="px-4 py-2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($solicitudes as $solicitud)
                <tr class="border-b">
                    <td class="px-4 py-2">{{ $solicitud->id }}</td>
                    <td class="px-4 py-2">{{ $solicitud->usuario->nombre ?? 'N/A' }}</td>
                    <td class="px-4 py-2">{{ $solicitud->apartamento->numero_apartamento ?? 'N/A' }}</td>
                    <td class="px-4 py-2">{{ $solicitud->fecha_solicitud->format('d/m/Y') }}</td>
                    <td class="px-4 py-2">{{ $solicitud->estado_solicitud }}</td>
                    <td class="px-4 py-2 flex space-x-2">
                        <a href="{{ route('solicitudes.show', $solicitud->id) }}" class="text-blue-500">Ver</a>
                        <a href="{{ route('solicitudes.edit', $solicitud->id) }}" class="text-yellow-500">Editar</a>
                        <form action="{{ route('solicitudes.destroy', $solicitud->id) }}" method="POST" onsubmit="return confirm('¿Eliminar esta solicitud?');">
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
