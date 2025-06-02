<?php

namespace App\Http\Controllers;

use App\Models\ReporteProblema;
use App\Models\Usuario;
use App\Models\Apartamento;
use App\Models\Contrato;
use App\Models\Rol;
use Illuminate\Http\Request;

class ReporteProblemaController extends Controller
{
    /**
     * Constructor: añade middleware de permisos
     */
    public function __construct()
    {
        $this->middleware('permission:ver_reporte_problema')->only(['index', 'show']);
        $this->middleware('permission:crear_reporte_problema')->only(['create', 'store']);
        $this->middleware('permission:editar_reporte_problema')->only(['edit', 'update']);
        $this->middleware('permission:eliminar_reporte_problema')->only(['destroy']);
    }

    // Método para mostrar la lista de reportes (Index)
    public function index()
    {
        // Admin y propietario pueden ver todos los reportes
        if (auth()->user()->hasRole(['admin', 'propietario'])) {
            $reportes = ReporteProblema::with('apartamento', 'usuario')->orderBy('id', 'desc')->get();
        }
        // Inquilino solo ve sus propios reportes
        else {
            $reportes = ReporteProblema::with('apartamento', 'usuario')
                ->where('usuario_id', auth()->id())
                ->orderBy('id', 'desc')
                ->get();
        }

        return view('admin.reporte_problema.index', compact('reportes'));
    }

    // Método para mostrar el formulario de creación (Create)
    public function create()
    {
        // Obtener el ID del rol "inquilino"
        $rolInquilino = Rol::where('nombre', 'inquilino')->first();

        // Si es admin o propietario, puede seleccionar cualquier usuario con rol inquilino
        if (auth()->user()->hasRole(['admin', 'propietario'])) {
            if ($rolInquilino) {
                $usuarios = Usuario::whereHas('roles', function($query) use ($rolInquilino) {
                    $query->where('rol_id', $rolInquilino->id);
                })->orderBy('nombre', 'asc')->get();
            } else {
                $usuarios = collect(); // Colección vacía si no se encuentra el rol
            }
        }
        // Si es inquilino, solo puede reportar problemas como su propio usuario
        else {
            $usuarios = Usuario::where('id', auth()->id())->get();
        }

        // Obtener todos los apartamentos
        $apartamentos = Apartamento::orderBy('numero_apartamento', 'asc')->get();

        // Estados disponibles
        $estados = [
            'pendiente' => 'Pendiente',
            'atendido' => 'Atendido',
            'cerrado' => 'Cerrado'
        ];

        // Tipos de problemas comunes
        $tipos = [
            'plomeria' => 'Plomería',
            'electricidad' => 'Electricidad',
            'estructura' => 'Estructura',
            'seguridad' => 'Seguridad',
            'limpieza' => 'Limpieza',
            'otro' => 'Otro'
        ];

        return view('admin.reporte_problema.create', compact('usuarios', 'apartamentos', 'estados', 'tipos'));
    }

    // Método para guardar un nuevo reporte (Store)
    public function store(Request $request)
    {
        $request->validate([
            'apartamento_id' => 'required|exists:apartamento,id',
            'usuario_id' => 'required|exists:usuario,id',
            'descripcion' => 'required|string|max:500',
            'tipo' => 'nullable|string|max:50',
            'estado' => 'required|string|in:pendiente,atendido,cerrado',
        ]);

        // Si es inquilino, forzar que el reporte sea para su propio usuario
        if (auth()->user()->hasRole('inquilino')) {
            $request->merge(['usuario_id' => auth()->id()]);

            // Verificar que el apartamento pertenezca a un contrato activo del usuario
            $tieneContrato = Contrato::where('usuario_id', auth()->id())
                ->where('apartamento_id', $request->apartamento_id)
                ->where('estado', 'activo')
                ->exists();

            if (!$tieneContrato) {
                return back()->with('error', 'Solo puede reportar problemas de apartamentos que tiene contratados.');
            }
        }

        // Registrar fecha de reporte
        $request->merge(['fecha_reporte' => now()]);

        ReporteProblema::create($request->all());

        return redirect()->route('reporte_problema.index')
                         ->with('success', 'Reporte de problema creado correctamente.');
    }

    // Método para mostrar un reporte específico (Show)
    public function show($id)
    {
        $reporteProblema = ReporteProblema::with('apartamento', 'usuario')->findOrFail($id);

        // Si es inquilino, verificar que sea su propio reporte
        if (auth()->user()->hasRole('inquilino') && $reporteProblema->usuario_id != auth()->id()) {
            abort(403, 'No tiene permiso para ver este reporte.');
        }

        return view('admin.reporte_problema.show', compact('reporteProblema'));
    }

    // Método para mostrar el formulario de edición (Edit)
    public function edit($id)
    {
        $reporte = ReporteProblema::findOrFail($id);

        // Inquilino solo puede editar sus propios reportes y solo si están pendientes
        if (auth()->user()->hasRole('inquilino')) {
            if ($reporte->usuario_id != auth()->id()) {
                abort(403, 'No tiene permiso para editar este reporte.');
            }

            if ($reporte->estado != 'pendiente') {
                return redirect()->route('reporte_problema.show', $reporte->id)
                    ->with('error', 'No se pueden editar reportes que ya han sido atendidos.');
            }
        }

        // Obtener el ID del rol "inquilino"
        $rolInquilino = Rol::where('nombre', 'inquilino')->first();

        // Para admin y propietario
        if (auth()->user()->hasRole(['admin', 'propietario'])) {
            if ($rolInquilino) {
                $usuarios = Usuario::whereHas('roles', function($query) use ($rolInquilino) {
                    $query->where('rol_id', $rolInquilino->id);
                })->orderBy('nombre', 'asc')->get();
            } else {
                $usuarios = collect(); // Colección vacía si no se encuentra el rol
            }
        }
        // Para inquilino
        else {
            $usuarios = Usuario::where('id', auth()->id())->get();
        }

        // Obtener todos los apartamentos
        $apartamentos = Apartamento::orderBy('numero_apartamento', 'asc')->get();

        // Estados disponibles
        $estados = [
            'pendiente' => 'Pendiente',
            'atendido' => 'Atendido',
            'cerrado' => 'Cerrado'
        ];

        // Tipos de problemas comunes
        $tipos = [
            'plomeria' => 'Plomería',
            'electricidad' => 'Electricidad',
            'estructura' => 'Estructura',
            'seguridad' => 'Seguridad',
            'limpieza' => 'Limpieza',
            'otro' => 'Otro'
        ];

        return view('admin.reporte_problema.edit', compact('reporte', 'usuarios', 'apartamentos', 'estados', 'tipos'));
    }

    // Método para actualizar un reporte (Update)
    public function update(Request $request, $id)
    {
        $reporte = ReporteProblema::findOrFail($id);

        // Inquilino solo puede editar sus propios reportes y solo si están pendientes
        if (auth()->user()->hasRole('inquilino')) {
            if ($reporte->usuario_id != auth()->id()) {
                abort(403, 'No tiene permiso para actualizar este reporte.');
            }

            if ($reporte->estado != 'pendiente') {
                return redirect()->route('reporte_problema.show', $reporte->id)
                    ->with('error', 'No se pueden actualizar reportes que ya han sido atendidos.');
            }

            // Inquilinos solo pueden editar la descripción y el tipo
            $request->validate([
                'descripcion' => 'required|string|max:500',
                'tipo' => 'nullable|string|max:50',
            ]);

            $reporte->update([
                'descripcion' => $request->descripcion,
                'tipo' => $request->tipo,
            ]);
        }
        // Admin y propietario pueden actualizar todo, incluyendo el estado
        else {
            $request->validate([
                'apartamento_id' => 'required|exists:apartamento,id',
                'usuario_id' => 'required|exists:usuario,id',
                'descripcion' => 'required|string|max:500',
                'tipo' => 'nullable|string|max:50',
                'estado' => 'required|string|in:pendiente,atendido,cerrado',
            ]);

            $reporte->update($request->all());
        }

        return redirect()->route('reporte_problema.index')
                         ->with('success', 'Reporte de problema actualizado correctamente.');
    }

    // Método para eliminar un reporte (Destroy)
    public function destroy($id)
    {
        $reporte = ReporteProblema::findOrFail($id);

        // Solo admin puede eliminar reportes o el propietario si están pendientes
        if (auth()->user()->hasRole('propietario') && $reporte->estado != 'pendiente') {
            return redirect()->route('reporte_problema.index')
                ->with('error', 'Solo se pueden eliminar reportes pendientes.');
        }

        // Inquilino solo puede eliminar sus propios reportes pendientes
        if (auth()->user()->hasRole('inquilino')) {
            if ($reporte->usuario_id != auth()->id()) {
                abort(403, 'No tiene permiso para eliminar este reporte.');
            }

            if ($reporte->estado != 'pendiente') {
                return redirect()->route('reporte_problema.index')
                    ->with('error', 'Solo se pueden eliminar reportes pendientes.');
            }
        }

        $reporte->delete();

        return redirect()->route('reporte_problema.index')
                         ->with('success', 'Reporte de problema eliminado correctamente.');
    }

    // Método para cambiar rápidamente el estado de un reporte (acción adicional)
    public function cambiarEstado(Request $request, $id)
    {
        // Solo admin y propietario pueden cambiar el estado
        if (!auth()->user()->hasRole(['admin', 'propietario'])) {
            abort(403, 'No tiene permiso para cambiar el estado.');
        }

        $request->validate([
            'estado' => 'required|string|in:pendiente,atendido,cerrado',
        ]);

        $reporte = ReporteProblema::findOrFail($id);
        $reporte->update(['estado' => $request->estado]);

        return redirect()->route('reporte_problema.show', $id)
            ->with('success', 'Estado del reporte actualizado correctamente.');
    }
}
