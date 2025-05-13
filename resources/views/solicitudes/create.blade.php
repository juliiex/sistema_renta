<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Solicitud</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-4">Crear Solicitud de Alquiler</h2>

        @if ($errors->any())
            <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('solicitudes.store') }}" method="POST" class="space-y-4">
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
                <label for="estado_solicitud" class="block font-semibold">Estado de Solicitud:</label>
                <select name="estado_solicitud" id="estado_solicitud" class="w-full p-2 border rounded" required>
                    <option value="pendiente" selected>Pendiente</option>
                    <option value="aprobada">Aprobada</option>
                    <option value="rechazada">Rechazada</option>
                </select>
            </div>

            <!-- Campo de fecha de solicitud, se llena automáticamente con la fecha actual -->
            <div>
                <label for="fecha_solicitud" class="block font-semibold">Fecha de Solicitud:</label>
                <input type="date" name="fecha_solicitud" id="fecha_solicitud" class="w-full p-2 border rounded" value="{{ now()->format('Y-m-d') }}" readonly required>
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Guardar</button>
        </form>
    </div>
</body>
</html>

