<?php

namespace App\Http\Controllers\Propietario;

use App\Http\Controllers\Controller;
use App\Models\EstadoAlquiler;
use App\Models\Contrato;
use App\Models\Apartamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EstadoAlquilerController extends Controller
{
    /**
     * Constructor para verificar que el usuario sea propietario
     */
    public function __construct()
    {
        $this->middleware('role:propietario');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pisofiltro = $request->piso;

        // Obtener todos los contratos activos (o filtrados por piso)
        $contratosQuery = Contrato::where('estado', 'activo')
                          ->with(['usuario', 'apartamento.edificio', 'estadosAlquiler' => function ($query) {
                              $query->orderBy('fecha_reporte', 'desc');
                          }]);

        // Si se filtra por piso, primero obtenemos los apartamentos
        if ($request->has('piso') && $request->piso != 'todos') {
            $apartamentos = Apartamento::where('piso', $request->piso)->pluck('id')->toArray();
            $contratosQuery->whereIn('apartamento_id', $apartamentos);
        }

        $contratos = $contratosQuery->get();

        // Obtener pisos disponibles para el filtro
        $pisos = Apartamento::distinct('piso')
                ->orderBy('piso')
                ->pluck('piso')
                ->toArray();

        // Preparar estadísticas para todos los contratos
        $estadisticasAlquiler = [];

        foreach ($contratos as $contrato) {
            $ultimoEstado = $contrato->estadosAlquiler->first(); // Ya está ordenado por fecha desc

            // Normalizar el estado a minúsculas para comparaciones consistentes
            $estadoPago = $ultimoEstado ? strtolower($ultimoEstado->estado_pago) : 'pendiente';

            $estadisticasAlquiler[] = [
                'contrato_id' => $contrato->id,
                'usuario_id' => $contrato->usuario_id,
                'usuario_nombre' => $contrato->usuario->nombre,
                'apartamento_id' => $contrato->apartamento_id,
                'apartamento' => $contrato->apartamento->numero_apartamento,
                'edificio' => $contrato->apartamento->edificio->nombre,
                'piso' => $contrato->apartamento->piso,
                'ultimo_estado' => $estadoPago, // Guardamos en minúsculas
                'fecha_ultimo_estado' => $ultimoEstado ? $ultimoEstado->fecha_reporte->format('d/m/Y') : 'N/A',
                'total_estados' => $contrato->estadosAlquiler->count(),
            ];
        }

        // Ordenar por edificio, piso y apartamento
        $estadisticas = collect($estadisticasAlquiler)->sortBy([
            ['edificio', 'asc'],
            ['piso', 'asc'],
            ['apartamento', 'asc'],
        ])->all();

        return view('propietario.estados-alquiler.index', compact('estadisticas', 'pisos', 'pisofiltro'));
    }

    /**
     * Ver historial de estados por contrato
     */
    public function porContrato($contratoId)
    {
        $contrato = Contrato::with(['usuario', 'apartamento.edificio', 'estadosAlquiler' => function ($query) {
            $query->orderBy('fecha_reporte', 'desc');
        }])->findOrFail($contratoId);

        $estados = $contrato->estadosAlquiler;

        return view('propietario.estados-alquiler.por-contrato',
            compact('contrato', 'estados'));
    }

    /**
     * Actualizar estado de alquiler
     */
    public function actualizarEstado(Request $request, $contratoId)
    {
        $request->validate([
            'estado_pago' => 'required|in:pendiente,pagado,atrasado',
        ]);

        $contrato = Contrato::findOrFail($contratoId);

        // Asegurar que siempre se guarde en minúsculas
        $estadoPago = strtolower($request->estado_pago);

        // Crear nuevo estado de alquiler
        $estadoAlquiler = new EstadoAlquiler([
            'contrato_id' => $contratoId,
            'usuario_id' => Auth::id(),
            'estado_pago' => $estadoPago, // Siempre en minúsculas
            'fecha_reporte' => now()->format('Y-m-d'),
        ]);

        $estadoAlquiler->save();

        return redirect()->back()->with('success', 'Estado de alquiler actualizado correctamente a ' . ucfirst($estadoPago));
    }
}
