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
        <h2 class="text-2xl font-bold mb-4">Detalles del Usuario</h2>

        <div class="mb-4">
            <strong>Nombre:</strong> {{ $usuario->nombre }}
        </div>

        <div class="mb-4">
            <strong>Correo:</strong> {{ $usuario->correo }}
        </div>

        <div class="mb-4">
            <strong>Teléfono:</strong> {{ $usuario->telefono }}
        </div>

        <div class="mb-4">
            <strong>Avatar:</strong>
            @if($usuario->avatar)
                <img src="{{ asset('storage/' . $usuario->avatar) }}" class="mt-2 w-24 h-24 rounded-full">
            @else
                <p>No tiene avatar.</p>
            @endif
        </div>

        <a href="{{ route('usuarios.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Volver a la lista</a>
    </div>
</body>
</html>
