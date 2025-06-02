<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Solicitud de Alquiler</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Crear Solicitud de Alquiler</h2>
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

        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-4">
            <div class="flex">
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        <strong>Nota:</strong> Solo se pueden crear solicitudes para apartamentos disponibles.
                    </p>
                </div>
            </div>
        </div>

        <form action="{{ route('solicitudes.store') }}" method="POST" class="space-y-4">
            @csrf

            <div class="mb-4">
                <label for="usuario_id" class="block text-sm font-medium text-gray-700 mb-1">Usuario</label>
                <select name="usuario_id" id="usuario_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required {{ auth()->user()->hasRole(['inquilino', 'posible_inquilino']) ? 'disabled' : '' }}>
                    <option value="">Seleccione un usuario</option>
                    @foreach ($usuarios as $usuario)
                        <option value="{{ $usuario->id }}" {{ auth()->id() == $usuario->id ? 'selected' : '' }}>
                            {{ $usuario->nombre }} ({{ $usuario->correo ?? 'Sin correo' }})
                        </option>
                    @endforeach
                </select>
                @if(auth()->user()->hasRole(['inquilino', 'posible_inquilino']))
                    <input type="hidden" name="usuario_id" value="{{ auth()->id() }}">
                    <p class="text-xs text-gray-500 mt-1">Las solicitudes siempre se crean para tu usuario.</p>
                @endif
            </div>

            <div class="mb-4">
                <label for="apartamento_id" class="block text-sm font-medium text-gray-700 mb-1">Apartamento</label>
                <select name="apartamento_id" id="apartamento_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Seleccione un apartamento</option>
                    @foreach ($apartamentos as $apartamento)
                        <option value="{{ $apartamento->id }}">
                            Apartamento {{ $apartamento->numero_apartamento }}
                            @if(isset($apartamento->edificio))
                                - {{ $apartamento->edificio->nombre }}
                            @endif
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1">Solo se muestran apartamentos disponibles.</p>
            </div>

            <div class="flex justify-end pt-4">
                <a href="{{ route('solicitudes.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded mr-2 hover:bg-gray-600 transition">Cancelar</a>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">Guardar Solicitud</button>
            </div>
        </form>
    </div>
</body>
</html>
