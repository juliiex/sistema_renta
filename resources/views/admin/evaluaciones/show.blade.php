<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de la Evaluación</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Detalles de la Evaluación</h2>
            <div class="flex space-x-2">
                <a href="{{ route('evaluaciones.trashed') }}" class="bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600 transition">Ver Eliminadas</a>
                <a href="{{ route('evaluaciones.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">Volver a la lista</a>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="text-lg font-semibold mb-3 text-gray-700">Información del Apartamento</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">Apartamento:</strong>
                        <p>
                            @if($evaluacion->apartamento)
                                Apartamento {{ $evaluacion->apartamento->numero_apartamento }} -
                                Piso {{ $evaluacion->apartamento->piso }}
                            @else
                                No disponible
                            @endif
                        </p>
                    </div>

                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">Edificio:</strong>
                        <p>{{ $evaluacion->apartamento->edificio->nombre ?? 'No disponible' }}</p>
                    </div>

                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">Dirección:</strong>
                        <p>{{ $evaluacion->apartamento->edificio->direccion ?? 'No disponible' }}</p>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-semibold mb-3 text-gray-700">Detalles de la Evaluación</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">Evaluado por:</strong>
                        <p>{{ $evaluacion->usuario->nombre ?? 'Usuario no disponible' }}</p>
                    </div>

                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">Calificación:</strong>
                        <div class="flex mt-1">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $evaluacion->calificacion)
                                    <i class="fas fa-star text-yellow-400"></i>
                                @else
                                    <i class="far fa-star text-gray-300"></i>
                                @endif
                            @endfor
                            <span class="ml-2">({{ $evaluacion->calificacion }}/5)</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">Fecha de Evaluación:</strong>
                        <p>{{ $evaluacion->fecha_evaluacion->format('d/m/Y') }}</p>
                    </div>

                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">Fecha de Registro:</strong>
                        <p>
                            {{ $evaluacion->fecha_evaluacion ? $evaluacion->fecha_evaluacion->format('d/m/Y') : 'Sin fecha' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-3 text-gray-700">Comentarios</h3>
            <div class="bg-gray-50 p-4 rounded-lg">
                @if($evaluacion->comentario)
                    <p class="italic">{{ $evaluacion->comentario }}</p>
                @else
                    <p class="text-gray-500">No se proporcionaron comentarios.</p>
                @endif
            </div>
        </div>

        @if(auth()->user()->hasRole(['admin', 'propietario']) || auth()->id() === $evaluacion->usuario_id)
        <div class="flex justify-end space-x-2">
            <a href="{{ route('evaluaciones.edit', $evaluacion->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition">Editar</a>
            <form action="{{ route('evaluaciones.destroy', $evaluacion->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Está seguro que desea eliminar esta evaluación?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">Eliminar</button>
            </form>
        </div>
        @endif
    </div>
</body>
</html>
