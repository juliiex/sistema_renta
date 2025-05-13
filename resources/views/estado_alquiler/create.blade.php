<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Estado de Alquiler</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-4">Crear Estado de Alquiler</h2>

        @if ($errors->any())
            <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('estado_alquiler.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="contrato_id" class="block font-semibold">Contrato:</label>
                <select name="contrato_id" id="contrato_id" class="w-full p-2 border rounded">
                    @foreach ($contratos as $contrato)
                        <option value="{{ $contrato->id }}">Contrato {{ $contrato->id }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="usuario_id" class="block font-semibold">Usuario (Administrador):</label>
                <select name="usuario_id" id="usuario_id" class="w-full p-2 border rounded">
                    @foreach ($usuarios as $usuario)
                        <option value="{{ $usuario->id }}">{{ $usuario->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="estado_pago" class="block font-semibold">Estado de Pago:</label>
                <select name="estado_pago" id="estado_pago" class="w-full p-2 border rounded">
                    <option value="Pendiente">Pendiente</option>
                    <option value="Pagado">Pagado</option>
                    <option value="Atrasado">Atrasado</option>
                </select>
            </div>

            <div>
                <label for="fecha_reporte" class="block font-semibold">Fecha de Reporte:</label>
                <input type="date" name="fecha_reporte" id="fecha_reporte" class="w-full p-2 border rounded">
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Guardar</button>
        </form>
    </div>
</body>
</html>

