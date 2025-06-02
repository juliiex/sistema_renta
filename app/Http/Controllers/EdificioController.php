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
     * Constructor: añade middleware de permisos
     */
    public function __construct()
    {
        $this->middleware('permission:ver_edificio')->only(['index', 'show']);
        $this->middleware('permission:crear_edificio')->only(['create', 'store']);
        $this->middleware('permission:editar_edificio')->only(['edit', 'update']);
        $this->middleware('permission:eliminar_edificio')->only(['destroy']);
    }

    /**
     * Mostrar todos los edificios.
     */
    public function index()
    {
        try {
            $edificios = Edificio::all();
            return view('admin.edificio.index', compact('edificios'));
        } catch (Exception $e) {
            return back()->with('error', 'Error al obtener los edificios: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar un edificio específico.
     */
    public function show($id)
    {
        try {
            $edificio = Edificio::findOrFail($id);
            return view('admin.edificio.show', compact('edificio'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('edificio.index')->with('error', 'Edificio no encontrado');
        } catch (Exception $e) {
            return back()->with('error', 'Error al obtener el edificio: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar el formulario para crear un nuevo edificio.
     */
    public function create()
    {
        return view('admin.edificio.create');
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
                'cantidad_pisos' => 'required|integer|min:1',
                'descripcion' => 'nullable|string',
                'imagen' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
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
            return back()->with('error', 'Error al crear el edificio: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Mostrar el formulario para editar un edificio.
     */
    public function edit($id)
    {
        try {
            $edificio = Edificio::findOrFail($id);
            return view('admin.edificio.edit', compact('edificio'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('edificio.index')->with('error', 'Edificio no encontrado');
        } catch (Exception $e) {
            return back()->with('error', 'Error al obtener el edificio: ' . $e->getMessage());
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
                'cantidad_pisos' => 'required|integer|min:1',
                'descripcion' => 'nullable|string',
                'imagen' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            ]);

            // Subir la nueva imagen si está presente
            if ($request->hasFile('imagen')) {
                // Eliminar la imagen anterior si existe
                if ($edificio->imagen && Storage::disk('public')->exists($edificio->imagen)) {
                    Storage::disk('public')->delete($edificio->imagen);
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
            return redirect()->route('edificio.index')->with('error', 'Edificio no encontrado');
        } catch (Exception $e) {
            return back()->with('error', 'Error al actualizar el edificio: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Eliminar un edificio.
     */
    public function destroy($id)
    {
        try {
            $edificio = Edificio::findOrFail($id);

            // Verificar si tiene apartamentos asociados
            if ($edificio->apartamentos()->count() > 0) {
                return redirect()->route('edificio.index')->with('error', 'No se puede eliminar el edificio porque tiene apartamentos asociados');
            }

            // Eliminar la imagen si existe
            if ($edificio->imagen && Storage::disk('public')->exists($edificio->imagen)) {
                Storage::disk('public')->delete($edificio->imagen);
            }

            // Eliminar el edificio
            $edificio->delete();

            return redirect()->route('edificio.index')->with('success', 'Edificio eliminado exitosamente');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('edificio.index')->with('error', 'Edificio no encontrado');
        } catch (Exception $e) {
            return back()->with('error', 'Error al eliminar el edificio: ' . $e->getMessage());
        }
    }
}
