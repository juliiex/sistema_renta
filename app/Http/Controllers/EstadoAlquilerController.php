<?php

namespace App\Http\Controllers;

use App\Models\EstadoAlquiler;
use App\Models\Contrato;
use App\Models\Usuario;
use Illuminate\Http\Request;

class EstadoAlquilerController extends Controller
{
    /**
     * Constructor: añade middleware de permisos
     */
    public function __construct()
    {
        $this->middleware('permission:ver_estado_alquiler')->only(['index', 'show']);
        $this->middleware('permission:crear_estado_alquiler')->only(['create', 'store']);
        $this->middleware('permission:editar_estado_alquiler')->only(['edit', 'update']);
        $this->middleware('permission:eliminar_estado_alquiler')->only(['destroy']);
    }

    /**
     * Muestra la lista de estados de alquiler.
     */
    public function index()
    {
        // Para admin y propietario: todos los estados
        if (auth()->user()->hasRole(['admin', 'propietario'])) {
            $estadosAlquiler = EstadoAlquiler::with('contrato', 'usuario')->get();
        }
        // Para inquilinos: solo sus propios estados de alquiler
        else {
            $estadosAlquiler = EstadoAlquiler::with('contrato', 'usuario')
                ->whereHas('contrato', function($query) {
                    $query->where('usuario_id', auth()->id());
                })
                ->orWhere('usuario_id', auth()->id())
                ->get();
        }

        return view('admin.estado_alquiler.index', compact('estadosAlquiler'));
    }

    /**
     * Muestra el formulario para crear un nuevo estado de alquiler.
     */
    public function create()
    {
        // Para admin: todos los contratos
        if (auth()->user()->hasRole('admin')) {
            $contratos = Contrato::with('usuario', 'apartamento')->orderBy('id', 'asc')->get();
        }
        // Para propietario: solo los contratos activos
        else if (auth()->user()->hasRole('propietario')) {
            $contratos = Contrato::with('usuario', 'apartamento')
                        ->where('estado', 'activo')
                        ->orderBy('id', 'asc')
                        ->get();
        }
        // Para otros roles: solo sus propios contratos
        else {
            $contratos = Contrato::with('usuario', 'apartamento')
                        ->where('usuario_id', auth()->id())
                        ->orderBy('id', 'asc')
                        ->get();
        }

        // Para admin y propietario: todos los usuarios
        if (auth()->user()->hasRole(['admin', 'propietario'])) {
            $usuarios = Usuario::orderBy('id', 'asc')->get();
        }
        // Para otros roles: solo ellos mismos
        else {
            $usuarios = Usuario::where('id', auth()->id())->get();
        }

        return view('admin.estado_alquiler.create', compact('contratos', 'usuarios'));
    }

    /**
     * Almacena un nuevo estado de alquiler en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'contrato_id' => 'required|exists:contrato,id',
            'usuario_id' => 'required|exists:usuario,id',
            'estado_pago' => 'required|string|max:255',
            'fecha_reporte' => 'required|date',
        ]);

        // Si el usuario no es admin o propietario, verificar que sea su propio contrato
        if (!auth()->user()->hasRole(['admin', 'propietario'])) {
            $contrato = Contrato::find($request->contrato_id);
            if ($contrato->usuario_id != auth()->id()) {
                return redirect()->back()->with('error', 'No tiene permiso para crear un estado de alquiler para este contrato');
            }

            // Forzar que el usuario_id sea el del usuario autenticado
            $request->merge(['usuario_id' => auth()->id()]);
        }

        EstadoAlquiler::create($request->all());

        return redirect()->route('estado_alquiler.index')->with('success', 'Estado de alquiler creado exitosamente.');
    }

    /**
     * Muestra un estado de alquiler específico.
     */
    public function show($id)
    {
        $estadoAlquiler = EstadoAlquiler::with(['contrato.apartamento', 'usuario'])->find($id);

        if (!$estadoAlquiler) {
            return redirect()->route('estado_alquiler.index')->with('error', 'Estado de alquiler no encontrado.');
        }

        // Si el usuario no es admin o propietario, verificar que sea su propio estado o contrato
        if (!auth()->user()->hasRole(['admin', 'propietario'])) {
            $esPropio = $estadoAlquiler->usuario_id == auth()->id() ||
                       ($estadoAlquiler->contrato && $estadoAlquiler->contrato->usuario_id == auth()->id());

            if (!$esPropio) {
                return redirect()->route('estado_alquiler.index')
                    ->with('error', 'No tiene permiso para ver este estado de alquiler.');
            }
        }

        return view('admin.estado_alquiler.show', compact('estadoAlquiler'));
    }

    /**
     * Muestra el formulario para editar un estado de alquiler existente.
     */
    public function edit($id)
    {
        $estadoAlquiler = EstadoAlquiler::find($id);

        if (!$estadoAlquiler) {
            return redirect()->route('estado_alquiler.index')->with('error', 'Estado de alquiler no encontrado.');
        }

        // Si el usuario no es admin o propietario, verificar que sea su propio estado o contrato
        if (!auth()->user()->hasRole(['admin', 'propietario'])) {
            $esPropio = $estadoAlquiler->usuario_id == auth()->id() ||
                       ($estadoAlquiler->contrato && $estadoAlquiler->contrato->usuario_id == auth()->id());

            if (!$esPropio) {
                return redirect()->route('estado_alquiler.index')
                    ->with('error', 'No tiene permiso para editar este estado de alquiler.');
            }
        }

        // Para admin: todos los contratos
        if (auth()->user()->hasRole('admin')) {
            $contratos = Contrato::with('usuario', 'apartamento')->orderBy('id', 'asc')->get();
        }
        // Para propietario: solo los contratos activos o el contrato actual
        else if (auth()->user()->hasRole('propietario')) {
            $contratos = Contrato::with('usuario', 'apartamento')
                        ->where('estado', 'activo')
                        ->orWhere('id', $estadoAlquiler->contrato_id)
                        ->orderBy('id', 'asc')
                        ->get();
        }
        // Para otros roles: solo sus propios contratos
        else {
            $contratos = Contrato::with('usuario', 'apartamento')
                        ->where('usuario_id', auth()->id())
                        ->orderBy('id', 'asc')
                        ->get();
        }

        // Para admin y propietario: todos los usuarios
        if (auth()->user()->hasRole(['admin', 'propietario'])) {
            $usuarios = Usuario::orderBy('id', 'asc')->get();
        }
        // Para otros roles: solo ellos mismos
        else {
            $usuarios = Usuario::where('id', auth()->id())->get();
        }

        return view('admin.estado_alquiler.edit', compact('estadoAlquiler', 'contratos', 'usuarios'));
    }

    /**
     * Actualiza un estado de alquiler en la base de datos.
     */
    public function update(Request $request, $id)
    {
        $estadoAlquiler = EstadoAlquiler::find($id);

        if (!$estadoAlquiler) {
            return redirect()->route('estado_alquiler.index')->with('error', 'Estado de alquiler no encontrado.');
        }

        // Si el usuario no es admin o propietario, verificar que sea su propio estado o contrato
        if (!auth()->user()->hasRole(['admin', 'propietario'])) {
            $esPropio = $estadoAlquiler->usuario_id == auth()->id() ||
                       ($estadoAlquiler->contrato && $estadoAlquiler->contrato->usuario_id == auth()->id());

            if (!$esPropio) {
                return redirect()->route('estado_alquiler.index')
                    ->with('error', 'No tiene permiso para actualizar este estado de alquiler.');
            }

            // Forzar que el usuario_id sea el del usuario autenticado o el original
            $request->merge(['usuario_id' => auth()->id()]);

            // No permitir cambiar el contrato
            $request->merge(['contrato_id' => $estadoAlquiler->contrato_id]);
        }

        $request->validate([
            'contrato_id' => 'required|exists:contrato,id',
            'usuario_id' => 'required|exists:usuario,id',
            'estado_pago' => 'required|string|max:255',
            'fecha_reporte' => 'required|date',
        ]);

        $estadoAlquiler->update($request->all());

        return redirect()->route('estado_alquiler.index')->with('success', 'Estado de alquiler actualizado correctamente.');
    }

    /**
     * Elimina un estado de alquiler.
     */
    public function destroy($id)
    {
        $estadoAlquiler = EstadoAlquiler::find($id);

        if (!$estadoAlquiler) {
            return redirect()->route('estado_alquiler.index')->with('error', 'Estado de alquiler no encontrado.');
        }

        // Solo admin o propietario pueden eliminar estados de alquiler
        if (!auth()->user()->hasRole(['admin', 'propietario'])) {
            return redirect()->route('estado_alquiler.index')
                ->with('error', 'No tiene permiso para eliminar este estado de alquiler.');
        }

        $estadoAlquiler->delete();
        return redirect()->route('estado_alquiler.index')->with('success', 'Estado de alquiler eliminado correctamente.');
    }
}
