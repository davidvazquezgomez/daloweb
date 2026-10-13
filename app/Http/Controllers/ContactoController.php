<?php

namespace App\Http\Controllers;

use App\Mail\CorreoContacto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Models\Lead;
use App\Support\OrigenContacto;

class ContactoController extends Controller
{
    public function enviar(Request $request)
    {
        $packs = array_filter(config('comercial.packs'), fn ($pack) => $pack['tipo'] === $request->input('tipo'));
        $validated = $request->validate([
            'nombre'  => 'required|string|max:100',
            'email'   => 'required|email|max:150',
            'tipo'    => 'required|string|in:web,app,reservas,medida',
            'mensaje' => 'required|string|max:2000',
            'pack' => ['nullable', 'string', Rule::in(array_keys($packs))],
            'origen' => ['nullable', 'string', Rule::in(array_keys(config('leads.origenes')))],
        ]);

        $lead = null;
        $origen = OrigenContacto::resolver($request);
        try {
            if (config('leads.captura')) {
                $lead = Lead::create([
                    'nombre' => $validated['nombre'], 'email' => $validated['email'],
                    'tipo' => $validated['tipo'], 'pack' => $validated['pack'] ?? null,
                    'origen' => $origen, 'mensaje' => $validated['mensaje'],
                ]);
            }
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

            if ($lead) {
                try { $lead->update(['correo_estado' => 'enviado']); }
                catch (\Exception $storageError) { Log::error('Correo enviado; no se pudo actualizar su estado en el lead.'); }
            }

            return redirect()->to(route('inicio') . '#contacto')
                ->with('success', 'Mensaje enviado correctamente.')
                ->with('contact_event', [
                    'event' => 'lead_submit_success', 'service' => $validated['tipo'],
                    'pack' => $validated['pack'] ?? null, 'id' => (string) Str::uuid(),
                    'origin' => $origen,
                ]);
        } catch (\Exception $e) {
            if ($lead) {
                try { $lead->update(['correo_estado' => 'fallido']); }
                catch (\Exception $storageError) { Log::error('No se pudo actualizar el estado del correo del lead.'); }
            }
            Log::error('Error enviando contacto.', ['tipo_error' => get_class($e)]);
            return redirect()->to(route('inicio') . '#contacto')->withInput()
                ->with('error', 'Error al enviar el mensaje. Inténtalo de nuevo.')
                ->with('contact_event', ['event' => 'lead_submit_error', 'reason' => 'delivery']);
        }
    }
}
