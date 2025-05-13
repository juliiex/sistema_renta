<?php

namespace App\Http\Controllers;

use App\Models\SolicitudAlquiler;
use App\Models\Usuario;
use App\Models\Apartamento;
use Illuminate\Http\Request;

class SolicitudAlquilerController extends Controller
{
    // Mostrar todas las solicitudes de alquiler
    public function index()
    {
        $solicitudes = SolicitudAlquiler::with('usuario', 'apartamento')->get();
        return view('solicitudes.index', compact('solicitudes'));
    }

    // Mostrar formulario para crear una nueva solicitud de alquiler
    public function create()
    {
        $usuarios = Usuario::all();
        $apartamentos = Apartamento::all();
        return view('solicitudes.create', compact('usuarios', 'apartamentos'));
    }

    // Guardar una nueva solicitud de alquiler
    public function store(Request $request)
    {
        // Validación de los datos recibidos
        $request->validate([
            'usuario_id' => 'required|exists:usuario,id',
            'apartamento_id' => 'required|exists:apartamento,id',
            'estado_solicitud' => 'required|string|in:pendiente,aprobada,rechazada',
        ]);

        // Crear la solicitud, asignando la fecha de solicitud automáticamente
        SolicitudAlquiler::create([
            'usuario_id' => $request->usuario_id,
            'apartamento_id' => $request->apartamento_id,
            'estado_solicitud' => $request->estado_solicitud,
            'fecha_solicitud' => now(), // Fecha actual
        ]);

        return redirect()->route('solicitudes.index')->with('success', 'Solicitud de alquiler creada exitosamente.');
    }

    // Mostrar una solicitud de alquiler específica
    public function show($id)
    {
        $solicitud = SolicitudAlquiler::with('usuario', 'apartamento')->findOrFail($id);
        return view('solicitudes.show', compact('solicitud'));
    }

    // Mostrar formulario para editar una solicitud de alquiler
    public function edit($id)
    {
        $solicitud = SolicitudAlquiler::findOrFail($id);
        $usuarios = Usuario::all();
        $apartamentos = Apartamento::all();
        return view('solicitudes.edit', compact('solicitud', 'usuarios', 'apartamentos'));
    }

    // Actualizar una solicitud de alquiler existente
    public function update(Request $request, $id)
    {
        // Validación de los datos recibidos
        $request->validate([
            'usuario_id' => 'required|exists:usuario,id',
            'apartamento_id' => 'required|exists:apartamento,id',
            'estado_solicitud' => 'required|string|in:pendiente,aprobada,rechazada',
        ]);

        // Obtener la solicitud y actualizarla
        $solicitud = SolicitudAlquiler::findOrFail($id);
        $solicitud->update([
            'usuario_id' => $request->usuario_id,
            'apartamento_id' => $request->apartamento_id,
            'estado_solicitud' => $request->estado_solicitud,
        ]);

        return redirect()->route('solicitudes.index')->with('success', 'Solicitud de alquiler actualizada correctamente.');
    }

    // Eliminar una solicitud de alquiler
    public function destroy($id)
    {
        $solicitud = SolicitudAlquiler::findOrFail($id);
        $solicitud->delete();

        return redirect()->route('solicitudes.index')->with('success', 'Solicitud de alquiler eliminada correctamente.');
    }
}

