<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            // Loguemos para depurar
            Log::info('Intento de autenticación para: ' . $request->correo);

            // Intenta autenticar al usuario con las credenciales proporcionadas.
            $request->authenticate();

            // Loguemos el éxito
            Log::info('Autenticación exitosa para: ' . $request->correo);

            // Regenera la sesión para prevenir ataques de secuencias de reenvío.
            $request->session()->regenerate();

            // Obtener el usuario autenticado
            $usuario = Auth::user();
            Log::info('Usuario autenticado con ID: ' . $usuario->id);
            Log::info('Roles: ' . implode(', ', $usuario->roles()->pluck('nombre')->toArray()));

            // Redirige según el rol del usuario
            if ($usuario->hasRole(['admin', 'propietario'])) {
                Log::info('Redirigiendo a dashboard');
                return redirect()->route('dashboard')->with('success', '¡Bienvenido de nuevo!');
            }

            Log::info('Redirigiendo a home');
            return redirect()->route('home')->with('success', '¡Bienvenido de nuevo!');
        } catch (\Exception $e) {
            // Loguemos el error con detalles
            Log::error('Error de autenticación: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            // Si la autenticación falla, redirige al formulario de login con un mensaje de error.
            return back()->withErrors(['correo' => 'El correo electrónico o la contraseña son incorrectos'])
                         ->withInput(); // Esto preserva los datos del formulario
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Has cerrado sesión correctamente.');
    }
}
