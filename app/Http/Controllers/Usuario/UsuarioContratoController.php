<?php

namespace App\Http\Controllers\Usuario;

use App\Http\Controllers\Controller;
use App\Models\Contrato;
use App\Models\EstadoAlquiler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UsuarioContratoController extends Controller
{
    // Ya tiene middleware en las rutas para comprobar que es inquilino

    // Ver todos los contratos del usuario
    public function index()
    {
        $contratos = Contrato::where('usuario_id', Auth::id())
            ->orderBy('fecha_inicio', 'desc')
            ->paginate(10);

        return view('usuario.contratos.lista', compact('contratos'));
    }

    // Ver detalles de un contrato específico
    public function show($id)
    {
        $contrato = Contrato::where('id', $id)
            ->where('usuario_id', Auth::id())
            ->firstOrFail();

        // Obtener los estados de alquiler (pagos) de este contrato
        $pagos = EstadoAlquiler::where('contrato_id', $contrato->id)
            ->orderBy('fecha_reporte', 'desc')
            ->get();

        return view('usuario.contratos.detalle', compact('contrato', 'pagos'));
    }
}
