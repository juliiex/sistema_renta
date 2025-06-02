<?php

namespace App\Http\Controllers;

use App\Models\Evaluacion;
use App\Models\Usuario;
use App\Models\Apartamento;
use Illuminate\Http\Request;

class EvaluacionController extends Controller
{
    /**
     * Constructor: añade middleware de permisos
     */
    public function __construct()
    {
        $this->middleware('permission:ver_evaluacion')->only(['index', 'show']);
        $this->middleware('permission:crear_evaluacion')->only(['create', 'store']);
        $this->middleware('permission:editar_evaluacion')->only(['edit', 'update']);
        $this->middleware('permission:eliminar_evaluacion')->only(['destroy']);
    }

    public function index()
    {
        // Todas las evaluaciones son públicas, cualquier persona puede verlas
        $evaluaciones = Evaluacion::with('usuario', 'apartamento')->get();
        return view('admin.evaluaciones.index', compact('evaluaciones'));
    }

    public function create()
    {
        // Si el usuario es admin o propietario, permite seleccionar cualquier usuario y apartamento
        if (auth()->user()->hasRole(['admin', 'propietario'])) {
            $usuarios = Usuario::orderBy('id', 'asc')->get();
            $apartamentos = Apartamento::orderBy('numero_apartamento', 'asc')->get();
        }
        // Para otros roles, solo permitir seleccionar apartamentos que han usado
        else {
            $usuarios = Usuario::where('id', auth()->id())->get();

            // Obtener apartamentos relacionados con los contratos del usuario
            $apartamentos = Apartamento::whereHas('contratos', function($query) {
                $query->where('usuario_id', auth()->id());
            })->orderBy('numero_apartamento', 'asc')->get();

            // Si no hay apartamentos vinculados a contratos, mostrar mensaje
            if ($apartamentos->isEmpty()) {
                return redirect()->route('evaluaciones.index')
                    ->with('error', 'No tienes apartamentos para evaluar. Solo puedes evaluar apartamentos que has alquilado.');
            }
        }

        return view('admin.evaluaciones.create', compact('usuarios', 'apartamentos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'usuario_id' => 'required|exists:usuario,id',
            'apartamento_id' => 'required|exists:apartamento,id',
            'calificacion' => 'required|integer|min:1|max:5',
            'comentario' => 'nullable|string|max:500',
            'fecha_evaluacion' => 'required|date',
        ]);

        // Si no es admin o propietario, verificar que sea su propio usuario_id
        if (!auth()->user()->hasRole(['admin', 'propietario']) && $request->usuario_id != auth()->id()) {
            return redirect()->route('evaluaciones.create')
                ->with('error', 'Solo puede crear evaluaciones para su propio usuario');
        }

        // Si no es admin o propietario, verificar que haya tenido contrato con el apartamento
        if (!auth()->user()->hasRole(['admin', 'propietario'])) {
            $tieneContrato = Apartamento::where('id', $request->apartamento_id)
                ->whereHas('contratos', function($query) {
                    $query->where('usuario_id', auth()->id());
                })->exists();

            if (!$tieneContrato) {
                return redirect()->route('evaluaciones.create')
                    ->with('error', 'Solo puede evaluar apartamentos que ha alquilado');
            }
        }

        Evaluacion::create($request->all());

        return redirect()->route('evaluaciones.index')->with('success', 'Evaluación creada exitosamente.');
    }

    public function show($id)
    {
        $evaluacion = Evaluacion::with('usuario', 'apartamento')->findOrFail($id);
        return view('admin.evaluaciones.show', compact('evaluacion'));
    }

    public function edit($id)
    {
        $evaluacion = Evaluacion::findOrFail($id);

        // Solo el creador de la evaluación o un administrador pueden editar
        if ($evaluacion->usuario_id != auth()->id() && !auth()->user()->hasRole(['admin', 'propietario'])) {
            return redirect()->route('evaluaciones.index')
                ->with('error', 'Solo puede editar sus propias evaluaciones');
        }

        // Si el usuario es admin o propietario, permite seleccionar cualquier usuario y apartamento
        if (auth()->user()->hasRole(['admin', 'propietario'])) {
            $usuarios = Usuario::orderBy('id', 'asc')->get();
            $apartamentos = Apartamento::orderBy('numero_apartamento', 'asc')->get();
        }
        // Para otros roles, solo permitir su usuario y mantener el apartamento original
        else {
            $usuarios = Usuario::where('id', auth()->id())->get();
            $apartamentos = Apartamento::where('id', $evaluacion->apartamento_id)->get();
        }

        return view('admin.evaluaciones.edit', compact('evaluacion', 'usuarios', 'apartamentos'));
    }

    public function update(Request $request, $id)
    {
        $evaluacion = Evaluacion::findOrFail($id);

        // Solo el creador de la evaluación o un administrador pueden actualizar
        if ($evaluacion->usuario_id != auth()->id() && !auth()->user()->hasRole(['admin', 'propietario'])) {
            return redirect()->route('evaluaciones.index')
                ->with('error', 'Solo puede actualizar sus propias evaluaciones');
        }

        $request->validate([
            'usuario_id' => 'required|exists:usuario,id',
            'apartamento_id' => 'required|exists:apartamento,id',
            'calificacion' => 'required|integer|min:1|max:5',
            'comentario' => 'nullable|string|max:500',
            'fecha_evaluacion' => 'required|date',
        ]);

        // Si no es admin o propietario, no permitir cambiar el usuario o apartamento
        if (!auth()->user()->hasRole(['admin', 'propietario'])) {
            $request->merge([
                'usuario_id' => $evaluacion->usuario_id,
                'apartamento_id' => $evaluacion->apartamento_id
            ]);
        }

        $evaluacion->update($request->all());

        return redirect()->route('evaluaciones.index')->with('success', 'Evaluación actualizada exitosamente.');
    }

    public function destroy($id)
    {
        $evaluacion = Evaluacion::findOrFail($id);

        // Solo el creador de la evaluación o un administrador pueden eliminar
        if ($evaluacion->usuario_id != auth()->id() && !auth()->user()->hasRole(['admin', 'propietario'])) {
            return redirect()->route('evaluaciones.index')
                ->with('error', 'Solo puede eliminar sus propias evaluaciones');
        }

        $evaluacion->delete();

        return redirect()->route('evaluaciones.index')->with('success', 'Evaluación eliminada exitosamente.');
    }
}

