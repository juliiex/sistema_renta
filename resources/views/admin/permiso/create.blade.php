<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Crear Permiso</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function toggleCustomName() {
            const tipoPermiso = document.getElementById('tipo_permiso').value;
            const moduloGroup = document.getElementById('modulo_group');
            const customNameGroup = document.getElementById('custom_name_group');

            if (tipoPermiso === 'personalizado') {
                moduloGroup.classList.add('hidden');
                customNameGroup.classList.remove('hidden');
            } else {
                moduloGroup.classList.remove('hidden');
                customNameGroup.classList.add('hidden');
            }
        }
    </script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Crear Permiso</h2>
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

        @if (session('error'))
            <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
            <div class="flex">
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        <strong>Nota:</strong> Los permisos del sistema siguen el formato "accion_modulo" (ejemplo: ver_usuario).
                        Los permisos personalizados pueden tener cualquier nombre, pero es recomendable seguir una convención similar.
                    </p>
                </div>
            </div>
        </div>

        <form action="{{ route('permiso.store') }}" method="POST" class="space-y-4">
            @csrf

            <div class="mb-4">
                <label for="tipo_permiso" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Permiso</label>
                <select name="tipo_permiso" id="tipo_permiso" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="toggleCustomName()" required>
                    <option value="" disabled selected>Seleccione el tipo de permiso</option>
                    <option value="ver">Ver (permiso de lectura)</option>
                    <option value="crear">Crear (permiso de creación)</option>
                    <option value="editar">Editar (permiso de actualización)</option>
                    <option value="eliminar">Eliminar (permiso de borrado)</option>
                    <option value="personalizado">Personalizado (nombre libre)</option>
                </select>
            </div>

            <div id="modulo_group" class="mb-4">
                <label for="modulo" class="block text-sm font-medium text-gray-700 mb-1">Módulo del Sistema</label>
                <select name="modulo" id="modulo" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="" disabled selected>Seleccione el módulo</option>
                    @foreach($modulos as $modulo)
                        <option value="{{ $modulo }}">{{ ucfirst($modulo) }}</option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1">El permiso resultante tendrá el formato: tipo_modulo (ejemplo: ver_usuario)</p>
            </div>

            <div id="custom_name_group" class="mb-4 hidden">
                <label for="nombre_personalizado" class="block text-sm font-medium text-gray-700 mb-1">Nombre Personalizado</label>
                <input type="text" name="nombre_personalizado" id="nombre_personalizado" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ingrese un nombre para el permiso">
                <p class="text-xs text-gray-500 mt-1">Use un nombre descriptivo que indique claramente el propósito del permiso</p>
            </div>

            <div class="flex justify-end pt-4">
                <a href="{{ route('permiso.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded mr-2 hover:bg-gray-600 transition">Cancelar</a>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">Guardar Permiso</button>
            </div>
        </form>
    </div>
</body>
</html>
