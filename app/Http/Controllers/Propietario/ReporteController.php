<?php

namespace App\Http\Controllers\Propietario;

use App\Http\Controllers\Controller;
use App\Models\ReporteProblema;
use App\Models\Apartamento;
use App\Models\Contrato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    /**
     * Constructor para verificar que el usuario sea propietario
     */
    public function __construct()
    {
        $this->middleware('role:propietario');
    }

    /**
     * Mostrar lista de reportes con filtros
     */
    public function index(Request $request)
    {
        $pisofiltro = $request->piso ?? 'todos';
        $estadofiltro = $request->estado ?? 'todos';

        // Obtener todos los reportes (o filtrados por piso y estado)
        $reportesQuery = ReporteProblema::with(['apartamento.edificio', 'usuario']);

        // Si se filtra por piso, primero obtenemos los apartamentos
        if ($request->has('piso') && $request->piso != 'todos') {
            $apartamentos = Apartamento::where('piso', $request->piso)->pluck('id')->toArray();
            $reportesQuery->whereIn('apartamento_id', $apartamentos);
        }

        // Si se filtra por estado
        if ($request->has('estado') && $request->estado != 'todos') {
            $reportesQuery->where('estado', $request->estado);
        }

        // Ordenar por fecha de reporte (más recientes primero)
        $reportes = $reportesQuery->orderBy('fecha_reporte', 'desc')->get();

        // Obtener pisos disponibles para el filtro
        $pisos = Apartamento::distinct('piso')
                ->orderBy('piso')
                ->pluck('piso')
                ->toArray();

        return view('propietario.reportes.index', compact('reportes', 'pisos', 'pisofiltro', 'estadofiltro'));
    }

    /**
     * Mostrar un reporte específico
     */
    public function show($id)
    {
        $reporte = ReporteProblema::with(['apartamento.edificio', 'usuario'])
                    ->findOrFail($id);

        return view('propietario.reportes.show', compact('reporte'));
    }

    /**
     * Mostrar reportes por apartamento
     */
    public function porApartamento($apartamentoId)
    {
        $apartamento = Apartamento::with('edificio')->findOrFail($apartamentoId);

        // Obtener el inquilino actual si existe
        $inquilino = Contrato::where('apartamento_id', $apartamentoId)
                    ->where('estado', 'activo')
                    ->with('usuario')
                    ->first()?->usuario;

        // Obtener todos los reportes de este apartamento
        $reportes = ReporteProblema::where('apartamento_id', $apartamentoId)
                    ->orderBy('fecha_reporte', 'desc')
                    ->get();

        return view('propietario.reportes.por-apartamento',
            compact('apartamento', 'inquilino', 'reportes'));
    }

    /**
     * Actualizar el estado de un reporte
     */
    public function actualizarEstado(Request $request, $reporteId)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,atendido,cerrado',
        ]);

        $reporte = ReporteProblema::findOrFail($reporteId);

        // Actualizar estado
        $reporte->estado = strtolower($request->estado);
        $reporte->save();

        return redirect()->back()->with('success', 'Estado del reporte actualizado correctamente.');
    }
}
