<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Roles de Usuario</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Gestión de Roles de Usuario</h2>
            <a href="{{ route('menu') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">Volver al Menú</a>
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

        <div class="flex space-x-3 mb-4">
            <a href="{{ route('usuario_rol.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                Asignar Rol a Usuario
            </a>

        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white mt-4 border border-gray-200">
                <thead>
                    <tr class="bg-gray-200 text-gray-700">
                        <th class="px-4 py-2 text-left">ID</th>
                        <th class="px-4 py-2 text-left">Usuario</th>
                        <th class="px-4 py-2 text-left">Rol</th>
                        <th class="px-4 py-2 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($usuarioRoles as $usuarioRol)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $usuarioRol->id }}</td>
                        <td class="px-4 py-2">{{ $usuarioRol->usuario->nombre ?? 'N/A' }}</td>
                        <td class="px-4 py-2">
                            {{ $usuarioRol->rol->nombre ?? 'N/A' }}
                            @if(in_array($usuarioRol->rol->nombre ?? '', ['admin', 'propietario']))
                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs ml-1">Protegido</span>
                            @elseif(in_array($usuarioRol->rol->nombre ?? '', ['inquilino', 'posible_inquilino']))
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs ml-1">Sistema</span>
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route('usuario_rol.show', $usuarioRol->id) }}" class="text-blue-500 hover:underline">Ver</a>

                                @if(!in_array($usuarioRol->rol->nombre ?? '', ['admin', 'propietario']) || auth()->user()->hasRole('admin'))
                                    <a href="{{ route('usuario_rol.edit', $usuarioRol->id) }}" class="text-yellow-500 hover:underline">Editar</a>
                                    <form action="{{ route('usuario_rol.destroy', $usuarioRol->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Está seguro que desea eliminar esta asignación de rol?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:underline">Eliminar</button>
                                    </form>
                                @else
                                    <span class="text-gray-400">Editar</span>
                                    <span class="text-gray-400">Eliminar</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-2 text-center text-gray-500">No hay asignaciones de roles a usuarios registradas</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
