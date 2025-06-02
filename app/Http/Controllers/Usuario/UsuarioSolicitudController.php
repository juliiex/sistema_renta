<?php

namespace App\Http\Controllers\Usuario;

use App\Http\Controllers\Controller;
use App\Models\SolicitudAlquiler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsuarioSolicitudController extends Controller
{
    // Ver todas las solicitudes del usuario
    public function index()
    {
        $solicitudes = SolicitudAlquiler::where('usuario_id', Auth::id())
            ->orderBy('fecha_solicitud', 'desc')
            ->paginate(10);

        return view('usuario.solicitudes.lista', compact('solicitudes'));
    }

    // Ver detalles de una solicitud específica
    public function show($id)
    {
        $solicitud = SolicitudAlquiler::where('id', $id)
            ->where('usuario_id', Auth::id())
            ->firstOrFail();

        return view('usuario.solicitudes.detalle', compact('solicitud'));
    }

    // Cancelar una solicitud pendiente
    public function cancelar($id)
    {
        $solicitud = SolicitudAlquiler::where('id', $id)
            ->where('usuario_id', Auth::id())
            ->where('estado_solicitud', 'pendiente')
            ->firstOrFail();

        // Cancelar la solicitud
        $solicitud->update([
            'estado_solicitud' => 'cancelada'
        ]);

        return redirect()->route('usuario.solicitudes.lista')
            ->with('success', 'La solicitud ha sido cancelada correctamente.');
    }
}
