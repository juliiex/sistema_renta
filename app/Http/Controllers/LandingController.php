<?php

namespace App\Http\Controllers;

use App\Models\Apartamento;
use App\Models\Edificio;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Muestra la landing page con apartamentos disponibles del sistema
     */
    public function index()
    {
        // Obtener los 3 apartamentos disponibles más recientes
        $apartamentosDestacados = Apartamento::where('estado', 'disponible')
            ->with(['edificio', 'evaluaciones'])
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // Calcular calificaciones para cada apartamento
        foreach ($apartamentosDestacados as $apartamento) {
            $promedio = $apartamento->evaluaciones->avg('calificacion');
            $total = $apartamento->evaluaciones->count();
            $apartamento->rating = $promedio ? round($promedio, 1) : 0;
            $apartamento->total_reviews = $total;
        }

        // Obtener el total de apartamentos y edificios disponibles
        $totalApartamentosDisponibles = Apartamento::where('estado', 'disponible')->count();
        $totalEdificios = Edificio::count();

        return view('landing', compact('apartamentosDestacados', 'totalApartamentosDisponibles', 'totalEdificios'));
    }
}
