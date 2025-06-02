<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;
use App\Models\Edificio;
use App\Models\Apartamento;
use App\Models\Contrato;
use App\Models\SolicitudAlquiler;
use App\Models\ReporteProblema;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Muestra el dashboard principal adaptado al rol del usuario.
     */
    public function index()
    {
        $usuario = Auth::user();
        $esAdmin = $usuario->hasRole('admin');
        $esPropietario = $usuario->hasRole('propietario');

        // Si no es admin ni propietario, redirigir
        if (!$esAdmin && !$esPropietario) {
            return redirect()->route('menu')->with('error', 'No tienes permiso para acceder al dashboard');
        }

        // Datos comunes para ambos roles
        $data = [
            'usuario' => $usuario,
            'esAdmin' => $esAdmin,
            'esPropietario' => $esPropietario,
        ];

        // Cargar datos específicos según el rol
        if ($esAdmin) {
            $data = array_merge($data, $this->obtenerMetricasAdmin());
            $data['graficos'] = $this->obtenerGraficosAdmin();
            $data['listas'] = $this->obtenerListasAdmin();

            // Vista para administrador
            return view('dashboard.admin', $data);
        } else {
            $data = array_merge($data, $this->obtenerMetricasPropietario($usuario->id));
            $data['graficos'] = $this->obtenerGraficosPropietario($usuario->id);
            $data['listas'] = $this->obtenerListasPropietario($usuario->id);

            // Vista para propietario
            return view('dashboard.propietario', $data);
        }
    }

    /**
     * Obtiene las métricas básicas para el administrador.
     */
    private function obtenerMetricasAdmin()
    {
        return [
            'totalEdificios' => Edificio::count(),
            'totalApartamentos' => Apartamento::count(),
            'apartamentosDisponibles' => Apartamento::where('estado', 'Disponible')->count(),
            'apartamentosOcupados' => Apartamento::where('estado', 'Ocupado')->count(),
            'totalUsuarios' => Usuario::count(),
            'usuariosPorRol' => [
                'admin' => Usuario::whereHas('roles', function($query) {
                    $query->where('nombre', 'admin');
                })->count(),
                'propietario' => Usuario::whereHas('roles', function($query) {
                    $query->where('nombre', 'propietario');
                })->count(),
                'inquilino' => Usuario::whereHas('roles', function($query) {
                    $query->where('nombre', 'inquilino');
                })->count(),
                'posible_inquilino' => Usuario::whereHas('roles', function($query) {
                    $query->where('nombre', 'posible_inquilino');
                })->count(),
            ],
            'porcentajeOcupacion' => $this->calcularPorcentajeOcupacion(),
        ];
    }

    /**
     * Obtiene las métricas básicas para un propietario.
     */
    private function obtenerMetricasPropietario($usuarioId)
    {
        // En este caso, asumimos que todos los apartamentos pertenecen al propietario
        // ya que según tu comentario, todos los apartamentos son suyos
        $totalApts = Apartamento::count();
        $aptsDisponibles = Apartamento::where('estado', 'Disponible')->count();
        $aptsOcupados = Apartamento::where('estado', 'Ocupado')->count();

        // Contamos la cantidad de edificios únicos
        $totalEdificios = Edificio::count();

        return [
            'totalEdificios' => $totalEdificios,
            'totalApartamentos' => $totalApts,
            'apartamentosDisponibles' => $aptsDisponibles,
            'apartamentosOcupados' => $aptsOcupados,
            'porcentajeOcupacion' => $this->calcularPorcentajeOcupacion(), // Usamos el mismo que para admin
        ];
    }

    /**
     * Calcula el porcentaje de ocupación general.
     */
    private function calcularPorcentajeOcupacion()
    {
        $total = Apartamento::count();
        if ($total === 0) return 0;

        $ocupados = Apartamento::where('estado', 'Ocupado')->count();
        return round(($ocupados / $total) * 100);
    }

    /**
     * Obtiene datos para gráficos del administrador.
     */
    private function obtenerGraficosAdmin()
    {
        // Datos para gráfico de distribución de apartamentos
        $estadosApartamentos = [
            'Disponible' => Apartamento::where('estado', 'Disponible')->count(),
            'Ocupado' => Apartamento::where('estado', 'Ocupado')->count(),
            'En mantenimiento' => Apartamento::where('estado', 'En mantenimiento')->count(),
        ];

        // Datos para gráfico de contratos por mes (últimos 6 meses)
        $contratosPorMes = $this->obtenerContratosPorMes();

        // Datos para gráfico de problemas por categoría
        $problemasPorCategoria = $this->obtenerProblemasPorCategoria();

        return [
            'estadosApartamentos' => $estadosApartamentos,
            'contratosPorMes' => $contratosPorMes,
            'problemasPorCategoria' => $problemasPorCategoria,
        ];
    }

    /**
     * Obtiene datos para gráficos del propietario.
     */
    private function obtenerGraficosPropietario($usuarioId)
    {
        // Para el propietario, usamos todos los apartamentos ya que todos son suyos
        $estadosApartamentos = [
            'Disponible' => Apartamento::where('estado', 'Disponible')->count(),
            'Ocupado' => Apartamento::where('estado', 'Ocupado')->count(),
            'En mantenimiento' => Apartamento::where('estado', 'En mantenimiento')->count(),
        ];

        // Obtenemos todos los IDs de apartamentos
        $apartamentosIds = Apartamento::pluck('id')->toArray();

        // Datos para gráfico de pagos por estado
        $pagosPorEstado = $this->obtenerPagosPorEstado($apartamentosIds);

        // Datos para gráfico de problemas reportados
        $problemasPorMes = $this->obtenerProblemasPorMes($apartamentosIds);

        return [
            'estadosApartamentos' => $estadosApartamentos,
            'pagosPorEstado' => $pagosPorEstado,
            'problemasPorMes' => $problemasPorMes,
        ];
    }

    /**
     * Obtiene contratos por mes (últimos 6 meses).
     */
    private function obtenerContratosPorMes()
    {
        $resultado = [];
        $meses = [];

        // Generar los últimos 6 meses
        for ($i = 5; $i >= 0; $i--) {
            $fecha = now()->subMonths($i);
            $mes = $fecha->format('M'); // Abreviatura del mes
            $año = $fecha->format('Y');
            $meses[] = $mes . ' ' . $año;

            // Contar contratos de ese mes
            $conteo = Contrato::whereYear('fecha_inicio', $fecha->year)
                ->whereMonth('fecha_inicio', $fecha->month)
                ->count();

            $resultado[] = $conteo;
        }

        return [
            'meses' => $meses,
            'conteos' => $resultado,
        ];
    }

    /**
     * Obtiene problemas por categoría.
     */
    private function obtenerProblemasPorCategoria()
    {
        $categorias = ReporteProblema::select('tipo')
            ->distinct()
            ->whereNotNull('tipo')
            ->pluck('tipo')
            ->toArray();

        // Añadimos manualmente "Sin categoría" para los nulls
        $categorias[] = 'Sin categoría';

        $datos = [];

        foreach ($categorias as $categoria) {
            if ($categoria === 'Sin categoría') {
                $count = ReporteProblema::whereNull('tipo')->count();
            } else {
                $count = ReporteProblema::where('tipo', $categoria)->count();
            }

            if ($count > 0) {
                $datos[$categoria] = $count;
            }
        }

        return $datos;
    }

    /**
     * Obtiene pagos por estado para apartamentos específicos.
     */
    private function obtenerPagosPorEstado($apartamentosIds)
    {
        // Buscar contratos de estos apartamentos
        $contratosIds = Contrato::whereIn('apartamento_id', $apartamentosIds)->pluck('id')->toArray();

        // Si no hay contratos, devolvemos datos predeterminados
        if (count($contratosIds) == 0) {
            return [
                'pagado' => 0,
                'pendiente' => 0,
                'retrasado' => 0
            ];
        }

        // Contar estados de alquiler agrupados por estado_pago
        $pagos = DB::table('estado_alquiler')
            ->whereIn('contrato_id', $contratosIds)
            ->select('estado_pago', DB::raw('count(*) as total'))
            ->groupBy('estado_pago')
            ->pluck('total', 'estado_pago')
            ->toArray();

        // Asegurar que siempre tenemos las tres categorías, incluso si están en 0
        if (!isset($pagos['pagado'])) $pagos['pagado'] = 0;
        if (!isset($pagos['pendiente'])) $pagos['pendiente'] = 0;
        if (!isset($pagos['retrasado'])) $pagos['retrasado'] = 0;

        return $pagos;
    }

    /**
     * Obtiene problemas por mes para apartamentos específicos.
     */
    private function obtenerProblemasPorMes($apartamentosIds)
    {
        $resultado = [];
        $meses = [];

        // Generar los últimos 3 meses
        for ($i = 2; $i >= 0; $i--) {
            $fecha = now()->subMonths($i);
            $mes = $fecha->format('M'); // Abreviatura del mes
            $año = $fecha->format('Y');
            $meses[] = $mes . ' ' . $año;

            // Contar problemas de ese mes para esos apartamentos
            $conteo = ReporteProblema::whereIn('apartamento_id', $apartamentosIds)
                ->whereYear('fecha_reporte', $fecha->year)
                ->whereMonth('fecha_reporte', $fecha->month)
                ->count();

            $resultado[] = $conteo;
        }

        return [
            'meses' => $meses,
            'conteos' => $resultado,
        ];
    }

    /**
     * Obtiene listas de acción rápida para administrador.
     */
    private function obtenerListasAdmin()
    {
        return [
            'problemasCriticos' => ReporteProblema::where('estado', 'pendiente')
                ->orderBy('fecha_reporte', 'desc')
                ->limit(5)
                ->with(['apartamento', 'usuario'])
                ->get(),

            'solicitudesPendientes' => SolicitudAlquiler::where('estado_solicitud', 'pendiente')
                ->orderBy('fecha_solicitud', 'desc')
                ->limit(5)
                ->with(['apartamento', 'usuario'])
                ->get(),

            'usuariosRecientes' => Usuario::orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
        ];
    }

    /**
     * Obtiene listas de acción rápida para propietario.
     */
    private function obtenerListasPropietario($usuarioId)
    {
        // Obtenemos todos los IDs de apartamentos ya que todos son del propietario
        $apartamentosIds = Apartamento::pluck('id')->toArray();

        // Buscar contratos de estos apartamentos
        $contratosIds = Contrato::whereIn('apartamento_id', $apartamentosIds)->pluck('id')->toArray();

        return [
            'contratosPorVencer' => Contrato::whereIn('id', $contratosIds)
                ->where('fecha_fin', '>=', now())
                ->where('fecha_fin', '<=', now()->addDays(30))
                ->orderBy('fecha_fin')
                ->limit(5)
                ->with(['apartamento', 'usuario'])
                ->get(),

            'pagosPendientes' => DB::table('estado_alquiler')
                ->whereIn('contrato_id', $contratosIds)
                ->where('estado_pago', 'pendiente')
                ->orderBy('fecha_reporte', 'desc')
                ->limit(5)
                ->get(),

            'solicitudesRecientes' => SolicitudAlquiler::whereIn('apartamento_id', $apartamentosIds)
                ->orderBy('fecha_solicitud', 'desc')
                ->limit(5)
                ->with(['usuario'])
                ->get(),
        ];
    }
}
