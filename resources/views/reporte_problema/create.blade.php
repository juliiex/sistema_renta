<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Reporte de Problema</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-4">Crear Reporte de Problema</h2>

        @if ($errors->any())
            <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('reporte_problema.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="usuario_id" class="block font-semibold">Usuario:</label>
                <select name="usuario_id" id="usuario_id" class="w-full p-2 border rounded" required>
                    <option value="" disabled selected>Seleccionar Usuario</option>
                    @foreach ($usuarios as $usuario)
                        <option value="{{ $usuario->id }}">{{ $usuario->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="apartamento_id" class="block font-semibold">Apartamento:</label>
                <select name="apartamento_id" id="apartamento_id" class="w-full p-2 border rounded" required>
                    <option value="" disabled selected>Seleccionar Apartamento</option>
                    @foreach ($apartamentos as $apartamento)
                        <option value="{{ $apartamento->id }}">Apartamento {{ $apartamento->numero_apartamento }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="descripcion" class="block font-semibold">Descripción:</label>
                <textarea name="descripcion" id="descripcion" class="w-full p-2 border rounded" rows="4" required></textarea>
            </div>

            <div>
                <label for="estado" class="block font-semibold">Estado:</label>
                <select name="estado" id="estado" class="w-full p-2 border rounded" required>
                    <option value="pendiente">Pendiente</option>
                    <option value="atendido">Atendido</option>
                    <option value="cerrado">Cerrado</option>
                </select>
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Guardar</button>
        </form>
    </div>
</body>
</html>

