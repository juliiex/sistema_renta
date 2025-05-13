<?php

namespace App\Http\Controllers;

use App\Models\EstadoAlquiler;
use App\Models\Contrato;
use App\Models\Usuario;
use Illuminate\Http\Request;

class EstadoAlquilerController extends Controller
{
    /**
     * Muestra la lista de estados de alquiler.
     */
    public function index()
    {
        $estadosAlquiler = EstadoAlquiler::with('contrato', 'usuario')->get();
        return view('estado_alquiler.index', compact('estadosAlquiler'));
    }

    /**
     * Muestra el formulario para crear un nuevo estado de alquiler.
     */
    public function create()
    {
        $contratos = Contrato::all();
        $usuarios = Usuario::all();
        return view('estado_alquiler.create', compact('contratos', 'usuarios'));
    }

    /**
     * Almacena un nuevo estado de alquiler en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'contrato_id' => 'required|exists:contrato,id',
            'usuario_id' => 'required|exists:usuario,id',
            'estado_pago' => 'required|string|max:255',
            'fecha_reporte' => 'required|date',
        ]);

        EstadoAlquiler::create($request->all());

        return redirect()->route('estado_alquiler.index')->with('success', 'Estado de alquiler creado exitosamente.');
    }

    /**
     * Muestra un estado de alquiler específico.
     */
    public function show($id)
    {
        $estadoAlquiler = EstadoAlquiler::with('contrato', 'usuario')->find($id);

        if (!$estadoAlquiler) {
            return redirect()->route('estado_alquiler.index')->with('error', 'Estado de alquiler no encontrado.');
        }

        return view('estado_alquiler.show', compact('estadoAlquiler'));
    }

    /**
     * Muestra el formulario para editar un estado de alquiler existente.
     */
    public function edit($id)
    {
        $estadoAlquiler = EstadoAlquiler::find($id);
        $contratos = Contrato::all();
        $usuarios = Usuario::all();

        if (!$estadoAlquiler) {
            return redirect()->route('estado_alquiler.index')->with('error', 'Estado de alquiler no encontrado.');
        }

        return view('estado_alquiler.edit', compact('estadoAlquiler', 'contratos', 'usuarios'));
    }

    /**
     * Actualiza un estado de alquiler en la base de datos.
     */
    public function update(Request $request, $id)
    {
        $estadoAlquiler = EstadoAlquiler::find($id);

        if (!$estadoAlquiler) {
            return redirect()->route('estado_alquiler.index')->with('error', 'Estado de alquiler no encontrado.');
        }

        $request->validate([
            'contrato_id' => 'required|exists:contrato,id',
            'usuario_id' => 'required|exists:usuario,id',
            'estado_pago' => 'required|string|max:255',
            'fecha_reporte' => 'required|date',
        ]);

        $estadoAlquiler->update($request->all());

        return redirect()->route('estado_alquiler.index')->with('success', 'Estado de alquiler actualizado correctamente.');
    }

    /**
     * Elimina un estado de alquiler.
     */
    public function destroy($id)
    {
        $estadoAlquiler = EstadoAlquiler::find($id);

        if ($estadoAlquiler) {
            $estadoAlquiler->delete();
            return redirect()->route('estado_alquiler.index')->with('success', 'Estado de alquiler eliminado correctamente.');
        }

        return redirect()->route('estado_alquiler.index')->with('error', 'Estado de alquiler no encontrado.');
    }
}
