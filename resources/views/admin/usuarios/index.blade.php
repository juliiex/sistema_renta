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
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">
                @if(auth()->user()->hasRole('admin'))
                    Lista de Usuarios
                @elseif(auth()->user()->hasRole('propietario'))
                    Lista de Inquilinos
                @else
                    Mi Perfil
                @endif
            </h2>

            <a href="{{ route('home') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">Volver al Menú</a>
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

        @can('crear_usuario')
            <a href="{{ route('usuarios.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block hover:bg-blue-600 transition">Crear Usuario</a>
        @endcan

        <table class="min-w-full bg-white mt-4 shadow rounded-lg overflow-hidden">
            <thead>
                <tr class="bg-gray-200 text-left">
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Avatar</th>
                    <th class="px-4 py-2">Nombre</th>
                    <th class="px-4 py-2">Correo</th>
                    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('propietario'))
                        <th class="px-4 py-2">Roles</th>
                    @endif
                    <th class="px-4 py-2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($usuarios as $usuario)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-2">{{ $usuario->id }}</td>
                    <td class="px-4 py-2">
                        @if ($usuario->avatar)
                            <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-blue-300 shadow">
                                <img src="{{ asset('storage/avatars/' . $usuario->avatar) }}" alt="Avatar de {{ $usuario->nombre }}" class="w-full h-full object-cover">
                            </div>
                        @else
                            <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 border-2 border-gray-300">
                                <span>{{ substr($usuario->nombre, 0, 1) }}</span>
                            </div>
                        @endif
                    </td>
                    <td class="px-4 py-2">{{ $usuario->nombre }}</td>
                    <td class="px-4 py-2">{{ $usuario->correo }}</td>
                    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('propietario'))
                        <td class="px-4 py-2">
                            @foreach($usuario->roles as $rol)
                                <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full mr-1">{{ $rol->nombre }}</span>
                            @endforeach
                        </td>
                    @endif
                    <td class="px-4 py-2 flex space-x-2">
                        @can('ver_usuario')
                            <a href="{{ route('usuarios.show', $usuario->id) }}" class="text-blue-500 hover:text-blue-700 transition">Ver</a>
                        @endcan

                        @if(auth()->user()->hasRole('admin') ||
                           (auth()->user()->hasRole('propietario') && !$usuario->hasRole('admin') && !$usuario->hasRole('propietario')) ||
                           auth()->id() == $usuario->id)
                            @can('editar_usuario')
                                <a href="{{ route('usuarios.edit', $usuario->id) }}" class="text-yellow-500 hover:text-yellow-700 transition">Editar</a>
                            @endcan
                        @endif

                        @if(auth()->user()->hasRole('admin') ||
                          (auth()->user()->hasRole('propietario') && !$usuario->hasRole('admin') && !$usuario->hasRole('propietario')))
                            @can('eliminar_usuario')
                                <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este usuario?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 transition">Eliminar</button>
                                </form>
                            @endcan
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
