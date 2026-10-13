<?php

namespace App\Http\Controllers;

class GuiaController extends Controller
{
    public function index()
    {
        return view('public.guias', [
            'seo' => ['title' => 'Guías para preparar tu proyecto digital | DaloWeb', 'descripcion' => 'Guías de DaloWeb para comparar presupuestos web, entender el mantenimiento y decidir entre web y app antes de contratar.', 'path' => '/guias'],
            'breadcrumb' => 'Guías',
        ]);
    }

    public function mostrar(string $slug)
    {
        $guia = config('guias')[$slug] ?? null;
        abort_unless($guia, 404);

        return view('public.guia', [
            'guia' => $guia,
            'seo' => ['title' => $guia['title'], 'descripcion' => $guia['descripcion'], 'path' => '/guias/' . $slug],
            'breadcrumb' => $guia['nombre'],
            'breadcrumbs' => [
                ['name' => 'Inicio', 'path' => '/'],
                ['name' => 'Guías', 'path' => '/guias'],
                ['name' => $guia['nombre'], 'path' => '/guias/' . $slug],
            ],
        ]);
    }
}
