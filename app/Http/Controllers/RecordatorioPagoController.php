<?php

namespace App\Http\Controllers;

use App\Models\RecordatorioPago;
use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Contrato;
use App\Models\Rol;

class RecordatorioPagoController extends Controller
{
    /**
     * Constructor: añade middleware de permisos
     */
    public function __construct()
    {
        $this->middleware('permission:ver_recordatorio_pago')->only(['index', 'show']);
        $this->middleware('permission:crear_recordatorio_pago')->only(['create', 'store']);
        $this->middleware('permission:editar_recordatorio_pago')->only(['edit', 'update']);
        $this->middleware('permission:eliminar_recordatorio_pago')->only(['destroy']);

        // Restringir a roles admin y propietario
        $this->middleware('role:admin|propietario')->except(['index', 'show']);
    }

    // Muestra una lista de todos los recordatorios de pago
    public function index()
    {
        // Admin y propietario pueden ver todos los recordatorios
        if (auth()->user()->hasRole(['admin', 'propietario'])) {
            $recordatorios = RecordatorioPago::with('usuario')->orderBy('fecha_envio', 'desc')->get();
        }
        // Otros usuarios solo ven sus propios recordatorios
        else {
            $recordatorios = RecordatorioPago::with('usuario')
                ->where('usuario_id', auth()->id())
                ->orderBy('fecha_envio', 'desc')
                ->get();
        }

        return view('admin.recordatorio_pago.index', compact('recordatorios'));
    }

    // Muestra el formulario para crear un nuevo recordatorio de pago
    public function create()
    {
        // Obtener el ID del rol "inquilino"
        $rolInquilino = Rol::where('nombre', 'inquilino')->first();

        if ($rolInquilino) {
            // Obtener usuarios con rol de inquilino y que tengan contratos activos
            $usuarios = Usuario::whereHas('roles', function($query) use ($rolInquilino) {
                $query->where('rol_id', $rolInquilino->id);
            })->whereHas('contratos', function($query) {
                $query->where('estado', 'activo');
            })->orderBy('nombre', 'asc')->get();
        } else {
            $usuarios = collect(); // Colección vacía si no se encuentra el rol
        }

        // Lista de métodos de envío disponibles
        $metodos = [
            'Correo' => 'Correo electrónico',
            'SMS' => 'Mensaje de texto (SMS)',
            'App' => 'Notificación en la aplicación',
            'WhatsApp' => 'Mensaje por WhatsApp'
        ];

        return view('admin.recordatorio_pago.create', compact('usuarios', 'metodos'));
    }

    // Almacena un nuevo recordatorio de pago
    public function store(Request $request)
    {
        $request->validate([
            'usuario_id' => 'required|exists:usuario,id',
            'metodo' => 'required|string|max:255',
            'fecha_envio' => 'required|date',
        ]);

        // Verificar que el usuario es un inquilino y tiene un contrato activo
        $usuario = Usuario::find($request->usuario_id);
        $tieneContrato = Contrato::where('usuario_id', $request->usuario_id)
            ->where('estado', 'activo')
            ->exists();

        if (!$usuario->hasRole('inquilino') || !$tieneContrato) {
            return redirect()->back()
                ->with('error', 'Solo se pueden enviar recordatorios a inquilinos con contratos activos.')
                ->withInput();
        }

        $recordatorio = RecordatorioPago::create([
            'usuario_id' => $request->usuario_id,
            'metodo' => $request->metodo,
            'fecha_envio' => $request->fecha_envio,
        ]);

        return redirect()->route('recordatorio_pago.index')->with('success', 'Recordatorio de pago creado con éxito.');
    }

    // Muestra los detalles de un recordatorio de pago específico
    public function show($id)
    {
        $recordatorio = RecordatorioPago::with(['usuario'])->findOrFail($id);

        // Verificar que el usuario pueda ver este recordatorio
        if (!auth()->user()->hasRole(['admin', 'propietario']) &&
            $recordatorio->usuario_id != auth()->id()) {
            abort(403, 'No tiene permiso para ver este recordatorio.');
        }

        return view('admin.recordatorio_pago.show', compact('recordatorio'));
    }

    // Muestra el formulario para editar un recordatorio de pago
    public function edit($id)
    {
        $recordatorio = RecordatorioPago::findOrFail($id);

        // Obtener el ID del rol "inquilino"
        $rolInquilino = Rol::where('nombre', 'inquilino')->first();

        if ($rolInquilino) {
            // Obtener usuarios con rol de inquilino y que tengan contratos activos
            $usuarios = Usuario::whereHas('roles', function($query) use ($rolInquilino) {
                $query->where('rol_id', $rolInquilino->id);
            })->whereHas('contratos', function($query) {
                $query->where('estado', 'activo');
            })->orderBy('nombre', 'asc')->get();
        } else {
            $usuarios = collect(); // Colección vacía si no se encuentra el rol
        }

        // Lista de métodos de envío disponibles
        $metodos = [
            'Correo' => 'Correo electrónico',
            'SMS' => 'Mensaje de texto (SMS)',
            'App' => 'Notificación en la aplicación',
            'WhatsApp' => 'Mensaje por WhatsApp'
        ];

        return view('admin.recordatorio_pago.edit', compact('recordatorio', 'usuarios', 'metodos'));
    }

    // Actualiza un recordatorio de pago específico
    public function update(Request $request, $id)
    {
        $recordatorio = RecordatorioPago::findOrFail($id);

        $request->validate([
            'usuario_id' => 'required|exists:usuario,id',
            'metodo' => 'required|string|max:255',
            'fecha_envio' => 'required|date',
        ]);

        // Verificar que el usuario es un inquilino y tiene un contrato activo
        $usuario = Usuario::find($request->usuario_id);
        $tieneContrato = Contrato::where('usuario_id', $request->usuario_id)
            ->where('estado', 'activo')
            ->exists();

        if (!$usuario->hasRole('inquilino') || !$tieneContrato) {
            return redirect()->back()
                ->with('error', 'Solo se pueden enviar recordatorios a inquilinos con contratos activos.')
                ->withInput();
        }

        $recordatorio->update([
            'usuario_id' => $request->usuario_id,
            'metodo' => $request->metodo,
            'fecha_envio' => $request->fecha_envio,
        ]);

        return redirect()->route('recordatorio_pago.index')->with('success', 'Recordatorio de pago actualizado con éxito.');
    }

    // Elimina un recordatorio de pago
    public function destroy($id)
    {
        $recordatorio = RecordatorioPago::findOrFail($id);
        $recordatorio->delete();

        return redirect()->route('recordatorio_pago.index')->with('success', 'Recordatorio de pago eliminado con éxito.');
    }

    /**
     * Método que en un futuro podría convertirse en un comando programado
     * para enviar recordatorios automáticamente
     */
    public function enviarRecordatorios()
    {
        // Este método se podría convertirse en un comando programado
        // para enviar recordatorios automáticamente según la fecha_envio

        $fechaActual = date('Y-m-d');

        $recordatoriosPendientes = RecordatorioPago::where('fecha_envio', '<=', $fechaActual)->get();

        foreach ($recordatoriosPendientes as $recordatorio) {
            // Lógica para enviar el recordatorio según el método (email, SMS, etc.)
            // ...
        }

        return "Recordatorios enviados: " . $recordatoriosPendientes->count();
    }
}
