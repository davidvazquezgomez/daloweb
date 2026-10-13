<?php

namespace App\Http\Controllers;

class LegalController extends Controller
{
    public function aviso()
    {
        return $this->pagina('aviso-legal', 'Aviso legal', 'Información sobre el titular de DaloWeb y las condiciones de uso de su sitio web.');
    }

    public function privacidad()
    {
        return $this->pagina('privacidad', 'Política de privacidad', 'Información sobre los datos tratados al contactar con DaloWeb, sus finalidades y tus derechos.');
    }

    public function cookies()
    {
        return $this->pagina('cookies', 'Política de cookies', 'Información sobre las cookies de DaloWeb, Google Analytics y cómo aceptar, rechazar o retirar tu consentimiento.');
    }

    private function pagina(string $slug, string $titulo, string $descripcion)
    {
        return view('public.' . $slug, [
            'seo' => ['title' => $titulo . ' | DaloWeb', 'descripcion' => $descripcion, 'path' => '/' . $slug, 'noindex' => in_array('XXXXXXX', config('legal'), true)],
            'breadcrumb' => $titulo,
        ]);
    }
}
