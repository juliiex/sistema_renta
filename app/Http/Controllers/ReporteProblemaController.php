<?php

namespace App\Http\Controllers;

use App\Models\ReporteProblema;
use App\Models\Usuario;
use App\Models\Apartamento;
use Illuminate\Http\Request;

class ReporteProblemaController extends Controller
{
    // Método para mostrar la lista de reportes (Index)
    public function index()
    {
        $reportes = ReporteProblema::with('apartamento', 'usuario')->get();
        return view('reporte_problema.index', compact('reportes'));
    }

    // Método para mostrar el formulario de creación (Create)
    public function create()
    {
        $usuarios = Usuario::all();
        $apartamentos = Apartamento::all();
        return view('reporte_problema.create', compact('usuarios', 'apartamentos'));
    }

    // Método para guardar un nuevo reporte (Store)
    public function store(Request $request)
    {
        $request->validate([
            'apartamento_id' => 'required|exists:apartamento,id',
            'usuario_id' => 'required|exists:usuario,id',
            'descripcion' => 'required|string|max:500',
            'tipo' => 'nullable|string|max:50',
            'estado' => 'required|string|in:pendiente,atendido,cerrado',
        ]);

        ReporteProblema::create($request->all());

        return redirect()->route('reporte_problema.index')
                         ->with('success', 'Reporte de problema creado correctamente.');
    }

    // Método para mostrar un reporte específico (Show)
    // Método para mostrar un reporte específico (Show)
public function show($id)
{
    $reporteProblema = ReporteProblema::with('apartamento', 'usuario')->findOrFail($id);
    return view('reporte_problema.show', compact('reporteProblema'));
}


    // Método para mostrar el formulario de edición (Edit)
    public function edit($id)
    {
        $reporte = ReporteProblema::findOrFail($id);
        $usuarios = Usuario::all();
        $apartamentos = Apartamento::all();
        return view('reporte_problema.edit', compact('reporte', 'usuarios', 'apartamentos'));
    }

    // Método para actualizar un reporte (Update)
    public function update(Request $request, $id)
    {
        $request->validate([
            'apartamento_id' => 'required|exists:apartamento,id',
            'usuario_id' => 'required|exists:usuario,id',
            'descripcion' => 'required|string|max:500',
            'tipo' => 'nullable|string|max:50',
            'estado' => 'required|string|in:pendiente,atendido,cerrado',
        ]);

        $reporte = ReporteProblema::findOrFail($id);
        $reporte->update($request->all());

        return redirect()->route('reporte_problema.index')
                         ->with('success', 'Reporte de problema actualizado correctamente.');
    }

    // Método para eliminar un reporte (Destroy)
    public function destroy($id)
    {
        $reporte = ReporteProblema::findOrFail($id);
        $reporte->delete();

        return redirect()->route('reporte_problema.index')
                         ->with('success', 'Reporte de problema eliminado correctamente.');
    }
}

