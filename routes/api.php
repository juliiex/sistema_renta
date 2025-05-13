<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApartamentoController;
use App\Http\Controllers\ContratoController;
use App\Http\Controllers\EdificioController;
use App\Http\Controllers\EstadoAlquilerController;
use App\Http\Controllers\EvaluacionController;
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\QuejaController;
use App\Http\Controllers\RecordatorioPagoController;
use App\Http\Controllers\ReporteProblemaController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\RolPermisoController;
use App\Http\Controllers\SolicitudAlquilerController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\UsuarioRolController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('apartamentos')->group(function () {
    Route::get('/', [ApartamentoController::class, 'index']);
    Route::get('/{id}', [ApartamentoController::class, 'show']);
    Route::get('/create', [ApartamentoController::class, 'create']);
    Route::post('/', [ApartamentoController::class, 'store']);
    Route::put('/{id}', [ApartamentoController::class, 'update']);
    Route::delete('/{id}', [ApartamentoController::class, 'destroy']);
});

// Contrato Routes
Route::prefix('contratos')->group(function () {
    Route::get('/', [ContratoController::class, 'index']);
    Route::get('/{id}', [ContratoController::class, 'show']);
    Route::get('/create', [ContratoController::class, 'create']);
    Route::post('/', [ContratoController::class, 'store']);
    Route::put('/{id}', [ContratoController::class, 'update']);
    Route::delete('/{id}', [ContratoController::class, 'destroy']);
});

// Edificio Routes
Route::prefix('edificio')->group(function () {
    Route::get('/', [EdificioController::class, 'index']);
    Route::get('/{id}', [EdificioController::class, 'show']);
    Route::post('/', [EdificioController::class, 'store']);
    Route::put('/{id}', [EdificioController::class, 'update']);
    Route::delete('/{id}', [EdificioController::class, 'destroy']);
});

// EstadoAlquiler Routes - NUEVAS RUTAS AGREGADAS
Route::prefix('estado_alquiler')->group(function () {
    Route::get('/', [EstadoAlquilerController::class, 'index']);
    Route::get('/{id}', [EstadoAlquilerController::class, 'show']);
    Route::get('/create', [EstadoAlquilerController::class, 'create']);
    Route::post('/', [EstadoAlquilerController::class, 'store']);
    Route::put('/{id}', [EstadoAlquilerController::class, 'update']);
    Route::delete('/{id}', [EstadoAlquilerController::class, 'destroy']);
});

Route::prefix('evaluaciones')->group(function () {
    Route::get('/', [EvaluacionController::class, 'index']);
    Route::get('/{id}', [EvaluacionController::class, 'show']);
    Route::post('/', [EvaluacionController::class, 'store']);
    Route::put('/{id}', [EvaluacionController::class, 'update']);
    Route::delete('/{id}', [EvaluacionController::class, 'destroy']);
});

Route::prefix('permisos')->group(function () {
    Route::get('/', [PermisoController::class, 'index']);
    Route::get('/{id}', [PermisoController::class, 'show']);
    Route::post('/', [PermisoController::class, 'store']);
    Route::put('/{id}', [PermisoController::class, 'update']);
    Route::delete('/{id}', [PermisoController::class, 'destroy']);
});

Route::prefix('queja')->group(function () {
    Route::get('/', [QuejaController::class, 'index']);
    Route::get('/{id}', [QuejaController::class, 'show']);
    Route::post('/', [QuejaController::class, 'store']);
    Route::put('/{id}', [QuejaController::class, 'update']);
    Route::delete('/{id}', [QuejaController::class, 'destroy']);
});

Route::prefix('recordatorio-pago')->group(function () {
    Route::get('/', [RecordatorioPagoController::class, 'index']);
    Route::get('/{id}', [RecordatorioPagoController::class, 'show']);
    Route::post('/', [RecordatorioPagoController::class, 'store']);
    Route::put('/{id}', [RecordatorioPagoController::class, 'update']);
    Route::delete('/{id}', [RecordatorioPagoController::class, 'destroy']);
});

Route::prefix('reporte-problema')->group(function () {
    Route::get('/', [ReporteProblemaController::class, 'index']);
    Route::get('/{id}', [ReporteProblemaController::class, 'show']);
    Route::post('/', [ReporteProblemaController::class, 'store']);
    Route::put('/{id}', [ReporteProblemaController::class, 'update']);
    Route::delete('/{id}', [ReporteProblemaController::class, 'destroy']);
});

Route::prefix('rol')->group(function () {
    Route::get('/', [RolController::class, 'index']);
    Route::get('/{rol}', [RolController::class, 'show']); // <- Corregido aquí
    Route::post('/', [RolController::class, 'store']);
    Route::put('/{id}', [RolController::class, 'update']);
    Route::delete('/{id}', [RolController::class, 'destroy']);
});

Route::prefix('rol-permiso')->group(function () {
    Route::get('/', [RolPermisoController::class, 'index']);
    Route::get('/{id}', [RolPermisoController::class, 'show']); // <- Corregido aquí
    Route::post('/', [RolPermisoController::class, 'store']);
    Route::put('/{id}', [RolPermisoController::class, 'update']);
    Route::delete('/{id}', [RolPermisoController::class, 'destroy']);
});

Route::prefix('solicitud-alquiler')->group(function () {
    Route::get('/', [SolicitudAlquilerController::class, 'index']);
    Route::get('/{id}', [SolicitudAlquilerController::class, 'show']); // <- Corregido aquí
    Route::post('/', [SolicitudAlquilerController::class, 'store']);
    Route::put('/{id}', [SolicitudAlquilerController::class, 'update']);
    Route::delete('/{id}', [SolicitudAlquilerController::class, 'destroy']);
});

Route::prefix('usuarios')->group(function () {
    Route::get('/', [UsuarioController::class, 'index']);
    Route::post('/', [UsuarioController::class, 'store']);
    Route::get('/{usuario}', [UsuarioController::class, 'show']);
    Route::put('/{usuario}', [UsuarioController::class, 'update']);
    Route::delete('/{usuario}', [UsuarioController::class, 'destroy']);
});

Route::prefix('usuario-rol')->group(function () {
    Route::get('/', [UsuarioRolController::class, 'index']);
    Route::get('/{id}', [UsuarioRolController::class, 'show']); // <- Corregido aquí
    Route::post('/', [UsuarioRolController::class, 'store']);
    Route::put('/{id}', [UsuarioRolController::class, 'update']);
    Route::delete('/{id}', [UsuarioRolController::class, 'destroy']);
});


