<?php

namespace App\Http\Controllers\Propietario;

use App\Http\Controllers\Controller;
use App\Models\Evaluacion;
use App\Models\Apartamento;
use App\Models\Edificio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EvaluacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $usuario = Auth::user();
        $pisofiltro = $request->piso;

        // Obtener todos los apartamentos (o filtrados por piso)
        $apartamentosQuery = Apartamento::with(['edificio', 'evaluaciones.usuario']);

        // Aplicar filtro por piso si se solicita
        if ($request->has('piso') && $request->piso != 'todos') {
            $apartamentosQuery->where('piso', $request->piso);
        }

        $apartamentos = $apartamentosQuery->get();

        // Obtener pisos disponibles para el filtro
        $pisos = Apartamento::distinct('piso')
                ->orderBy('piso')
                ->pluck('piso')
                ->toArray();

        // Preparar estadísticas para todos los apartamentos, tengan o no evaluaciones
        $estadisticasPorPiso = [];

        foreach ($apartamentos as $apartamento) {
            if ($apartamento->edificio) {
                $evalsApartamento = $apartamento->evaluaciones;
                $promedio = $evalsApartamento->avg('calificacion') ?: 0;
                $totalEvals = $evalsApartamento->count();

                $piso = $apartamento->piso;

                $estadisticasPorPiso[$apartamento->id] = [
                    'id' => $apartamento->id,
                    'apartamento' => $apartamento->numero_apartamento,
                    'piso' => $piso,
                    'edificio' => $apartamento->edificio->nombre,
                    'promedio' => number_format($promedio, 1),
                    'total' => $totalEvals
                ];
            }
        }

        // Ordenar por piso y luego por número de apartamento
        $estadisticas = collect($estadisticasPorPiso)->sortBy([
            ['piso', 'asc'],
            ['apartamento', 'asc'],
        ])->all();

        return view('propietario.evaluaciones.index', compact('estadisticas', 'pisos', 'pisofiltro'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $evaluacion = Evaluacion::with(['usuario', 'apartamento.edificio'])->findOrFail($id);

        return view('propietario.evaluaciones.show', compact('evaluacion'));
    }

    /**
     * Ver evaluaciones por apartamento
     */
    public function porApartamento($apartamentoId)
    {
        $apartamento = Apartamento::with('edificio')->findOrFail($apartamentoId);
        $evaluaciones = Evaluacion::where('apartamento_id', $apartamentoId)
                          ->with('usuario')
                          ->latest('fecha_evaluacion')
                          ->get();

        $promedioCalificacion = $evaluaciones->avg('calificacion') ?: 0;

        return view('propietario.evaluaciones.por-apartamento',
            compact('apartamento', 'evaluaciones', 'promedioCalificacion'));
    }
}
