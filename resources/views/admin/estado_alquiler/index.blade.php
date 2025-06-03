<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Estados de Alquiler</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Lista de Estados de Alquiler</h2>
            <div class="flex space-x-2">
                <a href="{{ route('estado_alquiler.trashed') }}" class="bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600 transition">Ver Eliminados</a>
                <a href="{{ route('home') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">Volver al Dashboard</a>
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

        <a href="{{ route('estado_alquiler.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block hover:bg-blue-600 transition">Crear Estado de Alquiler</a>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white mt-4 border border-gray-200">
                <thead>
                    <tr class="bg-gray-200 text-gray-700">
                        <th class="px-4 py-2 text-left">ID</th>
                        <th class="px-4 py-2 text-left">Contrato</th>
                        <th class="px-4 py-2 text-left">Apartamento</th>
                        <th class="px-4 py-2 text-left">Usuario</th>
                        <th class="px-4 py-2 text-left">Estado Pago</th>
                        <th class="px-4 py-2 text-left">Fecha Reporte</th>
                        <th class="px-4 py-2 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($estadosAlquiler as $estado)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $estado->id }}</td>
                        <td class="px-4 py-2">{{ $estado->contrato->id ?? 'N/A' }}</td>
                        <td class="px-4 py-2">
                            @if($estado->contrato && $estado->contrato->apartamento)
                                {{ $estado->contrato->apartamento->numero_apartamento }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="px-4 py-2">{{ $estado->usuario->nombre ?? 'N/A' }}</td>
                        <td class="px-4 py-2">
                            @php
                                $estado_pago = strtolower($estado->estado_pago);
                            @endphp
                            @if($estado_pago == 'pagado')
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Pagado</span>
                            @elseif($estado_pago == 'pendiente')
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Pendiente</span>
                            @elseif($estado_pago == 'atrasado')
                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Atrasado</span>
                            @else
                                {{ ucfirst($estado->estado_pago) }}
                            @endif
                        </td>
                        <td class="px-4 py-2">{{ $estado->fecha_reporte->format('d/m/Y') }}</td>
                        <td class="px-4 py-2">
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route('estado_alquiler.show', $estado->id) }}" class="text-blue-500 hover:underline">Ver</a>
                                <a href="{{ route('estado_alquiler.edit', $estado->id) }}" class="text-yellow-500 hover:underline">Editar</a>
                                <form action="{{ route('estado_alquiler.destroy', $estado->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Está seguro que desea eliminar este estado de alquiler?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-2 text-center text-gray-500">No hay estados de alquiler registrados</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
