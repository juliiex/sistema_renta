<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Contratos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Lista de Contratos</h2>
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

        <a href="{{ route('contrato.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block hover:bg-blue-600 transition">Crear Contrato</a>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white mt-4 border border-gray-200 shadow-sm rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-gray-100 text-left">
                        <th class="px-6 py-3 text-gray-600 font-semibold text-sm uppercase">ID</th>
                        <th class="px-6 py-3 text-gray-600 font-semibold text-sm uppercase">Usuario</th>
                        <th class="px-6 py-3 text-gray-600 font-semibold text-sm uppercase">Apartamento</th>
                        <th class="px-6 py-3 text-gray-600 font-semibold text-sm uppercase">Fecha Inicio</th>
                        <th class="px-6 py-3 text-gray-600 font-semibold text-sm uppercase">Fecha Fin</th>
                        <th class="px-6 py-3 text-gray-600 font-semibold text-sm uppercase">Estado</th>
                        <th class="px-6 py-3 text-gray-600 font-semibold text-sm uppercase">Firma</th>
                        <th class="px-6 py-3 text-gray-600 font-semibold text-sm uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($contratos as $contrato)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $contrato->id }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if($contrato->usuario && $contrato->usuario->avatar)
                                <img src="{{ asset('storage/avatars/' . $contrato->usuario->avatar) }}" class="h-8 w-8 rounded-full mr-2" alt="Avatar">
                                @endif
                                <span>{{ $contrato->usuario->nombre ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-medium">{{ $contrato->apartamento->numero_apartamento ?? 'N/A' }}</span>
                            <span class="text-gray-500 text-xs block">Edificio: {{ $contrato->apartamento->edificio->nombre ?? 'N/A' }}</span>
                        </td>
                        <td class="px-6 py-4">{{ $contrato->fecha_inicio->format('d/m/Y') }}</td>
                        <td class="px-6 py-4">{{ $contrato->fecha_fin->format('d/m/Y') }}</td>
                        <td class="px-6 py-4">
                            @if($contrato->estado == 'activo')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Activo</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Inactivo</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($contrato->estado_firma == 'firmado')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Firmado</span>
                                @if($contrato->firma_imagen)
                                    <a href="{{ route('contrato.show', $contrato->id) }}" class="text-xs text-blue-600 hover:underline block mt-1">
                                        Ver firma
                                    </a>
                                @endif
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pendiente</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-2">
                                <a href="{{ route('contrato.show', $contrato->id) }}" class="text-blue-500 hover:text-blue-700 transition">Ver</a>
                                <a href="{{ route('contrato.edit', $contrato->id) }}" class="text-yellow-500 hover:text-yellow-700 transition">Editar</a>
                                <form action="{{ route('contrato.destroy', $contrato->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este contrato?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 transition">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @if($contratos->isEmpty())
                <div class="text-center py-4 text-gray-500">
                    No hay contratos registrados.
                </div>
            @endif
        </div>
    </div>
</body>
</html>
