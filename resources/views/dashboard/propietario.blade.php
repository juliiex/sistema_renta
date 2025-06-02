@extends('layouts.dashboard-sidebar')

@section('title', 'Dashboard Propietario')

@section('dashboard-content')
<!-- Top Row: Métricas -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <!-- Total de Edificios -->
    <div class="bg-white p-4 rounded-lg shadow dashboard-card">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-gray-500 text-sm">Mis Edificios</p>
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
                <p class="text-gray-500 text-sm">Mis Apartamentos</p>
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

    <!-- Ingresos -->
    <div class="bg-white p-4 rounded-lg shadow dashboard-card">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-gray-500 text-sm">Ingresos Mensuales Est.</p>
                <h3 class="text-2xl font-bold mt-1">${{ number_format($apartamentosOcupados * 1000, 0) }}</h3>
                <div class="mt-2 text-xs text-gray-500">Basado en apartamentos ocupados</div>
            </div>
            <div class="bg-emerald-100 p-2 rounded-md">
                <i class="fas fa-dollar-sign text-emerald-500 text-lg"></i>
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

    <!-- Gráfico 2: Estado de Pagos Actuales -->
    <div class="bg-white p-4 rounded-lg shadow">
        <h3 class="text-gray-700 font-semibold mb-4">Estado de Pagos Actuales</h3>
        <div class="chart-container">
            <canvas id="pagosEstadoChart"></canvas>
        </div>
    </div>

    <!-- Gráfico 3: Problemas Reportados (3 meses) -->
    <div class="bg-white p-4 rounded-lg shadow">
        <h3 class="text-gray-700 font-semibold mb-4">Problemas Reportados (3 meses)</h3>
        <div class="chart-container">
            <canvas id="problemasMesChart"></canvas>
        </div>
    </div>
</div>

<!-- Bottom Row: Listas de Acción -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
    <!-- Lista 1: Contratos Próximos a Vencer -->
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-gray-700 font-semibold">Contratos Próximos a Vencer</h3>
            <a href="{{ route('contrato.index') }}" class="text-blue-600 hover:underline text-sm">Ver todos</a>
        </div>

        @if(count($listas['contratosPorVencer']) > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                        <tr>
                            <th class="px-4 py-2">Apto.</th>
                            <th class="px-4 py-2">Inquilino</th>
                            <th class="px-4 py-2">Vence</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($listas['contratosPorVencer'] as $contrato)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-2">{{ $contrato->apartamento->numero_apartamento }}</td>
                                <td class="px-4 py-2">{{ $contrato->usuario->nombre }}</td>
                                <td class="px-4 py-2">{{ $contrato->fecha_fin->format('d/m/Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 text-center py-4">No hay contratos próximos a vencer</p>
        @endif
    </div>

    <!-- Lista 2: Pagos Pendientes -->
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-gray-700 font-semibold">Pagos Pendientes</h3>
            <a href="{{ route('estado_alquiler.index') }}" class="text-blue-600 hover:underline text-sm">Ver todos</a>
        </div>

        @if(count($listas['pagosPendientes']) > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                        <tr>
                            <th class="px-4 py-2">Contrato ID</th>
                            <th class="px-4 py-2">Estado</th>
                            <th class="px-4 py-2">Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($listas['pagosPendientes'] as $pago)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-2">{{ $pago->contrato_id }}</td>
                                <td class="px-4 py-2">
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Pendiente</span>
                                </td>
                                <td class="px-4 py-2">{{ date('d/m/Y', strtotime($pago->fecha_reporte)) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 text-center py-4">No hay pagos pendientes</p>
        @endif
    </div>

    <!-- Lista 3: Últimas Solicitudes de Alquiler -->
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-gray-700 font-semibold">Últimas Solicitudes de Alquiler</h3>
            <a href="{{ route('solicitudes.index') }}" class="text-blue-600 hover:underline text-sm">Ver todas</a>
        </div>

        @if(count($listas['solicitudesRecientes']) > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                        <tr>
                            <th class="px-4 py-2">Solicitante</th>
                            <th class="px-4 py-2">Estado</th>
                            <th class="px-4 py-2">Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($listas['solicitudesRecientes'] as $solicitud)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-2">{{ $solicitud->usuario->nombre }}</td>
                                <td class="px-4 py-2">
                                    @if($solicitud->estado_solicitud == 'pendiente')
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Pendiente</span>
                                    @elseif($solicitud->estado_solicitud == 'aprobada')
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Aprobada</span>
                                    @else
                                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Rechazada</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2">{{ $solicitud->fecha_solicitud->format('d/m/Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 text-center py-4">No hay solicitudes recientes</p>
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

        // Gráfico de Pagos por Estado
        var pagosEstadoChartCtx = document.getElementById('pagosEstadoChart').getContext('2d');
        var pagosEstadoChart = new Chart(pagosEstadoChartCtx, {
            type: 'pie',
            data: {
                labels: Object.keys({{ Illuminate\Support\Js::from($graficos['pagosPorEstado']) }}),
                datasets: [{
                    data: Object.values({{ Illuminate\Support\Js::from($graficos['pagosPorEstado']) }}),
                    backgroundColor: [
                        'rgba(34, 197, 94, 0.7)',  // pagado
                        'rgba(234, 179, 8, 0.7)',  // pendiente
                        'rgba(239, 68, 68, 0.7)',  // retrasado
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });

        // Gráfico de Problemas por Mes
        var problemasMesChartCtx = document.getElementById('problemasMesChart').getContext('2d');
        var problemasMesChart = new Chart(problemasMesChartCtx, {
            type: 'bar',
            data: {
                labels: {{ Illuminate\Support\Js::from($graficos['problemasPorMes']['meses']) }},
                datasets: [{
                    label: 'Problemas Reportados',
                    data: {{ Illuminate\Support\Js::from($graficos['problemasPorMes']['conteos']) }},
                    backgroundColor: 'rgba(249, 115, 22, 0.7)',
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
