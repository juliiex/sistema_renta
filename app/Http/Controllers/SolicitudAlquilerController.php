<?php

namespace App\Http\Controllers;

use App\Models\SolicitudAlquiler;
use App\Models\Usuario;
use App\Models\Apartamento;
use App\Models\Contrato;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SolicitudAlquilerController extends Controller
{
    /**
     * Constructor: añade middleware de permisos
     */
    public function __construct()
    {
        $this->middleware('permission:ver_solicitud_alquiler')->only(['index', 'show']);
        $this->middleware('permission:crear_solicitud_alquiler')->only(['create', 'store']);
        $this->middleware('permission:editar_solicitud_alquiler')->only(['edit', 'update']);
        $this->middleware('permission:eliminar_solicitud_alquiler')->only(['destroy']);
    }

    // Mostrar todas las solicitudes de alquiler
    public function index()
    {
        // Admin y propietario ven todas las solicitudes
        if (auth()->user()->hasRole(['admin', 'propietario'])) {
            $solicitudes = SolicitudAlquiler::with('usuario', 'apartamento')->get();
        }
        // Inquilinos y posibles inquilinos solo ven sus propias solicitudes
        else {
            $solicitudes = SolicitudAlquiler::with('usuario', 'apartamento')
                ->where('usuario_id', auth()->id())
                ->get();
        }

        return view('admin.solicitudes.index', compact('solicitudes'));
    }

    // Mostrar formulario para crear una nueva solicitud de alquiler
    public function create()
    {
        // Para admin y propietario, mostrar usuarios que NO sean admin ni propietario
        if (auth()->user()->hasRole(['admin', 'propietario'])) {
            // Obtener IDs de usuarios que son admin o propietario (para excluirlos)
            $rolAdminId = Rol::where('nombre', 'admin')->pluck('id')->first();
            $rolPropietarioId = Rol::where('nombre', 'propietario')->pluck('id')->first();

            // Obtener IDs de usuarios con roles de admin o propietario
            $usuariosExcluir = DB::table('usuarios_roles')
                ->whereIn('rol_id', [$rolAdminId, $rolPropietarioId])
                ->pluck('usuario_id')
                ->toArray();

            // Traer usuarios que NO estén en la lista de exclusión
            $usuarios = Usuario::whereNotIn('id', $usuariosExcluir)
                ->orderBy('nombre', 'asc')
                ->get();

            // Si no hay usuarios elegibles, mostrar todos
            if ($usuarios->isEmpty()) {
                $usuarios = Usuario::orderBy('nombre', 'asc')->get();
            }

            // Buscar apartamentos disponibles con flexibilidad en el estado
            $apartamentos = Apartamento::where(function($query) {
                    $query->whereRaw('LOWER(estado) = ?', ['disponible'])
                          ->orWhereRaw('LOWER(estado) = ?', ['disponibles'])
                          ->orWhereRaw('LOWER(estado) = ?', ['free'])
                          ->orWhereRaw('LOWER(estado) = ?', ['available']);
                })
                ->orderBy('numero_apartamento', 'asc')
                ->get();
        }
        // Para inquilinos y posibles inquilinos
        else {
            $usuarios = Usuario::where('id', auth()->id())->get();

            // Buscar apartamentos disponibles con flexibilidad en el estado
            $apartamentos = Apartamento::where(function($query) {
                    $query->whereRaw('LOWER(estado) = ?', ['disponible'])
                          ->orWhereRaw('LOWER(estado) = ?', ['disponibles'])
                          ->orWhereRaw('LOWER(estado) = ?', ['free'])
                          ->orWhereRaw('LOWER(estado) = ?', ['available']);
                })
                ->orderBy('numero_apartamento', 'asc')
                ->get();
        }

        return view('admin.solicitudes.create', compact('usuarios', 'apartamentos'));
    }

    // Guardar una nueva solicitud de alquiler
    public function store(Request $request)
    {
        // Validación de los datos recibidos
        $request->validate([
            'usuario_id' => 'required|exists:usuario,id',
            'apartamento_id' => 'required|exists:apartamento,id',
        ]);

        // Si no es admin o propietario, forzar que la solicitud sea para el usuario autenticado
        if (!auth()->user()->hasRole(['admin', 'propietario'])) {
            $request->merge(['usuario_id' => auth()->id()]);
        }

        // Verificar que el usuario no sea admin ni propietario
        $usuario = Usuario::find($request->usuario_id);
        if ($usuario->hasRole(['admin', 'propietario'])) {
            return redirect()->back()->with('error', 'No se pueden crear solicitudes para administradores o propietarios.');
        }

        // Verificar que el apartamento esté disponible (con mayor flexibilidad)
        $apartamento = Apartamento::find($request->apartamento_id);
        if (!$apartamento || !in_array(strtolower($apartamento->estado), ['disponible', 'disponibles', 'free', 'available'])) {
            return back()->with('error', 'Este apartamento ya no está disponible. Estado actual: ' . $apartamento->estado);
        }

        // Verificar si el usuario ya tiene una solicitud pendiente para este apartamento
        $existente = SolicitudAlquiler::where('usuario_id', $request->usuario_id)
            ->where('apartamento_id', $request->apartamento_id)
            ->where('estado_solicitud', 'pendiente')
            ->exists();

        if ($existente) {
            return back()->with('error', 'Ya existe una solicitud pendiente para este apartamento.');
        }

        // Crear la solicitud con estado pendiente por defecto
        SolicitudAlquiler::create([
            'usuario_id' => $request->usuario_id,
            'apartamento_id' => $request->apartamento_id,
            'estado_solicitud' => 'pendiente', // Por defecto siempre pendiente
            'fecha_solicitud' => now(), // Fecha actual
        ]);

        return redirect()->route('solicitudes.index')->with('success', 'Solicitud de alquiler creada exitosamente.');
    }

    // Mostrar una solicitud de alquiler específica
    public function show($id)
    {
        $solicitud = SolicitudAlquiler::with('usuario', 'apartamento')->findOrFail($id);

        // Verificar permiso para ver esta solicitud
        if (!auth()->user()->hasRole(['admin', 'propietario']) &&
            $solicitud->usuario_id != auth()->id()) {
            abort(403, 'No tiene permiso para ver esta solicitud.');
        }

        return view('admin.solicitudes.show', compact('solicitud'));
    }

    // Mostrar formulario para editar una solicitud de alquiler
    public function edit($id)
    {
        $solicitud = SolicitudAlquiler::findOrFail($id);

        // Solo admin y propietario pueden editar cualquier solicitud
        // Para inquilino y posible inquilino, solo pueden editar sus propias solicitudes y si están pendientes
        if (!auth()->user()->hasRole(['admin', 'propietario'])) {
            if ($solicitud->usuario_id != auth()->id()) {
                abort(403, 'No tiene permiso para editar esta solicitud.');
            }

            if ($solicitud->estado_solicitud != 'pendiente') {
                return redirect()->route('solicitudes.show', $solicitud->id)
                    ->with('error', 'No se puede editar una solicitud que ya ha sido procesada.');
            }
        }

        // Para admin y propietario
        if (auth()->user()->hasRole(['admin', 'propietario'])) {
            // Obtener IDs de usuarios que son admin o propietario (para excluirlos)
            $rolAdminId = Rol::where('nombre', 'admin')->pluck('id')->first();
            $rolPropietarioId = Rol::where('nombre', 'propietario')->pluck('id')->first();

            // Obtener IDs de usuarios con roles de admin o propietario
            $usuariosExcluir = DB::table('usuarios_roles')
                ->whereIn('rol_id', [$rolAdminId, $rolPropietarioId])
                ->pluck('usuario_id')
                ->toArray();

            // Asegurarse de NO excluir al usuario actual de la solicitud
            // (incluso si es admin o propietario por alguna razón anterior)
            $usuariosExcluir = array_diff($usuariosExcluir, [$solicitud->usuario_id]);

            // Traer usuarios que NO estén en la lista de exclusión
            $usuarios = Usuario::whereNotIn('id', $usuariosExcluir)
                ->orderBy('nombre', 'asc')
                ->get();

            // Si la solicitud está pendiente, mostrar todos los apartamentos disponibles y el actual
            // con mayor flexibilidad en los estados
            if ($solicitud->estado_solicitud == 'pendiente') {
                $apartamentos = Apartamento::where(function($query) {
                        $query->whereRaw('LOWER(estado) = ?', ['disponible'])
                              ->orWhereRaw('LOWER(estado) = ?', ['disponibles'])
                              ->orWhereRaw('LOWER(estado) = ?', ['free'])
                              ->orWhereRaw('LOWER(estado) = ?', ['available']);
                    })
                    ->orWhere('id', $solicitud->apartamento_id)
                    ->get();
            } else {
                // Si ya está procesada, solo mostrar el apartamento actual
                $apartamentos = Apartamento::where('id', $solicitud->apartamento_id)->get();
            }
        }
        // Para inquilinos y posibles inquilinos
        else {
            $usuarios = Usuario::where('id', auth()->id())->get();

            // Si la solicitud está pendiente, mostrar apartamentos disponibles y el actual
            // con mayor flexibilidad en los estados
            $apartamentos = Apartamento::where(function($query) {
                    $query->whereRaw('LOWER(estado) = ?', ['disponible'])
                          ->orWhereRaw('LOWER(estado) = ?', ['disponibles'])
                          ->orWhereRaw('LOWER(estado) = ?', ['free'])
                          ->orWhereRaw('LOWER(estado) = ?', ['available']);
                })
                ->orWhere('id', $solicitud->apartamento_id)
                ->get();
        }

        return view('admin.solicitudes.edit', compact('solicitud', 'usuarios', 'apartamentos'));
    }

    // Actualizar una solicitud de alquiler existente
    public function update(Request $request, $id)
    {
        $solicitud = SolicitudAlquiler::findOrFail($id);

        // Verificar permisos
        if (!auth()->user()->hasRole(['admin', 'propietario'])) {
            if ($solicitud->usuario_id != auth()->id()) {
                abort(403, 'No tiene permiso para actualizar esta solicitud.');
            }

            if ($solicitud->estado_solicitud != 'pendiente') {
                return redirect()->route('solicitudes.show', $solicitud->id)
                    ->with('error', 'No se puede actualizar una solicitud que ya ha sido procesada.');
            }
        }

        // Validación de los datos recibidos
        if (auth()->user()->hasRole(['admin', 'propietario'])) {
            $request->validate([
                'usuario_id' => 'required|exists:usuario,id',
                'apartamento_id' => 'required|exists:apartamento,id',
                'estado_solicitud' => 'required|string|in:pendiente,aprobada,rechazada',
            ]);
        } else {
            $request->validate([
                'apartamento_id' => 'required|exists:apartamento,id',
            ]);

            // Forzar usuario_id y estado pendiente para usuarios normales
            $request->merge([
                'usuario_id' => auth()->id(),
                'estado_solicitud' => 'pendiente'
            ]);
        }

        // Si cambia el usuario, verificar que no sea admin ni propietario
        if ($solicitud->usuario_id != $request->usuario_id) {
            $nuevoUsuario = Usuario::find($request->usuario_id);
            if ($nuevoUsuario->hasRole(['admin', 'propietario'])) {
                return redirect()->back()->with('error', 'No se pueden crear solicitudes para administradores o propietarios.');
            }
        }

        // Verificar que el apartamento esté disponible (si está cambiando)
        // con mayor flexibilidad en los estados
        if ($solicitud->apartamento_id != $request->apartamento_id) {
            $apartamento = Apartamento::find($request->apartamento_id);
            if (!$apartamento || !in_array(strtolower($apartamento->estado), ['disponible', 'disponibles', 'free', 'available'])) {
                return back()->with('error', 'Este apartamento ya no está disponible. Estado actual: ' . $apartamento->estado);
            }
        }

        // Si se está aprobando la solicitud
        if ($request->estado_solicitud == 'aprobada' && $solicitud->estado_solicitud != 'aprobada') {
            // Verificar que el apartamento siga disponible
            // con mayor flexibilidad en los estados
            $apartamento = Apartamento::find($request->apartamento_id);
            if (!$apartamento || !in_array(strtolower($apartamento->estado), ['disponible', 'disponibles', 'free', 'available'])) {
                return back()->with('error', 'Este apartamento ya no está disponible. Estado actual: ' . $apartamento->estado);
            }

            // Cambiar el estado del apartamento a ocupado
            $apartamento->update(['estado' => 'ocupado']);

            // Asignar rol de inquilino si es posible inquilino
            $usuario = Usuario::find($request->usuario_id);
            if ($usuario->hasRole('posible_inquilino') && !$usuario->hasRole('inquilino')) {
                $rolInquilino = Rol::where('nombre', 'inquilino')->first();
                if ($rolInquilino) {
                    $usuario->roles()->attach($rolInquilino->id);
                }
            }
        }

        $solicitud->update([
            'usuario_id' => $request->usuario_id,
            'apartamento_id' => $request->apartamento_id,
            'estado_solicitud' => $request->estado_solicitud,
            'fecha_procesado' => ($request->estado_solicitud != 'pendiente' && $solicitud->estado_solicitud == 'pendiente') ? now() : $solicitud->fecha_procesado
        ]);

        return redirect()->route('solicitudes.index')->with('success', 'Solicitud de alquiler actualizada correctamente.');
    }

    // Eliminar una solicitud de alquiler
    public function destroy($id)
    {
        $solicitud = SolicitudAlquiler::findOrFail($id);

        // Solo admin y propietario pueden eliminar cualquier solicitud
        // Inquilinos y posibles inquilinos solo pueden eliminar sus propias solicitudes pendientes
        if (!auth()->user()->hasRole(['admin', 'propietario'])) {
            if ($solicitud->usuario_id != auth()->id()) {
                abort(403, 'No tiene permiso para eliminar esta solicitud.');
            }

            if ($solicitud->estado_solicitud != 'pendiente') {
                return redirect()->route('solicitudes.index')
                    ->with('error', 'No se puede eliminar una solicitud que ya ha sido procesada.');
            }
        }

        $solicitud->delete();

        return redirect()->route('solicitudes.index')->with('success', 'Solicitud de alquiler eliminada correctamente.');
    }

    // Método para aprobar rápidamente una solicitud
    public function aprobar($id)
    {
        // Solo admin y propietario pueden aprobar solicitudes
        if (!auth()->user()->hasRole(['admin', 'propietario'])) {
            abort(403, 'No tiene permiso para aprobar solicitudes.');
        }

        $solicitud = SolicitudAlquiler::findOrFail($id);

        if ($solicitud->estado_solicitud != 'pendiente') {
            return redirect()->route('solicitudes.show', $id)
                ->with('error', 'Esta solicitud ya fue procesada anteriormente.');
        }

        // Verificar que el apartamento sigue disponible
        // con mayor flexibilidad en los estados
        $apartamento = Apartamento::find($solicitud->apartamento_id);
        if (!$apartamento || !in_array(strtolower($apartamento->estado), ['disponible', 'disponibles', 'free', 'available'])) {
            return redirect()->route('solicitudes.show', $id)
                ->with('error', 'Este apartamento ya no está disponible. Estado actual: ' . $apartamento->estado);
        }

        // Cambiar el estado del apartamento
        $apartamento->update(['estado' => 'ocupado']);

        // Actualizar la solicitud
        $solicitud->update([
            'estado_solicitud' => 'aprobada',
            'fecha_procesado' => now()
        ]);

        // Asignar rol de inquilino si es posible inquilino
        $usuario = Usuario::find($solicitud->usuario_id);
        if ($usuario->hasRole('posible_inquilino') && !$usuario->hasRole('inquilino')) {
            $rolInquilino = Rol::where('nombre', 'inquilino')->first();
            if ($rolInquilino) {
                $usuario->roles()->attach($rolInquilino->id);
            }
        }

        return redirect()->route('solicitudes.index')
            ->with('success', 'Solicitud aprobada correctamente.');
    }

    // Método para rechazar rápidamente una solicitud
    public function rechazar($id)
    {
        // Solo admin y propietario pueden rechazar solicitudes
        if (!auth()->user()->hasRole(['admin', 'propietario'])) {
            abort(403, 'No tiene permiso para rechazar solicitudes.');
        }

        $solicitud = SolicitudAlquiler::findOrFail($id);

        if ($solicitud->estado_solicitud != 'pendiente') {
            return redirect()->route('solicitudes.show', $id)
                ->with('error', 'Esta solicitud ya fue procesada anteriormente.');
        }

        // Actualizar la solicitud
        $solicitud->update([
            'estado_solicitud' => 'rechazada',
            'fecha_procesado' => now()
        ]);

        return redirect()->route('solicitudes.index')
            ->with('success', 'Solicitud rechazada correctamente.');
    }
}
