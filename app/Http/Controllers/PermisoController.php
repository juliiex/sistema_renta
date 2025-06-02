<?php

namespace App\Http\Controllers;

use App\Models\Permiso;
use Illuminate\Http\Request;

class PermisoController extends Controller
{
    /**
     * Constructor: añade middleware de permisos
     */
    public function __construct()
    {
        // Solo admin debería poder gestionar permisos del sistema
        $this->middleware('role:admin');

        $this->middleware('permission:ver_permiso')->only(['index', 'show']);
        $this->middleware('permission:crear_permiso')->only(['create', 'store']);
        $this->middleware('permission:editar_permiso')->only(['edit', 'update']);
        $this->middleware('permission:eliminar_permiso')->only(['destroy']);
    }

    // Método para mostrar la lista de permisos
    public function index()
    {
        $permisos = Permiso::orderBy('id', 'asc')->get();

        // Separar permisos del sistema de los personalizados
        $permisosDelSistema = $permisos->filter(function($permiso) {
            return $this->esPermisoDelSistema($permiso->nombre);
        });

        $permisosPersonalizados = $permisos->filter(function($permiso) {
            return !$this->esPermisoDelSistema($permiso->nombre);
        });

        return view('admin.permiso.index', compact('permisosDelSistema', 'permisosPersonalizados'));
    }

    // Método para mostrar el formulario de creación
    public function create()
    {
        // Lista de módulos disponibles en el sistema para generar permisos
        $modulos = [
            'usuario', 'apartamento', 'edificio', 'contrato',
            'estado_alquiler', 'evaluacion', 'solicitud_alquiler',
            'recordatorio_pago', 'reporte_problema', 'queja',
            'permiso', 'rol'
        ];

        return view('admin.permiso.create', compact('modulos'));
    }

    // Método para almacenar un nuevo permiso
    public function store(Request $request)
    {
        $request->validate([
            'tipo_permiso' => 'required|string|in:ver,crear,editar,eliminar,personalizado',
            'modulo' => 'required_unless:tipo_permiso,personalizado|string',
            'nombre_personalizado' => 'required_if:tipo_permiso,personalizado|string|max:255',
        ]);

        if ($request->tipo_permiso === 'personalizado') {
            $nombre = $request->nombre_personalizado;
        } else {
            $nombre = $request->tipo_permiso . '_' . $request->modulo;
        }

        // Verificar si ya existe
        if (Permiso::where('nombre', $nombre)->exists()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'El permiso ya existe en el sistema.');
        }

        Permiso::create([
            'nombre' => $nombre,
            'guard_name' => 'web',
        ]);

        return redirect()->route('permiso.index')->with('success', 'Permiso creado exitosamente.');
    }

    // Método para mostrar los detalles de un permiso
    public function show($id)
    {
        $permiso = Permiso::findOrFail($id);
        $esPermisoDelSistema = $this->esPermisoDelSistema($permiso->nombre);

        // Obtener los roles que tienen este permiso
        $roles = $permiso->roles()->get();

        return view('admin.permiso.show', compact('permiso', 'esPermisoDelSistema', 'roles'));
    }

    // Método para mostrar el formulario de edición
    public function edit($id)
    {
        $permiso = Permiso::findOrFail($id);

        // No permitir editar permisos del sistema
        if ($this->esPermisoDelSistema($permiso->nombre)) {
            return redirect()->route('permiso.index')
                ->with('error', 'Los permisos del sistema no pueden ser modificados.');
        }

        return view('admin.permiso.edit', compact('permiso'));
    }

    // Método para actualizar un permiso
    public function update(Request $request, $id)
    {
        $permiso = Permiso::findOrFail($id);

        // No permitir editar permisos del sistema
        if ($this->esPermisoDelSistema($permiso->nombre)) {
            return redirect()->route('permiso.index')
                ->with('error', 'Los permisos del sistema no pueden ser modificados.');
        }

        $request->validate([
            'nombre' => 'required|string|max:255|unique:permiso,nombre,' . $permiso->id,
        ]);

        $permiso->update([
            'nombre' => $request->nombre,
            'guard_name' => 'web',
        ]);

        return redirect()->route('permiso.index')->with('success', 'Permiso actualizado exitosamente.');
    }

    // Método para eliminar un permiso
    public function destroy($id)
    {
        $permiso = Permiso::findOrFail($id);

        // No permitir eliminar permisos del sistema
        if ($this->esPermisoDelSistema($permiso->nombre)) {
            return redirect()->route('permiso.index')
                ->with('error', 'Los permisos del sistema no pueden ser eliminados.');
        }

        // Verificar si el permiso está asignado a algún rol
        if ($permiso->roles()->count() > 0) {
            return redirect()->route('permiso.index')
                ->with('error', 'No se puede eliminar el permiso porque está asignado a uno o más roles.');
        }

        $permiso->delete();

        return redirect()->route('permiso.index')->with('success', 'Permiso eliminado exitosamente.');
    }

    /**
     * Verifica si un permiso es parte del sistema base y no debe ser modificado
     */
    private function esPermisoDelSistema($nombrePermiso)
    {
        // Lista de permisos básicos del sistema que no deben ser modificados
        $permisosDelSistema = [
            'ver_usuario', 'crear_usuario', 'editar_usuario', 'eliminar_usuario',
            'ver_apartamento', 'crear_apartamento', 'editar_apartamento', 'eliminar_apartamento',
            'ver_edificio', 'crear_edificio', 'editar_edificio', 'eliminar_edificio',
            'ver_contrato', 'crear_contrato', 'editar_contrato', 'eliminar_contrato',
            'ver_estado_alquiler', 'crear_estado_alquiler', 'editar_estado_alquiler', 'eliminar_estado_alquiler',
            'ver_evaluacion', 'crear_evaluacion', 'editar_evaluacion', 'eliminar_evaluacion',
            'ver_solicitud_alquiler', 'crear_solicitud_alquiler', 'editar_solicitud_alquiler', 'eliminar_solicitud_alquiler',
            'ver_recordatorio_pago', 'crear_recordatorio_pago', 'editar_recordatorio_pago', 'eliminar_recordatorio_pago',
            'ver_reporte_problema', 'crear_reporte_problema', 'editar_reporte_problema', 'eliminar_reporte_problema',
            'ver_queja', 'crear_queja', 'editar_queja', 'eliminar_queja',
            'ver_permiso', 'crear_permiso', 'editar_permiso', 'eliminar_permiso',
            'ver_rol', 'crear_rol', 'editar_rol', 'eliminar_rol'
        ];

        return in_array($nombrePermiso, $permisosDelSistema);
    }
}
