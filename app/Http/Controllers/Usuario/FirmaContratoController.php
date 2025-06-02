<?php

namespace App\Http\Controllers\Usuario;

use App\Http\Controllers\Controller;
use App\Models\Contrato;
use App\Models\Rol;
use App\Models\Usuario;
use App\Models\Apartamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FirmaContratoController extends Controller
{
    /**
     * Constructor para verificar que el usuario sea posible_inquilino
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->hasRole('posible inquilino')) {
                return redirect()->route('home')->with('error', 'No tiene permisos para acceder a esta sección.');
            }

            return $next($request);
        });
    }

    /**
     * Mostrar lista de contratos pendientes de firma
     */
    public function index()
    {
        $contratos = Contrato::where('usuario_id', Auth::id())
            ->where('estado_firma', 'pendiente')
            ->with('apartamento.edificio')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('usuario.firma.index', compact('contratos'));
    }

    /**
     * Mostrar formulario para firmar un contrato específico
     */
    public function show($id)
    {
        $contrato = Contrato::where('id', $id)
            ->where('usuario_id', Auth::id())
            ->where('estado_firma', 'pendiente')
            ->with('apartamento.edificio')
            ->firstOrFail();

        return view('usuario.firma.firmar', compact('contrato'));
    }

    /**
     * Procesar la firma de un contrato
     */
    public function store(Request $request, $id)
    {
        $contrato = Contrato::where('id', $id)
            ->where('usuario_id', Auth::id())
            ->where('estado_firma', 'pendiente')
            ->firstOrFail();

        $request->validate([
            'firma_base64' => 'required',
        ]);

        // Procesar la imagen de la firma
        $imagenBase64 = $request->firma_base64;
        $imagen = str_replace('data:image/png;base64,', '', $imagenBase64);
        $imagen = str_replace(' ', '+', $imagen);

        $nombreArchivo = 'firma_contrato_' . $contrato->id . '_' . time() . '.png';
        $rutaArchivo = 'firmas/' . $nombreArchivo;

        Storage::disk('public')->put($rutaArchivo, base64_decode($imagen));

        // Activar el contrato y guardar la firma
        $contrato->firma_imagen = $rutaArchivo;
        $contrato->estado_firma = 'firmado';
        $contrato->estado = 'activo'; // Activar el contrato
        $contrato->save();

        // Cambiar rol de usuario de posible_inquilino a inquilino
        $usuario = Usuario::find(Auth::id());

        // Buscar roles
        $rolInquilino = Rol::where('nombre', 'inquilino')->first();
        $rolPosibleInquilino = Rol::where('nombre', 'posible inquilino')->first();

        if ($rolInquilino && $rolPosibleInquilino) {
            Log::info('Actualizando rol de usuario: ' . $usuario->id);
            Log::info('Rol posible inquilino ID: ' . $rolPosibleInquilino->id);
            Log::info('Rol inquilino ID: ' . $rolInquilino->id);

            // Verificar si el usuario ya tiene el rol de inquilino
            $yaEsInquilino = DB::table('usuarios_roles')
                ->where('usuario_id', $usuario->id)
                ->where('rol_id', $rolInquilino->id)
                ->exists();

            if (!$yaEsInquilino) {
                // Buscar el registro actual de posible inquilino
                $registroActual = DB::table('usuarios_roles')
                    ->where('usuario_id', $usuario->id)
                    ->where('rol_id', $rolPosibleInquilino->id)
                    ->first();

                if ($registroActual) {
                    // Actualizar el registro existente para cambiar el rol
                    DB::table('usuarios_roles')
                        ->where('id', $registroActual->id)
                        ->update(['rol_id' => $rolInquilino->id]);

                    Log::info('Rol actualizado exitosamente de posible inquilino a inquilino');
                } else {
                    // Si no tiene el rol de posible inquilino, asignarle el rol de inquilino
                    $usuario->roles()->attach($rolInquilino->id);
                    Log::info('Usuario no tenía rol de posible inquilino, se le asignó el rol de inquilino');
                }
            } else {
                Log::info('El usuario ya tiene el rol de inquilino');

                // Eliminar el rol de posible inquilino si existe
                DB::table('usuarios_roles')
                    ->where('usuario_id', $usuario->id)
                    ->where('rol_id', $rolPosibleInquilino->id)
                    ->delete();

                Log::info('Se eliminó el rol de posible inquilino');
            }
        } else {
            Log::error('No se encontraron los roles necesarios');
        }

        // Actualizar estado del apartamento a ocupado
        $apartamento = Apartamento::find($contrato->apartamento_id);
        if ($apartamento) {
            if ($apartamento->estado === 'Disponible') {
                $apartamento->update(['estado' => 'Ocupado']);
            } else {
                $apartamento->update(['estado' => 'Ocupado']);
            }
        }

        // Cerrar sesión para que el usuario vuelva a iniciar con sus nuevos roles
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Has firmado el contrato exitosamente. Por favor inicia sesión nuevamente para acceder a tu cuenta con los nuevos permisos.');
    }
}
