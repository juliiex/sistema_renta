<?php

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
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;

/*
|--------------------------------------------------------------------------|
| Rutas principales del sistema                                              |
|--------------------------------------------------------------------------|
*/

// Página de Bienvenida
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

/*
|--------------------------------------------------------------------------|
| Autenticación                                                              |
|--------------------------------------------------------------------------|
*/

// Login
Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest');

// Registro
Route::get('/register', [RegisteredUserController::class, 'create'])
    ->middleware('guest')
    ->name('register');
Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('guest');

// Logout
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------|
| Perfil del Usuario                                                         |
|--------------------------------------------------------------------------|
*/

Route::middleware('auth')->get('/profile', function () {
    $user = auth()->user(); // Obtener el usuario logueado
    return view('profile', compact('user'));
})->name('profile');

/*
|--------------------------------------------------------------------------|
| CRUD de entidades (protegidas por autenticación)                          |
|--------------------------------------------------------------------------|
*/

Route::middleware('auth')->group(function () {
    Route::resource('apartamento', ApartamentoController::class);
    Route::resource('usuario', UsuarioController::class);
    Route::resource('contrato', ContratoController::class);
    Route::resource('edificio', EdificioController::class);
    Route::resource('estado_alquiler', EstadoAlquilerController::class);
    Route::resource('evaluaciones', EvaluacionController::class);
    Route::resource('permisos', PermisoController::class);
    Route::resource('queja', QuejaController::class);
    Route::resource('recordatorio_pago', RecordatorioPagoController::class);
    Route::resource('reporte_problema', ReporteProblemaController::class);
    Route::resource('rol', RolController::class);
    Route::resource('rol-permiso', RolPermisoController::class);
    Route::resource('solicitudes', SolicitudAlquilerController::class);
    Route::resource('usuarios', UsuarioController::class);
    Route::resource('usuario-rol', UsuarioRolController::class);

    // Ruta para obtener los apartamentos de un usuario específico
    Route::get('/get-apartamentos/{usuario_id}', [EvaluacionController::class, 'getApartamentosByUsuario']);
});
