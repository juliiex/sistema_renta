<?php

namespace App\Http\Controllers\Propietario;

use App\Http\Controllers\Controller;
use App\Models\SolicitudAlquiler;
use App\Models\Apartamento;
use App\Models\Contrato;
use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SolicitudController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $usuario = Auth::user();
        $pisofiltro = $request->piso;

        // Obtener todos los apartamentos (o filtrados por piso)
        $apartamentosQuery = Apartamento::with(['edificio']);

        // Aplicar filtro por piso si se solicita
        if ($request->has('piso') && $request->piso != 'todos') {
            $apartamentosQuery->where('piso', $request->piso);
        }

        $apartamentos = $apartamentosQuery->get();

        // Obtener pisos disponibles para el filtro
        $pisos = Apartamento::distinct('piso')
                ->orderBy('piso')
                ->pluck('piso')
                ->toArray();

        // Preparar estadísticas agrupadas por pisos
        $resumenSolicitudesPorApartamento = [];

        foreach ($apartamentos as $apartamento) {
            if ($apartamento->edificio) {
                // Obtener solicitudes manualmente
                $solicitudes = SolicitudAlquiler::where('apartamento_id', $apartamento->id)
                              ->with('usuario')
                              ->get();

                $totalSolicitudes = $solicitudes->count();
                $pendientes = $solicitudes->where('estado_solicitud', 'pendiente')->count();
                $aprobadas = $solicitudes->where('estado_solicitud', 'aprobada')->count();
                $rechazadas = $solicitudes->where('estado_solicitud', 'rechazada')->count();

                $resumenSolicitudesPorApartamento[$apartamento->id] = [
                    'id' => $apartamento->id,
                    'apartamento' => $apartamento->numero_apartamento,
                    'piso' => $apartamento->piso,
                    'edificio' => $apartamento->edificio->nombre,
                    'total' => $totalSolicitudes,
                    'pendientes' => $pendientes,
                    'aprobadas' => $aprobadas,
                    'rechazadas' => $rechazadas,
                    'estado' => $apartamento->estado
                ];
            }
        }

        // Ordenar por piso y luego por número de apartamento
        $resumen = collect($resumenSolicitudesPorApartamento)->sortBy([
            ['piso', 'asc'],
            ['apartamento', 'asc'],
        ])->all();

        return view('propietario.solicitudes.index', compact('resumen', 'pisos', 'pisofiltro'));
    }

    /**
     * Display a listing of solicitudes for a specific apartment.
     */
    public function porApartamento($apartamentoId)
    {
        $usuario = Auth::user();

        $apartamento = Apartamento::with(['edificio'])
                       ->findOrFail($apartamentoId);

        $solicitudes = SolicitudAlquiler::where('apartamento_id', $apartamentoId)
                       ->with('usuario')
                       ->orderBy('fecha_solicitud', 'desc')
                       ->get();

        // Contar solicitudes por estado
        $estadisticas = [
            'total' => $solicitudes->count(),
            'pendientes' => $solicitudes->where('estado_solicitud', 'pendiente')->count(),
            'aprobadas' => $solicitudes->where('estado_solicitud', 'aprobada')->count(),
            'rechazadas' => $solicitudes->where('estado_solicitud', 'rechazada')->count(),
        ];

        return view('propietario.solicitudes.por-apartamento',
            compact('apartamento', 'solicitudes', 'estadisticas'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $solicitud = SolicitudAlquiler::with(['usuario', 'apartamento.edificio'])->findOrFail($id);

        return view('propietario.solicitudes.show', compact('solicitud'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $solicitud = SolicitudAlquiler::with(['usuario', 'apartamento.edificio'])->findOrFail($id);

        return view('propietario.solicitudes.edit', compact('solicitud'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $solicitud = SolicitudAlquiler::findOrFail($id);

        $request->validate([
            'estado_solicitud' => 'required|in:pendiente,aprobada,rechazada',
        ]);

        // Guardar el estado anterior para saber si cambió a aprobada
        $estadoAnterior = $solicitud->estado_solicitud;

        // Actualizar estado de la solicitud
        $solicitud->estado_solicitud = $request->estado_solicitud;
        $solicitud->save();

        // Si se aprobó la solicitud (y no estaba aprobada antes)
        if ($request->estado_solicitud === 'aprobada' && $estadoAnterior !== 'aprobada') {
            // Marcar apartamento como ocupado
            if ($solicitud->apartamento->estado === 'Disponible' || $solicitud->apartamento->estado === 'disponible' || $solicitud->apartamento->estado === 'DISPONIBLE') {
                $apartamento = $solicitud->apartamento;
                $apartamento->estado = 'Ocupado';
                $apartamento->save();
            }

            // NUEVO: Crear un contrato pendiente de firma
            $this->crearContratoPendiente($solicitud);
        }

        return redirect()->route('propietario.solicitudes.show', $solicitud->id)
            ->with('success', 'Solicitud actualizada exitosamente.');
    }

    /**
     * Approve a solicitud quickly
     */
    public function aprobar($id)
    {
        $solicitud = SolicitudAlquiler::findOrFail($id);

        // Verificar si ya está aprobada
        if ($solicitud->estado_solicitud === 'aprobada') {
            return redirect()->back()->with('info', 'La solicitud ya está aprobada.');
        }

        // Verificar que el apartamento esté disponible
        if ($solicitud->apartamento->estado !== 'Disponible' && $solicitud->apartamento->estado !== 'disponible' && $solicitud->apartamento->estado !== 'DISPONIBLE') {
            return redirect()->back()->with('error', 'No se puede aprobar la solicitud porque el apartamento no está disponible.');
        }

        // Aprobar la solicitud
        $solicitud->estado_solicitud = 'aprobada';
        $solicitud->save();

        // Cambiar estado del apartamento a ocupado
        $apartamento = $solicitud->apartamento;
        $apartamento->estado = 'Ocupado';
        $apartamento->save();

        // NUEVO: Crear un contrato pendiente de firma
        $this->crearContratoPendiente($solicitud);

        return redirect()->back()->with('success', 'Solicitud aprobada exitosamente. Se ha generado un contrato pendiente de firma para el usuario.');
    }

    /**
     * NUEVO MÉTODO: Crea un contrato pendiente de firma
     */
    protected function crearContratoPendiente($solicitud)
    {
        // Asignar rol de posible_inquilino al usuario si no lo tiene
        $usuario = Usuario::find($solicitud->usuario_id);
        $rolPosibleInquilino = Rol::where('nombre', 'posible_inquilino')->first();

        if ($rolPosibleInquilino && !$usuario->hasRole('posible_inquilino') && !$usuario->hasRole('inquilino')) {
            $usuario->roles()->attach($rolPosibleInquilino->id);
        }

        // Comprobar si ya existe un contrato pendiente para este usuario y apartamento
        $contratoExistente = Contrato::where('usuario_id', $solicitud->usuario_id)
            ->where('apartamento_id', $solicitud->apartamento_id)
            ->where('estado_firma', 'pendiente')
            ->first();

        if ($contratoExistente) {
            // Ya existe un contrato, no crear otro
            return;
        }

        // Fechas para el contrato
        $fechaInicio = Carbon::now();
        $fechaFin = Carbon::now()->addYear(); // Contrato de 1 año por defecto

        // Crear el contrato pendiente
        $contrato = new Contrato();
        $contrato->usuario_id = $solicitud->usuario_id;
        $contrato->apartamento_id = $solicitud->apartamento_id;
        $contrato->fecha_inicio = $fechaInicio;
        $contrato->fecha_fin = $fechaFin;
        $contrato->estado = 'inactivo'; // Inactivo hasta que se firme
        $contrato->estado_firma = 'pendiente'; // Pendiente de firma
        $contrato->save();
    }

    /**
     * Reject a solicitud quickly
     */
    public function rechazar($id)
    {
        $solicitud = SolicitudAlquiler::findOrFail($id);

        // Verificar si ya está rechazada
        if ($solicitud->estado_solicitud === 'rechazada') {
            return redirect()->back()->with('info', 'La solicitud ya está rechazada.');
        }

        // Rechazar la solicitud
        $solicitud->estado_solicitud = 'rechazada';
        $solicitud->save();

        return redirect()->back()->with('success', 'Solicitud rechazada exitosamente.');
    }
}
