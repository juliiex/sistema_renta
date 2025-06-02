<?php

namespace App\Http\Controllers;

use App\Models\UsuarioRol;
use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Http\Request;

class UsuarioRolController extends Controller
{
    /**
     * Constructor: añade middleware de permisos
     */
    public function __construct()
    {
        // Solo admin debería poder gestionar asignaciones de roles a usuarios
        $this->middleware('role:admin');

        // Se podría añadir para propietario, pero con restricciones en los métodos
        $this->middleware('role:admin|propietario');
    }

    public function index()
    {
        // Admin ve todas las relaciones
        if (auth()->user()->hasRole('admin')) {
            $usuarioRoles = UsuarioRol::with('usuario', 'rol')->get();
        }
        // Propietario solo ve relaciones de inquilinos y posibles inquilinos
        else {
            $usuarioRoles = UsuarioRol::with('usuario', 'rol')
                ->whereHas('usuario.roles', function($query) {
                    $query->whereIn('nombre', ['inquilino', 'posible_inquilino']);
                })
                ->get();
        }

        return view('admin.usuario_rol.index', compact('usuarioRoles'));
    }

    public function create()
    {
        // Admin puede asignar cualquier rol a cualquier usuario
        if (auth()->user()->hasRole('admin')) {
            $usuarios = Usuario::all();
            $roles = Rol::all();
        }
        // Propietario solo puede asignar roles de inquilino y posible inquilino
        // y solo a usuarios que ya tengan esos roles
        else {
            $usuarios = Usuario::whereHas('roles', function($query) {
                $query->whereIn('nombre', ['inquilino', 'posible_inquilino']);
            })->get();

            $roles = Rol::whereIn('nombre', ['inquilino', 'posible_inquilino'])->get();
        }

        return view('admin.usuario_rol.create', compact('usuarios', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'usuario_id' => 'required|exists:usuario,id',
            'rol_id' => 'required|exists:rol,id',
        ]);

        // Verificar que la relación no exista ya
        $existente = UsuarioRol::where('usuario_id', $request->usuario_id)
            ->where('rol_id', $request->rol_id)
            ->first();

        if ($existente) {
            return redirect()->route('usuario_rol.create')
                ->with('error', 'Esta relación usuario-rol ya existe.');
        }

        // Si es propietario, verificar que solo asigna roles permitidos
        if (!auth()->user()->hasRole('admin')) {
            $rol = Rol::find($request->rol_id);
            $usuario = Usuario::find($request->usuario_id);

            if (!in_array($rol->nombre, ['inquilino', 'posible_inquilino'])) {
                return redirect()->route('usuario_rol.create')
                    ->with('error', 'No tiene permiso para asignar este rol.');
            }

            // Verificar que el usuario no tenga roles de admin o propietario
            if ($usuario->hasRole(['admin', 'propietario'])) {
                return redirect()->route('usuario_rol.create')
                    ->with('error', 'No puede modificar los roles de un administrador o propietario.');
            }
        }

        UsuarioRol::create($request->all());
        return redirect()->route('usuario_rol.index')->with('success', 'Relación usuario-rol creada correctamente.');
    }

    public function show($id)
    {
        $usuarioRol = UsuarioRol::with('usuario', 'rol')->findOrFail($id);

        // Si es propietario, verificar que solo ve relaciones permitidas
        if (!auth()->user()->hasRole('admin')) {
            $rol = $usuarioRol->rol;
            $usuario = $usuarioRol->usuario;

            if (!in_array($rol->nombre, ['inquilino', 'posible_inquilino']) ||
                $usuario->hasRole(['admin', 'propietario'])) {
                abort(403, 'No tiene permiso para ver esta relación usuario-rol.');
            }
        }

        return view('admin.usuario_rol.show', compact('usuarioRol'));
    }

    public function edit($id)
    {
        $usuarioRol = UsuarioRol::findOrFail($id);

        // Si es propietario, verificar que solo edita relaciones permitidas
        if (!auth()->user()->hasRole('admin')) {
            $rol = $usuarioRol->rol;
            $usuario = $usuarioRol->usuario;

            if (!in_array($rol->nombre, ['inquilino', 'posible_inquilino']) ||
                $usuario->hasRole(['admin', 'propietario'])) {
                abort(403, 'No tiene permiso para editar esta relación usuario-rol.');
            }
        }

        // Admin puede asignar cualquier rol a cualquier usuario
        if (auth()->user()->hasRole('admin')) {
            $usuarios = Usuario::all();
            $roles = Rol::all();
        }
        // Propietario con restricciones
        else {
            $usuarios = Usuario::whereHas('roles', function($query) {
                $query->whereIn('nombre', ['inquilino', 'posible_inquilino']);
            })->get();

            $roles = Rol::whereIn('nombre', ['inquilino', 'posible_inquilino'])->get();
        }

        return view('admin.usuario_rol.edit', compact('usuarioRol', 'usuarios', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $usuarioRol = UsuarioRol::findOrFail($id);

        // Si es propietario, verificar que solo actualiza relaciones permitidas
        if (!auth()->user()->hasRole('admin')) {
            $rol = $usuarioRol->rol;
            $usuario = $usuarioRol->usuario;

            if (!in_array($rol->nombre, ['inquilino', 'posible_inquilino']) ||
                $usuario->hasRole(['admin', 'propietario'])) {
                abort(403, 'No tiene permiso para actualizar esta relación usuario-rol.');
            }
        }

        $request->validate([
            'usuario_id' => 'required|exists:usuario,id',
            'rol_id' => 'required|exists:rol,id',
        ]);

        // Verificar que la nueva relación no exista ya (excepto la actual)
        $existente = UsuarioRol::where('usuario_id', $request->usuario_id)
            ->where('rol_id', $request->rol_id)
            ->where('id', '!=', $id)
            ->first();

        if ($existente) {
            return redirect()->route('usuario_rol.edit', $id)
                ->with('error', 'Esta relación usuario-rol ya existe.');
        }

        // Si es propietario, verificar que solo asigna roles permitidos
        if (!auth()->user()->hasRole('admin')) {
            $rol = Rol::find($request->rol_id);
            $usuario = Usuario::find($request->usuario_id);

            if (!in_array($rol->nombre, ['inquilino', 'posible_inquilino'])) {
                return redirect()->route('usuario_rol.edit', $id)
                    ->with('error', 'No tiene permiso para asignar este rol.');
            }

            // Verificar que el usuario no tenga roles de admin o propietario
            if ($usuario->hasRole(['admin', 'propietario'])) {
                return redirect()->route('usuario_rol.edit', $id)
                    ->with('error', 'No puede modificar los roles de un administrador o propietario.');
            }
        }

        $usuarioRol->update($request->all());
        return redirect()->route('usuario_rol.index')->with('success', 'Relación usuario-rol actualizada correctamente.');
    }

    public function destroy($id)
    {
        $usuarioRol = UsuarioRol::findOrFail($id);

        // Si es propietario, verificar que solo elimina relaciones permitidas
        if (!auth()->user()->hasRole('admin')) {
            $rol = $usuarioRol->rol;
            $usuario = $usuarioRol->usuario;

            if (!in_array($rol->nombre, ['inquilino', 'posible_inquilino']) ||
                $usuario->hasRole(['admin', 'propietario'])) {
                abort(403, 'No tiene permiso para eliminar esta relación usuario-rol.');
            }
        }

        // No permitir eliminar la última relación de rol de admin del sistema
        if ($usuarioRol->rol->nombre == 'admin') {
            // Contar cuántos usuarios tienen el rol admin
            $adminCount = UsuarioRol::whereHas('rol', function($query) {
                $query->where('nombre', 'admin');
            })->count();

            if ($adminCount <= 1) {
                return redirect()->route('usuario_rol.index')
                    ->with('error', 'No se puede eliminar el último rol de administrador del sistema.');
            }
        }

        $usuarioRol->delete();

        return redirect()->route('usuario_rol.index')->with('success', 'Relación usuario-rol eliminada correctamente.');
    }

    /**
     * Método para asignar múltiples roles a un usuario
     */
    public function asignarMultiplesRoles()
    {
        // Admin puede asignar cualquier rol a cualquier usuario
        if (auth()->user()->hasRole('admin')) {
            $usuarios = Usuario::all();
            $roles = Rol::all();
        }
        // Propietario con restricciones
        else {
            $usuarios = Usuario::whereHas('roles', function($query) {
                $query->whereIn('nombre', ['inquilino', 'posible_inquilino']);
            })->get();

            $roles = Rol::whereIn('nombre', ['inquilino', 'posible_inquilino'])->get();
        }

        return view('admin.usuario_rol.asignar_multiples', compact('usuarios', 'roles'));
    }

    /**
     * Guardar múltiples asignaciones de roles a un usuario
     */
    public function guardarMultiplesRoles(Request $request)
    {
        $request->validate([
            'usuario_id' => 'required|exists:usuario,id',
            'rol_ids' => 'required|array',
            'rol_ids.*' => 'exists:rol,id',
        ]);

        $usuario = Usuario::find($request->usuario_id);
        $rolIds = $request->rol_ids;

        // Si es propietario, verificar restricciones
        if (!auth()->user()->hasRole('admin')) {
            // Verificar que el usuario no tenga roles de admin o propietario
            if ($usuario->hasRole(['admin', 'propietario'])) {
                return redirect()->route('usuario_rol.asignar_multiples')
                    ->with('error', 'No puede modificar los roles de un administrador o propietario.');
            }

            // Verificar que todos los roles seleccionados son permitidos
            $rolesPermitidos = Rol::whereIn('nombre', ['inquilino', 'posible_inquilino'])
                ->pluck('id')
                ->toArray();

            foreach ($rolIds as $rolId) {
                if (!in_array($rolId, $rolesPermitidos)) {
                    return redirect()->route('usuario_rol.asignar_multiples')
                        ->with('error', 'No tiene permiso para asignar algunos de los roles seleccionados.');
                }
            }
        }

        // Si se está intentando quitar el rol admin y es el último, evitarlo
        if ($usuario->hasRole('admin')) {
            $tieneAdmin = in_array(Rol::where('nombre', 'admin')->first()->id, $rolIds);

            if (!$tieneAdmin) {
                $adminCount = UsuarioRol::whereHas('rol', function($query) {
                    $query->where('nombre', 'admin');
                })->count();

                if ($adminCount <= 1) {
                    return redirect()->route('usuario_rol.asignar_multiples')
                        ->with('error', 'No se puede eliminar el último rol de administrador del sistema.');
                }
            }
        }

        // Eliminar todas las relaciones existentes para este usuario
        UsuarioRol::where('usuario_id', $usuario->id)->delete();

        // Crear las nuevas relaciones
        foreach ($rolIds as $rolId) {
            UsuarioRol::create([
                'usuario_id' => $usuario->id,
                'rol_id' => $rolId,
            ]);
        }

        return redirect()->route('usuario_rol.index')
            ->with('success', 'Roles asignados correctamente al usuario.');
    }
}
