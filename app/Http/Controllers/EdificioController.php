<?php

namespace App\Http\Controllers;

use App\Models\Edificio;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Support\Facades\Storage;

class EdificioController extends Controller
{
    /**
     * Mostrar todos los edificios.
     */
    public function index()
    {
        try {
            $edificios = Edificio::all();
            return view('edificio.index', compact('edificios'));
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al obtener los edificios', 'exception' => $e->getMessage()], 500);
        }
    }

    /**
     * Mostrar un edificio específico.
     */
    public function show($id)
    {
        try {
            $edificio = Edificio::findOrFail($id);
            return view('edificio.show', compact('edificio'));
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Edificio no encontrado'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al obtener el edificio', 'exception' => $e->getMessage()], 500);
        }
    }

    /**
     * Mostrar el formulario para crear un nuevo edificio.
     */
    public function create()
    {
        return view('edificio.create');
    }

    /**
     * Guardar un nuevo edificio.
     */
    public function store(Request $request)
    {
        try {
            // Validación de los campos
            $validatedData = $request->validate([
                'nombre' => 'required|string|max:255|unique:edificio,nombre',
                'direccion' => 'required|string|max:255',
                'cantidad_pisos' => 'required|integer',
                'descripcion' => 'nullable|string',
                'imagen' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // Validación para la imagen
            ]);

            // Subir la imagen si está presente
            if ($request->hasFile('imagen')) {
                $imagenPath = $request->file('imagen')->store('edificios', 'public');
                $validatedData['imagen'] = $imagenPath;
            }

            // Crear el nuevo edificio
            $edificio = Edificio::create($validatedData);

            return redirect()->route('edificio.index')->with('success', 'Edificio creado exitosamente');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al crear el edificio', 'exception' => $e->getMessage()], 500);
        }
    }

    /**
     * Mostrar el formulario para editar un edificio.
     */
    public function edit($id)
    {
        try {
            $edificio = Edificio::findOrFail($id);
            return view('edificio.edit', compact('edificio'));
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Edificio no encontrado'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al obtener el edificio', 'exception' => $e->getMessage()], 500);
        }
    }

    /**
     * Actualizar un edificio existente.
     */
    public function update(Request $request, $id)
    {
        try {
            $edificio = Edificio::findOrFail($id);

            // Validación de los datos
            $validatedData = $request->validate([
                'nombre' => 'required|string|max:255|unique:edificio,nombre,' . $id,
                'direccion' => 'required|string|max:255',
                'cantidad_pisos' => 'required|integer',
                'descripcion' => 'nullable|string',
                'imagen' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            ]);

            // Subir la nueva imagen si está presente
            if ($request->hasFile('imagen')) {
                // Eliminar la imagen anterior si existe
                if ($edificio->imagen && Storage::exists('public/' . $edificio->imagen)) {
                    Storage::delete('public/' . $edificio->imagen);
                }
                $imagenPath = $request->file('imagen')->store('edificios', 'public');
                $validatedData['imagen'] = $imagenPath;
            }

            // Actualizar el edificio
            $edificio->update($validatedData);

            return redirect()->route('edificio.index')->with('success', 'Edificio actualizado exitosamente');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Edificio no encontrado'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al actualizar el edificio', 'exception' => $e->getMessage()], 500);
        }
    }

    /**
     * Eliminar un edificio.
     */
    public function destroy($id)
    {
        try {
            $edificio = Edificio::findOrFail($id);

            // Eliminar la imagen si existe
            if ($edificio->imagen && Storage::exists('public/' . $edificio->imagen)) {
                Storage::delete('public/' . $edificio->imagen);
            }

            // Eliminar el edificio
            $edificio->delete();

            return redirect()->route('edificio.index')->with('success', 'Edificio eliminado exitosamente');
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Edificio no encontrado'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al eliminar el edificio', 'exception' => $e->getMessage()], 500);
        }
    }
}



