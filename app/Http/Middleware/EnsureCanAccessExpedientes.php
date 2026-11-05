<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureCanAccessExpedientes
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Control estricto de roles: la plantilla docente queda bloqueada por completo
        if ($user && !$user->puedeVerExpedientes()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Acceso Denegado por Confidencialidad',
                    'mensaje' => 'La plantilla docente no tiene autorización para consultar expedientes psicopedagógicos o diagnósticos clínicos.'
                ], 403);
            }

            return response()->view('errors.403', [
                'motivo' => 'De conformidad con los protocolos de confidencialidad institucional del Colegio Paidos y la normativa de protección de datos de salud infantil, los expedientes psicopedagógicos, diagnósticos clínicos y bitácoras de intervención están reservados con exclusividad para la Especialista en Psicopedagogía y Dirección Escolar. La plantilla docente tiene estrictamente restringido el acceso a esta información.'
            ], 403);
        }

        return $next($request);
    }
}

