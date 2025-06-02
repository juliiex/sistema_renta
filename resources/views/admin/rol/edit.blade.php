<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Rol</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Editar Rol</h2>
            <a href="{{ route('rol.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">Volver a la lista</a>
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

        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
            <div class="flex">
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        <strong>Advertencia:</strong> Editar un rol puede afectar a los usuarios que lo tienen asignado y a los permisos del sistema.
                    </p>
                </div>
            </div>
        </div>

        <form action="{{ route('rol.update', $rol->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre del Rol</label>
                <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $rol->nombre) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ingrese el nombre del rol" required>
            </div>

            <div class="mb-4">
                <label for="guard_name" class="block text-sm font-medium text-gray-700 mb-1">Guard Name</label>
                <input type="text" name="guard_name" id="guard_name" value="{{ old('guard_name', $rol->guard_name) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="web" required>
                <p class="text-xs text-gray-500 mt-1">Si no sabe qué es esto, deje el valor "web".</p>
            </div>

            <div class="flex justify-end pt-4">
                <a href="{{ route('rol.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded mr-2 hover:bg-gray-600 transition">Cancelar</a>
                <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition">Actualizar Rol</button>
            </div>
        </form>
    </div>
</body>
</html>
