<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use Illuminate\Http\Request;

class RolController extends Controller
{
    /**
     * Constructor: añade middleware de permisos
     */
    public function __construct()
    {
        // Solo admin debería poder gestionar roles del sistema
        $this->middleware('role:admin');

        $this->middleware('permission:ver_rol')->only(['index', 'show']);
        $this->middleware('permission:crear_rol')->only(['create', 'store']);
        $this->middleware('permission:editar_rol')->only(['edit', 'update']);
        $this->middleware('permission:eliminar_rol')->only(['destroy']);
    }

    // Mostrar listado de roles en una vista
    public function index()
    {
        $roles = Rol::orderBy('id', 'asc')->get(); // ordenar por id ascendente
        return view('admin.rol.index', compact('roles'));
    }

    // Mostrar formulario para crear un nuevo rol
    public function create()
    {
        return view('admin.rol.create');
    }

    // Guardar un nuevo rol en BD
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255|unique:rol,nombre',
            'guard_name' => 'required|string|max:255',
        ]);

        // Asegurar que el guard_name tenga un valor por defecto
        if (empty($validatedData['guard_name'])) {
            $validatedData['guard_name'] = 'web';
        }

        Rol::create($validatedData);

        return redirect()->route('rol.index')->with('success', 'Rol creado exitosamente');
    }

    // Mostrar un rol específico
    public function show(Rol $rol)
    {
        return view('admin.rol.show', compact('rol'));
    }

    // Mostrar formulario para editar un rol existente
    public function edit(Rol $rol)
    {
        // No permitir editar roles del sistema
        if ($this->esRolDelSistema($rol->nombre)) {
            return redirect()->route('rol.index')
                ->with('error', 'Los roles del sistema no pueden ser modificados.');
        }

        return view('admin.rol.edit', compact('rol'));
    }

    // Actualizar un rol en BD
    public function update(Request $request, Rol $rol)
    {
        // No permitir editar roles del sistema
        if ($this->esRolDelSistema($rol->nombre)) {
            return redirect()->route('rol.index')
                ->with('error', 'Los roles del sistema no pueden ser modificados.');
        }

        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255|unique:rol,nombre,' . $rol->id,
            'guard_name' => 'required|string|max:255',
        ]);

        // Asegurar que el guard_name tenga un valor por defecto
        if (empty($validatedData['guard_name'])) {
            $validatedData['guard_name'] = 'web';
        }

        $rol->update($validatedData);

        return redirect()->route('rol.index')->with('success', 'Rol actualizado correctamente');
    }

    // Eliminar un rol
    public function destroy(Rol $rol)
    {
        // No permitir eliminar roles del sistema
        if ($this->esRolDelSistema($rol->nombre)) {
            return redirect()->route('rol.index')
                ->with('error', 'Los roles del sistema no pueden ser eliminados.');
        }

        // Verificar si el rol tiene usuarios asignados
        if ($rol->usuarios()->count() > 0) {
            return redirect()->route('rol.index')
                ->with('error', 'No se puede eliminar el rol porque tiene usuarios asignados.');
        }

        $rol->delete();
        return redirect()->route('rol.index')->with('success', 'Rol eliminado correctamente');
    }

    /**
     * Verifica si un rol es parte del sistema base y no debe ser modificado
     */
    private function esRolDelSistema($nombreRol)
    {
        // Lista de roles básicos del sistema que no deben ser modificados
        $rolesDelSistema = ['admin', 'propietario', 'inquilino', 'posible_inquilino'];

        return in_array($nombreRol, $rolesDelSistema);
    }
}
