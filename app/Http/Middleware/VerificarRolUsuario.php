<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificarRolUsuario
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $usuario = $request->user();

        if (! $usuario || ! in_array($usuario->rol->value, $roles, true)) {
           abort(403, 'Acceso Denegado. No tienes los privilegios necesarios para ver esta sección.');
        }

        return $next($request);
    }
}