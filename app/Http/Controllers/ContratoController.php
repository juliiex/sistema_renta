<?php

namespace App\Http\Controllers;

use App\Models\Contrato;
use App\Models\Apartamento;
use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ContratoController extends Controller
{
    /**
     * Constructor: añade middleware de permisos
     */
    public function __construct()
    {
        $this->middleware('permission:ver_contrato')->only(['index', 'show', 'trashed']);
        $this->middleware('permission:crear_contrato')->only(['create', 'store']);
        $this->middleware('permission:editar_contrato')->only(['edit', 'update', 'restore']);
        $this->middleware('permission:eliminar_contrato')->only(['destroy', 'forceDelete']);
    }

    /**
     * Muestra todos los contratos.
     */
    public function index()
    {
        // Para admin y propietario: todos los contratos
        if (auth()->user()->hasRole(['admin', 'propietario'])) {
            $contratos = Contrato::with('usuario', 'apartamento')->get();
        }
        // Para inquilinos: solo sus propios contratos
        else {
            $contratos = Contrato::with('usuario', 'apartamento')
                ->where('usuario_id', auth()->id())
                ->get();
        }

        return view('admin.contrato.index', compact('contratos'));
    }

    public function create()
    {
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
            ->orderBy('id', 'asc')
            ->get();

        // Si no hay usuarios elegibles, mostrar todos
        if ($usuarios->isEmpty()) {
            $usuarios = Usuario::orderBy('id', 'asc')->get();
        }

        // Solo apartamentos disponibles - probamos con todos los posibles formatos de "disponible"
        $apartamentos = Apartamento::where(function($query) {
                $query->where('estado', 'Disponible')
                      ->orWhere('estado', 'disponible')
                      ->orWhere('estado', 'DISPONIBLE');
            })
            ->orderBy('numero_apartamento', 'asc')
            ->get();

        return view('admin.contrato.create', compact('usuarios', 'apartamentos'));
    }

    /**
     * Busca usuarios por ID, correo o nombre
     */
    public function buscarUsuarios(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return response()->json([]);
        }

        // Obtener IDs de usuarios que son admin o propietario (para excluirlos)
        $rolAdminId = Rol::where('nombre', 'admin')->pluck('id')->first();
        $rolPropietarioId = Rol::where('nombre', 'propietario')->pluck('id')->first();

        // Obtener IDs de usuarios con roles de admin o propietario
        $usuariosExcluir = DB::table('usuarios_roles')
            ->whereIn('rol_id', [$rolAdminId, $rolPropietarioId])
            ->pluck('usuario_id')
            ->toArray();

        $usuarios = Usuario::whereNotIn('id', $usuariosExcluir)
            ->where(function($q) use ($query) {
                $q->where('id', $query)
                  ->orWhere('correo', 'like', "%{$query}%")
                  ->orWhere('nombre', 'like', "%{$query}%");
            })
            ->orderBy('id', 'asc')
            ->take(10)
            ->get(['id', 'nombre', 'correo']);

        return response()->json($usuarios);
    }

    /**
     * Actualiza el rol del usuario según corresponda sin crear registros duplicados
     */
    private function actualizarRolUsuario($usuarioId, $rolDestino)
    {
        // Verificar que tanto el usuarioId como el rolDestino no sean null
        if (!$usuarioId || !$rolDestino) {
            \Log::error("Error al actualizar rol: ID de usuario o rol es null", [
                'usuario_id' => $usuarioId,
                'rol_id' => $rolDestino
            ]);
            return;
        }

        // Primero verificamos si el usuario ya tiene el rol que queremos asignarle
        $yaExisteRolDestino = DB::table('usuarios_roles')
            ->where('usuario_id', $usuarioId)
            ->where('rol_id', $rolDestino)
            ->exists();

        if ($yaExisteRolDestino) {
            // Si ya tiene el rol destino, no hacemos nada
            return;
        }

        // Verificamos si el usuario tiene algún rol en la tabla usuarios_roles
        $tieneRol = DB::table('usuarios_roles')
            ->where('usuario_id', $usuarioId)
            ->exists();

        if ($tieneRol) {
            // Si tiene algún rol, lo actualizamos al nuevo rol
            DB::table('usuarios_roles')
                ->where('usuario_id', $usuarioId)
                ->update(['rol_id' => $rolDestino]);
        } else {
            // Si no tiene ningún rol, creamos un nuevo registro
            DB::table('usuarios_roles')->insert([
                'usuario_id' => $usuarioId,
                'rol_id' => $rolDestino
            ]);
        }
    }

    /**
     * Almacena un nuevo contrato en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'usuario_id' => 'required|exists:usuario,id',
            'apartamento_id' => 'required|exists:apartamento,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date',
            'firma_base64' => 'required',
        ]);

        // Verificar que el usuario no sea admin ni propietario
        $usuario = Usuario::find($request->usuario_id);
        if ($usuario->hasRole(['admin', 'propietario'])) {
            return redirect()->back()->with('error', 'No se pueden crear contratos para administradores o propietarios.');
        }

        // Verificar que el apartamento realmente exista y obtenerlo
        $apartamento = Apartamento::find($request->apartamento_id);
        if (!$apartamento) {
            return redirect()->back()->with('error', 'El apartamento seleccionado no existe.');
        }

        // Verificar si el apartamento está siendo utilizado en otro contrato activo
        $contratosActivos = Contrato::where('apartamento_id', $request->apartamento_id)
            ->where('estado', 'activo')
            ->count();

        if ($contratosActivos > 0) {
            return redirect()->back()->with('error', 'Este apartamento ya está asignado a otro contrato activo.');
        }

        // Procesar la firma como imagen
        $imagenBase64 = $request->firma_base64;
        $imagen = str_replace('data:image/png;base64,', '', $imagenBase64);
        $imagen = str_replace(' ', '+', $imagen);

        $nombreArchivo = 'firma_contrato_' . time() . '.png';
        $rutaArchivo = 'firmas/' . $nombreArchivo;

        Storage::disk('public')->put($rutaArchivo, base64_decode($imagen));

        // Crear el contrato
        $contrato = Contrato::create([
            'usuario_id' => $request->usuario_id,
            'apartamento_id' => $request->apartamento_id,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'firma_imagen' => $rutaArchivo,
            'estado_firma' => 'firmado',
            'estado' => $request->estado ?? 'activo',
        ]);

        // Actualizar el estado del apartamento a ocupado (usando el valor exacto según tu base de datos)
        if ($apartamento->estado === 'Disponible') {
            $apartamento->update(['estado' => 'Ocupado']);
        } else {
            $apartamento->update(['estado' => 'ocupado']);
        }

        // Obtener los IDs de los roles
        $rolInquilinoId = Rol::where('nombre', 'inquilino')->pluck('id')->first();

        if ($rolInquilinoId) {
            // Actualizar el rol del usuario a inquilino
            $this->actualizarRolUsuario($request->usuario_id, $rolInquilinoId);
        }

        return redirect()->route('contrato.index')->with('success', 'Contrato creado exitosamente. El usuario ha sido asignado como inquilino.');
    }

    /**
     * Muestra un contrato específico.
     */
    public function show($id)
    {
        $contrato = Contrato::with('usuario', 'apartamento')->find($id);

        if (!$contrato) {
            return redirect()->route('contrato.index')->with('error', 'Contrato no encontrado.');
        }

        // Si el usuario es inquilino, verificar que sea su contrato
        if (auth()->user()->hasRole('inquilino') && $contrato->usuario_id != auth()->id()) {
            abort(403, 'No tiene permiso para ver este contrato.');
        }

        return view('admin.contrato.show', compact('contrato'));
    }

    /**
     * Muestra el formulario para editar un contrato existente.
     */
    public function edit($id)
    {
        $contrato = Contrato::find($id);
        if (!$contrato) {
            return redirect()->route('contrato.index')->with('error', 'Contrato no encontrado.');
        }

        // Si el usuario es inquilino, verificar que sea su contrato
        if (auth()->user()->hasRole('inquilino') && $contrato->usuario_id != auth()->id()) {
            abort(403, 'No tiene permiso para editar este contrato.');
        }

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
            ->orderBy('id', 'asc')
            ->get();

        // Si no hay usuarios elegibles, mostrar todos
        if ($usuarios->isEmpty()) {
            $usuarios = Usuario::orderBy('id', 'asc')->get();
        }

        // Obtener todos los apartamentos para la edición
        $apartamentos = Apartamento::orderBy('numero_apartamento', 'asc')->get();

        return view('admin.contrato.edit', compact('contrato', 'usuarios', 'apartamentos'));
    }

    /**
     * Actualiza un contrato en la base de datos.
     */
    public function update(Request $request, $id)
    {
        $contrato = Contrato::find($id);
        if (!$contrato) {
            return redirect()->route('contrato.index')->with('error', 'Contrato no encontrado.');
        }

        // Si el usuario es inquilino, verificar que sea su contrato
        if (auth()->user()->hasRole('inquilino') && $contrato->usuario_id != auth()->id()) {
            abort(403, 'No tiene permiso para editar este contrato.');
        }

        $request->validate([
            'usuario_id' => 'required|exists:usuario,id',
            'apartamento_id' => 'required|exists:apartamento,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date',
        ]);

        // Si cambia el apartamento, verificar disponibilidad
        if ($contrato->apartamento_id != $request->apartamento_id) {
            $contratosActivos = Contrato::where('apartamento_id', $request->apartamento_id)
                ->where('estado', 'activo')
                ->count();

            if ($contratosActivos > 0) {
                return redirect()->back()->with('error', 'Este apartamento ya está asignado a otro contrato activo.');
            }

            // Liberar el apartamento anterior
            $apartamentoAnterior = Apartamento::find($contrato->apartamento_id);
            if ($apartamentoAnterior) {
                $apartamentoAnterior->update(['estado' => 'disponible']);
            }

            // Ocupar el nuevo apartamento
            $apartamentoNuevo = Apartamento::find($request->apartamento_id);
            if ($apartamentoNuevo) {
                $apartamentoNuevo->update(['estado' => 'ocupado']);
            }
        }

        // Si cambia el usuario, actualizar roles
        if ($contrato->usuario_id != $request->usuario_id) {
            // Verificar si el usuario anterior tiene otros contratos activos
            $contratosActivosUsuarioAnterior = Contrato::where('usuario_id', $contrato->usuario_id)
                ->where('id', '!=', $id)
                ->where('estado', 'activo')
                ->count();

            if ($contratosActivosUsuarioAnterior == 0) {
                // Cambiar rol de inquilino a posible_inquilino para el usuario anterior
                $rolPosibleInquilinoId = Rol::where('nombre', 'posible inquilino')->pluck('id')->first();
                if ($rolPosibleInquilinoId) {
                    $this->actualizarRolUsuario($contrato->usuario_id, $rolPosibleInquilinoId);
                }
            }

            // Actualizar el rol del nuevo usuario a inquilino
            $rolInquilinoId = Rol::where('nombre', 'inquilino')->pluck('id')->first();
            if ($rolInquilinoId) {
                $this->actualizarRolUsuario($request->usuario_id, $rolInquilinoId);
            }
        }

        // Actualizar datos básicos del contrato
        $contrato->usuario_id = $request->usuario_id;
        $contrato->apartamento_id = $request->apartamento_id;
        $contrato->fecha_inicio = $request->fecha_inicio;
        $contrato->fecha_fin = $request->fecha_fin;
        $contrato->estado = $request->estado;

        // Procesar la nueva firma si se proporciona
        if ($request->has('firma_base64') && !empty($request->firma_base64)) {
            // Si ya tenía una firma, eliminarla
            if ($contrato->firma_imagen && Storage::disk('public')->exists($contrato->firma_imagen)) {
                Storage::disk('public')->delete($contrato->firma_imagen);
            }

            // Guardar la nueva firma
            $imagenBase64 = $request->firma_base64;
            $imagen = str_replace('data:image/png;base64,', '', $imagenBase64);
            $imagen = str_replace(' ', '+', $imagen);

            $nombreArchivo = 'firma_contrato_' . $contrato->id . '_' . time() . '.png';
            $rutaArchivo = 'firmas/' . $nombreArchivo;

            Storage::disk('public')->put($rutaArchivo, base64_decode($imagen));

            $contrato->firma_imagen = $rutaArchivo;
        }

        $contrato->save();

        return redirect()->route('contrato.index')->with('success', 'Contrato actualizado correctamente.');
    }

    /**
     * Elimina un contrato de la base de datos.
     */
    public function destroy($id)
    {
        $contrato = Contrato::find($id);
        if (!$contrato) {
            return redirect()->route('contrato.index')->with('error', 'Contrato no encontrado.');
        }

        // Liberar el apartamento
        $apartamento = Apartamento::find($contrato->apartamento_id);
        if ($apartamento) {
            if ($apartamento->estado === 'Ocupado') {
                $apartamento->update(['estado' => 'Disponible']);
            } else {
                $apartamento->update(['estado' => 'disponible']);
            }
        }

        // Eliminar el contrato (soft delete)
        $contrato->delete();

        return redirect()->route('contrato.index')->with('success', 'Contrato eliminado correctamente.');
    }

    /**
     * Mostrar contratos eliminados (soft deleted).
     */
    public function trashed()
    {
        // Para admin y propietario: todos los contratos eliminados
        if (auth()->user()->hasRole(['admin', 'propietario'])) {
            $contratos = Contrato::onlyTrashed()->with('usuario', 'apartamento')->get();
        }
        // Para inquilinos: solo sus propios contratos eliminados
        else {
            $contratos = Contrato::onlyTrashed()->with('usuario', 'apartamento')
                ->where('usuario_id', auth()->id())
                ->get();
        }

        return view('admin.contrato.trashed', compact('contratos'));
    }

    /**
     * Restaurar un contrato eliminado.
     */
    public function restore($id)
    {
        $contrato = Contrato::onlyTrashed()->findOrFail($id);

        // Verificar si se puede restaurar (verificar que el apartamento esté disponible)
        $apartamento = Apartamento::find($contrato->apartamento_id);
        if ($apartamento && ($apartamento->estado === 'Ocupado' || $apartamento->estado === 'ocupado')) {
            return redirect()->route('contrato.trashed')
                ->with('error', 'No se puede restaurar este contrato porque el apartamento ya está ocupado.');
        }

        $contrato->restore();

        // Actualizar el estado del apartamento a ocupado
        if ($apartamento) {
            if ($apartamento->estado === 'Disponible') {
                $apartamento->update(['estado' => 'Ocupado']);
            } else {
                $apartamento->update(['estado' => 'ocupado']);
            }
        }

        // Obtener los IDs de los roles
        $rolInquilinoId = Rol::where('nombre', 'inquilino')->pluck('id')->first();

        // Actualizar el rol del usuario a inquilino si se encontró el rol
        if ($rolInquilinoId) {
            $this->actualizarRolUsuario($contrato->usuario_id, $rolInquilinoId);
        }

        return redirect()->route('contrato.trashed')
            ->with('success', 'Contrato restaurado correctamente.');
    }

    /**
     * Eliminar permanentemente un contrato.
     */
    public function forceDelete($id)
    {
        $contrato = Contrato::onlyTrashed()->findOrFail($id);

        // Eliminar la imagen de firma si existe
        if ($contrato->firma_imagen && Storage::disk('public')->exists($contrato->firma_imagen)) {
            Storage::disk('public')->delete($contrato->firma_imagen);
        }

        // Antes de eliminar, verificar si el usuario tiene otros contratos activos
        $otrosContratos = Contrato::where('usuario_id', $contrato->usuario_id)
            ->where('id', '!=', $id)
            ->where('estado', 'activo')
            ->count();

        // Guardar el ID del usuario antes de eliminar el contrato
        $usuarioId = $contrato->usuario_id;

        $contrato->forceDelete();

        // Si no tiene más contratos activos, actualizar su rol a posible_inquilino
        if ($otrosContratos == 0) {
            // Obtener el ID del rol posible_inquilino
            $rolPosibleInquilinoId = Rol::where('nombre', 'posible inquilino')->pluck('id')->first();

            // Actualizar el rol del usuario a posible_inquilino solo si encontramos el rol
            if ($rolPosibleInquilinoId) {
                $this->actualizarRolUsuario($usuarioId, $rolPosibleInquilinoId);
            }
        }

        return redirect()->route('contrato.trashed')
            ->with('success', 'Contrato eliminado permanentemente.');
    }
}
