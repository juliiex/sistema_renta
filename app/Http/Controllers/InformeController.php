<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Edificio;
use App\Models\Apartamento;
use App\Models\Contrato;
use App\Models\EstadoAlquiler;
use App\Models\SolicitudAlquiler;
use App\Models\ReporteProblema;
use App\Models\Usuario; // ¡Añadida esta línea!
use Illuminate\Support\Facades\DB;

class InformeController extends Controller
{
    /**
     * Constructor para verificar permisos
     */
    public function __construct()
    {
        $this->middleware('role:admin|propietario');
    }

    /**
     * Generar informe general para propietario o admin
     */
    public function generarInforme()
    {
        $usuario = Auth::user();
        $esPropietario = $usuario->hasRole('propietario');
        $esAdmin = $usuario->hasRole('admin');

        if ($esPropietario) {
            $data = $this->obtenerDatosPropietario();
            $view = 'informes.propietario';
            $titulo = 'Informe General del Propietario';
        } else {
            $data = $this->obtenerDatosAdmin();
            $view = 'informes.admin';
            $titulo = 'Informe General del Administrador';
        }

        $data['fecha_generacion'] = now()->format('d/m/Y H:i');
        $data['usuario'] = $usuario;

        $pdf = PDF::loadView($view, $data);

        return $pdf->download($titulo . '.pdf');
    }

    /**
     * Obtener datos para el informe del propietario
     */
    private function obtenerDatosPropietario()
    {
        // Obtener apartamentos
        $apartamentos = Apartamento::all();
        $apartamentosDisponibles = $apartamentos->where('estado', 'Disponible');
        $apartamentosOcupados = $apartamentos->where('estado', 'Ocupado');

        // Calcular ingresos mensuales sumando los precios reales de apartamentos ocupados
        $ingresosMensuales = $apartamentosOcupados->sum('precio');

        // Contamos la cantidad de edificios únicos
        $totalEdificios = Edificio::count();

        // Porcentaje de ocupación
        $porcentajeOcupacion = ($apartamentos->count() > 0)
            ? round(($apartamentosOcupados->count() / $apartamentos->count()) * 100)
            : 0;

        // Estados de apartamentos para gráfico
        $estadosApartamentos = [
            'Disponible' => $apartamentosDisponibles->count(),
            'Ocupado' => $apartamentosOcupados->count(),
            'En mantenimiento' => Apartamento::where('estado', 'En mantenimiento')->count(),
        ];

        // Obtener todos los IDs de apartamentos
        $apartamentosIds = $apartamentos->pluck('id')->toArray();

        // Buscar contratos de estos apartamentos
        $contratosIds = Contrato::whereIn('apartamento_id', $apartamentosIds)->pluck('id')->toArray();
        $contratos = Contrato::whereIn('id', $contratosIds)->with(['apartamento', 'usuario'])->get();

        // Estados de pagos
        $pagosPorEstado = $this->obtenerPagosPorEstado($contratosIds);

        // Problemas reportados por mes
        $problemasPorMes = $this->obtenerProblemasPorMes($apartamentosIds);

        // Contratos por vencer
        $contratosPorVencer = Contrato::whereIn('id', $contratosIds)
            ->where('fecha_fin', '>=', now())
            ->where('fecha_fin', '<=', now()->addDays(30))
            ->orderBy('fecha_fin')
            ->with(['apartamento', 'usuario'])
            ->get();

        // Pagos pendientes
        $pagosPendientes = EstadoAlquiler::whereIn('contrato_id', $contratosIds)
            ->where('estado_pago', 'pendiente')
            ->orderBy('fecha_reporte', 'desc')
            ->with(['contrato.apartamento', 'contrato.usuario'])
            ->get();

        // Solicitudes recientes
        $solicitudesRecientes = SolicitudAlquiler::whereIn('apartamento_id', $apartamentosIds)
            ->orderBy('fecha_solicitud', 'desc')
            ->with(['usuario', 'apartamento'])
            ->get();

        return [
            'totalEdificios' => $totalEdificios,
            'totalApartamentos' => $apartamentos->count(),
            'apartamentosDisponibles' => $apartamentosDisponibles->count(),
            'apartamentosOcupados' => $apartamentosOcupados->count(),
            'ingresosMensuales' => $ingresosMensuales,
            'porcentajeOcupacion' => $porcentajeOcupacion,
            'estadosApartamentos' => $estadosApartamentos,
            'pagosPorEstado' => $pagosPorEstado,
            'problemasPorMes' => $problemasPorMes,
            'contratosPorVencer' => $contratosPorVencer,
            'pagosPendientes' => $pagosPendientes,
            'solicitudesRecientes' => $solicitudesRecientes,
            'contratos' => $contratos
        ];
    }

    /**
     * Obtener datos para el informe del administrador
     */
    private function obtenerDatosAdmin()
    {
        // Datos básicos
        $totalEdificios = Edificio::count();
        $totalApartamentos = Apartamento::count();
        $apartamentosDisponibles = Apartamento::where('estado', 'Disponible')->count();
        $apartamentosOcupados = Apartamento::where('estado', 'Ocupado')->count();
        $totalUsuarios = Usuario::count();

        // Usuarios por rol
        $usuariosPorRol = [
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
                $query->where('nombre', 'posible inquilino');
            })->count(),
        ];

        // Porcentaje de ocupación
        $porcentajeOcupacion = ($totalApartamentos > 0)
            ? round(($apartamentosOcupados / $totalApartamentos) * 100)
            : 0;

        // Gráficos y listas
        $estadosApartamentos = [
            'Disponible' => $apartamentosDisponibles,
            'Ocupado' => $apartamentosOcupados,
            'En mantenimiento' => Apartamento::where('estado', 'En mantenimiento')->count(),
        ];

        $contratosPorMes = $this->obtenerContratosPorMes();
        $problemasPorCategoria = $this->obtenerProblemasPorCategoria();

        $problemasCriticos = ReporteProblema::where('estado', 'pendiente')
            ->orderBy('fecha_reporte', 'desc')
            ->with(['apartamento', 'usuario'])
            ->get();

        $solicitudesPendientes = SolicitudAlquiler::where('estado_solicitud', 'pendiente')
            ->orderBy('fecha_solicitud', 'desc')
            ->with(['apartamento', 'usuario'])
            ->get();

        $usuariosRecientes = Usuario::orderBy('created_at', 'desc')
            ->get();

        return [
            'totalEdificios' => $totalEdificios,
            'totalApartamentos' => $totalApartamentos,
            'apartamentosDisponibles' => $apartamentosDisponibles,
            'apartamentosOcupados' => $apartamentosOcupados,
            'totalUsuarios' => $totalUsuarios,
            'usuariosPorRol' => $usuariosPorRol,
            'porcentajeOcupacion' => $porcentajeOcupacion,
            'estadosApartamentos' => $estadosApartamentos,
            'contratosPorMes' => $contratosPorMes,
            'problemasPorCategoria' => $problemasPorCategoria,
            'problemasCriticos' => $problemasCriticos,
            'solicitudesPendientes' => $solicitudesPendientes,
            'usuariosRecientes' => $usuariosRecientes
        ];
    }

    /**
     * Obtiene pagos por estado para apartamentos específicos.
     */
    private function obtenerPagosPorEstado($contratosIds)
    {
        // Si no hay contratos, devolvemos datos predeterminados
        if (count($contratosIds) == 0) {
            return [
                'pagado' => 0,
                'pendiente' => 0,
                'atrasado' => 0
            ];
        }

        // Contar estados de alquiler agrupados por estado_pago
        $pagos = DB::table('estado_alquiler')
            ->whereIn('contrato_id', $contratosIds)
            ->select(DB::raw('LOWER(estado_pago) as estado_pago'), DB::raw('count(*) as total'))
            ->groupBy(DB::raw('LOWER(estado_pago)'))
            ->pluck('total', 'estado_pago')
            ->toArray();

        // Asegurar que siempre tenemos las tres categorías, incluso si están en 0
        if (!isset($pagos['pagado'])) $pagos['pagado'] = 0;
        if (!isset($pagos['pendiente'])) $pagos['pendiente'] = 0;
        if (!isset($pagos['atrasado'])) $pagos['atrasado'] = 0;

        return $pagos;
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
}
