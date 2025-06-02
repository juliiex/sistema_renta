<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Editar Permiso</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Editar Permiso</h2>
            <a href="{{ route('permiso.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">Volver a la lista</a>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
                <ul class="list-disc list-inside">
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
                        <strong>Nota:</strong> Está editando un permiso personalizado. Tenga en cuenta que cambiar el nombre de un permiso puede afectar a los roles que lo utilizan.
                    </p>
                </div>
            </div>
        </div>

        <form action="{{ route('permiso.update', $permiso->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre del Permiso</label>
                <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $permiso->nombre) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ingrese el nombre del permiso" required>
                <p class="text-xs text-gray-500 mt-1">Se recomienda seguir el formato "accion_modulo" o utilizar un nombre descriptivo</p>
            </div>

            <div class="flex justify-end pt-4">
                <a href="{{ route('permiso.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded mr-2 hover:bg-gray-600 transition">Cancelar</a>
                <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition">Actualizar Permiso</button>
            </div>
        </form>
    </div>
</body>
</html>
