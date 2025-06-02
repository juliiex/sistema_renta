<?php

namespace App\Http\Controllers\Usuario;

use App\Http\Controllers\Controller;
use App\Models\ReporteProblema;
use App\Models\Apartamento;
use App\Models\Contrato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsuarioReporteController extends Controller
{
    // Ya tiene middleware en las rutas para comprobar que es inquilino

    // Ver todos los reportes del usuario
    public function index()
    {
        $reportes = ReporteProblema::where('usuario_id', Auth::id())
            ->with(['apartamento', 'apartamento.edificio'])
            ->orderBy('fecha_reporte', 'desc')
            ->paginate(10);

        return view('usuario.reportes.lista', compact('reportes'));
    }

    // Ver detalles de un reporte específico
    public function show($id)
    {
        $reporte = ReporteProblema::where('id', $id)
            ->where('usuario_id', Auth::id())
            ->with(['apartamento', 'apartamento.edificio'])
            ->firstOrFail();

        return view('usuario.reportes.detalle', compact('reporte'));
    }

    // Formulario para crear nuevo reporte - MODIFICADO para aceptar apartamento_id
    public function create(Request $request)
    {
        // Obtener solo los apartamentos que el inquilino tiene contratados
        $apartamentosContratados = Contrato::where('usuario_id', Auth::id())
            ->where('estado', 'activo')
            ->with(['apartamento', 'apartamento.edificio'])
            ->get()
            ->pluck('apartamento');

        // Si no tiene contratos activos, redirigir
        if ($apartamentosContratados->isEmpty()) {
            return redirect()->route('home')
                ->with('error', 'No tienes apartamentos contratados para reportar problemas.');
        }

        // El apartamento_id puede venir como parámetro de consulta cuando se hace clic desde la vista de Mi Apartamento
        $apartamentoSeleccionado = $request->query('apartamento_id');

        return view('usuario.reportes.nuevo', compact('apartamentosContratados', 'apartamentoSeleccionado'));
    }

    // Guardar nuevo reporte
    public function store(Request $request)
    {
        $request->validate([
            'apartamento_id' => 'required|exists:apartamento,id',
            'descripcion' => 'required|string|max:500',
            'tipo' => 'required|string|max:100',
        ], [
            'apartamento_id.required' => 'Debes seleccionar un apartamento.',
            'apartamento_id.exists' => 'El apartamento seleccionado no existe.',
            'descripcion.required' => 'La descripción del problema es obligatoria.',
            'descripcion.max' => 'La descripción no debe exceder los 500 caracteres.',
            'tipo.required' => 'Debes seleccionar el tipo de problema.',
            'tipo.max' => 'El tipo de problema no es válido.'
        ]);

        // Verificar que el apartamento pertenezca a un contrato activo del usuario
        $tieneContrato = Contrato::where('usuario_id', Auth::id())
            ->where('apartamento_id', $request->apartamento_id)
            ->where('estado', 'activo')
            ->exists();

        if (!$tieneContrato) {
            return redirect()->back()->withErrors(['apartamento_id' => 'No tienes un contrato activo para este apartamento.']);
        }

        // Crear el reporte
        ReporteProblema::create([
            'apartamento_id' => $request->apartamento_id,
            'usuario_id' => Auth::id(),
            'descripcion' => $request->descripcion,
            'tipo' => $request->tipo,
            'estado' => 'pendiente',
            'fecha_reporte' => now()
        ]);

        return redirect()->route('usuario.reportes.lista')
            ->with('success', 'Tu reporte ha sido enviado correctamente.');
    }
}
