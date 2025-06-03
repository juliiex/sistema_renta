<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roles Eliminados</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Roles Eliminados</h2>
            <a href="{{ route('rol.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">Volver a la lista</a>
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

        @if($roles->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white mt-4 border border-gray-200">
                    <thead>
                        <tr class="bg-gray-200 text-gray-700">
                            <th class="px-4 py-2 text-left">ID</th>
                            <th class="px-4 py-2 text-left">Nombre</th>
                            <th class="px-4 py-2 text-left">Guard Name</th>
                            <th class="px-4 py-2 text-left">Eliminado el</th>
                            <th class="px-4 py-2 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $rol)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $rol->id }}</td>
                            <td class="px-4 py-2">
                                {{ $rol->nombre }}
                                @php
                                    $esRolSistema = in_array(strtolower($rol->nombre), ['admin', 'propietario', 'inquilino', 'posible inquilino']);
                                @endphp
                                @if($esRolSistema)
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Sistema</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">{{ $rol->guard_name }}</td>
                            <td class="px-4 py-2">{{ $rol->deleted_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-2">
                                <div class="flex justify-center space-x-2">
                                    <form action="{{ route('rol.restore', $rol->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-green-500 hover:underline">Restaurar</button>
                                    </form>

                                    @if(!$esRolSistema && $rol->usuarios()->count() == 0)
                                        <form action="{{ route('rol.force-delete', $rol->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar permanentemente este rol? Esta acción no se puede deshacer.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:underline">Eliminar permanentemente</button>
                                        </form>
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
                            No hay roles eliminados.
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</body>
</html>
