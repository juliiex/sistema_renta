<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de Asignación de Rol</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Detalles de Asignación de Rol</h2>
            <a href="{{ route('usuario_rol.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">Volver a la lista</a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="text-lg font-semibold mb-3 text-gray-700">Información de la Asignación</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">ID de la Asignación:</strong>
                        <p>{{ $usuarioRol->id }}</p>
                    </div>

                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">Usuario:</strong>
                        <p>{{ $usuarioRol->usuario->nombre }}</p>
                    </div>

                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">Correo del Usuario:</strong>
                        <p>{{ $usuarioRol->usuario->correo ?? 'No disponible' }}</p>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-semibold mb-3 text-gray-700">Detalles del Rol</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">Rol:</strong>
                        <p>{{ $usuarioRol->rol->nombre }}
                        @if(in_array($usuarioRol->rol->nombre, ['admin', 'propietario']))
                            <span class="ml-2 px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Protegido</span>
                        @elseif(in_array($usuarioRol->rol->nombre, ['inquilino', 'posible_inquilino']))
                            <span class="ml-2 px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Sistema</span>
                        @endif
                        </p>
                    </div>

                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">Guard Name:</strong>
                        <p>{{ $usuarioRol->rol->guard_name ?? 'web' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex space-x-4 mt-6">
            @if(!in_array($usuarioRol->rol->nombre ?? '', ['admin', 'propietario']) || auth()->user()->hasRole('admin'))
                <a href="{{ route('usuario_rol.edit', $usuarioRol->id) }}"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded transition">
                    Editar
                </a>

                <form action="{{ route('usuario_rol.destroy', $usuarioRol->id) }}" method="POST"
                    onsubmit="return confirm('¿Está seguro que desea eliminar esta asignación de rol?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded transition">
                        Eliminar
                    </button>
                </form>
            @endif

            <a href="{{ route('usuario_rol.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded transition">
                Volver
            </a>
        </div>
    </div>
</body>
</html>
