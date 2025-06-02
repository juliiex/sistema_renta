<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Lista de Permisos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Lista de Permisos</h2>
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

        <a href="{{ route('permiso.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block hover:bg-blue-600 transition">
            Crear Permiso
        </a>

        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-2 text-blue-700 border-b pb-1">Permisos del Sistema</h3>
            <p class="text-sm text-gray-600 mb-4">Estos permisos son necesarios para el funcionamiento del sistema y no pueden ser modificados.</p>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead>
                        <tr class="bg-gray-100 text-left">
                            <th class="px-4 py-2">ID</th>
                            <th class="px-4 py-2">Nombre</th>
                            <th class="px-4 py-2 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($permisosDelSistema as $permiso)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-2">{{ $permiso->id }}</td>
                                <td class="px-4 py-2">{{ $permiso->nombre }}</td>
                                <td class="px-4 py-2 flex justify-center">
                                    <a href="{{ route('permiso.show', $permiso->id) }}" class="text-blue-500 hover:underline">Ver</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-2 text-center text-gray-500">No hay permisos del sistema registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div>
            <h3 class="text-lg font-semibold mb-2 text-green-700 border-b pb-1">Permisos Personalizados</h3>
            <p class="text-sm text-gray-600 mb-4">Estos permisos son creados por los administradores y pueden ser modificados o eliminados.</p>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead>
                        <tr class="bg-gray-100 text-left">
                            <th class="px-4 py-2">ID</th>
                            <th class="px-4 py-2">Nombre</th>
                            <th class="px-4 py-2 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($permisosPersonalizados as $permiso)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-2">{{ $permiso->id }}</td>
                                <td class="px-4 py-2">{{ $permiso->nombre }}</td>
                                <td class="px-4 py-2">
                                    <div class="flex justify-center space-x-2">
                                        <a href="{{ route('permiso.show', $permiso->id) }}" class="text-blue-500 hover:underline">Ver</a>
                                        <a href="{{ route('permiso.edit', $permiso->id) }}" class="text-yellow-500 hover:underline">Editar</a>
                                        <form action="{{ route('permiso.destroy', $permiso->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Está seguro que desea eliminar este permiso?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:underline">Eliminar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-2 text-center text-gray-500">No hay permisos personalizados registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
