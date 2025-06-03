<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Rol</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Detalles del Rol</h2>
            <div class="flex space-x-2">
                <a href="{{ route('rol.trashed') }}" class="bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600 transition">Ver Eliminados</a>
                <a href="{{ route('rol.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">Volver a la lista</a>
            </div>
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
                <h3 class="text-lg font-semibold mb-3 text-gray-700">Información del Rol</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">ID:</strong>
                        <p>{{ $rol->id }}</p>
                    </div>

                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">Nombre:</strong>
                        <p>{{ $rol->nombre }}
                        @php
                            $esRolSistema = in_array(strtolower($rol->nombre), ['admin', 'propietario', 'inquilino', 'posible inquilino']);
                        @endphp
                        @if($esRolSistema)
                            <span class="ml-2 px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Rol del Sistema</span>
                        @endif
                        </p>
                    </div>

                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">Guard Name:</strong>
                        <p>{{ $rol->guard_name }}</p>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-semibold mb-3 text-gray-700">Estadísticas</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">Usuarios con este rol:</strong>
                        <p>{{ $rol->usuarios()->count() }}</p>
                    </div>

                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">Tipo de rol:</strong>
                        <p>{{ $esRolSistema ? 'Rol del Sistema (No modificable)' : 'Rol Personalizado' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex space-x-4 mt-6">
            @if(!$esRolSistema)
                <a href="{{ route('rol.edit', $rol->id) }}"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded transition">
                    Editar
                </a>

                <form action="{{ route('rol.destroy', $rol->id) }}" method="POST"
                    onsubmit="return confirm('¿Está seguro que desea eliminar este rol?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded transition">
                        Eliminar
                    </button>
                </form>
            @endif

            <a href="{{ route('rol.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded transition">
                Volver
            </a>
        </div>
    </div>
</body>
</html>
