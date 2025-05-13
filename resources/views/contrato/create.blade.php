<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Contrato</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-4">Crear Contrato</h2>

        @if ($errors->any())
            <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('contrato.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="usuario_id" class="block text-sm font-medium">Usuario</label>
                <select name="usuario_id" id="usuario_id" class="w-full p-2 border rounded">
                    <option value="">Seleccione un usuario</option>
                    @foreach ($usuarios as $usuario)
                        <option value="{{ $usuario->id }}">{{ $usuario->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="apartamento_id" class="block text-sm font-medium">Apartamento</label>
                <select name="apartamento_id" id="apartamento_id" class="w-full p-2 border rounded">
                    <option value="">Seleccione un apartamento</option>
                    @foreach ($apartamentos as $apartamento)
                        <option value="{{ $apartamento->id }}">{{ $apartamento->numero_apartamento }} - Piso {{ $apartamento->piso }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="fecha_inicio" class="block text-sm font-medium">Fecha de Inicio</label>
                <input type="date" name="fecha_inicio" id="fecha_inicio" class="w-full p-2 border rounded" required>
            </div>

            <div class="mb-4">
                <label for="fecha_fin" class="block text-sm font-medium">Fecha de Fin</label>
                <input type="date" name="fecha_fin" id="fecha_fin" class="w-full p-2 border rounded" required>
            </div>

            <div class="mb-4">
                <label for="firma_digital" class="block text-sm font-medium">Firma Digital</label>
                <input type="text" name="firma_digital" id="firma_digital" class="w-full p-2 border rounded" placeholder="Ingrese la firma digital" required>
            </div>

            <div class="mb-4">
                <label for="estado" class="block text-sm font-medium">Estado</label>
                <select name="estado" id="estado" class="w-full p-2 border rounded">
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                </select>
            </div>

            <div class="flex justify-end">
                <a href="{{ route('contrato.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Cancelar</a>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Guardar</button>
            </div>
        </form>
    </div>
</body>
</html>
