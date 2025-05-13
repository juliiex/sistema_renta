<?php

namespace App\Http\Controllers;

use App\Models\Apartamento;
use App\Models\Edificio;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApartamentoController extends Controller
{
    /**
     * Mostrar la lista de apartamentos.
     */
    public function index()
    {
        $apartamentos = Apartamento::with('edificio')->get();
        return view('apartamento.index', compact('apartamentos'));
    }

    /**
     * Mostrar el formulario para crear un nuevo apartamento.
     */
    public function create()
    {
        $edificios = Edificio::all();
        return view('apartamento.create', compact('edificios'));
    }

    /**
     * Crear un nuevo apartamento.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'edificio_id' => 'required|exists:edificio,id',
            'numero_apartamento' => 'required|string|max:10|unique:apartamento,numero_apartamento',
            'piso' => 'required|integer|min:1',
            'precio' => 'required|numeric|min:0',
            'tamaño' => 'required|numeric|min:1',
            'estado' => 'required|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'descripcion' => 'nullable|string'
        ]);

        if ($request->hasFile('imagen')) {
            $validatedData['imagen'] = $request->file('imagen')->store('apartamentos', 'public');
        }

        Apartamento::create($validatedData);

        return redirect()->route('apartamento.index')->with('success', 'Apartamento creado correctamente.');
    }

    /**
     * Mostrar los detalles de un apartamento.
     */
    public function show($id)
    {
        $apartamento = Apartamento::with('edificio')->findOrFail($id);
        return view('apartamento.show', compact('apartamento'));
    }

    /**
     * Mostrar el formulario para editar un apartamento.
     */
    public function edit($id)
    {
        $apartamento = Apartamento::findOrFail($id);
        $edificios = Edificio::all();
        return view('apartamento.edit', compact('apartamento', 'edificios'));
    }

    /**
     * Actualizar un apartamento existente.
     */
    public function update(Request $request, $id)
    {
        $apartamento = Apartamento::findOrFail($id);

        $validatedData = $request->validate([
            'edificio_id' => 'required|exists:edificio,id',
            'numero_apartamento' => 'required|string|max:10|unique:apartamento,numero_apartamento,' . $id,
            'piso' => 'required|integer|min:1',
            'precio' => 'required|numeric|min:0',
            'tamaño' => 'required|numeric|min:1',
            'estado' => 'required|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'descripcion' => 'nullable|string'
        ]);

        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($apartamento->imagen && Storage::disk('public')->exists($apartamento->imagen)) {
                Storage::disk('public')->delete($apartamento->imagen);
            }

            $validatedData['imagen'] = $request->file('imagen')->store('apartamentos', 'public');
        }

        $apartamento->update($validatedData);

        return redirect()->route('apartamento.index')->with('success', 'Apartamento actualizado correctamente.');
    }

    /**
     * Eliminar un apartamento.
     */
    public function destroy($id)
    {
        $apartamento = Apartamento::findOrFail($id);

        // Eliminar imagen si existe
        if ($apartamento->imagen && Storage::disk('public')->exists($apartamento->imagen)) {
            Storage::disk('public')->delete($apartamento->imagen);
        }

        $apartamento->delete();

        return redirect()->route('apartamento.index')->with('success', 'Apartamento eliminado correctamente.');
    }
}
