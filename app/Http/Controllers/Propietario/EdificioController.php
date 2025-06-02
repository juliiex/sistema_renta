<?php

namespace App\Http\Controllers\Propietario;

use App\Http\Controllers\Controller;
use App\Models\Edificio;
use App\Models\Apartamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EdificioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuario = Auth::user();

        // Enfoque más directo para obtener edificios relacionados con apartamentos del propietario
        // Primero obtenemos los IDs de edificios que tienen apartamentos de este propietario
        $edificioIds = Apartamento::where('usuario_id', $usuario->id)
                        ->distinct('edificio_id')
                        ->pluck('edificio_id');

        $edificios = Edificio::whereIn('id', $edificioIds)->get();

        // Si no hay resultados, por ahora para pruebas mostraremos todos los edificios
        if ($edificios->isEmpty()) {
            $edificios = Edificio::all();
        }

        return view('propietario.edificios.index', compact('edificios'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $usuario = Auth::user();
        $edificio = Edificio::findOrFail($id);

        // Limitar a mostrar solo 3 apartamentos en la vista detalle
        $apartamentos = $edificio->apartamentos()->take(3)->get();
        $totalApartamentos = $edificio->apartamentos()->count();

        return view('propietario.edificios.show',
            compact('edificio', 'apartamentos', 'totalApartamentos'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $usuario = Auth::user();
        $edificio = Edificio::findOrFail($id);

        return view('propietario.edificios.edit', compact('edificio'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $usuario = Auth::user();
        $edificio = Edificio::findOrFail($id);

        $request->validate([
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048'
        ]);

        // Solo se permite actualizar la imagen y la descripción
        $edificio->descripcion = $request->descripcion;

        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($edificio->imagen && Storage::disk('public')->exists($edificio->imagen)) {
                Storage::disk('public')->delete($edificio->imagen);
            }

            // Guardar imagen siguiendo el ejemplo que proporcionaste
            $imagenPath = $request->file('imagen')->store('edificios', 'public');
            $edificio->imagen = $imagenPath;
        }

        $edificio->save();

        return redirect()->route('propietario.edificios.show', $edificio->id)
            ->with('success', 'Información del edificio actualizada exitosamente.');
    }
}
