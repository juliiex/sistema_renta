<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Detalles del Permiso</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Detalles del Permiso</h2>
            <a href="{{ route('permiso.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">Volver a la lista</a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if($esPermisoDelSistema)
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                <div class="flex">
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            <strong>Permiso del Sistema:</strong> Este es un permiso base del sistema y no puede ser modificado ni eliminado.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-semibold mb-3 text-gray-700">Información del Permiso</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">ID:</strong>
                        <p>{{ $permiso->id }}</p>
                    </div>

                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">Nombre:</strong>
                        <p>{{ $permiso->nombre }}</p>
                    </div>

                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">Guard:</strong>
                        <p>{{ $permiso->guard_name }}</p>
                    </div>

                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">Creado:</strong>
                        <p>{{ $permiso->created_at ? $permiso->created_at->format('d/m/Y H:i') : 'Sin fecha' }}</p>
                    </div>

                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">Actualizado:</strong>
                        <p>{{ $permiso->updated_at ? $permiso->updated_at->format('d/m/Y H:i') : 'Sin fecha' }}</p>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-semibold mb-3 text-gray-700">Roles Asociados</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    @if($roles->count() > 0)
                        <ul class="list-disc pl-5">
                            @foreach($roles as $rol)
                                <li>{{ $rol->nombre }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-500">Este permiso no está asignado a ningún rol.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-2 mt-6">
            @if(!$esPermisoDelSistema)
                <a href="{{ route('permiso.edit', $permiso->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition">Editar</a>
                <form action="{{ route('permiso.destroy', $permiso->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Está seguro que desea eliminar este permiso?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">Eliminar</button>
                </form>
            @endif
        </div>
    </div>
</body>
</html>
