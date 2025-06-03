<?php

namespace App\Http\Controllers;

use App\Models\Queja;
use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Http\Request;

class QuejaController extends Controller
{
    /**
     * Constructor: añade middleware de permisos
     */
    public function __construct()
    {
        $this->middleware('permission:ver_queja')->only(['index', 'show', 'trashed']);
        $this->middleware('permission:crear_queja')->only(['create', 'store']);
        $this->middleware('permission:editar_queja')->only(['edit', 'update', 'restore']);
        $this->middleware('permission:eliminar_queja')->only(['destroy', 'forceDelete']);
    }

    // Mostrar todas las quejas
    public function index()
    {
        // Todas las quejas son visibles para todos los usuarios
        $quejas = Queja::with('usuario')->orderBy('id', 'desc')->get();
        return view('admin.queja.index', compact('quejas'));
    }

    // Mostrar formulario de creación
    public function create()
    {
        // Si es admin, puede seleccionar cualquier usuario que sea inquilino
        if (auth()->user()->hasRole(['admin', 'propietario'])) {
            // Obtener el ID del rol "inquilino"
            $rolInquilino = Rol::where('nombre', 'inquilino')->first();

            if ($rolInquilino) {
                // Obtener usuarios con rol de inquilino
                $usuarios = Usuario::whereHas('roles', function($query) use ($rolInquilino) {
                    $query->where('rol_id', $rolInquilino->id);
                })->orderBy('nombre', 'asc')->get();
            } else {
                $usuarios = collect(); // Colección vacía si no se encuentra el rol
            }
        } else {
            // Si no es admin, solo puede crear quejas para sí mismo
            $usuarios = Usuario::where('id', auth()->id())->get();
        }

        // Lista de tipos de quejas
        $tiposQuejas = [
            'Aplicativo' => 'Aplicativo',
            'Servicio' => 'Servicio',
            'Mantenimiento' => 'Mantenimiento',
            'Seguridad' => 'Seguridad',
            'Vecinos' => 'Vecinos',
            'Instalaciones' => 'Instalaciones',
            'Otro' => 'Otro'
        ];

        return view('admin.queja.create', compact('usuarios', 'tiposQuejas'));
    }

    // Guardar una nueva queja
    public function store(Request $request)
    {
        $request->validate([
            'usuario_id' => 'required|exists:usuario,id',
            'descripcion' => 'required|string|max:500',
            'tipo' => 'required|string|max:50',
            'fecha_envio' => 'required|date',
        ]);

        // Si no es admin o propietario, aseguramos que solo cree quejas para sí mismo
        if (!auth()->user()->hasRole(['admin', 'propietario']) && $request->usuario_id != auth()->id()) {
            return redirect()->route('queja.create')
                ->with('error', 'Solo puede crear quejas para su propio usuario.');
        }

        // Verificar que el usuario seleccionado tenga rol de inquilino (si el creador es admin o propietario)
        if (auth()->user()->hasRole(['admin', 'propietario'])) {
            $usuario = Usuario::find($request->usuario_id);
            if (!$usuario->hasRole('inquilino')) {
                return redirect()->route('queja.create')
                    ->with('error', 'Solo se pueden crear quejas para usuarios con rol de inquilino.');
            }
        }

        Queja::create($request->all());
        return redirect()->route('queja.index')->with('success', 'Queja creada correctamente.');
    }

    // Mostrar una queja específica
    public function show($id)
    {
        // Cualquier usuario puede ver cualquier queja
        $queja = Queja::with('usuario')->findOrFail($id);
        return view('admin.queja.show', compact('queja'));
    }

    // Mostrar formulario de edición
    public function edit($id)
    {
        $queja = Queja::findOrFail($id);

        // Solo el creador de la queja o un administrador pueden editar
        if ($queja->usuario_id != auth()->id() && !auth()->user()->hasRole(['admin', 'propietario'])) {
            return redirect()->route('queja.index')
                ->with('error', 'Solo puede editar sus propias quejas.');
        }

        // Si es admin o propietario, puede cambiar el usuario (solo a inquilinos)
        if (auth()->user()->hasRole(['admin', 'propietario'])) {
            // Obtener el ID del rol "inquilino"
            $rolInquilino = Rol::where('nombre', 'inquilino')->first();

            if ($rolInquilino) {
                // Obtener usuarios con rol de inquilino
                $usuarios = Usuario::whereHas('roles', function($query) use ($rolInquilino) {
                    $query->where('rol_id', $rolInquilino->id);
                })->orderBy('nombre', 'asc')->get();
            } else {
                $usuarios = collect(); // Colección vacía si no se encuentra el rol
            }
        } else {
            // Si no es admin, no puede cambiar el usuario (solo el suyo)
            $usuarios = Usuario::where('id', auth()->id())->get();
        }

        // Lista de tipos de quejas
        $tiposQuejas = [
            'Aplicativo' => 'Aplicativo',
            'Servicio' => 'Servicio',
            'Mantenimiento' => 'Mantenimiento',
            'Seguridad' => 'Seguridad',
            'Vecinos' => 'Vecinos',
            'Instalaciones' => 'Instalaciones',
            'Otro' => 'Otro'
        ];

        return view('admin.queja.edit', compact('queja', 'usuarios', 'tiposQuejas'));
    }

    // Actualizar una queja
    public function update(Request $request, $id)
    {
        $queja = Queja::findOrFail($id);

        // Solo el creador de la queja o un administrador pueden actualizar
        if ($queja->usuario_id != auth()->id() && !auth()->user()->hasRole(['admin', 'propietario'])) {
            return redirect()->route('queja.index')
                ->with('error', 'Solo puede actualizar sus propias quejas.');
        }

        $request->validate([
            'usuario_id' => 'required|exists:usuario,id',
            'descripcion' => 'required|string|max:500',
            'tipo' => 'required|string|max:50',
            'fecha_envio' => 'required|date',
        ]);

        // Si no es admin o propietario, no permitir cambiar el usuario
        if (!auth()->user()->hasRole(['admin', 'propietario'])) {
            $request->merge(['usuario_id' => $queja->usuario_id]);
        } else {
            // Verificar que el usuario seleccionado tenga rol de inquilino
            $usuario = Usuario::find($request->usuario_id);
            if (!$usuario->hasRole('inquilino')) {
                return redirect()->route('queja.edit', $queja->id)
                    ->with('error', 'Solo se pueden asignar quejas a usuarios con rol de inquilino.');
            }
        }

        $queja->update($request->all());

        return redirect()->route('queja.index')->with('success', 'Queja actualizada correctamente.');
    }

    // Eliminar una queja (soft delete)
    public function destroy($id)
    {
        $queja = Queja::findOrFail($id);

        // Solo el creador de la queja o un administrador pueden eliminar
        if ($queja->usuario_id != auth()->id() && !auth()->user()->hasRole(['admin', 'propietario'])) {
            return redirect()->route('queja.index')
                ->with('error', 'Solo puede eliminar sus propias quejas.');
        }

        $queja->delete();

        return redirect()->route('queja.index')->with('success', 'Queja eliminada correctamente.');
    }

    /**
     * Mostrar quejas eliminadas (soft deleted).
     */
    public function trashed()
    {
        if (auth()->user()->hasRole(['admin', 'propietario'])) {
            // Admin y propietario pueden ver todas las quejas eliminadas
            $quejas = Queja::onlyTrashed()->with('usuario')->orderBy('id', 'desc')->get();
        } else {
            // Otros usuarios solo pueden ver sus propias quejas eliminadas
            $quejas = Queja::onlyTrashed()
                ->with('usuario')
                ->where('usuario_id', auth()->id())
                ->orderBy('id', 'desc')
                ->get();
        }

        return view('admin.queja.trashed', compact('quejas'));
    }

    /**
     * Restaurar una queja eliminada.
     */
    public function restore($id)
    {
        $queja = Queja::onlyTrashed()->findOrFail($id);

        // Solo el creador de la queja o un administrador pueden restaurar
        if ($queja->usuario_id != auth()->id() && !auth()->user()->hasRole(['admin', 'propietario'])) {
            return redirect()->route('queja.trashed')
                ->with('error', 'Solo puede restaurar sus propias quejas.');
        }

        $queja->restore();

        return redirect()->route('queja.trashed')
            ->with('success', 'Queja restaurada correctamente.');
    }

    /**
     * Eliminar permanentemente una queja.
     */
    public function forceDelete($id)
    {
        $queja = Queja::onlyTrashed()->findOrFail($id);

        // Solo el creador de la queja o un administrador pueden eliminar permanentemente
        if ($queja->usuario_id != auth()->id() && !auth()->user()->hasRole(['admin', 'propietario'])) {
            return redirect()->route('queja.trashed')
                ->with('error', 'Solo puede eliminar permanentemente sus propias quejas.');
        }

        $queja->forceDelete();

        return redirect()->route('queja.trashed')
            ->with('success', 'Queja eliminada permanentemente.');
    }
}
