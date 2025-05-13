<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Recordatorio de Pago</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-4">Editar Recordatorio de Pago</h2>

        @if ($errors->any())
            <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('recordatorio_pago.update', $recordatorio->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="usuario_id" class="block font-semibold">Usuario:</label>
                <select name="usuario_id" id="usuario_id" class="w-full p-2 border rounded" required>
                    <option value="" disabled>Seleccionar Usuario</option>
                    @foreach ($usuarios as $usuario)
                        <option value="{{ $usuario->id }}" {{ $usuario->id == $recordatorio->usuario_id ? 'selected' : '' }}>{{ $usuario->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="apartamento_id" class="block font-semibold">Apartamento:</label>
                <select name="apartamento_id" id="apartamento_id" class="w-full p-2 border rounded" required>
                    <option value="" disabled>Seleccionar Apartamento</option>
                    @foreach ($apartamentos as $apartamento)
                        <option value="{{ $apartamento->id }}" {{ $apartamento->id == $recordatorio->apartamento_id ? 'selected' : '' }}>Apartamento {{ $apartamento->numero_apartamento }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="metodo" class="block font-semibold">Método de Envío:</label>
                <select name="metodo" id="metodo" class="w-full p-2 border rounded" required>
                    <option value="" disabled>Seleccionar Método</option>
                    <option value="Correo" {{ $recordatorio->metodo == 'Correo' ? 'selected' : '' }}>Correo</option>
                    <option value="SMS" {{ $recordatorio->metodo == 'SMS' ? 'selected' : '' }}>SMS</option>
                    <option value="App" {{ $recordatorio->metodo == 'App' ? 'selected' : '' }}>Notificación de la App</option>
                </select>
            </div>

            <div>
                <label for="fecha_envio" class="block font-semibold">Fecha de Envío:</label>
                <input type="date" name="fecha_envio" id="fecha_envio" value="{{ old('fecha_envio', $recordatorio->fecha_envio) }}" class="w-full p-2 border rounded" required>
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Guardar Cambios</button>
        </form>
    </div>
</body>
</html>

