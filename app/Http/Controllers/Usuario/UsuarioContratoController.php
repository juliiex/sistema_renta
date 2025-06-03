<?php

namespace App\Http\Controllers\Usuario;

use App\Http\Controllers\Controller;
use App\Models\Contrato;
use App\Models\EstadoAlquiler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class UsuarioContratoController extends Controller
{
    // Métodos existentes...

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

    // Método para descargar el contrato en PDF
    public function descargarContrato($id)
    {
        $contrato = Contrato::where('id', $id)
            ->where('usuario_id', Auth::id())
            ->with(['usuario', 'apartamento.edificio'])
            ->firstOrFail();

        $pdf = PDF::loadView('usuario.contratos.pdf', compact('contrato'));

        // Nombre del archivo: Contrato_NumeroContrato_Apellido_Fecha.pdf
        $nombreArchivo = 'Contrato_' . $contrato->id . '_' .
                          str_replace(' ', '_', $contrato->usuario->nombre) . '_' .
                          $contrato->fecha_inicio->format('dmY') . '.pdf';

        return $pdf->stream($nombreArchivo);
    }
}
