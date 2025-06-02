<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignar Permiso a Rol</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Asignar Permiso a Rol</h2>
            <a href="{{ route('rol_permiso.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">Volver a la lista</a>
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

        @if (session('error'))
            <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-4">
            <div class="flex">
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        <strong>Importante:</strong> Al asignar permisos a roles del sistema (admin, propietario, inquilino, posible_inquilino), tenga en cuenta que algunas restricciones pueden aplicarse para mantener la integridad del sistema.
                    </p>
                </div>
            </div>
        </div>

        <form action="{{ route('rol_permiso.store') }}" method="POST" class="space-y-4">
            @csrf

            <div class="mb-4">
                <label for="rol_id" class="block text-sm font-medium text-gray-700 mb-1">Seleccione un Rol</label>
                <select name="rol_id" id="rol_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="" disabled selected>-- Seleccione un rol --</option>
                    @foreach ($roles as $rol)
                        <option value="{{ $rol->id }}" {{ old('rol_id') == $rol->id ? 'selected' : '' }}>
                            {{ $rol->nombre }}
                            @if(in_array($rol->nombre, ['admin', 'propietario', 'inquilino', 'posible_inquilino']))
                                (Sistema)
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="permiso_id" class="block text-sm font-medium text-gray-700 mb-1">Seleccione un Permiso</label>
                <select name="permiso_id" id="permiso_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="" disabled selected>-- Seleccione un permiso --</option>
                    @foreach ($permisos as $permiso)
                        <option value="{{ $permiso->id }}" {{ old('permiso_id') == $permiso->id ? 'selected' : '' }}>
                            {{ $permiso->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-end pt-4">
                <a href="{{ route('rol_permiso.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded mr-2 hover:bg-gray-600 transition">Cancelar</a>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">Asignar Permiso</button>
            </div>
        </form>
    </div>
</body>
</html>
