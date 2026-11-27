<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();

        // LIMPIAMOS CUALQUIER INTENTO DE RUTA ANTERIOR GUARDADA EN LA SESIÓN
        session()->forget('url.intended');

        // REDIRECCIÓN ESTRICTA SEGÚN EL ROL
        if ($user->rol->value === 'administrador') {
            return redirect('/admin/dashboard');
        }
        
        return redirect('/solicitante/dashboard');
    }
}