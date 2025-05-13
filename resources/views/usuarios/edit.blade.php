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
        <h2 class="text-2xl font-bold mb-4">Editar Usuario</h2>

        <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="nombre" class="block text-gray-700">Nombre:</label>
                <input type="text" name="nombre" value="{{ $usuario->nombre }}" class="w-full p-2 border rounded">
            </div>

            <div class="mb-4">
                <label for="correo" class="block text-gray-700">Correo:</label>
                <input type="email" name="correo" value="{{ $usuario->correo }}" class="w-full p-2 border rounded">
            </div>

            <div class="mb-4">
                <label for="telefono" class="block text-gray-700">Teléfono:</label>
                <input type="text" name="telefono" value="{{ $usuario->telefono }}" class="w-full p-2 border rounded">
            </div>

            <div class="mb-4">
                <label for="contraseña" class="block text-gray-700">Contraseña:</label>
                <input type="password" name="contraseña" class="w-full p-2 border rounded">
            </div>

            <div class="mb-4">
                <label for="avatar" class="block text-gray-700">Avatar:</label>
                <input type="file" name="avatar" class="w-full p-2">
                @if($usuario->avatar)
                    <img src="{{ asset('storage/' . $usuario->avatar) }}" class="mt-2 w-24 h-24 rounded-full">
                @endif
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded">Actualizar Usuario</button>
            </div>
        </form>
    </div>
</body>
</html>
