<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuarios</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-4">Lista de Usuarios</h2>

        @if (session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('usuarios.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Crear Usuario</a>

        <table class="min-w-full bg-white mt-4">
            <thead>
                <tr class="bg-gray-200 text-left">
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Nombre</th>
                    <th class="px-4 py-2">Correo</th>
                    <th class="px-4 py-2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($usuarios as $usuario)
                <tr class="border-b">
                    <td class="px-4 py-2">{{ $usuario->id }}</td>
                    <td class="px-4 py-2">{{ $usuario->nombre }}</td>
                    <td class="px-4 py-2">{{ $usuario->correo }}</td>
                    <td class="px-4 py-2 flex space-x-2">
                        <a href="{{ route('usuarios.show', $usuario->id) }}" class="text-blue-500">Ver</a>
                        <a href="{{ route('usuarios.edit', $usuario->id) }}" class="text-yellow-500">Editar</a>
                        <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este usuario?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
