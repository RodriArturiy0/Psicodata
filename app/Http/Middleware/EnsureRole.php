<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = Auth::user();

        if ($user && !in_array($user->rol, $roles)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Acceso no autorizado',
                    'mensaje' => 'Su rol (' . $user->rol . ') no cuenta con los permisos necesarios para este módulo.'
                ], 403);
            }

            return response()->view('errors.403', [
                'motivo' => 'Su cuenta (' . $user->name . ') con rol ' . $user->rol . ' no tiene autorización para acceder a este módulo institucional.'
            ], 403);
        }

        return $next($request);
    }
}

