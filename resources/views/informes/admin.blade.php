<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Informe General del Administrador</title>
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
        <h1>Informe General del Administrador</h1>
        <div class="fecha">Generado el: {{ $fecha_generacion }}</div>
        <div>Usuario: {{ $usuario->nombre }}</div>
    </div>

    <!-- Métricas Principales -->
    <div class="section">
        <h2 class="section-title">Resumen General</h2>
        <div class="metric-container">
            <div class="metric">
                <div class="metric-title">Total de Edificios</div>
                <div class="metric-value">{{ $totalEdificios }}</div>
            </div>
            <div class="metric">
                <div class="metric-title">Total de Apartamentos</div>
                <div class="metric-value">{{ $totalApartamentos }}</div>
                <div class="metric-subtitle">{{ $apartamentosDisponibles }} disponibles | {{ $apartamentosOcupados }} ocupados</div>
            </div>
            <div class="metric">
                <div class="metric-title">Total de Usuarios</div>
                <div class="metric-value">{{ $totalUsuarios }}</div>
                <div class="metric-subtitle">
                    {{ $usuariosPorRol['admin'] ?? 0 }} admin |
                    {{ $usuariosPorRol['propietario'] ?? 0 }} propietarios |
                    {{ $usuariosPorRol['inquilino'] ?? 0 }} inquilinos
                </div>
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

    <!-- Contratos por Mes -->
    <div class="section">
        <h2 class="section-title">Contratos por Mes (Últimos 6 meses)</h2>
        <table>
            <tr>
                <th>Mes</th>
                <th>Cantidad de Contratos</th>
            </tr>
            @foreach($contratosPorMes['meses'] as $index => $mes)
                <tr>
                    <td>{{ $mes }}</td>
                    <td>{{ $contratosPorMes['conteos'][$index] }}</td>
                </tr>
            @endforeach
        </table>
    </div>

    <div class="page-break"></div>

    <!-- Problemas por Categoría -->
    <div class="section">
        <h2 class="section-title">Problemas por Categoría</h2>
        <table>
            <tr>
                <th>Categoría</th>
                <th>Cantidad</th>
            </tr>
            @foreach($problemasPorCategoria as $categoria => $cantidad)
                <tr>
                    <td>{{ $categoria }}</td>
                    <td>{{ $cantidad }}</td>
                </tr>
            @endforeach
        </table>
    </div>

    <!-- Problemas Críticos -->
    <div class="section">
        <h2 class="section-title">Problemas Críticos Pendientes</h2>
        @if(count($problemasCriticos) > 0)
            <table>
                <tr>
                    <th>Apartamento</th>
                    <th>Usuario</th>
                    <th>Problema</th>
                    <th>Fecha</th>
                </tr>
                @foreach($problemasCriticos->take(10) as $problema)
                    <tr>
                        <td>{{ $problema->apartamento->numero_apartamento }}</td>
                        <td>{{ $problema->usuario->nombre }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($problema->descripcion, 50) }}</td>
                        <td>{{ $problema->fecha_reporte->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
            </table>
        @else
            <p>No hay problemas críticos pendientes</p>
        @endif
    </div>

    <!-- Solicitudes Pendientes -->
    <div class="section">
        <h2 class="section-title">Solicitudes Pendientes</h2>
        @if(count($solicitudesPendientes) > 0)
            <table>
                <tr>
                    <th>Apartamento</th>
                    <th>Solicitante</th>
                    <th>Fecha</th>
                </tr>
                @foreach($solicitudesPendientes->take(10) as $solicitud)
                    <tr>
                        <td>{{ $solicitud->apartamento->numero_apartamento }}</td>
                        <td>{{ $solicitud->usuario->nombre }}</td>
                        <td>{{ $solicitud->fecha_solicitud->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
            </table>
        @else
            <p>No hay solicitudes pendientes</p>
        @endif
    </div>

    <div class="footer">
        © {{ date('Y') }} Sistema de Gestión de Apartamentos - Todos los derechos reservados
    </div>
</body>
</html>
