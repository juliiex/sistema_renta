<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Si se pasa un solo rol separado por pipe (rol1|rol2|rol3)
        if (count($roles) === 1 && strpos($roles[0], '|') !== false) {
            $roles = explode('|', $roles[0]);
        }

        foreach ($roles as $role) {
            // Usar tu método personalizado hasRole
            if (auth()->user()->hasRole($role)) {
                return $next($request);
            }
        }

        abort(403, 'No tienes permiso para acceder a esta página.');
    }
}
