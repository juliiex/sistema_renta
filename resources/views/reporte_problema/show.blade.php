<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Reporte de Problema</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">

    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-4">Detalles del Reporte de Problema</h2>

        <div class="mb-4">
            <strong>Usuario:</strong>
            <p>{{ $reporteProblema->usuario->nombre ?? 'No asignado' }}</p>
        </div>

        <div class="mb-4">
            <strong>Apartamento:</strong>
            <p>{{ $reporteProblema->apartamento->numero_apartamento ?? 'No asignado' }}</p>
        </div>

        <div class="mb-4">
            <strong>Descripción:</strong>
            <p>{{ $reporteProblema->descripcion ?? 'No proporcionada' }}</p>
        </div>

        <div class="mb-4">
            <strong>Tipo:</strong>
            <p>{{ $reporteProblema->tipo ?? 'No especificado' }}</p>
        </div>

        <div class="mb-4">
            <strong>Estado:</strong>
            <p>{{ $reporteProblema->estado ?? 'No asignado' }}</p>
        </div>

        <div class="mb-4">
            <strong>Fecha de Reporte:</strong>
            <p>{{ $reporteProblema->fecha_reporte ? $reporteProblema->fecha_reporte->format('d/m/Y H:i') : 'No asignada' }}</p>
        </div>

        <div class="flex justify-end space-x-4">
            <a href="{{ route('reporte_problema.edit', $reporteProblema->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded">Editar</a>
            <form action="{{ route('reporte_problema.destroy', $reporteProblema->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este reporte de problema?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Eliminar</button>
            </form>
            <a href="{{ route('reporte_problema.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Volver a la lista</a>
        </div>
    </div>

</body>
</html>

