<?php

namespace App\Http\Controllers;

use App\Models\Apartamento;
use App\Models\Contrato;
use App\Models\SolicitudAlquiler;
use App\Models\ReporteProblema;
use App\Models\Queja;
use App\Models\Evaluacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    /**
     * Muestra la página de inicio personalizada según el rol del usuario.
     */
    public function index()
    {
        $usuario = Auth::user();

        // Logs de depuración
        Log::info('HomeController: Usuario autenticado con ID: ' . ($usuario ? $usuario->id : 'null'));
        Log::info('HomeController: Correo: ' . ($usuario ? $usuario->correo : 'null'));

        // Si no hay usuario autenticado, redirigimos a login
        if (!$usuario) {
            Log::warning('HomeController: No hay usuario autenticado, redirigiendo a login');
            return redirect()->route('login');
        }

        try {
            Log::info('HomeController: Roles: ' . implode(', ', $usuario->roles()->pluck('nombre')->toArray()));
        } catch (\Exception $e) {
            Log::error('Error al obtener roles: ' . $e->getMessage());
        }

        // Verifica el rol del usuario y redirige según corresponda
        if ($usuario->hasRole(['admin', 'propietario'])) {
            Log::info('HomeController: Redirigiendo a dashboard');
            // Administradores y propietarios van al dashboard
            return redirect()->route('dashboard');
        }

        Log::info('HomeController: Continuando a la vista home.index');

        // Para inquilinos y posibles inquilinos
        $apartamentosDisponibles = Apartamento::where('estado', 'disponible')
            ->take(4)
            ->get();

        // Datos específicos para inquilinos (no aplicable a posibles inquilinos)
        $misContratos = collect([]);
        $misSolicitudes = collect([]);
        $misReportes = collect([]);
        $misQuejas = collect([]);
        $quejasRecientes = collect([]); // Todas las quejas recientes
        $contratosAnteriores = collect([]); // Contratos anteriores (inactivos)

        // NUEVO: Contratos pendientes de firma (para posibles inquilinos)
        $contratosPendientesFirma = collect([]);

        try {
            // Solicitudes de alquiler realizadas (disponible para ambos roles)
            $misSolicitudes = SolicitudAlquiler::where('usuario_id', $usuario->id)
                ->orderBy('fecha_solicitud', 'desc')
                ->take(3)
                ->get();

            // NUEVO: Obtener contratos pendientes de firma
            if ($usuario->hasRole('posible inquilino')) {
                $contratosPendientesFirma = Contrato::where('usuario_id', $usuario->id)
                    ->where('estado_firma', 'pendiente')
                    ->with('apartamento', 'apartamento.edificio')
                    ->get();
            }
        } catch (\Exception $e) {
            Log::error('Error al obtener solicitudes o contratos: ' . $e->getMessage());
        }

        if ($usuario->hasRole('inquilino')) {
            try {
                // Contratos activos del inquilino
                $misContratos = Contrato::where('usuario_id', $usuario->id)
                    ->where('estado', 'activo')
                    ->with('apartamento', 'apartamento.edificio', 'apartamento.evaluaciones')
                    ->get();

                // Contratos inactivos (anteriores) del inquilino
                $contratosAnteriores = Contrato::where('usuario_id', $usuario->id)
                    ->where('estado', 'inactivo')
                    ->with('apartamento', 'apartamento.edificio', 'apartamento.evaluaciones')
                    ->get();

                // Últimos 3 reportes realizados por el inquilino
                $misReportes = ReporteProblema::where('usuario_id', $usuario->id)
                    ->orderBy('fecha_reporte', 'desc')
                    ->take(3)
                    ->get();

                // Mis quejas recientes
                $misQuejas = Queja::where('usuario_id', $usuario->id)
                    ->orderBy('fecha_envio', 'desc')
                    ->take(3)
                    ->get();

                // Todas las quejas recientes (para inquilinos)
                $quejasRecientes = Queja::orderBy('fecha_envio', 'desc')
                    ->take(5)
                    ->get();

                // Para cada contrato, verificar si el usuario ya ha evaluado ese apartamento
                foreach ($misContratos as $contrato) {
                    $evaluacion = Evaluacion::where('usuario_id', $usuario->id)
                        ->where('apartamento_id', $contrato->apartamento_id)
                        ->first();
                    $contrato->ya_evaluado = !is_null($evaluacion);
                    $contrato->evaluacion = $evaluacion;
                }

                foreach ($contratosAnteriores as $contrato) {
                    $evaluacion = Evaluacion::where('usuario_id', $usuario->id)
                        ->where('apartamento_id', $contrato->apartamento_id)
                        ->first();
                    $contrato->ya_evaluado = !is_null($evaluacion);
                    $contrato->evaluacion = $evaluacion;
                }
            } catch (\Exception $e) {
                Log::error('Error al cargar datos de inquilino: ' . $e->getMessage());
            }
        }

        // Determinar si es inquilino o posible inquilino
        $esInquilino = $usuario->hasRole('inquilino');
        $esPosibleInquilino = $usuario->hasRole('posible inquilino');

        // Determinar si tiene uno o múltiples apartamentos activos
        $tieneMultiplesApartamentos = $misContratos->count() > 1;
        $tieneApartamentosAnteriores = $contratosAnteriores->count() > 0;

        // NUEVO: Verificar si hay contratos pendientes de firma
        $tieneContratosPendientesFirma = $contratosPendientesFirma->count() > 0;

        Log::info('HomeController: Renderizando vista home.index');

        return view('home.index', compact(
            'usuario',
            'apartamentosDisponibles',
            'misContratos',
            'misSolicitudes',
            'misReportes',
            'misQuejas',
            'quejasRecientes',
            'esInquilino',
            'esPosibleInquilino',
            'tieneMultiplesApartamentos',
            'tieneApartamentosAnteriores',
            'contratosAnteriores',
            'contratosPendientesFirma',
            'tieneContratosPendientesFirma'
        ));
    }
}
