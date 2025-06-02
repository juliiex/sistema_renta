<?php

namespace App\Http\Controllers\Propietario;

use App\Http\Controllers\Controller;
use App\Models\RecordatorioPago;
use App\Models\Usuario;
use App\Models\Contrato;
use App\Models\Apartamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RecordatorioController extends Controller
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
        // Obtener los contratos activos de los apartamentos
        $contratos = Contrato::where('estado', 'activo')
                      ->with(['usuario', 'apartamento.edificio'])
                      ->get();

        // Obtener los recordatorios de pago más recientes para cada usuario
        $recordatorios = [];
        foreach ($contratos as $contrato) {
            $usuario = $contrato->usuario;
            $recordatorioPago = RecordatorioPago::where('usuario_id', $usuario->id)
                                  ->orderBy('fecha_envio', 'desc')
                                  ->first();

            $recordatorios[] = [
                'contrato_id' => $contrato->id,
                'usuario_id' => $usuario->id,
                'usuario_nombre' => $usuario->nombre,
                'apartamento' => $contrato->apartamento->numero_apartamento,
                'edificio' => $contrato->apartamento->edificio->nombre,
                'piso' => $contrato->apartamento->piso,
                'ultimo_recordatorio' => $recordatorioPago ? $recordatorioPago->fecha_envio : null,
                'metodo' => $recordatorioPago ? $recordatorioPago->metodo : 'No enviado',
            ];
        }

        // Ordenar por edificio, piso y apartamento
        $recordatorios = collect($recordatorios)->sortBy([
            ['edificio', 'asc'],
            ['piso', 'asc'],
            ['apartamento', 'asc'],
        ])->all();

        return view('propietario.recordatorios.index', compact('recordatorios'));
    }

    /**
     * Ver recordatorios por usuario
     */
    public function porUsuario($usuarioId)
    {
        $usuario = Usuario::findOrFail($usuarioId);
        $recordatorios = RecordatorioPago::where('usuario_id', $usuarioId)
                          ->orderBy('fecha_envio', 'desc')
                          ->get();

        $contratos = Contrato::where('usuario_id', $usuarioId)
                      ->where('estado', 'activo')
                      ->with('apartamento.edificio')
                      ->get();

        return view('propietario.recordatorios.por-usuario',
            compact('usuario', 'recordatorios', 'contratos'));
    }
}
