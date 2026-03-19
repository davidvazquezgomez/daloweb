<?php

namespace App\Http\Controllers;

use App\Mail\CorreoContacto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactoController extends Controller
{
    public function enviar(Request $request)
    {
        $validated = $request->validate([
            'nombre'  => 'required|string|max:100',
            'email'   => 'required|email|max:150',
            'tipo'    => 'required|in:web,app,reservas,medida',
            'mensaje' => 'required|string|max:2000',
        ]);

        try {
            Mail::to('hola@daloweb.es')->send(new CorreoContacto(
                nombre: $validated['nombre'],
                email: $validated['email'],
                tipo: $validated['tipo'],
                mensaje: $validated['mensaje'],
            ));

            return back()->with('success', 'Mensaje enviado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error enviando contacto: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Error al enviar el mensaje. Inténtalo de nuevo.');
        }
    }
}
