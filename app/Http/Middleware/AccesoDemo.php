<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Enums\RolUsuario;
use App\Models\Demo;

class AccesoDemo
{
    public function handle(Request $request, Closure $next)
    {
        $slug = $request->route('slug');
        $demo = Demo::where('slug', $slug)->where('activa', true)->first();

        if (!$demo) {
            abort(404);
        }

        if ($demo->visibilidad === 'privada') {
            $user = $request->user();
            if (!$user || $user->rol !== RolUsuario::Admin) {
                abort(404);
            }
        }

        $request->attributes->set('demo', $demo);

        return $next($request);
    }
}
