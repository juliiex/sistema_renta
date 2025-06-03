<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluaciones Eliminadas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Evaluaciones Eliminadas</h2>
            <a href="{{ route('evaluaciones.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">Volver a la lista</a>
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

        @if ($evaluaciones->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white mt-4 border border-gray-200">
                    <thead>
                        <tr class="bg-gray-200 text-gray-700">
                            <th class="px-4 py-2 text-left">ID</th>
                            <th class="px-4 py-2 text-left">Usuario</th>
                            <th class="px-4 py-2 text-left">Apartamento</th>
                            <th class="px-4 py-2 text-left">Calificación</th>
                            <th class="px-4 py-2 text-left">Fecha de Evaluación</th>
                            <th class="px-4 py-2 text-left">Eliminado el</th>
                            <th class="px-4 py-2 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($evaluaciones as $evaluacion)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $evaluacion->id }}</td>
                            <td class="px-4 py-2">{{ $evaluacion->usuario->nombre ?? 'N/A' }}</td>
                            <td class="px-4 py-2">
                                @if($evaluacion->apartamento)
                                    Apto. {{ $evaluacion->apartamento->numero_apartamento }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                <div class="flex">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $evaluacion->calificacion)
                                            <i class="fas fa-star text-yellow-400"></i>
                                        @else
                                            <i class="far fa-star text-gray-300"></i>
                                        @endif
                                    @endfor
                                </div>
                            </td>
                            <td class="px-4 py-2">{{ $evaluacion->fecha_evaluacion->format('d/m/Y') }}</td>
                            <td class="px-4 py-2">{{ $evaluacion->deleted_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-2">
                                <div class="flex justify-center space-x-2">
                                    @if(auth()->user()->hasRole(['admin', 'propietario']) || auth()->id() === $evaluacion->usuario_id)
                                        <form action="{{ route('evaluaciones.restore', $evaluacion->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-green-500 hover:underline">Restaurar</button>
                                        </form>
                                        <form action="{{ route('evaluaciones.force-delete', $evaluacion->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar permanentemente esta evaluación? Esta acción no se puede deshacer.');">
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
                            No hay evaluaciones eliminadas.
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</body>
</html>
