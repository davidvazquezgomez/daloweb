<?php

namespace App\Http\Controllers;

use App\Mail\CorreoContacto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ContactoController extends Controller
{
    public function enviar(Request $request)
    {
        $packs = array_filter(config('comercial.packs'), fn ($pack) => $pack['tipo'] === $request->input('tipo'));
        $validated = $request->validate([
            'nombre'  => 'required|string|max:100',
            'email'   => 'required|email|max:150',
            'tipo'    => 'required|in:web,app,reservas,medida',
            'mensaje' => 'required|string|max:2000',
            'pack' => ['nullable', 'string', Rule::in(array_keys($packs))],
        ]);

        try {
            if (config('mail.default') !== 'smtp' || !config('mail.mailers.smtp.host')) {
                throw new \RuntimeException('El formulario de contacto requiere SMTP configurado.');
            }

            Mail::to('hola@daloweb.es')->send(new CorreoContacto(
                nombre: $validated['nombre'],
                email: $validated['email'],
                tipo: $validated['tipo'],
                mensaje: $validated['mensaje'],
                pack: $validated['pack'] ?? null,
            ));

            return redirect()->to(route('inicio') . '#contacto')->with('success', 'Mensaje enviado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error enviando contacto: ' . $e->getMessage());
            return redirect()->to(route('inicio') . '#contacto')->withInput()->with('error', 'Error al enviar el mensaje. Inténtalo de nuevo.');
        }
    }
}
