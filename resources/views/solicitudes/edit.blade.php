<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Solicitud de Alquiler</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-5xl mx-auto bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-4">Editar Solicitud de Alquiler</h2>

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

        <form action="{{ route('solicitudes.update', $solicitud->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="usuario_id" class="block text-sm font-semibold text-gray-700">Usuario</label>
                <select name="usuario_id" id="usuario_id" class="w-full mt-2 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach ($usuarios as $usuario)
                        <option value="{{ $usuario->id }}" {{ $usuario->id == $solicitud->usuario_id ? 'selected' : '' }}>
                            {{ $usuario->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('usuario_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="apartamento_id" class="block text-sm font-semibold text-gray-700">Apartamento</label>
                <select name="apartamento_id" id="apartamento_id" class="w-full mt-2 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach ($apartamentos as $apartamento)
                        <option value="{{ $apartamento->id }}" {{ $apartamento->id == $solicitud->apartamento_id ? 'selected' : '' }}>
                            {{ $apartamento->numero_apartamento }}
                        </option>
                    @endforeach
                </select>
                @error('apartamento_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="estado_solicitud" class="block text-sm font-semibold text-gray-700">Estado de Solicitud</label>
                <select name="estado_solicitud" id="estado_solicitud" class="w-full mt-2 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="pendiente" {{ $solicitud->estado_solicitud == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="aprobada" {{ $solicitud->estado_solicitud == 'aprobada' ? 'selected' : '' }}>Aprobada</option>
                    <option value="rechazada" {{ $solicitud->estado_solicitud == 'rechazada' ? 'selected' : '' }}>Rechazada</option>
                </select>
                @error('estado_solicitud')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-6">
                <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Actualizar Solicitud
                </button>
            </div>
        </form>

        <a href="{{ route('solicitudes.index') }}" class="inline-block text-blue-500 hover:text-blue-700">Volver a la lista</a>
    </div>
</body>
</html>
