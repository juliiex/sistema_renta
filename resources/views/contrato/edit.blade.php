<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Contrato</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-4">Editar Contrato</h2>

        @if (session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('contrato.update', $contrato->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="usuario_id" class="block text-gray-700">Usuario:</label>
                <select name="usuario_id" class="w-full p-2 border rounded">
                    @foreach ($usuarios as $usuario)
                        <option value="{{ $usuario->id }}" {{ $contrato->usuario_id == $usuario->id ? 'selected' : '' }}>
                            {{ $usuario->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="apartamento_id" class="block text-gray-700">Apartamento:</label>
                <select name="apartamento_id" class="w-full p-2 border rounded">
                    @foreach ($apartamentos as $apartamento)
                        <option value="{{ $apartamento->id }}" {{ $contrato->apartamento_id == $apartamento->id ? 'selected' : '' }}>
                            {{ $apartamento->numero_apartamento }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="fecha_inicio" class="block text-gray-700">Fecha de Inicio:</label>
                <input type="date" name="fecha_inicio" value="{{ $contrato->fecha_inicio->format('Y-m-d') }}" class="w-full p-2 border rounded">
            </div>

            <div class="mb-4">
                <label for="fecha_fin" class="block text-gray-700">Fecha de Fin:</label>
                <input type="date" name="fecha_fin" value="{{ $contrato->fecha_fin->format('Y-m-d') }}" class="w-full p-2 border rounded">
            </div>

            <div class="mb-4">
                <label for="firma_digital" class="block text-gray-700">Firma Digital:</label>
                <input type="text" name="firma_digital" value="{{ $contrato->firma_digital }}" class="w-full p-2 border rounded">
            </div>

            <div class="mb-4">
                <label for="estado" class="block text-gray-700">Estado:</label>
                <input type="text" name="estado" value="{{ $contrato->estado }}" class="w-full p-2 border rounded">
            </div>

            <div class="flex justify-end">
                <a href="{{ route('contrato.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Cancelar</a>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Actualizar Contrato</button>
            </div>
        </form>
    </div>
</body>
</html>
