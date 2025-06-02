<?php

namespace App\Http\Controllers;

use App\Models\RolPermiso;
use App\Models\Rol;
use App\Models\Permiso;
use Illuminate\Http\Request;

class RolPermisoController extends Controller
{
    /**
     * Constructor: añade middleware de permisos
     */
    public function __construct()
    {
        // Solo admin debería poder gestionar asignaciones de permisos a roles
        $this->middleware('role:admin');
    }

    public function index()
    {
        $rolPermisos = RolPermiso::with('rol', 'permiso')->get();
        return view('admin.rol_permiso.index', compact('rolPermisos'));
    }

    public function show($id)
    {
        $rolPermiso = RolPermiso::with('rol', 'permiso')->findOrFail($id);
        return view('admin.rol_permiso.show', compact('rolPermiso'));
    }

    public function create()
    {
        $roles = Rol::all();
        $permisos = Permiso::all();
        return view('admin.rol_permiso.create', compact('roles', 'permisos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'rol_id' => 'required|exists:rol,id',
            'permiso_id' => 'required|exists:permiso,id',
        ]);

        // Verificar que la relación no exista ya
        $existente = RolPermiso::where('rol_id', $request->rol_id)
            ->where('permiso_id', $request->permiso_id)
            ->first();

        if ($existente) {
            return redirect()->route('admin.rol_permiso.create')
                ->with('error', 'Esta relación rol-permiso ya existe.');
        }

        // Verificar si se está modificando un rol del sistema
        $rol = Rol::find($request->rol_id);
        if ($this->esRolDelSistema($rol->nombre)) {
            // Verificar si se está retirando un permiso esencial para ese rol
            $permiso = Permiso::find($request->permiso_id);
            if (!$this->esPermisoPermitidoParaRolDelSistema($rol->nombre, $permiso->nombre)) {
                return redirect()->route('admin.rol_permiso.create')
                    ->with('error', 'No se pueden modificar permisos esenciales para roles del sistema.');
            }
        }

        RolPermiso::create($request->only('rol_id', 'permiso_id'));

        // Redirige al index después de crear
        return redirect()->route('admin.rol_permiso.index')
                         ->with('success', 'Relación rol-permiso creada correctamente.');
    }

    public function edit($id)
    {
        $rolPermiso = RolPermiso::findOrFail($id);

        // No permitir editar relaciones de roles del sistema
        $rol = $rolPermiso->rol;
        if ($this->esRolDelSistema($rol->nombre)) {
            return redirect()->route('admin.rol_permiso.index')
                ->with('error', 'No se pueden modificar las relaciones de roles del sistema.');
        }

        $roles = Rol::all();
        $permisos = Permiso::all();
        return view('admin.rol_permiso.edit', compact('rolPermiso', 'roles', 'permisos'));
    }

    public function update(Request $request, $id)
    {
        $rolPermiso = RolPermiso::findOrFail($id);

        // No permitir editar relaciones de roles del sistema
        $rol = $rolPermiso->rol;
        if ($this->esRolDelSistema($rol->nombre)) {
            return redirect()->route('admin.rol_permiso.index')
                ->with('error', 'No se pueden modificar las relaciones de roles del sistema.');
        }

        $request->validate([
            'rol_id' => 'required|exists:rol,id',
            'permiso_id' => 'required|exists:permiso,id',
        ]);

        // Verificar que la nueva relación no exista ya (excepto la actual)
        $existente = RolPermiso::where('rol_id', $request->rol_id)
            ->where('permiso_id', $request->permiso_id)
            ->where('id', '!=', $id)
            ->first();

        if ($existente) {
            return redirect()->route('admin.rol_permiso.edit', $id)
                ->with('error', 'Esta relación rol-permiso ya existe.');
        }

        $rolPermiso->update($request->only('rol_id', 'permiso_id'));

        // Redirige al index después de actualizar
        return redirect()->route('admin.rol_permiso.index')
                         ->with('success', 'Relación rol-permiso actualizada correctamente.');
    }

    public function destroy($id)
    {
        $rolPermiso = RolPermiso::findOrFail($id);

        // No permitir eliminar relaciones de roles del sistema
        $rol = $rolPermiso->rol;
        if ($this->esRolDelSistema($rol->nombre)) {
            return redirect()->route('admin.rol_permiso.index')
                ->with('error', 'No se pueden eliminar las relaciones de roles del sistema.');
        }

        $rolPermiso->delete();

        return redirect()->route('admin.rol_permiso.index')
                         ->with('success', 'Relación rol-permiso eliminada correctamente.');
    }

    /**
     * Verifica si un rol es parte del sistema base
     */
    private function esRolDelSistema($nombreRol)
    {
        $rolesDelSistema = ['admin', 'propietario', 'inquilino', 'posible_inquilino'];
        return in_array($nombreRol, $rolesDelSistema);
    }

    /**
     * Verifica si un permiso es adecuado para un rol del sistema
     */
    private function esPermisoPermitidoParaRolDelSistema($nombreRol, $nombrePermiso)
    {
        // Aquí podrías implementar una lógica más compleja si fuera necesario
        // Por ahora, permitimos cualquier permiso adicional para roles del sistema
        return true;
    }

    /**
     * Método para asignar múltiples permisos a un rol
     */
    public function asignarMultiplesPermisos()
    {
        $roles = Rol::all();
        $permisos = Permiso::all();
        return view('admin.rol_permiso.asignar_multiples', compact('roles', 'permisos'));
    }

    /**
     * Guarda múltiples asignaciones de permisos a un rol
     */
    public function guardarMultiplesPermisos(Request $request)
    {
        $request->validate([
            'rol_id' => 'required|exists:rol,id',
            'permiso_ids' => 'required|array',
            'permiso_ids.*' => 'exists:permiso,id',
        ]);

        $rol = Rol::find($request->rol_id);
        $permisoIds = $request->permiso_ids;

        // Primero eliminar todas las relaciones existentes para este rol
        RolPermiso::where('rol_id', $rol->id)->delete();

        // Luego crear las nuevas relaciones
        foreach ($permisoIds as $permisoId) {
            RolPermiso::create([
                'rol_id' => $rol->id,
                'permiso_id' => $permisoId,
            ]);
        }

        return redirect()->route('admin.rol_permiso.index')
            ->with('success', 'Permisos asignados correctamente al rol.');
    }
}
