@extends('layouts.dashboard-sidebar')

@section('title', 'Dashboard Administrador')

@section('dashboard-content')
<!-- Botón para descargar informe -->
<div class="mb-4 flex justify-end">
    <a href="{{ route('informe.generar') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition flex items-center">
        <i class="fas fa-download mr-2"></i> Descargar Informe
    </a>
</div>

<!-- Top Row: Métricas -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <!-- Total de Edificios -->
    <div class="bg-white p-4 rounded-lg shadow dashboard-card">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total de Edificios</p>
                <h3 class="text-2xl font-bold mt-1">{{ $totalEdificios }}</h3>
            </div>
            <div class="bg-blue-100 p-2 rounded-md">
                <i class="fas fa-building text-blue-500 text-lg"></i>
            </div>
        </div>
    </div>

    <!-- Total de Apartamentos -->
    <div class="bg-white p-4 rounded-lg shadow dashboard-card">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total de Apartamentos</p>
                <h3 class="text-2xl font-bold mt-1">{{ $totalApartamentos }}</h3>
                <div class="flex items-center mt-2 text-xs">
                    <span class="text-green-500 mr-2">{{ $apartamentosDisponibles }} disponibles</span>
                    <span class="text-red-500">{{ $apartamentosOcupados }} ocupados</span>
                </div>
            </div>
            <div class="bg-green-100 p-2 rounded-md">
                <i class="fas fa-home text-green-500 text-lg"></i>
            </div>
        </div>
    </div>

    <!-- Total de Usuarios -->
    <div class="bg-white p-4 rounded-lg shadow dashboard-card">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total de Usuarios</p>
                <h3 class="text-2xl font-bold mt-1">{{ $totalUsuarios }}</h3>
                <div class="flex flex-wrap gap-1 mt-2 text-xs">
                    <span class="bg-gray-100 text-gray-800 px-1 rounded">{{ $usuariosPorRol['admin'] ?? 0 }} admin</span>
                    <span class="bg-gray-100 text-gray-800 px-1 rounded">{{ $usuariosPorRol['propietario'] ?? 0 }} propietarios</span>
                    <span class="bg-gray-100 text-gray-800 px-1 rounded">{{ $usuariosPorRol['inquilino'] ?? 0 }} inquilinos</span>
                </div>
            </div>
            <div class="bg-purple-100 p-2 rounded-md">
                <i class="fas fa-users text-purple-500 text-lg"></i>
            </div>
        </div>
    </div>

    <!-- Porcentaje de Ocupación -->
    <div class="bg-white p-4 rounded-lg shadow dashboard-card">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-gray-500 text-sm">Porcentaje de Ocupación</p>
                <h3 class="text-2xl font-bold mt-1">{{ $porcentajeOcupacion }}%</h3>
                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $porcentajeOcupacion }}%"></div>
                </div>
            </div>
            <div class="bg-yellow-100 p-2 rounded-md">
                <i class="fas fa-chart-pie text-yellow-500 text-lg"></i>
            </div>
        </div>
    </div>
</div>

<!-- Middle Row: Gráficos -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
    <!-- Gráfico 1: Distribución de Apartamentos por Estado -->
    <div class="bg-white p-4 rounded-lg shadow">
        <h3 class="text-gray-700 font-semibold mb-4">Distribución de Apartamentos</h3>
        <div class="chart-container">
            <canvas id="apartamentosChart"></canvas>
        </div>
    </div>

    <!-- Gráfico 2: Contratos por Mes -->
    <div class="bg-white p-4 rounded-lg shadow">
        <h3 class="text-gray-700 font-semibold mb-4">Contratos por Mes</h3>
        <div class="chart-container">
            <canvas id="contratosMesChart"></canvas>
        </div>
    </div>

    <!-- Gráfico 3: Problemas por Categoría -->
    <div class="bg-white p-4 rounded-lg shadow">
        <h3 class="text-gray-700 font-semibold mb-4">Problemas por Categoría</h3>
        <div class="chart-container">
            <canvas id="problemasCategoriaChart"></canvas>
        </div>
    </div>
</div>

<!-- Bottom Row: Listas de Acción -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
    <!-- Lista 1: Problemas Críticos -->
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-gray-700 font-semibold">Problemas Críticos</h3>
            <a href="{{ route('reporte_problema.index') }}" class="text-blue-600 hover:underline text-sm">Ver todos</a>
        </div>

        @if(count($listas['problemasCriticos']) > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                        <tr>
                            <th class="px-4 py-2">Apto.</th>
                            <th class="px-4 py-2">Problema</th>
                            <th class="px-4 py-2">Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($listas['problemasCriticos'] as $problema)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-2">{{ $problema->apartamento->numero_apartamento }}</td>
                                <td class="px-4 py-2">{{ \Illuminate\Support\Str::limit($problema->descripcion, 20) }}</td>
                                <td class="px-4 py-2">{{ $problema->fecha_reporte->format('d/m/Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 text-center py-4">No hay problemas críticos pendientes</p>
        @endif
    </div>

    <!-- Lista 2: Solicitudes Pendientes -->
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-gray-700 font-semibold">Solicitudes Pendientes</h3>
            <a href="{{ route('solicitudes.index') }}" class="text-blue-600 hover:underline text-sm">Ver todas</a>
        </div>

        @if(count($listas['solicitudesPendientes']) > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                        <tr>
                            <th class="px-4 py-2">Apto.</th>
                            <th class="px-4 py-2">Solicitante</th>
                            <th class="px-4 py-2">Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($listas['solicitudesPendientes'] as $solicitud)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-2">{{ $solicitud->apartamento->numero_apartamento }}</td>
                                <td class="px-4 py-2">{{ $solicitud->usuario->nombre }}</td>
                                <td class="px-4 py-2">{{ $solicitud->fecha_solicitud->format('d/m/Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 text-center py-4">No hay solicitudes pendientes</p>
        @endif
    </div>

    <!-- Lista 3: Últimos Usuarios Registrados -->
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-gray-700 font-semibold">Últimos Usuarios Registrados</h3>
            <a href="{{ route('usuarios.index') }}" class="text-blue-600 hover:underline text-sm">Ver todos</a>
        </div>

        @if(count($listas['usuariosRecientes']) > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                        <tr>
                            <th class="px-4 py-2">Nombre</th>
                            <th class="px-4 py-2">Correo</th>
                            <th class="px-4 py-2">Registro</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($listas['usuariosRecientes'] as $usuario)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-2">{{ $usuario->nombre }}</td>
                                <td class="px-4 py-2">{{ $usuario->correo }}</td>
                                <td class="px-4 py-2">{{ $usuario->created_at->format('d/m/Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 text-center py-4">No hay usuarios recientes</p>
        @endif
    </div>
</div>
@endsection

@push('dashboard-scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gráfico de Apartamentos por Estado
        var apartamentosChartCtx = document.getElementById('apartamentosChart').getContext('2d');
        var apartamentosChart = new Chart(apartamentosChartCtx, {
            type: 'doughnut',
            data: {
                labels: Object.keys({{ Illuminate\Support\Js::from($graficos['estadosApartamentos']) }}),
                datasets: [{
                    data: Object.values({{ Illuminate\Support\Js::from($graficos['estadosApartamentos']) }}),
                    backgroundColor: [
                        'rgba(34, 197, 94, 0.7)',  // Disponible
                        'rgba(239, 68, 68, 0.7)',  // Ocupado
                        'rgba(250, 204, 21, 0.7)', // En mantenimiento
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                },
                cutout: '60%'
            }
        });

        // Gráfico de Contratos por Mes
        var contratosMesChartCtx = document.getElementById('contratosMesChart').getContext('2d');
        var contratosMesChart = new Chart(contratosMesChartCtx, {
            type: 'line',
            data: {
                labels: {{ Illuminate\Support\Js::from($graficos['contratosPorMes']['meses']) }},
                datasets: [{
                    label: 'Contratos',
                    data: {{ Illuminate\Support\Js::from($graficos['contratosPorMes']['conteos']) }},
                    backgroundColor: 'rgba(59, 130, 246, 0.2)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 2,
                    tension: 0.2
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });

        // Gráfico de Problemas por Categoría
        var problemasCategoriaChartCtx = document.getElementById('problemasCategoriaChart').getContext('2d');
        var problemasCategoriaChart = new Chart(problemasCategoriaChartCtx, {
            type: 'bar',
            data: {
                labels: Object.keys({{ Illuminate\Support\Js::from($graficos['problemasPorCategoria']) }}),
                datasets: [{
                    label: 'Problemas',
                    data: Object.values({{ Illuminate\Support\Js::from($graficos['problemasPorCategoria']) }}),
                    backgroundColor: [
                        'rgba(99, 102, 241, 0.7)',    // Indigo
                        'rgba(245, 158, 11, 0.7)',    // Amber
                        'rgba(16, 185, 129, 0.7)',    // Emerald
                        'rgba(239, 68, 68, 0.7)',     // Red
                        'rgba(139, 92, 246, 0.7)',    // Violet
                        'rgba(14, 165, 233, 0.7)',    // Sky
                        'rgba(249, 115, 22, 0.7)',    // Orange
                        'rgba(236, 72, 153, 0.7)'     // Pink
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
