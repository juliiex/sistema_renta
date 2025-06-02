<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Permisos por Rol</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Gestión de Permisos por Rol</h2>
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

        <div class="mb-4">
            <a href="{{ route('rol_permiso.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                Asignar Permiso a Rol
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white mt-4 border border-gray-200">
                <thead>
                    <tr class="bg-gray-200 text-gray-700">
                        <th class="px-4 py-2 text-left">ID</th>
                        <th class="px-4 py-2 text-left">Rol</th>
                        <th class="px-4 py-2 text-left">Permiso</th>
                        <th class="px-4 py-2 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rolPermisos as $rolPermiso)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $rolPermiso->id }}</td>
                        <td class="px-4 py-2">
                            {{ $rolPermiso->rol->nombre ?? 'N/A' }}
                            @if(in_array($rolPermiso->rol->nombre ?? '', ['admin', 'propietario', 'inquilino', 'posible_inquilino']))
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs ml-1">Sistema</span>
                            @endif
                        </td>
                        <td class="px-4 py-2">{{ $rolPermiso->permiso->nombre ?? 'N/A' }}</td>
                        <td class="px-4 py-2">
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route('rol_permiso.show', $rolPermiso->id) }}" class="text-blue-500 hover:underline">Ver</a>

                                @if(!in_array($rolPermiso->rol->nombre ?? '', ['admin', 'propietario', 'inquilino', 'posible_inquilino']))
                                    <a href="{{ route('rol_permiso.edit', $rolPermiso->id) }}" class="text-yellow-500 hover:underline">Editar</a>
                                    <form action="{{ route('rol_permiso.destroy', $rolPermiso->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Está seguro que desea eliminar esta asignación de permiso?');">
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
                        <td colspan="4" class="px-4 py-2 text-center text-gray-500">No hay asignaciones de permisos a roles registradas</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
