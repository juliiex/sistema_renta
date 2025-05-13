<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Evaluaciones</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-5xl mx-auto bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-4">Lista de Evaluaciones</h2>

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

        <a href="{{ route('evaluaciones.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Crear Evaluación</a>

        <table class="min-w-full bg-white mt-4 border border-gray-200">
            <thead>
                <tr class="bg-gray-200 text-left">
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Usuario</th>
                    <th class="px-4 py-2">Calificación</th>
                    <th class="px-4 py-2">Fecha de Evaluación</th>
                    <th class="px-4 py-2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($evaluaciones as $evaluacion)
                <tr class="border-b">
                    <td class="px-4 py-2">{{ $evaluacion->id }}</td>
                    <td class="px-4 py-2">{{ $evaluacion->usuario->nombre ?? 'N/A' }}</td>
                    <td class="px-4 py-2">{{ $evaluacion->calificacion }}</td>
                    <td class="px-4 py-2">{{ $evaluacion->fecha_evaluacion->format('d/m/Y') }}</td>
                    <td class="px-4 py-2 flex space-x-2">
                        <a href="{{ route('evaluaciones.show', $evaluacion->id) }}" class="text-blue-500">Ver</a>
                        <a href="{{ route('evaluaciones.edit', $evaluacion->id) }}" class="text-yellow-500">Editar</a>
                        <form action="{{ route('evaluaciones.destroy', $evaluacion->id) }}" method="POST" onsubmit="return confirm('¿Eliminar esta evaluación?');">
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

