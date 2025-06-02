<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Recordatorio de Pago</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Editar Recordatorio de Pago</h2>
            <a href="{{ route('recordatorio_pago.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">Volver a la lista</a>
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

        <form action="{{ route('recordatorio_pago.update', $recordatorio->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="usuario_id" class="block text-sm font-medium text-gray-700 mb-1">Usuario (Inquilino)</label>
                <select name="usuario_id" id="usuario_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Seleccione un inquilino</option>
                    @foreach ($usuarios as $usuario)
                        <option value="{{ $usuario->id }}" {{ old('usuario_id', $recordatorio->usuario_id) == $usuario->id ? 'selected' : '' }}>
                            {{ $usuario->nombre }} ({{ $usuario->correo }})
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1">Solo se muestran inquilinos con contratos activos.</p>
            </div>

            <div class="mb-4">
                <label for="metodo" class="block text-sm font-medium text-gray-700 mb-1">Método de Envío</label>
                <select name="metodo" id="metodo" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Seleccione un método de envío</option>
                    @foreach ($metodos as $key => $value)
                        <option value="{{ $key }}" {{ old('metodo', $recordatorio->metodo) == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="fecha_envio" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Envío</label>
                <input type="date" name="fecha_envio" id="fecha_envio"
                    value="{{ old('fecha_envio', $recordatorio->fecha_envio ? $recordatorio->fecha_envio->format('Y-m-d') : '') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <p class="text-xs text-gray-500 mt-1">Fecha en la que se enviará el recordatorio.</p>
            </div>

            <div class="flex justify-end pt-4">
                <a href="{{ route('recordatorio_pago.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded mr-2 hover:bg-gray-600 transition">Cancelar</a>
                <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition">Actualizar Recordatorio</button>
            </div>
        </form>
    </div>
</body>
</html>
