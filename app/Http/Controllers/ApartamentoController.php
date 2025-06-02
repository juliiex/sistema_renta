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
     * Constructor: añade middleware de permisos
     */
    public function __construct()
    {
        $this->middleware('permission:ver_apartamento')->only(['index', 'show']);
        $this->middleware('permission:crear_apartamento')->only(['create', 'store']);
        $this->middleware('permission:editar_apartamento')->only(['edit', 'update']);
        $this->middleware('permission:eliminar_apartamento')->only(['destroy']);
    }

    /**
     * Mostrar la lista de apartamentos.
     */
    public function index()
    {
        // Para el admin/propietario - ver todos
        if (auth()->user()->hasRole(['admin', 'propietario'])) {
            $apartamentos = Apartamento::with('edificio')->get();
        }
        // Para inquilinos - ver solo los suyos (si están relacionados a algún contrato)
        else if (auth()->user()->hasRole('inquilino')) {
            $apartamentos = Apartamento::with('edificio')
                ->whereHas('contratos', function($query) {
                    $query->where('usuario_id', auth()->id());
                })
                ->get();
        }
        // Para posibles inquilinos - ver solo disponibles
        else {
            $apartamentos = Apartamento::with('edificio')
                ->where('estado', 'disponible')
                ->get();
        }

        return view('admin.apartamento.index', compact('apartamentos'));
    }

    /**
     * Mostrar el formulario para crear un nuevo apartamento.
     */
    public function create()
    {
        $edificios = Edificio::all();
        return view('admin.apartamento.create', compact('edificios'));
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

        // Verificar acceso para inquilinos (solo pueden ver sus apartamentos)
        if (auth()->user()->hasRole('inquilino')) {
            $tieneAcceso = false;

            // Verificar si el usuario tiene contratos relacionados con este apartamento
            if (auth()->user()->contratos) {
                $tieneAcceso = auth()->user()->contratos()
                    ->where('apartamento_id', $id)
                    ->exists();
            }

            if (!$tieneAcceso) {
                abort(403, 'No tiene permiso para ver este apartamento');
            }
        }

        return view('admin.apartamento.show', compact('apartamento'));
    }

    /**
     * Mostrar el formulario para editar un apartamento.
     */
    public function edit($id)
    {
        $apartamento = Apartamento::findOrFail($id);
        $edificios = Edificio::all();
        return view('admin.apartamento.edit', compact('apartamento', 'edificios'));
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
