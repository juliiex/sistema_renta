<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Contrato</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-4">Detalles del Contrato</h2>

        <div class="mb-4">
            <strong>Usuario:</strong>
            <p>{{ $contrato->usuario->nombre }}</p>
        </div>

        <div class="mb-4">
            <strong>Apartamento:</strong>
            <p>{{ $contrato->apartamento->numero_apartamento }}</p>
        </div>

        <div class="mb-4">
            <strong>Fecha de Inicio:</strong>
            <p>{{ $contrato->fecha_inicio->format('d-m-Y') }}</p>
        </div>

        <div class="mb-4">
            <strong>Fecha de Fin:</strong>
            <p>{{ $contrato->fecha_fin->format('d-m-Y') }}</p>
        </div>

        <div class="mb-4">
            <strong>Firma Digital:</strong>
            <p>{{ $contrato->firma_digital }}</p>
        </div>

        <div class="mb-4">
            <strong>Estado:</strong>
            <p>{{ ucfirst($contrato->estado) }}</p>
        </div>

        <div class="flex justify-end space-x-4">
            <a href="{{ route('contrato.edit', $contrato->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded">Editar</a>
            <form action="{{ route('contrato.destroy', $contrato->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este contrato?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Eliminar</button>
            </form>
            <a href="{{ route('contrato.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Volver a la lista</a>
        </div>
    </div>
</body>
</html>
