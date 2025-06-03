<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Roles</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Gestión de Roles</h2>
            <div class="flex space-x-2">
                <a href="{{ route('rol.trashed') }}" class="bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600 transition">Ver Eliminados</a>
                <a href="{{ route('home') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">Volver al Dashboard</a>
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

        <a href="{{ route('rol.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block hover:bg-blue-600 transition">Crear Nuevo Rol</a>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white mt-4 border border-gray-200">
                <thead>
                    <tr class="bg-gray-200 text-gray-700">
                        <th class="px-4 py-2 text-left">ID</th>
                        <th class="px-4 py-2 text-left">Nombre</th>
                        <th class="px-4 py-2 text-left">Guard Name</th>
                        <th class="px-4 py-2 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($roles as $rol)
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
                        <td class="px-4 py-2">
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route('rol.show', $rol->id) }}" class="text-blue-500 hover:underline">Ver</a>

                                @if(!$esRolSistema)
                                    <a href="{{ route('rol.edit', $rol->id) }}" class="text-yellow-500 hover:underline">Editar</a>
                                    <form action="{{ route('rol.destroy', $rol->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Está seguro que desea eliminar este rol?');">
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
                        <td colspan="4" class="px-4 py-2 text-center text-gray-500">No hay roles registrados</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
