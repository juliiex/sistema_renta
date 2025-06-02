<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Editar Usuario</h2>
            <a href="{{ route('usuarios.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Cancelar</a>
        </div>

        @if (session('error'))
            <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="nombre" class="block text-gray-700">Nombre:</label>
                <input type="text" name="nombre" value="{{ old('nombre', $usuario->nombre) }}" class="w-full p-2 border rounded">
            </div>

            <div class="mb-4">
                <label for="correo" class="block text-gray-700">Correo:</label>
                <input type="email" name="correo" value="{{ old('correo', $usuario->correo) }}" class="w-full p-2 border rounded">
            </div>

            <div class="mb-4">
                <label for="telefono" class="block text-gray-700">Teléfono:</label>
                <input type="text" name="telefono" value="{{ old('telefono', $usuario->telefono) }}" class="w-full p-2 border rounded">
            </div>

            <div class="mb-4">
                <label for="contraseña" class="block text-gray-700">Contraseña:</label>
                <input type="password" name="contraseña" class="w-full p-2 border rounded">
                <p class="text-sm text-gray-500 mt-1">Dejar en blanco para mantener la contraseña actual.</p>
            </div>

            <div class="mb-4">
                <label for="avatar" class="block text-gray-700">Avatar:</label>
                <input type="file" name="avatar" class="w-full p-2 border rounded">
                @if($usuario->avatar)
                    <div class="mt-2">
                        <p class="text-sm text-gray-500">Avatar actual:</p>
                        <img src="{{ asset('storage/' . $usuario->avatar) }}" class="mt-1 w-24 h-24 rounded-full object-cover">
                    </div>
                @endif
            </div>

            @if(auth()->user()->hasRole(['admin', 'propietario']))
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Roles:</label>
                <div class="grid grid-cols-2 gap-2">
                    @foreach($roles as $rol)
                    <div class="flex items-center">
                        <input type="checkbox" name="roles[]" value="{{ $rol->id }}" id="rol_{{ $rol->id }}"
                            {{ in_array($rol->id, $usuario->roles->pluck('id')->toArray()) ? 'checked' : '' }}
                            {{ !auth()->user()->hasRole('admin') && in_array($rol->nombre, ['admin', 'propietario']) ? 'disabled' : '' }}
                            class="mr-2">
                        <label for="rol_{{ $rol->id }}">{{ $rol->nombre }}</label>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="flex justify-end">
                <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded">Actualizar Usuario</button>
            </div>
        </form>
    </div>
</body>
</html>
