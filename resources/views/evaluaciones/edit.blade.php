<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Evaluación</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-4">Editar Evaluación</h2>

        @if ($errors->any())
            <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('evaluaciones.update', $evaluacion->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="usuario_id" class="block font-semibold">Usuario:</label>
                <select name="usuario_id" id="usuario_id" class="w-full p-2 border rounded">
                    <option value="">Elegir...</option>
                    @foreach ($usuarios as $usuario)
                        <option value="{{ $usuario->id }}" {{ $evaluacion->usuario_id == $usuario->id ? 'selected' : '' }}>{{ $usuario->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="apartamento_id" class="block font-semibold">Apartamento:</label>
                <select name="apartamento_id" id="apartamento_id" class="w-full p-2 border rounded">
                    <option value="">Elegir...</option>
                    @foreach ($apartamentos as $apartamento)
                        <option value="{{ $apartamento->id }}" {{ $evaluacion->apartamento_id == $apartamento->id ? 'selected' : '' }}>Apartamento {{ $apartamento->numero_apartamento }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="calificacion" class="block font-semibold">Calificación:</label>
                <input type="number" name="calificacion" id="calificacion" min="1" max="5" value="{{ $evaluacion->calificacion }}" class="w-full p-2 border rounded">
            </div>

            <div>
                <label for="comentario" class="block font-semibold">Comentario:</label>
                <textarea name="comentario" id="comentario" rows="4" class="w-full p-2 border rounded">{{ $evaluacion->comentario }}</textarea>
            </div>

            <div>
                <label for="fecha_evaluacion" class="block font-semibold">Fecha de Evaluación:</label>
                <input type="date" name="fecha_evaluacion" id="fecha_evaluacion" value="{{ $evaluacion->fecha_evaluacion->format('Y-m-d') }}" class="w-full p-2 border rounded">
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Actualizar</button>
        </form>
    </div>
</body>
</html>
