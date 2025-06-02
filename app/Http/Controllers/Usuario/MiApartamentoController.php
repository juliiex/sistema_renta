<?php

namespace App\Http\Controllers\Usuario;

use App\Http\Controllers\Controller;
use App\Models\Apartamento;
use App\Models\Contrato;
use App\Models\Evaluacion;
use App\Models\EstadoAlquiler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MiApartamentoController extends Controller
{
    /**
     * Mostrar el detalle de un apartamento para el usuario autenticado
     */
    public function show($contrato_id)
    {
        $usuario = Auth::user();

        // Obtener el contrato y asegurarse de que pertenezca al usuario autenticado
        $contrato = Contrato::where('id', $contrato_id)
            ->where('usuario_id', $usuario->id)
            ->with(['apartamento', 'apartamento.edificio'])
            ->firstOrFail();

        // Verificar si el usuario ya ha evaluado este apartamento
        $evaluacionExistente = Evaluacion::where('usuario_id', $usuario->id)
            ->where('apartamento_id', $contrato->apartamento_id)
            ->first();

        // Obtener los 3 últimos pagos de este contrato
        $pagos = EstadoAlquiler::where('contrato_id', $contrato->id)
            ->orderBy('fecha_reporte', 'desc')
            ->take(3)
            ->get();

        // Calcular calificación promedio del apartamento
        $calificacionPromedio = Evaluacion::where('apartamento_id', $contrato->apartamento_id)
            ->avg('calificacion');
        $totalEvaluaciones = Evaluacion::where('apartamento_id', $contrato->apartamento_id)
            ->count();

        $puedeEvaluar = !is_null($evaluacionExistente) ? false : true;

        // Verificar si el usuario tiene otros contratos (anteriores o activos)
        $otrosContratos = Contrato::where('usuario_id', $usuario->id)
            ->where('id', '!=', $contrato_id)
            ->orderBy('fecha_inicio', 'desc')
            ->get();

        return view('usuario.mi-apartamento.detalle', compact(
            'contrato',
            'evaluacionExistente',
            'pagos',
            'calificacionPromedio',
            'totalEvaluaciones',
            'puedeEvaluar',
            'otrosContratos'
        ));
    }

    /**
     * Guardar una evaluación para un apartamento
     */
    public function evaluarApartamento(Request $request, $contrato_id)
    {
        $usuario = Auth::user();

        // Validar que el contrato pertenezca al usuario
        $contrato = Contrato::where('id', $contrato_id)
            ->where('usuario_id', $usuario->id)
            ->firstOrFail();

        // Verificar que no haya evaluado anteriormente este apartamento
        $evaluacionExistente = Evaluacion::where('usuario_id', $usuario->id)
            ->where('apartamento_id', $contrato->apartamento_id)
            ->first();

        if ($evaluacionExistente) {
            return redirect()->back()->with('error', 'Ya has evaluado este apartamento anteriormente.');
        }

        // Validar los datos del formulario
        $request->validate([
            'calificacion' => 'required|integer|min:1|max:5',
            'comentario' => 'required|string|min:10|max:500',
        ], [
            'calificacion.required' => 'Debes seleccionar una calificación.',
            'calificacion.integer' => 'La calificación debe ser un número entero.',
            'calificacion.min' => 'La calificación mínima es 1 estrella.',
            'calificacion.max' => 'La calificación máxima es 5 estrellas.',
            'comentario.required' => 'El comentario es obligatorio.',
            'comentario.min' => 'El comentario debe tener al menos 10 caracteres.',
            'comentario.max' => 'El comentario no debe exceder los 500 caracteres.',
        ]);

        // Crear la evaluación
        Evaluacion::create([
            'usuario_id' => $usuario->id,
            'apartamento_id' => $contrato->apartamento_id,
            'calificacion' => $request->calificacion,
            'comentario' => $request->comentario,
            'fecha_evaluacion' => now(),
        ]);

        return redirect()->back()->with('success', '¡Gracias por tu evaluación! Tu opinión es muy importante para nosotros.');
    }
}
