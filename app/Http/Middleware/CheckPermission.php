<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Si hay varios permisos separados por pipe (permiso1|permiso2|permiso3)
        if (strpos($permission, '|') !== false) {
            $permissions = explode('|', $permission);
            foreach ($permissions as $permission_check) {
                if (auth()->user()->can($permission_check)) {
                    return $next($request);
                }
            }
            abort(403, 'No tienes permiso para acceder a esta página.');
        }

        // Un solo permiso
        if (auth()->user()->can($permission)) {
            return $next($request);
        }

        abort(403, 'No tienes permiso para acceder a esta página.');
    }
}
