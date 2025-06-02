<?php

namespace App\Http\Controllers\Usuario;

use App\Http\Controllers\Controller;
use App\Models\Apartamento;
use App\Models\Evaluacion;
use App\Models\SolicitudAlquiler;
use App\Models\Contrato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UsuarioApartamentoController extends Controller
{
    // Mostrar todos los apartamentos disponibles
    public function index(Request $request)
    {
        $minPrecio = 820000;
        $minTamano = 200;
        $maxPiso = 5;

        $precioMin = max((int)($request->precio_min ?? $minPrecio), $minPrecio);
        $precioMax = max((int)($request->precio_max ?? 0), 0);
        $tamanoMin = max((int)($request->tamano_min ?? $minTamano), $minTamano);
        $piso = ($request->filled('piso') && (int)$request->piso >= 1 && (int)$request->piso <= $maxPiso) ? (int)$request->piso : null;

        $query = Apartamento::where('estado', 'disponible');

        if ($request->filled('precio_min')) {
            $query->where('precio', '>=', $precioMin);
        } else {
            $query->where('precio', '>=', $minPrecio);
        }

        if ($request->filled('precio_max')) {
            $query->where('precio', '<=', $precioMax);
        }

        if ($request->filled('tamano_min')) {
            $query->where('tamaño', '>=', $tamanoMin);
        } else {
            $query->where('tamaño', '>=', $minTamano);
        }

        if (!is_null($piso)) {
            $query->where('piso', $piso);
        }

        switch ($request->get('sort')) {
            case 'precio_desc':
                $query->orderBy('precio', 'desc');
                break;
            case 'tamano_asc':
                $query->orderBy('tamaño', 'asc');
                break;
            case 'tamano_desc':
                $query->orderBy('tamaño', 'desc');
                break;
            default:
                $query->orderBy('precio', 'asc');
        }

        $apartamentos = $query->paginate(12);

        // Calcular calificaciones promedio para cada apartamento
        foreach ($apartamentos as $apartamento) {
            $promedioCalificacion = Evaluacion::where('apartamento_id', $apartamento->id)->avg('calificacion');
            $totalEvaluaciones = Evaluacion::where('apartamento_id', $apartamento->id)->count();

            $apartamento->calificacion_promedio = $promedioCalificacion ? round($promedioCalificacion, 1) : 0;
            $apartamento->total_evaluaciones = $totalEvaluaciones;
        }

        return view('usuario.apartamentos.explorar', compact('apartamentos', 'minPrecio', 'minTamano', 'maxPiso'));
    }

    // Ver detalle de un apartamento
    public function show($id)
    {
        $apartamento = Apartamento::findOrFail($id);

        if ($apartamento->estado !== 'Disponible') {
            return redirect()->route('usuario.apartamentos.explorar')
                ->with('error', 'Este apartamento ya no está disponible.');
        }

        $user = Auth::user();

        $solicitudExistente = SolicitudAlquiler::where('usuario_id', $user->id)
            ->where('apartamento_id', $id)
            ->whereIn('estado_solicitud', ['pendiente', 'aprobada'])
            ->first();

        $evaluacionExistente = null;
        $puedeEvaluar = false;
        if ($user && $user->hasRole('inquilino')) {
            $contrato = Contrato::where('usuario_id', $user->id)
                ->where('apartamento_id', $id)
                ->first();
            $evaluacionExistente = Evaluacion::where('usuario_id', $user->id)
                ->where('apartamento_id', $id)
                ->first();
            $puedeEvaluar = $contrato && !$evaluacionExistente;
        }

        // Obtener evaluaciones y calcular promedio
        $evaluaciones = Evaluacion::where('apartamento_id', $id)
                               ->with('usuario')
                               ->orderBy('fecha_evaluacion', 'desc')
                               ->paginate(5);

        $promedioCalificacion = Evaluacion::where('apartamento_id', $id)->avg('calificacion');
        $totalEvaluaciones = Evaluacion::where('apartamento_id', $id)->count();

        $apartamento->calificacion_promedio = $promedioCalificacion ? round($promedioCalificacion, 1) : 0;
        $apartamento->total_evaluaciones = $totalEvaluaciones;

        return view('usuario.apartamentos.detalle', compact(
            'apartamento',
            'solicitudExistente',
            'evaluacionExistente',
            'puedeEvaluar',
            'evaluaciones'
        ));
    }

    // Solicitar un apartamento (procesa el formulario)
    public function solicitar(Request $request, $id)
    {
        $apartamento = Apartamento::findOrFail($id);

        if ($apartamento->estado !== 'Disponible') {
            return redirect()->back()->with('error', 'Este apartamento ya no está disponible.');
        }

        $solicitudExistente = SolicitudAlquiler::where('usuario_id', Auth::id())
            ->where('apartamento_id', $id)
            ->where('estado_solicitud', 'pendiente')
            ->first();

        if ($solicitudExistente) {
            return redirect()->back()->with('error', 'Ya tienes una solicitud pendiente para este apartamento.');
        }

        SolicitudAlquiler::create([
            'usuario_id' => Auth::id(),
            'apartamento_id' => $id,
            'estado_solicitud' => 'pendiente',
            'fecha_solicitud' => now()
        ]);

        return redirect()->route('usuario.solicitudes.lista')
            ->with('success', 'Tu solicitud ha sido enviada correctamente. Pronto recibirás una respuesta.');
    }
}
