<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Reporte de Problema</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Editar Reporte de Problema</h2>
            <a href="{{ route('reporte_problema.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">Volver a la lista</a>
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

        <form action="{{ route('reporte_problema.update', $reporte->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="usuario_id" class="block text-sm font-medium text-gray-700 mb-1">Usuario (Inquilino)</label>
                <select name="usuario_id" id="usuario_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required {{ auth()->user()->hasRole('inquilino') ? 'disabled' : '' }}>
                    <option value="">Seleccione un inquilino</option>
                    @foreach ($usuarios as $usuario)
                        <option value="{{ $usuario->id }}" {{ old('usuario_id', $reporte->usuario_id) == $usuario->id ? 'selected' : '' }}>
                            {{ $usuario->nombre }} ({{ $usuario->correo ?? 'Sin correo' }})
                        </option>
                    @endforeach
                </select>
                @if(auth()->user()->hasRole('inquilino'))
                    <input type="hidden" name="usuario_id" value="{{ $reporte->usuario_id }}">
                    <p class="text-xs text-gray-500 mt-1">Como inquilino, no puedes cambiar el usuario del reporte.</p>
                @endif
            </div>

            <div class="mb-4">
                <label for="apartamento_id" class="block text-sm font-medium text-gray-700 mb-1">Apartamento</label>
                <select name="apartamento_id" id="apartamento_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required {{ auth()->user()->hasRole('inquilino') ? 'disabled' : '' }}>
                    <option value="">Seleccione un apartamento</option>
                    @foreach ($apartamentos as $apartamento)
                        <option value="{{ $apartamento->id }}" {{ old('apartamento_id', $reporte->apartamento_id) == $apartamento->id ? 'selected' : '' }}>
                            Apartamento {{ $apartamento->numero_apartamento }}
                            @if($apartamento->edificio)
                                - {{ $apartamento->edificio->nombre }}
                            @endif
                        </option>
                    @endforeach
                </select>
                @if(auth()->user()->hasRole('inquilino'))
                    <input type="hidden" name="apartamento_id" value="{{ $reporte->apartamento_id }}">
                    <p class="text-xs text-gray-500 mt-1">Como inquilino, no puedes cambiar el apartamento del reporte.</p>
                @endif
            </div>

            <div class="mb-4">
                <label for="tipo" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Problema</label>
                <select name="tipo" id="tipo" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Seleccione un tipo</option>
                    @foreach ($tipos as $key => $value)
                        <option value="{{ $key }}" {{ old('tipo', $reporte->tipo) == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">Descripción del Problema</label>
                <textarea name="descripcion" id="descripcion" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Describa el problema detalladamente" required>{{ old('descripcion', $reporte->descripcion) }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Máximo 500 caracteres.</p>
            </div>

            <div class="mb-4">
                <label for="estado" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                <select name="estado" id="estado" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required {{ auth()->user()->hasRole('inquilino') ? 'disabled' : '' }}>
                    @foreach ($estados as $key => $value)
                        <option value="{{ $key }}" {{ old('estado', $reporte->estado) == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
                @if(auth()->user()->hasRole('inquilino'))
                    <input type="hidden" name="estado" value="{{ $reporte->estado }}">
                    <p class="text-xs text-gray-500 mt-1">Como inquilino, no puedes cambiar el estado del reporte.</p>
                @endif
            </div>

            <div class="flex justify-end pt-4">
                <a href="{{ route('reporte_problema.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded mr-2 hover:bg-gray-600 transition">Cancelar</a>
                <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition">Actualizar Reporte</button>
            </div>
        </form>
    </div>
</body>
</html>
