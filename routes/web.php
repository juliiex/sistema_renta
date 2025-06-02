<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApartamentoController;
use App\Http\Controllers\ContratoController;
use App\Http\Controllers\DashboardController;
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
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\HomeController;

// Controladores de usuario - IMPORTANTE: Importaciones correctas
use App\Http\Controllers\Usuario\UsuarioApartamentoController;
use App\Http\Controllers\Usuario\UsuarioSolicitudController;
use App\Http\Controllers\Usuario\UsuarioContratoController;
use App\Http\Controllers\Usuario\UsuarioReporteController;
use App\Http\Controllers\Usuario\MiApartamentoController;
use App\Http\Controllers\Usuario\QuejaController as UsuarioQuejaController;
use App\Http\Controllers\Usuario\FirmaContratoController;

// Nuevos controladores para propietario
use App\Http\Controllers\Propietario\EdificioController as PropietarioEdificioController;
use App\Http\Controllers\Propietario\ApartamentoController as PropietarioApartamentoController;
use App\Http\Controllers\Propietario\EvaluacionController as PropietarioEvaluacionController;
use App\Http\Controllers\Propietario\SolicitudController as PropietarioSolicitudController;
use App\Http\Controllers\Propietario\ContratoController as PropietarioContratoController;
use App\Http\Controllers\Propietario\RecordatorioController as PropietarioRecordatorioController;
use App\Http\Controllers\Propietario\EstadoAlquilerController as PropietarioEstadoAlquilerController;
use App\Http\Controllers\Propietario\ReporteController as PropietarioReporteController;
use App\Http\Controllers\Propietario\QuejaController as PropietarioQuejaController;

/*
|--------------------------------------------------------------------------
| Ruta principal que ahora usa el HomeController
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])
    ->middleware('auth')
    ->name('home');

/*
|--------------------------------------------------------------------------
| Autenticación
|--------------------------------------------------------------------------
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
|--------------------------------------------------------------------------
| Perfil de Usuario (solo autenticados)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Menú Principal - Accesible a todos los usuarios autenticados
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    // Menú principal - La vista se adaptará según el rol del usuario
    Route::get('/menu', function () {
        return view('menu');
    })->name('menu');

    // Ruta para buscar usuarios
    Route::get('/buscar-usuarios', [ContratoController::class, 'buscarUsuarios'])->name('buscar.usuarios');
});

/*
|--------------------------------------------------------------------------
| Rutas para el Dashboard (Admin y Propietario)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin|propietario'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| Rutas para el Administrador
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('usuarios', UsuarioController::class);
    Route::resource('rol', RolController::class);
    Route::resource('permiso', PermisoController::class);
    Route::resource('rol_permiso', RolPermisoController::class);
    Route::resource('usuario_rol', UsuarioRolController::class);

    // CRUD para administradores
    Route::resource('apartamento', ApartamentoController::class);
    Route::resource('edificio', EdificioController::class);
    Route::resource('evaluaciones', EvaluacionController::class);
    Route::resource('contrato', ContratoController::class);
    Route::resource('estado_alquiler', EstadoAlquilerController::class);
    Route::resource('recordatorio_pago', RecordatorioPagoController::class);

    Route::get('/asignar-multiples-roles', [UsuarioRolController::class, 'asignarMultiplesRoles'])
        ->name('usuario_rol.asignarMultiplesRoles');
    Route::post('/guardar-multiples-roles', [UsuarioRolController::class, 'guardarMultiplesRoles'])
        ->name('usuario_rol.guardarMultiplesRoles');

    Route::get('/get-apartamentos/{usuario_id}', [EvaluacionController::class, 'getApartamentosByUsuario']);
});

/*
|--------------------------------------------------------------------------
| Rutas específicas para Propietarios
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:propietario'])->prefix('propietario')->name('propietario.')->group(function () {
    // Rutas para edificios
    Route::get('/edificios', [PropietarioEdificioController::class, 'index'])->name('edificios.index');
    Route::get('/edificios/{id}', [PropietarioEdificioController::class, 'show'])->name('edificios.show');
    Route::get('/edificios/{id}/edit', [PropietarioEdificioController::class, 'edit'])->name('edificios.edit');
    Route::put('/edificios/{id}', [PropietarioEdificioController::class, 'update'])->name('edificios.update');

    // Rutas para apartamentos
    Route::get('/apartamentos', [PropietarioApartamentoController::class, 'index'])->name('apartamentos.index');
    Route::get('/apartamentos/create', [PropietarioApartamentoController::class, 'create'])->name('apartamentos.create');
    Route::post('/apartamentos', [PropietarioApartamentoController::class, 'store'])->name('apartamentos.store');
    Route::get('/apartamentos/{id}', [PropietarioApartamentoController::class, 'show'])->name('apartamentos.show');
    Route::get('/apartamentos/{id}/edit', [PropietarioApartamentoController::class, 'edit'])->name('apartamentos.edit');
    Route::put('/apartamentos/{id}', [PropietarioApartamentoController::class, 'update'])->name('apartamentos.update');

    // Rutas para evaluaciones
    Route::get('/evaluaciones', [PropietarioEvaluacionController::class, 'index'])->name('evaluaciones.index');
    Route::get('/evaluaciones/{id}', [PropietarioEvaluacionController::class, 'show'])->name('evaluaciones.show');

    // Ruta para ver evaluaciones por apartamento
    Route::get('/apartamentos/{id}/evaluaciones', [PropietarioEvaluacionController::class, 'porApartamento'])
        ->name('apartamentos.evaluaciones');

    // Rutas para solicitudes
    Route::get('/solicitudes', [PropietarioSolicitudController::class, 'index'])->name('solicitudes.index');
    Route::get('/solicitudes/{id}', [PropietarioSolicitudController::class, 'show'])->name('solicitudes.show');
    Route::get('/solicitudes/{id}/edit', [PropietarioSolicitudController::class, 'edit'])->name('solicitudes.edit');
    Route::put('/solicitudes/{id}', [PropietarioSolicitudController::class, 'update'])->name('solicitudes.update');

    // Rutas para aprobar/rechazar rápidamente
    Route::post('/solicitudes/{id}/aprobar', [PropietarioSolicitudController::class, 'aprobar'])->name('solicitudes.aprobar');
    Route::post('/solicitudes/{id}/rechazar', [PropietarioSolicitudController::class, 'rechazar'])->name('solicitudes.rechazar');

    // Ruta para ver solicitudes por apartamento
    Route::get('/apartamentos/{id}/solicitudes', [PropietarioSolicitudController::class, 'porApartamento'])
        ->name('solicitudes.apartamento');

    // Rutas para contratos
    Route::get('/contratos', [PropietarioContratoController::class, 'index'])->name('contratos.index');
    Route::get('/contratos/{id}', [PropietarioContratoController::class, 'show'])->name('contratos.show');
    Route::get('/apartamentos/{id}/contratos', [PropietarioContratoController::class, 'porApartamento'])->name('contratos.por-apartamento');

    // Rutas para recordatorios de pago
    Route::get('/recordatorios', [PropietarioRecordatorioController::class, 'index'])->name('recordatorios.index');
    Route::get('/recordatorios/usuario/{id}', [PropietarioRecordatorioController::class, 'porUsuario'])->name('recordatorios.por-usuario');

    // Rutas para estados de alquiler
    Route::get('/estados-alquiler', [PropietarioEstadoAlquilerController::class, 'index'])->name('estados-alquiler.index');
    Route::get('/estados-alquiler/contrato/{id}', [PropietarioEstadoAlquilerController::class, 'porContrato'])->name('estados-alquiler.por-contrato');
    Route::post('/estados-alquiler/contrato/{id}/actualizar', [PropietarioEstadoAlquilerController::class, 'actualizarEstado'])->name('estados-alquiler.actualizar');

    // Rutas para reportes de problemas
    Route::get('/reportes', [PropietarioReporteController::class, 'index'])->name('reportes.index');
    Route::get('/reportes/{id}', [PropietarioReporteController::class, 'show'])->name('reportes.show');
    Route::get('/apartamentos/{id}/reportes', [PropietarioReporteController::class, 'porApartamento'])->name('reportes.por-apartamento');
    Route::post('/reportes/{id}/actualizar-estado', [PropietarioReporteController::class, 'actualizarEstado'])->name('reportes.actualizar-estado');

    // Rutas para quejas
    Route::get('/quejas', [PropietarioQuejaController::class, 'index'])->name('quejas.index');
    Route::get('/quejas/{id}', [PropietarioQuejaController::class, 'show'])->name('quejas.show');
    Route::delete('/quejas/{id}', [PropietarioQuejaController::class, 'destroy'])->name('quejas.destroy');
});

/*
|--------------------------------------------------------------------------
| Rutas para Todos los Usuarios Autenticados (con restricciones en controladores)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    Route::resource('queja', QuejaController::class); // Este es el controlador general para admin
    Route::resource('reporte_problema', ReporteProblemaController::class);
    Route::resource('solicitudes', SolicitudAlquilerController::class);

    Route::get('/apartamentos', [ApartamentoController::class, 'indexPublic'])->name('apartamentos.public');
    Route::get('/apartamento/{id}', [ApartamentoController::class, 'showPublic'])->name('apartamentos.public.show');
});

/*
|--------------------------------------------------------------------------
| NUEVAS RUTAS PARA VISTAS DE USUARIO (Inquilinos y posibles inquilinos)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('usuario')->name('usuario.')->group(function () {
    /*
     * Rutas para TODOS los usuarios (inquilinos y posibles inquilinos)
     */

    // Apartamentos
    Route::get('/explorar', [UsuarioApartamentoController::class, 'index'])
        ->name('apartamentos.explorar');
    Route::get('/explorar/{id}', [UsuarioApartamentoController::class, 'show'])
        ->name('apartamentos.detalle');
    Route::post('/explorar/{id}/solicitar', [UsuarioApartamentoController::class, 'solicitar'])
        ->name('apartamentos.solicitar');

    // Solicitudes
    Route::get('/solicitudes', [UsuarioSolicitudController::class, 'index'])
        ->name('solicitudes.lista');
    Route::get('/solicitudes/{id}', [UsuarioSolicitudController::class, 'show'])
        ->name('solicitudes.detalle');
    Route::post('/solicitudes/{id}/cancelar', [UsuarioSolicitudController::class, 'cancelar'])
        ->name('solicitudes.cancelar');

    /*
     * Rutas para las Quejas - CORREGIDAS Y EN ORDEN CORRECTO
     */
    // Rutas específicas primero (antes de las rutas con parámetros)
    Route::get('/quejas/crear', [UsuarioQuejaController::class, 'crear'])
        ->middleware('role:inquilino')
        ->name('quejas.crear');

    Route::get('/quejas/mis-quejas', [UsuarioQuejaController::class, 'misQuejas'])
        ->name('quejas.mis-quejas');

    Route::get('/quejas', [UsuarioQuejaController::class, 'index'])
        ->middleware('role:inquilino')
        ->name('quejas.index');

    Route::post('/quejas', [UsuarioQuejaController::class, 'store'])
        ->middleware('role:inquilino')
        ->name('quejas.store');

    // Ruta con parámetros AL FINAL
    Route::get('/quejas/{id}', [UsuarioQuejaController::class, 'detalle'])
        ->name('quejas.detalle');

    /*
     * Rutas SOLO para inquilinos (protegidas con middleware)
     */
    Route::middleware(['role:inquilino'])->group(function () {

        Route::get('/mi-apartamento/{contrato_id}', [MiApartamentoController::class, 'show'])
        ->name('mi-apartamento.detalle');
        Route::post('/mi-apartamento/{contrato_id}/evaluar', [MiApartamentoController::class, 'evaluarApartamento'])
        ->name('mi-apartamento.evaluar');
       // Contratos
        Route::get('/contratos', [UsuarioContratoController::class, 'index'])
            ->name('contratos.lista');
        Route::get('/contratos/{id}', [UsuarioContratoController::class, 'show'])
            ->name('contratos.detalle');

        // Reportes de problemas
        Route::get('/reportes', [UsuarioReporteController::class, 'index'])
            ->name('reportes.lista');
        Route::get('/reportes/nuevo', [UsuarioReporteController::class, 'create'])
            ->name('reportes.nuevo');
        Route::post('/reportes', [UsuarioReporteController::class, 'store'])
            ->name('reportes.guardar');
        Route::get('/reportes/{id}', [UsuarioReporteController::class, 'show'])
            ->name('reportes.detalle');
    });

    /*
     * Rutas para firma de contratos (para posibles inquilinos)
     */
    Route::get('/firma', [FirmaContratoController::class, 'index'])->name('firma.index');
    Route::get('/firma/{id}', [FirmaContratoController::class, 'show'])->name('firma.firmar');
    Route::post('/firma/{id}', [FirmaContratoController::class, 'store'])->name('firma.guardar');
});

// Ruta de diagnóstico para comprobar que el controlador funciona
Route::get('/test-queja', [App\Http\Controllers\Usuario\QuejaController::class, 'crear'])
    ->middleware('auth')
    ->name('test.queja');
