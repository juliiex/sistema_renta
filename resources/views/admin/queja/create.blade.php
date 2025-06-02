<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Queja</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Crear Queja</h2>
            <a href="{{ route('queja.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">Volver a la lista</a>
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

        <form action="{{ route('queja.store') }}" method="POST" class="space-y-4">
            @csrf

            <div class="mb-4">
                <label for="usuario_id" class="block text-sm font-medium text-gray-700 mb-1">Usuario (Inquilino)</label>
                <select name="usuario_id" id="usuario_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Seleccione un inquilino</option>
                    @foreach ($usuarios as $usuario)
                        <option value="{{ $usuario->id }}" {{ auth()->id() == $usuario->id ? 'selected' : '' }}>
                            {{ $usuario->nombre }} ({{ $usuario->correo }})
                        </option>
                    @endforeach
                </select>
                @if(!auth()->user()->hasRole(['admin', 'propietario']))
                    <p class="text-xs text-gray-500 mt-1">Como inquilino, solo puedes crear quejas para tu usuario.</p>
                @endif
            </div>

            <div class="mb-4">
                <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                <textarea name="descripcion" id="descripcion" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" rows="4" placeholder="Ingrese la descripción detallada de la queja" required>{{ old('descripcion') }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Máximo 500 caracteres.</p>
            </div>

            <div class="mb-4">
                <label for="tipo" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Queja</label>
                <select name="tipo" id="tipo" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Seleccione un tipo</option>
                    @foreach ($tiposQuejas as $key => $value)
                        <option value="{{ $key }}" {{ old('tipo') == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="fecha_envio" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Envío</label>
                <input type="date" name="fecha_envio" id="fecha_envio" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ date('Y-m-d') }}" readonly>
                <p class="text-xs text-gray-500 mt-1">La fecha se establece automáticamente al día de hoy.</p>
            </div>

            <div class="flex justify-end pt-4">
                <a href="{{ route('queja.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded mr-2 hover:bg-gray-600 transition">Cancelar</a>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">Guardar Queja</button>
            </div>
        </form>
    </div>
</body>
</html>
