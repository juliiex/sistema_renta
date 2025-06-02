<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Solicitud de Alquiler</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Editar Solicitud de Alquiler</h2>
            <a href="{{ route('solicitudes.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">Volver a la lista</a>
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

        <form action="{{ route('solicitudes.update', $solicitud->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="usuario_id" class="block text-sm font-medium text-gray-700 mb-1">Usuario</label>
                <select name="usuario_id" id="usuario_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required {{ !auth()->user()->hasRole(['admin', 'propietario']) ? 'disabled' : '' }}>
                    <option value="">Seleccione un usuario</option>
                    @foreach ($usuarios as $usuario)
                        <option value="{{ $usuario->id }}" {{ old('usuario_id', $solicitud->usuario_id) == $usuario->id ? 'selected' : '' }}>
                            {{ $usuario->nombre }} ({{ $usuario->correo ?? 'Sin correo' }})
                        </option>
                    @endforeach
                </select>
                @if(!auth()->user()->hasRole(['admin', 'propietario']))
                    <input type="hidden" name="usuario_id" value="{{ $solicitud->usuario_id }}">
                    <p class="text-xs text-gray-500 mt-1">No puedes cambiar el usuario de la solicitud.</p>
                @endif
            </div>

            <div class="mb-4">
                <label for="apartamento_id" class="block text-sm font-medium text-gray-700 mb-1">Apartamento</label>
                <select name="apartamento_id" id="apartamento_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required {{ !auth()->user()->hasRole(['admin', 'propietario']) && $solicitud->estado_solicitud != 'pendiente' ? 'disabled' : '' }}>
                    <option value="">Seleccione un apartamento</option>
                    @foreach ($apartamentos as $apartamento)
                        <option value="{{ $apartamento->id }}" {{ old('apartamento_id', $solicitud->apartamento_id) == $apartamento->id ? 'selected' : '' }}>
                            Apartamento {{ $apartamento->numero_apartamento }}
                            @if(isset($apartamento->edificio))
                                - {{ $apartamento->edificio->nombre }}
                            @endif
                        </option>
                    @endforeach
                </select>
                @if(!auth()->user()->hasRole(['admin', 'propietario']) && $solicitud->estado_solicitud != 'pendiente')
                    <input type="hidden" name="apartamento_id" value="{{ $solicitud->apartamento_id }}">
                    <p class="text-xs text-gray-500 mt-1">No puedes cambiar el apartamento de una solicitud ya procesada.</p>
                @endif
            </div>

            @if(auth()->user()->hasRole(['admin', 'propietario']))
            <div class="mb-4">
                <label for="estado_solicitud" class="block text-sm font-medium text-gray-700 mb-1">Estado de Solicitud</label>
                <select name="estado_solicitud" id="estado_solicitud" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="pendiente" {{ old('estado_solicitud', $solicitud->estado_solicitud) == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="aprobada" {{ old('estado_solicitud', $solicitud->estado_solicitud) == 'aprobada' ? 'selected' : '' }}>Aprobada</option>
                    <option value="rechazada" {{ old('estado_solicitud', $solicitud->estado_solicitud) == 'rechazada' ? 'selected' : '' }}>Rechazada</option>
                </select>
                <p class="text-xs text-gray-500 mt-1">Al aprobar una solicitud, el apartamento se marcará como ocupado.</p>
            </div>
            @else
                <input type="hidden" name="estado_solicitud" value="pendiente">
            @endif

            <div class="flex justify-end pt-4">
                <a href="{{ route('solicitudes.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded mr-2 hover:bg-gray-600 transition">Cancelar</a>
                <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition">Actualizar Solicitud</button>
            </div>
        </form>
    </div>
</body>
</html>
