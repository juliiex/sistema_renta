<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de la Evaluación</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-4">Detalles de la Evaluación</h2>

        <div class="mb-4">
            <strong>Usuario:</strong>
            <p>{{ $evaluacion->usuario->nombre }}</p>
        </div>

        <div class="mb-4">
            <strong>Apartamento:</strong>
            <p>{{ $evaluacion->apartamento->numero_apartamento }} - Piso {{ $evaluacion->apartamento->piso }}</p>
        </div>

        <div class="mb-4">
            <strong>Calificación:</strong>
            <p>{{ $evaluacion->calificacion }}</p>
        </div>

        <div class="mb-4">
            <strong>Comentario:</strong>
            <p>{{ $evaluacion->comentario }}</p>
        </div>

        <div class="mb-4">
            <strong>Fecha de Evaluación:</strong>
            <p>{{ $evaluacion->fecha_evaluacion->format('d/m/Y') }}</p>
        </div>

        <div class="flex justify-end space-x-4">
            <a href="{{ route('evaluaciones.edit', $evaluacion->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded">Editar</a>
            <form action="{{ route('evaluaciones.destroy', $evaluacion->id) }}" method="POST" onsubmit="return confirm('¿Eliminar esta evaluación?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Eliminar</button>
            </form>
            <a href="{{ route('evaluaciones.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Volver a la lista</a>
        </div>
    </div>
</body>
</html>
