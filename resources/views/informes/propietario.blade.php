<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Informe General del Propietario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        h1 {
            font-size: 20px;
            color: #333;
            margin-bottom: 5px;
        }
        .fecha {
            font-size: 12px;
            color: #666;
            margin-bottom: 20px;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .metric-container {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }
        .metric {
            width: 23%;
            margin: 0 1% 15px;
            padding: 10px;
            border: 1px solid #ddd;
            box-sizing: border-box;
        }
        .metric-title {
            font-size: 11px;
            color: #666;
        }
        .metric-value {
            font-size: 18px;
            font-weight: bold;
            margin: 5px 0;
        }
        .metric-subtitle {
            font-size: 10px;
            color: #999;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            text-align: center;
            font-size: 10px;
            color: #666;
            margin-top: 30px;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Informe General del Propietario</h1>
        <div class="fecha">Generado el: {{ $fecha_generacion }}</div>
        <div>Usuario: {{ $usuario->nombre }}</div>
    </div>

    <!-- Métricas Principales -->
    <div class="section">
        <h2 class="section-title">Resumen General</h2>
        <div class="metric-container">
            <div class="metric">
                <div class="metric-title">Mis Edificios</div>
                <div class="metric-value">{{ $totalEdificios }}</div>
            </div>
            <div class="metric">
                <div class="metric-title">Mis Apartamentos</div>
                <div class="metric-value">{{ $totalApartamentos }}</div>
                <div class="metric-subtitle">{{ $apartamentosDisponibles }} disponibles | {{ $apartamentosOcupados }} ocupados</div>
            </div>
            <div class="metric">
                <div class="metric-title">Ingresos Mensuales Est.</div>
                <div class="metric-value">${{ number_format($ingresosMensuales, 0) }}</div>
                <div class="metric-subtitle">Basado en apartamentos ocupados</div>
            </div>
            <div class="metric">
                <div class="metric-title">Porcentaje de Ocupación</div>
                <div class="metric-value">{{ $porcentajeOcupacion }}%</div>
            </div>
        </div>
    </div>

    <!-- Distribución de Apartamentos -->
    <div class="section">
        <h2 class="section-title">Distribución de Apartamentos</h2>
        <table>
            <tr>
                <th>Estado</th>
                <th>Cantidad</th>
                <th>Porcentaje</th>
            </tr>
            @foreach($estadosApartamentos as $estado => $cantidad)
                <tr>
                    <td>{{ $estado }}</td>
                    <td>{{ $cantidad }}</td>
                    <td>{{ $totalApartamentos > 0 ? round(($cantidad / $totalApartamentos) * 100, 1) : 0 }}%</td>
                </tr>
            @endforeach
        </table>
    </div>

    <!-- Estado de Pagos -->
    <div class="section">
        <h2 class="section-title">Estado de Pagos Actuales</h2>
        <table>
            <tr>
                <th>Estado</th>
                <th>Cantidad</th>
            </tr>
            @foreach($pagosPorEstado as $estado => $cantidad)
                <tr>
                    <td>{{ ucfirst($estado) }}</td>
                    <td>{{ $cantidad }}</td>
                </tr>
            @endforeach
        </table>
    </div>

    <div class="page-break"></div>

    <!-- Contratos Próximos a Vencer -->
    <div class="section">
        <h2 class="section-title">Contratos Próximos a Vencer</h2>
        @if(count($contratosPorVencer) > 0)
            <table>
                <tr>
                    <th>Apartamento</th>
                    <th>Inquilino</th>
                    <th>Vence</th>
                </tr>
                @foreach($contratosPorVencer as $contrato)
                    <tr>
                        <td>{{ $contrato->apartamento->numero_apartamento }}</td>
                        <td>{{ $contrato->usuario->nombre }}</td>
                        <td>{{ $contrato->fecha_fin->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
            </table>
        @else
            <p>No hay contratos próximos a vencer</p>
        @endif
    </div>

    <!-- Pagos Pendientes -->
    <div class="section">
        <h2 class="section-title">Pagos Pendientes</h2>
        @if(count($pagosPendientes) > 0)
            <table>
                <tr>
                    <th>Apartamento</th>
                    <th>Inquilino</th>
                    <th>Fecha</th>
                </tr>
                @foreach($pagosPendientes as $pago)
                    <tr>
                        <td>{{ $pago->contrato->apartamento->numero_apartamento }}</td>
                        <td>{{ $pago->contrato->usuario->nombre }}</td>
                        <td>{{ $pago->fecha_reporte->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
            </table>
        @else
            <p>No hay pagos pendientes</p>
        @endif
    </div>

    <!-- Últimas Solicitudes de Alquiler -->
    <div class="section">
        <h2 class="section-title">Últimas Solicitudes de Alquiler</h2>
        @if(count($solicitudesRecientes) > 0)
            <table>
                <tr>
                    <th>Apartamento</th>
                    <th>Solicitante</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                </tr>
                @foreach($solicitudesRecientes->take(10) as $solicitud)
                    <tr>
                        <td>{{ $solicitud->apartamento->numero_apartamento }}</td>
                        <td>{{ $solicitud->usuario->nombre }}</td>
                        <td>{{ ucfirst($solicitud->estado_solicitud) }}</td>
                        <td>{{ $solicitud->fecha_solicitud->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
            </table>
        @else
            <p>No hay solicitudes recientes</p>
        @endif
    </div>

    <div class="footer">
        © {{ date('Y') }} Sistema de Gestión de Apartamentos - Todos los derechos reservados
    </div>
</body>
</html>
