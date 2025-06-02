<?php

namespace App\Http\Controllers\Propietario;

use App\Http\Controllers\Controller;
use App\Models\Queja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuejaController extends Controller
{
    /**
     * Constructor para verificar que el usuario sea propietario
     */
    public function __construct()
    {
        $this->middleware('role:propietario');
    }

    /**
     * Mostrar todas las quejas
     */
    public function index()
    {
        // Obtener todas las quejas ordenadas por fecha (más recientes primero)
        $quejas = Queja::with('usuario')
                ->orderBy('fecha_envio', 'desc')
                ->paginate(15); // Paginar si hay muchas quejas

        return view('propietario.quejas.index', compact('quejas'));
    }

    /**
     * Mostrar una queja específica
     */
    public function show($id)
    {
        // Cargamos el usuario con la relación completa
        $queja = Queja::with(['usuario' => function($query) {
            $query->select('id', 'nombre', 'correo'); // Seleccionamos el campo correo (no email)
        }])->findOrFail($id);

        return view('propietario.quejas.show', compact('queja'));
    }
}
