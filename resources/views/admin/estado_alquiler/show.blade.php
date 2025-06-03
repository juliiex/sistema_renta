<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Estado de Alquiler</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Detalles del Estado de Alquiler</h2>
            <div class="flex space-x-2">
                <a href="{{ route('estado_alquiler.trashed') }}" class="bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600 transition">Ver Eliminados</a>
                <a href="{{ route('estado_alquiler.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">Volver a la lista</a>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="text-lg font-semibold mb-3 text-gray-700">Información del Contrato</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">ID del Estado:</strong>
                        <p>{{ $estadoAlquiler->id }}</p>
                    </div>

                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">Contrato:</strong>
                        <p>Contrato #{{ $estadoAlquiler->contrato->id }}</p>
                    </div>

                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">Apartamento:</strong>
                        <p>{{ $estadoAlquiler->contrato->apartamento->numero_apartamento ?? 'No disponible' }}</p>
                    </div>

                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">Inquilino:</strong>
                        <p>
                            @if($estadoAlquiler->contrato && $estadoAlquiler->contrato->usuario)
                                {{ $estadoAlquiler->contrato->usuario->nombre }}
                            @else
                                No disponible
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-semibold mb-3 text-gray-700">Información del Estado</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">Estado de Pago:</strong>
                        <div class="mt-1">
                            @php
                                $estado_pago = strtolower($estadoAlquiler->estado_pago);
                            @endphp
                            @if($estado_pago == 'pagado')
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">Pagado</span>
                            @elseif($estado_pago == 'pendiente')
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">Pendiente</span>
                            @elseif($estado_pago == 'atrasado')
                                <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm">Atrasado</span>
                            @else
                                <span>{{ ucfirst($estadoAlquiler->estado_pago) }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">Fecha de Reporte:</strong>
                        <p>{{ $estadoAlquiler->fecha_reporte->format('d/m/Y') }}</p>
                    </div>

                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">Reportado por:</strong>
                        <p>{{ $estadoAlquiler->usuario->nombre ?? 'Usuario no disponible' }}</p>
                    </div>

                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">Fecha de Registro:</strong>
                        <p>{{ $estadoAlquiler->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if(auth()->user()->hasRole(['admin', 'propietario']))
        <div class="flex justify-end space-x-2">
            <a href="{{ route('estado_alquiler.edit', $estadoAlquiler->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition">Editar</a>
            <form action="{{ route('estado_alquiler.destroy', $estadoAlquiler->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Está seguro que desea eliminar este estado de alquiler?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">Eliminar</button>
            </form>
        </div>
        @endif
    </div>
</body>
</html>
