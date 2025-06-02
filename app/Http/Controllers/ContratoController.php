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
        $this->middleware('permission:ver_contrato')->only(['index', 'show']);
        $this->middleware('permission:crear_contrato')->only(['create', 'store']);
        $this->middleware('permission:editar_contrato')->only(['edit', 'update']);
        $this->middleware('permission:eliminar_contrato')->only(['destroy']);
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

        // Asignar rol de inquilino al usuario si no lo tiene
        $rolInquilino = Rol::where('nombre', 'inquilino')->first();

        if ($rolInquilino && !$usuario->hasRole('inquilino')) {
            $usuario->roles()->attach($rolInquilino->id);

            // Eliminar rol de posible_inquilino si lo tiene
            $rolPosibleInquilino = Rol::where('nombre', 'posible_inquilino')->first();
            if ($rolPosibleInquilino && $usuario->hasRole('posible_inquilino')) {
                $usuario->roles()->detach($rolPosibleInquilino->id);
            }
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

        // Resto del código para obtener usuarios y apartamentos...

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

        // Verificaciones del cambio de usuario, apartamentos, etc...
        // (Mantenemos código existente)

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

        // Verificar si el usuario tiene otros contratos activos
        $usuario = Usuario::find($contrato->usuario_id);
        $contratosActivos = Contrato::where('usuario_id', $contrato->usuario_id)
            ->where('id', '!=', $id)
            ->where('estado', 'activo')
            ->count();

        // Eliminar la imagen de firma si existe
        if ($contrato->firma_imagen && Storage::disk('public')->exists($contrato->firma_imagen)) {
            Storage::disk('public')->delete($contrato->firma_imagen);
        }

        // Eliminar el contrato
        $contrato->delete();

        // Si el usuario no tiene más contratos activos, cambiar su rol a posible_inquilino
        // (Mantenemos código existente)

        return redirect()->route('contrato.index')->with('success', 'Contrato eliminado correctamente.');
    }
}
