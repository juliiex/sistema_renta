<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Estado de Alquiler</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Crear Estado de Alquiler</h2>
            <a href="{{ route('estado_alquiler.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">Volver a la lista</a>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('estado_alquiler.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="contrato_id" class="block text-sm font-medium text-gray-700 mb-1">Contrato:</label>
                <select name="contrato_id" id="contrato_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Seleccione un contrato</option>
                    @foreach ($contratos as $contrato)
                        <option value="{{ $contrato->id }}">
                            Contrato #{{ $contrato->id }} -
                            Apartamento: {{ $contrato->apartamento->numero_apartamento ?? 'N/A' }} -
                            Inquilino: {{ $contrato->usuario->nombre ?? 'N/A' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="usuario_id" class="block text-sm font-medium text-gray-700 mb-1">Usuario que reporta:</label>
                <select name="usuario_id" id="usuario_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Seleccione un usuario</option>
                    @foreach ($usuarios as $usuario)
                        <option value="{{ $usuario->id }}" {{ auth()->id() == $usuario->id ? 'selected' : '' }}>
                            {{ $usuario->nombre }} ({{ $usuario->correo }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="estado_pago" class="block text-sm font-medium text-gray-700 mb-1">Estado de Pago:</label>
                <select name="estado_pago" id="estado_pago" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Seleccione un estado</option>
                    <option value="Pendiente">Pendiente</option>
                    <option value="Pagado">Pagado</option>
                    <option value="Atrasado">Atrasado</option>
                </select>
            </div>

            <div>
                <label for="fecha_reporte" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Reporte:</label>
                <input type="date" name="fecha_reporte" id="fecha_reporte" value="{{ date('Y-m-d') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="flex justify-end pt-4">
                <a href="{{ route('estado_alquiler.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded mr-2 hover:bg-gray-600 transition">Cancelar</a>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">Guardar Estado</button>
            </div>
        </form>
    </div>
</body>
</html>
