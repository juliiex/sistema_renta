<?php

namespace App\Http\Controllers;

use App\Models\Contrato;
use App\Models\Apartamento;
use App\Models\Usuario;
use Illuminate\Http\Request;

class ContratoController extends Controller
{
    /**
     * Muestra todos los contratos.
     */
    public function index()
{
    $contratos = Contrato::with('usuario', 'apartamento')->get();
    return view('contrato.index', compact('contratos'));
}

public function create()
{
    $usuarios = Usuario::all(); // Traemos todos los usuarios
    $apartamentos = Apartamento::all(); // Traemos todos los apartamentos
    return view('contrato.create', compact('usuarios', 'apartamentos'));
}

    /**
     * Almacena un nuevo contrato en la base de datos.
     */
    public function store(Request $request)
{
    $request->validate([
        'usuario_id' => 'required|exists:usuario,id',
        'apartamento_id' => 'required|exists:apartamento,id',
        'fecha_inicio' => 'required|date',
        'fecha_fin' => 'required|date',
        'firma_digital' => 'required|string',
    ]);

    $contrato = Contrato::create($request->only([
        'usuario_id', 'apartamento_id', 'fecha_inicio', 'fecha_fin', 'firma_digital', 'estado'
    ]));

    return redirect()->route('contrato.index')->with('success', 'Contrato creado exitosamente.');
}

    /**
     * Muestra un contrato específico.
     */
    public function show($id)
    {
        $contrato = Contrato::with('usuario', 'apartamento')->find($id);
        if ($contrato) {
            return view('contrato.show', compact('contrato'));
        }
        return redirect()->route('contrato.index')->with('error', 'Contrato no encontrado.');
    }

    /**
     * Muestra el formulario para editar un contrato existente.
     */
    public function edit($id)
    {
        $contrato = Contrato::find($id);
        if (!$contrato) {
            return redirect()->route('contrato.index')->with('error', 'Contrato no encontrado.');
        }

        $usuarios = Usuario::all();
        $apartamentos = Apartamento::all();

        return view('contrato.edit', compact('contrato', 'usuarios', 'apartamentos'));
    }

    /**
     * Actualiza un contrato en la base de datos.
     */
    public function update(Request $request, $id)
    {
        $contrato = Contrato::find($id);
        if (!$contrato) {
            return redirect()->route('contrato.index')->with('error', 'Contrato no encontrado.');
        }

        $request->validate([
    'usuario_id' => 'required|exists:usuario,id', // Cambié a 'usuario'
    'apartamento_id' => 'required|exists:apartamento,id', // Cambié a 'apartamento'
    'fecha_inicio' => 'required|date',
    'fecha_fin' => 'required|date',
    'firma_digital' => 'required|string',
]);

        $contrato->update($request->only([
            'usuario_id', 'apartamento_id', 'fecha_inicio', 'fecha_fin', 'firma_digital', 'estado'
        ]));

        return redirect()->route('contrato.index')->with('success', 'Contrato actualizado correctamente.');
    }

    /**
     * Elimina un contrato de la base de datos.
     */
    public function destroy($id)
    {
        $contrato = Contrato::find($id);
        if ($contrato) {
            $contrato->delete();
            return redirect()->route('contrato.index')->with('success', 'Contrato eliminado correctamente.');
        }
        return redirect()->route('contrato.index')->with('error', 'Contrato no encontrado.');
    }
}


