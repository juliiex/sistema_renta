<?php

namespace App\Http\Controllers\Propietario;

use App\Http\Controllers\Controller;
use App\Models\Contrato;
use App\Models\Apartamento;
use App\Models\Edificio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContratoController extends Controller
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
        $usuario = Auth::user();
        $pisofiltro = $request->piso;

        // Obtener todos los apartamentos (o filtrados por piso)
        $apartamentosQuery = Apartamento::with(['edificio', 'contratos.usuario']);

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

        // Preparar estadísticas para todos los apartamentos
        $estadisticasPorApartamento = [];

        foreach ($apartamentos as $apartamento) {
            if ($apartamento->edificio) {
                $contratos = $apartamento->contratos;
                $contratosActivos = $contratos->where('estado', 'activo')->count();
                $contratosInactivos = $contratos->where('estado', 'inactivo')->count();
                $totalContratos = $contratos->count();

                // Buscar el contrato activo si existe
                $contratoActivo = $contratos->where('estado', 'activo')->first();

                // Determinar estado real del apartamento
                // Si tiene contrato activo o estado explícitamente está marcado como ocupado
                $estadoApartamento = $contratosActivos > 0 || strtolower($apartamento->estado) == 'ocupado'
                    ? 'ocupado'
                    : 'disponible';

                $piso = $apartamento->piso;

                $estadisticasPorApartamento[$apartamento->id] = [
                    'id' => $apartamento->id,
                    'apartamento' => $apartamento->numero_apartamento,
                    'piso' => $piso,
                    'edificio' => $apartamento->edificio->nombre,
                    'contratos_activos' => $contratosActivos,
                    'contratos_inactivos' => $contratosInactivos,
                    'total_contratos' => $totalContratos,
                    'inquilino_actual' => $contratoActivo ? $contratoActivo->usuario->nombre : 'Sin inquilino',
                    'estado' => $estadoApartamento // Usamos el estado calculado
                ];
            }
        }

        // Ordenar por piso y luego por número de apartamento
        $estadisticas = collect($estadisticasPorApartamento)->sortBy([
            ['piso', 'asc'],
            ['apartamento', 'asc'],
        ])->all();

        return view('propietario.contratos.index', compact('estadisticas', 'pisos', 'pisofiltro'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $contrato = Contrato::with(['usuario', 'apartamento.edificio'])->findOrFail($id);

        return view('propietario.contratos.show', compact('contrato'));
    }

    /**
     * Ver contratos por apartamento
     */
    public function porApartamento($apartamentoId)
    {
        $apartamento = Apartamento::with('edificio')->findOrFail($apartamentoId);

        // Contrato activo
        $contratoActivo = Contrato::where('apartamento_id', $apartamentoId)
                          ->where('estado', 'activo')
                          ->with('usuario')
                          ->first();

        // Contratos anteriores (inactivos)
        $contratosAnteriores = Contrato::where('apartamento_id', $apartamentoId)
                              ->where('estado', 'inactivo')
                              ->with('usuario')
                              ->orderBy('fecha_fin', 'desc')
                              ->get();

        $tieneContratoActivo = !is_null($contratoActivo);
        $tieneContratosAnteriores = $contratosAnteriores->count() > 0;

        return view('propietario.contratos.por-apartamento',
            compact('apartamento', 'contratoActivo', 'contratosAnteriores', 'tieneContratoActivo', 'tieneContratosAnteriores'));
    }
}
