<?php

namespace App\Http\Controllers\Usuario;

use App\Http\Controllers\Controller;
use App\Models\Contrato;
use App\Models\Evaluacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluacionController extends Controller
{
    public function store(Request $request, $apartamento_id)
    {
        $user = Auth::user();

        // Solo inquilinos con contrato pueden evaluar
        $contrato = Contrato::where('usuario_id', $user->id)
            ->where('apartamento_id', $apartamento_id)
            ->first();

        if (!$user->hasRole('inquilino') || !$contrato) {
            return back()->with('error', 'No puedes evaluar este apartamento.');
        }

        // Solo una evaluación por usuario-apartamento
        $existe = Evaluacion::where('usuario_id', $user->id)
            ->where('apartamento_id', $apartamento_id)
            ->first();
        if ($existe) {
            return back()->with('error', 'Ya has dejado una evaluación para este apartamento.');
        }

        $validated = $request->validate([
            'calificacion' => 'required|integer|min:1|max:5',
            'comentario' => 'required|string|max:500',
        ]);

        Evaluacion::create([
            'usuario_id' => $user->id,
            'apartamento_id' => $apartamento_id,
            'calificacion' => $validated['calificacion'],
            'comentario' => $validated['comentario'],
            'fecha_evaluacion' => now(),
        ]);

        return back()->with('success', '¡Gracias por tu evaluación!');
    }
}
