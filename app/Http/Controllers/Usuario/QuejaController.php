<?php

namespace App\Http\Controllers\Usuario;

use App\Http\Controllers\Controller;
use App\Models\Queja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuejaController extends Controller
{
    /**
     * Constructor que aplica middleware para asegurar que solo los inquilinos accedan a ciertas rutas
     */
    public function __construct()
    {
        $this->middleware('role:inquilino')->only(['index', 'crear', 'store']);
    }

    /**
     * Mostrar listado de todas las quejas (solo inquilinos)
     */
    public function index()
    {
        $quejas = Queja::orderBy('fecha_envio', 'desc')
            ->paginate(10);

        return view('usuario.quejas.index', compact('quejas'));
    }

    /**
     * Mostrar listado de mis quejas
     */
    public function misQuejas()
    {
        $quejas = Queja::where('usuario_id', Auth::user()->id)
            ->orderBy('fecha_envio', 'desc')
            ->paginate(10);

        return view('usuario.quejas.mis_quejas', compact('quejas'));
    }

    /**
     * Mostrar detalle de una queja específica
     */
    public function detalle($id)
    {
        $queja = Queja::findOrFail($id);

        // Si no es inquilino, verificamos que sea el propietario de la queja
        if (!Auth::user()->hasRole('inquilino') && $queja->usuario_id != Auth::user()->id) {
            abort(403, 'No tienes permisos para ver esta queja');
        }

        return view('usuario.quejas.detalle', compact('queja'));
    }

    /**
     * Mostrar formulario para crear una nueva queja (solo inquilinos)
     */
    public function crear()
    {
        // Opciones para el tipo de queja
        $tipoOpciones = [
            'Instalaciones' => 'Instalaciones',
            'Servicios' => 'Servicios',
            'Vecinos' => 'Vecinos',
            'Administración' => 'Administración',
            'Seguridad' => 'Seguridad',
            'Otros' => 'Otros'
        ];

        return view('usuario.quejas.crear', compact('tipoOpciones'));
    }

    /**
     * Almacenar una nueva queja (solo inquilinos)
     */
    public function store(Request $request)
    {
        $request->validate([
            'tipo' => 'required|string|max:50',
            'descripcion' => 'required|string|min:10|max:1000',
        ]);

        // Obtenemos directamente el ID numérico del usuario
        $usuario = Auth::user();

        Queja::create([
            'usuario_id' => $usuario->id, // Usamos directamente el ID numérico
            'descripcion' => $request->descripcion,
            'tipo' => $request->tipo,
            'fecha_envio' => now()
        ]);

        return redirect()->route('usuario.quejas.mis-quejas')
            ->with('success', 'Tu queja ha sido enviada correctamente.');
    }
}
