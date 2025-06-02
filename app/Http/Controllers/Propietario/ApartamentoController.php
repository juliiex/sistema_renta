<?php

namespace App\Http\Controllers\Propietario;

use App\Http\Controllers\Controller;
use App\Models\Apartamento;
use App\Models\Edificio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ApartamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $usuario = Auth::user();
        $query = Apartamento::with('edificio');

        // Aplicar filtro por estado si se proporciona
        if ($request->has('estado') && $request->estado != 'todos') {
            // Capitalizar la primera letra para hacer coincidir con la base de datos
            $estado = ucfirst($request->estado);
            $query->where('estado', $estado);
        }

        // Obtener todos los apartamentos
        $apartamentos = $query->get();

        return view('propietario.apartamentos.index', compact('apartamentos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $usuario = Auth::user();

        // Obtener edificios relacionados con los apartamentos del propietario
        $edificios = Edificio::whereHas('apartamentos', function($query) use ($usuario) {
            $query->where('usuario_id', $usuario->id);
        })->get();

        // Si no hay edificios, mostrar todos para pruebas
        if ($edificios->isEmpty()) {
            $edificios = Edificio::all();
        }

        return view('propietario.apartamentos.create', compact('edificios'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $usuario = Auth::user();

        $request->validate([
            'edificio_id' => 'required|exists:edificio,id',
            'numero_apartamento' => 'required|string|max:10',
            'piso' => 'required|integer|min:1',
            'precio' => 'required|numeric|min:0',
            'tamaño' => 'required|numeric|min:1',
            'estado' => 'required|in:Disponible,Ocupado,En mantenimiento',
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $apartamento = new Apartamento($request->except('imagen'));
        $apartamento->usuario_id = $usuario->id;

        if ($request->hasFile('imagen')) {
            // Guardar imagen siguiendo el ejemplo que proporcionaste
            $imagenPath = $request->file('imagen')->store('apartamentos', 'public');
            $apartamento->imagen = $imagenPath;
        }

        $apartamento->save();

        return redirect()->route('propietario.apartamentos.index')
            ->with('success', 'Apartamento creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $usuario = Auth::user();

        // Intentar encontrar el apartamento por ID
        $apartamento = Apartamento::with(['edificio', 'evaluaciones.usuario'])->findOrFail($id);

        // Obtener evaluaciones
        $evaluaciones = $apartamento->evaluaciones;

        // Calcular promedio de calificaciones
        $promedioCalificacion = $evaluaciones->avg('calificacion') ?: 0;

        return view('propietario.apartamentos.show',
            compact('apartamento', 'evaluaciones', 'promedioCalificacion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $usuario = Auth::user();
        $apartamento = Apartamento::findOrFail($id);

        // Obtener edificios
        $edificios = Edificio::all();

        return view('propietario.apartamentos.edit', compact('apartamento', 'edificios'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $usuario = Auth::user();
        $apartamento = Apartamento::findOrFail($id);

        $request->validate([
            'edificio_id' => 'required|exists:edificio,id',
            'numero_apartamento' => 'required|string|max:10',
            'piso' => 'required|integer|min:1',
            'precio' => 'required|numeric|min:0',
            'tamaño' => 'required|numeric|min:1',
            'estado' => 'required|in:Disponible,Ocupado,En mantenimiento',
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $apartamento->fill($request->except('imagen'));

        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($apartamento->imagen && Storage::disk('public')->exists($apartamento->imagen)) {
                Storage::disk('public')->delete($apartamento->imagen);
            }

            // Guardar imagen siguiendo el ejemplo que proporcionaste
            $imagenPath = $request->file('imagen')->store('apartamentos', 'public');
            $apartamento->imagen = $imagenPath;
        }

        $apartamento->save();

        return redirect()->route('propietario.apartamentos.show', $apartamento->id)
            ->with('success', 'Apartamento actualizado exitosamente.');
    }
}
