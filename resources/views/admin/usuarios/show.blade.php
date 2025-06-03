<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Usuario</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Detalles del Usuario</h2>
            <div class="flex space-x-2">
                <a href="{{ route('usuarios.trashed') }}" class="bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600 transition">Ver Eliminados</a>
                <a href="{{ route('usuarios.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">Volver a la lista</a>
            </div>
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

        <div class="flex flex-col md:flex-row gap-6 mb-6">
            <!-- Avatar del usuario -->
            <div class="flex-shrink-0 mb-4 md:mb-0">
                @if($usuario->avatar)
                    <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-blue-300 shadow-lg mx-auto">
                        <img src="{{ asset('storage/avatars/' . $usuario->avatar) }}" class="w-full h-full object-cover" alt="Avatar de {{ $usuario->nombre }}">
                    </div>
                @else
                    <div class="w-32 h-32 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 text-4xl border-4 border-gray-300 mx-auto">
                        <span>{{ substr($usuario->nombre, 0, 1) }}</span>
                    </div>
                @endif
            </div>

            <!-- Información del usuario -->
            <div class="flex-grow grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <strong class="block text-gray-700 mb-1">Nombre:</strong>
                    <span class="block bg-gray-50 p-2 rounded">{{ $usuario->nombre }}</span>
                </div>

                <div class="mb-4">
                    <strong class="block text-gray-700 mb-1">Correo:</strong>
                    <span class="block bg-gray-50 p-2 rounded">{{ $usuario->correo }}</span>
                </div>

                <div class="mb-4">
                    <strong class="block text-gray-700 mb-1">Teléfono:</strong>
                    <span class="block bg-gray-50 p-2 rounded">{{ $usuario->telefono }}</span>
                </div>

                @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('propietario'))
                <div class="mb-4 col-span-1 md:col-span-2">
                    <strong class="block text-gray-700 mb-1">Roles:</strong>
                    <div class="flex flex-wrap gap-2 mt-1">
                        @foreach($usuario->roles as $rol)
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full">{{ $rol->nombre }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="mt-6 flex space-x-2 justify-end">
            @if(auth()->user()->hasRole('admin') ||
               (auth()->user()->hasRole('propietario') && !$usuario->hasRole('admin') && !$usuario->hasRole('propietario')) ||
               auth()->id() == $usuario->id)
                @can('editar_usuario')
                    <a href="{{ route('usuarios.edit', $usuario->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition">Editar</a>
                @endcan
            @endif

            @if(auth()->user()->hasRole('admin') ||
              (auth()->user()->hasRole('propietario') && !$usuario->hasRole('admin') && !$usuario->hasRole('propietario')))
                @can('eliminar_usuario')
                    <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este usuario?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">Eliminar</button>
                    </form>
                @endcan
            @endif
        </div>
    </div>
</body>
</html>
