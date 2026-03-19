<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function mostrar()
    {
        return view('auth.login');
    }

    public function autenticar(Request $request)
    {
        $credenciales = $request->validate([
            'correo' => 'required|email',
            'contrasena' => 'required|string',
        ]);

        // Laravel usa 'password' internamente, mapeamos 'contrasena'
        $intento = Auth::attempt(
            ['correo' => $credenciales['correo'], 'password' => $credenciales['contrasena']],
            $request->boolean('recordarme')
        );

        if ($intento) {
            $usuario = Auth::user();

            // Solo administradores pueden acceder al panel
            if ($usuario->rol !== \App\Enums\RolUsuario::Admin) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'correo' => 'No tienes permisos para acceder al panel.',
                ])->onlyInput('correo');
            }

            $request->session()->regenerate();

            $usuario->ultimo_acceso = now();
            $usuario->save();

            return redirect()->intended(route('admin.panel'));
        }

        return back()->withErrors([
            'correo' => 'Las credenciales no coinciden.',
        ])->onlyInput('correo');
    }

    public function salir(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('inicio');
    }
}
