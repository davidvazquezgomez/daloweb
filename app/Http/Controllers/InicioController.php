<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Support\OrigenContacto;

class InicioController extends Controller
{
    public function index(Request $request)
    {
        $pack = $request->query('pack');
        $tipo = $request->query('tipo');

        return view('landing', [
            'packPredeterminado' => is_string($pack) && isset(config('comercial.packs')[$pack]) ? $pack : '',
            'tipoPredeterminado' => is_string($tipo) && in_array($tipo, ['web', 'reservas', 'app', 'medida'], true) ? $tipo : '',
            'origenContacto' => OrigenContacto::resolver($request),
        ]);
    }

    public function servicio(string $slug)
    {
        $servicio = config('servicios')[$slug] ?? null;
        abort_unless($servicio, 404);

        return view('public.servicio', [
            'servicio' => $servicio,
            'seo' => ['title' => $servicio['title'], 'descripcion' => $servicio['descripcion'], 'path' => '/' . $slug],
            'breadcrumb' => $servicio['nombre'],
        ]);
    }

    public function precios()
    {
        return view('public.precios', [
            'seo' => ['title' => 'Precios de diseño web, reservas y apps | DaloWeb', 'descripcion' => 'Compara los servicios de DaloWeb: web desde 200 €, reservas desde 600 € y apps desde 800 €. Revisa el alcance y qué confirmar antes de contratar.', 'path' => '/precios'],
            'breadcrumb' => 'Precios',
        ]);
    }

    public function proyectos()
    {
        return view('public.proyectos', [
            'seo' => ['title' => 'Proyectos de webs, apps y software | DaloWeb', 'descripcion' => 'Consulta los proyectos de DaloWeb: webs para negocios, aplicaciones móviles y herramientas de gestión, con capturas y enlaces a los trabajos.', 'path' => '/proyectos'],
            'breadcrumb' => 'Proyectos',
        ]);
    }

    public function sitemap()
    {
        $paths = array_merge(
            ['/'], array_map(fn ($slug) => '/' . $slug, array_keys(config('servicios'))),
            ['/precios', '/proyectos', '/guias'],
            array_map(fn ($slug) => '/guias/' . $slug, array_keys(config('guias'))),
        );

        return response()->view('sitemap', [
            'urls' => array_map(fn ($path) => rtrim(config('app.url'), '/') . $path, $paths),
        ])->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
