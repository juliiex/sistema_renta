<?php

namespace App\Http\Controllers;

use App\Models\RecordatorioPago;
use Illuminate\Http\Request;
use App\Models\Usuario;  // Importar el modelo Usuario
use App\Models\Apartamento;  // Importar el modelo Apartamento


class RecordatorioPagoController extends Controller
{
    // Muestra una lista de todos los recordatorios de pago
    public function index()
    {
        $recordatorios = RecordatorioPago::with('usuario')->get();
        return view('recordatorio_pago.index', compact('recordatorios'));
    }

    // Muestra el formulario para crear un nuevo recordatorio de pago
    public function create()
{
    $usuarios = Usuario::all();  // Obtener todos los usuarios
    $apartamentos = Apartamento::all();  // Obtener todos los apartamentos

    return view('recordatorio_pago.create', compact('usuarios', 'apartamentos'));
}

    // Almacena un nuevo recordatorio de pago
    public function store(Request $request)
    {
        $request->validate([
            'usuario_id' => 'required|exists:usuario,id',
            'metodo' => 'required|string|max:255',
            'fecha_envio' => 'required|date',
        ]);

        $recordatorio = RecordatorioPago::create([
            'usuario_id' => $request->usuario_id,
            'metodo' => $request->metodo,
            'fecha_envio' => $request->fecha_envio,
        ]);

        return redirect()->route('recordatorio_pago.index')->with('success', 'Recordatorio de pago creado con éxito.');
    }

    // Muestra los detalles de un recordatorio de pago específico
    public function show($id)
    {
        $recordatorio = RecordatorioPago::with('usuario')->findOrFail($id);
        return view('recordatorio_pago.show', compact('recordatorio'));
    }

    // Muestra el formulario para editar un recordatorio de pago
    public function edit($id)
{
    $recordatorio = RecordatorioPago::findOrFail($id);
    $usuarios = Usuario::all();  // Obtener todos los usuarios
    $apartamentos = Apartamento::all();  // Obtener todos los apartamentos

    return view('recordatorio_pago.edit', compact('recordatorio', 'usuarios', 'apartamentos'));
}

    // Actualiza un recordatorio de pago específico
    public function update(Request $request, $id)
    {
        $request->validate([
            'usuario_id' => 'required|exists:usuario,id',
            'metodo' => 'required|string|max:255',
            'fecha_envio' => 'required|date',
        ]);

        $recordatorio = RecordatorioPago::findOrFail($id);
        $recordatorio->update([
            'usuario_id' => $request->usuario_id,
            'metodo' => $request->metodo,
            'fecha_envio' => $request->fecha_envio,
        ]);

        return redirect()->route('recordatorio_pago.index')->with('success', 'Recordatorio de pago actualizado con éxito.');
    }

    // Elimina un recordatorio de pago
    public function destroy($id)
    {
        $recordatorio = RecordatorioPago::findOrFail($id);
        $recordatorio->delete();

        return redirect()->route('recordatorio_pago.index')->with('success', 'Recordatorio de pago eliminado con éxito.');
    }
}

