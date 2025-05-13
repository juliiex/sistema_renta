<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

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
            // Intenta autenticar al usuario con las credenciales proporcionadas.
            $request->authenticate();

            // Regenera la sesión para prevenir ataques de secuencias de reenvío.
            $request->session()->regenerate();

            // Redirige al usuario a la página de bienvenida.
            return redirect()->route('welcome')
                             ->with('success', '¡Bienvenido de nuevo!');
        } catch (\Exception $e) {
            // Si la autenticación falla, redirige al formulario de login con un mensaje de error.
            return back()->withErrors(['email' => 'El correo electrónico o la contraseña son incorrectos']);
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
