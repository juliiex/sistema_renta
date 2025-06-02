<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Reporte de Problema</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Detalles del Reporte de Problema</h2>
            <a href="{{ route('reporte_problema.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">Volver a la lista</a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="text-lg font-semibold mb-3 text-gray-700">Información del Reporte</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">ID:</strong>
                        <p>{{ $reporteProblema->id }}</p>
                    </div>

                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">Usuario (Inquilino):</strong>
                        <p>{{ $reporteProblema->usuario->nombre ?? 'No disponible' }}</p>
                    </div>

                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">Apartamento:</strong>
                        <p>{{ $reporteProblema->apartamento->numero_apartamento ?? 'No disponible' }}</p>
                    </div>

                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">Tipo:</strong>
                        <p>{{ $reporteProblema->tipo ?? 'No especificado' }}</p>
                    </div>

                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">Estado:</strong>
                        <p>
                            @if($reporteProblema->estado == 'pendiente')
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full">Pendiente</span>
                            @elseif($reporteProblema->estado == 'atendido')
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full">Atendido</span>
                            @elseif($reporteProblema->estado == 'cerrado')
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full">Cerrado</span>
                            @else
                                {{ $reporteProblema->estado }}
                            @endif
                        </p>
                    </div>

                    <div class="mb-3">
                        <strong class="block text-sm font-medium text-gray-700">Fecha de Reporte:</strong>
                        <p>{{ $reporteProblema->fecha_reporte ? $reporteProblema->fecha_reporte->format('d/m/Y') : 'No disponible' }}</p>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-semibold mb-3 text-gray-700">Descripción del Problema</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="whitespace-pre-line">{{ $reporteProblema->descripcion }}</p>
                </div>
            </div>
        </div>

        <div class="flex space-x-4 mt-6">
            @if(auth()->user()->hasRole(['admin', 'propietario']) ||
               (auth()->id() === $reporteProblema->usuario_id && $reporteProblema->estado === 'pendiente'))
                <a href="{{ route('reporte_problema.edit', $reporteProblema->id) }}"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded transition">
                    Editar
                </a>

                <form action="{{ route('reporte_problema.destroy', $reporteProblema->id) }}" method="POST"
                    onsubmit="return confirm('¿Estás seguro de eliminar este reporte?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded transition">
                        Eliminar
                    </button>
                </form>
            @endif

            <a href="{{ route('reporte_problema.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded transition">
                Volver
            </a>
        </div>
    </div>
</body>
</html>
