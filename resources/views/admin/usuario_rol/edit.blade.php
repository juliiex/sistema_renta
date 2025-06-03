<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Asignación de Rol</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Editar Asignación de Rol</h2>
            <a href="{{ route('usuario_rol.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">Volver a la lista</a>
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

        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
            <div class="flex">
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        <strong>Advertencia:</strong> Modificar esta asignación de rol puede afectar los permisos del usuario en el sistema.
                    </p>
                </div>
            </div>
        </div>

        <form action="{{ route('usuario_rol.update', $usuarioRol->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="usuario_id" class="block text-sm font-medium text-gray-700 mb-1">Usuario</label>
                <select name="usuario_id" id="usuario_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @foreach ($usuarios as $usuario)
                        <option value="{{ $usuario->id }}" {{ $usuarioRol->usuario_id == $usuario->id ? 'selected' : '' }}>
                            {{ $usuario->nombre }} ({{ $usuario->correo ?? 'Sin correo' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="rol_id" class="block text-sm font-medium text-gray-700 mb-1">Rol</label>
                <select name="rol_id" id="rol_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @foreach ($roles as $rol)
                        <option value="{{ $rol->id }}" {{ $usuarioRol->rol_id == $rol->id ? 'selected' : '' }}>
                            {{ $rol->nombre }}
                            @if(in_array($rol->nombre, ['admin', 'propietario', 'inquilino', 'posible inquilino']))
                                (Sistema)
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-end pt-4">
                <a href="{{ route('usuario_rol.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded mr-2 hover:bg-gray-600 transition">Cancelar</a>
                <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition">Actualizar Asignación</button>
            </div>
        </form>
    </div>
</body>
</html>
