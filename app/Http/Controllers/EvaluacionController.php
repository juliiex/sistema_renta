<?php

namespace App\Http\Controllers;

use App\Models\Evaluacion;
use App\Models\Usuario;
use App\Models\Apartamento;
use Illuminate\Http\Request;

class EvaluacionController extends Controller
{
    public function index()
    {
        $evaluaciones = Evaluacion::with('usuario', 'apartamento')->get();
        return view('evaluaciones.index', compact('evaluaciones'));
    }

    public function create()
    {
        $usuarios = Usuario::all();
        $apartamentos = Apartamento::all();
        return view('evaluaciones.create', compact('usuarios', 'apartamentos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'usuario_id' => 'required|exists:usuario,id',
            'apartamento_id' => 'required|exists:apartamento,id',
            'calificacion' => 'required|integer|min:1|max:5',
            'comentario' => 'nullable|string|max:500',
            'fecha_evaluacion' => 'required|date',
        ]);

        Evaluacion::create($request->all());

        return redirect()->route('evaluaciones.index')->with('success', 'Evaluación creada exitosamente.');
    }

    public function show($id)
    {
        $evaluacion = Evaluacion::with('usuario', 'apartamento')->findOrFail($id);
        return view('evaluaciones.show', compact('evaluacion'));
    }

    public function edit($id)
    {
        $evaluacion = Evaluacion::findOrFail($id);
        $usuarios = Usuario::all();
        $apartamentos = Apartamento::all();
        return view('evaluaciones.edit', compact('evaluacion', 'usuarios', 'apartamentos'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'usuario_id' => 'required|exists:usuario,id',
            'apartamento_id' => 'required|exists:apartamento,id',
            'calificacion' => 'required|integer|min:1|max:5',
            'comentario' => 'nullable|string|max:500',
            'fecha_evaluacion' => 'required|date',
        ]);

        $evaluacion = Evaluacion::findOrFail($id);
        $evaluacion->update($request->all());

        return redirect()->route('evaluaciones.index')->with('success', 'Evaluación actualizada exitosamente.');
    }

    public function destroy($id)
    {
        $evaluacion = Evaluacion::findOrFail($id);
        $evaluacion->delete();

        return redirect()->route('evaluaciones.index')->with('success', 'Evaluación eliminada exitosamente.');
    }
}


