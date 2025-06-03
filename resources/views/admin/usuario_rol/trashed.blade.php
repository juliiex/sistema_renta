<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignaciones de Rol Eliminadas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Asignaciones de Rol Eliminadas</h2>
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

        @if($usuarioRoles->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white mt-4 border border-gray-200">
                    <thead>
                        <tr class="bg-gray-200 text-gray-700">
                            <th class="px-4 py-2 text-left">ID</th>
                            <th class="px-4 py-2 text-left">Usuario</th>
                            <th class="px-4 py-2 text-left">Rol</th>
                            <th class="px-4 py-2 text-left">Eliminado el</th>
                            <th class="px-4 py-2 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($usuarioRoles as $usuarioRol)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $usuarioRol->id }}</td>
                            <td class="px-4 py-2">{{ $usuarioRol->usuario->nombre ?? 'N/A' }}</td>
                            <td class="px-4 py-2">
                                {{ $usuarioRol->rol->nombre ?? 'N/A' }}
                                @if(in_array($usuarioRol->rol->nombre ?? '', ['admin', 'propietario']))
                                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs ml-1">Protegido</span>
                                @elseif(in_array($usuarioRol->rol->nombre ?? '', ['inquilino', 'posible inquilino']))
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs ml-1">Sistema</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">{{ $usuarioRol->deleted_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-2">
                                <div class="flex justify-center space-x-2">
                                    @if(!in_array($usuarioRol->rol->nombre ?? '', ['admin', 'propietario']) || auth()->user()->hasRole('admin'))
                                        <form action="{{ route('usuario_rol.restore', $usuarioRol->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-green-500 hover:underline">Restaurar</button>
                                        </form>
                                        <form action="{{ route('usuario_rol.force-delete', $usuarioRol->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar permanentemente esta asignación de rol? Esta acción no se puede deshacer.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:underline">Eliminar permanentemente</button>
                                        </form>
                                    @else
                                        <span class="text-gray-400">Restaurar</span>
                                        <span class="text-gray-400">Eliminar permanentemente</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                <div class="flex">
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            No hay asignaciones de rol eliminadas.
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</body>
</html>
