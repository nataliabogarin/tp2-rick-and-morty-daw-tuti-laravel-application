<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Restringe el acceso a usuarios administradores
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect()->route('profile.edit')
                ->with('error', 'No tenés permisos para acceder a esta sección.');
        }

        return $next($request);
    }
}
