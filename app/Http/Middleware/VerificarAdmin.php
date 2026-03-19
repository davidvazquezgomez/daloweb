<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Enums\RolUsuario;

class VerificarAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || $request->user()->rol !== RolUsuario::Admin) {
            \Illuminate\Support\Facades\Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'correo' => 'No tienes permisos para acceder al panel.',
            ]);
        }

        return $next($request);
    }
}
