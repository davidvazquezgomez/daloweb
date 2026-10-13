@php
    $esPortada = request()->routeIs('inicio');
    $traducible = $esPortada;
    $seo = $seo ?? [
        'title' => 'DaloWeb — Webs, Apps y Software a Medida',
        'descripcion' => 'Webs profesionales, apps móviles, sistemas de reservas y software a medida para tu negocio. Consulta nuestros proyectos y solicita tu presupuesto.',
        'path' => '/',
    ];
    $baseUrl = rtrim(config('app.url'), '/');
    $canonical = $baseUrl . $seo['path'];
    $schema = [
        '@context' => 'https://schema.org',
        '@graph' => [[
            '@type' => 'Organization', '@id' => $baseUrl . '/#organizacion',
            'name' => 'DaloWeb', 'url' => $baseUrl . '/',
            'logo' => $baseUrl . '/img/favicon/favicon.svg', 'email' => 'hola@daloweb.es',
        ]],
    ];
    if (isset($breadcrumb)) {
        $breadcrumbs = $breadcrumbs ?? [
            ['name' => 'Inicio', 'path' => '/'],
            ['name' => $breadcrumb, 'path' => $seo['path']],
        ];
        $schema['@graph'][] = [
            '@type' => 'BreadcrumbList',
            'itemListElement' => array_map(fn ($item, $index) => [
                '@type' => 'ListItem', 'position' => $index + 1, 'name' => $item['name'], 'item' => $baseUrl . $item['path'],
            ], $breadcrumbs, array_keys($breadcrumbs)),
        ];
    }
    if (isset($guia)) {
        $schema['@graph'][] = [
            '@type' => 'Article', 'headline' => $guia['h1'], 'description' => $guia['descripcion'],
            'mainEntityOfPage' => $canonical, 'inLanguage' => 'es',
            'dateModified' => $guia['actualizado'],
            'author' => ['@type' => 'Organization', 'name' => 'DaloWeb', 'url' => $baseUrl . '/'],
            'publisher' => ['@id' => $baseUrl . '/#organizacion'],
        ];
    }
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $seo['title'] }}</title>
    <meta name="description" content="{{ $seo['descripcion'] }}">
    <link rel="canonical" href="{{ $canonical }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="DaloWeb">
    <meta property="og:title" content="{{ $seo['title'] }}">
    <meta property="og:description" content="{{ $seo['descripcion'] }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:image" content="{{ $baseUrl }}/img/proyectos/around-sevilla.png">
    <meta property="og:image:alt" content="Proyecto de DaloWeb para Around Sevilla Tours">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seo['title'] }}">
    <meta name="twitter:description" content="{{ $seo['descripcion'] }}">
    <meta name="twitter:image" content="{{ $baseUrl }}/img/proyectos/around-sevilla.png">
    <script type="application/ld+json">@json($schema)</script>
    <link rel="icon" type="image/svg+xml" href="{{ asset('img/favicon/favicon.svg') }}">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <link rel="stylesheet" href="{{ asset('css/comercial.css') }}">
    @if($traducible)
        <script src="{{ asset('js/landing/languages.js') }}" defer></script>
    @endif
    <script src="{{ asset('js/landing/analytics.js') }}" defer></script>
    <script src="{{ asset('js/landing/main.js') }}" defer></script>
</head>
<body class="{{ $esPortada ? 'pagina-inicio' : 'pagina-interior' }}" data-origen="{{ \App\Support\OrigenContacto::resolver(request()) }}">
    <a class="skip-link" href="#contenido">Saltar al contenido</a>
    @include('public.partials.header')
    <main id="contenido" tabindex="-1">
        @if(isset($breadcrumb))
            <nav class="container breadcrumbs" aria-label="Ruta de navegación">
                @foreach($breadcrumbs as $item)
                    @if(!$loop->first)<span aria-hidden="true">/</span>@endif
                    @if($loop->last)<span aria-current="page">{{ $item['name'] }}</span>
                    @else<a href="{{ url($item['path']) }}">{{ $item['name'] }}</a>@endif
                @endforeach
            </nav>
        @endif
        @yield('contenido')
    </main>
    @include('public.partials.footer')
    @if(session('contact_event'))
        <script type="application/json" id="contactEvent">@json(session('contact_event'))</script>
    @elseif($errors->any())
        @php($validationEvent = ['event' => 'lead_submit_error', 'reason' => 'validation'])
        <script type="application/json" id="contactEvent">@json($validationEvent)</script>
    @endif
</body>
</html>
