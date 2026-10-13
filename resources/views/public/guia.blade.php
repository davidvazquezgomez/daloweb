@extends('public.layout')

@section('contenido')
<article class="section guide-article">
    <div class="container narrow">
        <h1>{{ $guia['h1'] }}</h1>
        <p class="guide-byline">Por DaloWeb · Revisado el <time datetime="{{ $guia['actualizado'] }}">{{ \Carbon\Carbon::parse($guia['actualizado'])->format('d/m/Y') }}</time></p>
        <p class="lead">{{ $guia['intro'] }}</p>
        <nav class="guide-contents" aria-label="Contenido de la guía">
            <strong>En esta guía</strong>
            <ol>
                @foreach($guia['secciones'] as $indice => $seccion)
                    <li><a href="#apartado-{{ $indice + 1 }}">{{ $seccion['titulo'] }}</a></li>
                @endforeach
            </ol>
        </nav>
        @foreach($guia['secciones'] as $indice => $seccion)
            <section id="apartado-{{ $indice + 1 }}">
                <h2>{{ $seccion['titulo'] }}</h2>
                @foreach($seccion['parrafos'] as $parrafo)<p>{{ $parrafo }}</p>@endforeach
                @if(isset($seccion['lista']))
                    <ul class="scope-list">@foreach($seccion['lista'] as $item)<li>{{ $item }}</li>@endforeach</ul>
                @endif
            </section>
        @endforeach
        @if($guia['precios'] ?? false)
            <section class="guide-price-reference">
                <h2>Referencias de partida de DaloWeb</h2>
                <dl>
                    @foreach(config('comercial.packs') as $pack)
                        <div><dt>{{ $pack['nombre'] }}</dt><dd>Desde {{ $pack['precio'] }} €</dd></div>
                    @endforeach
                </dl>
                <a class="text-link" href="{{ route('precios') }}">Consultar alcance y condiciones →</a>
            </section>
        @endif
        <aside class="guide-next-step">
            <h2>Define tu siguiente paso</h2>
            <p>Consulta nuestro servicio de {{ mb_strtolower(config('servicios.' . $guia['servicio'] . '.nombre')) }} o cuéntanos qué necesitas para preparar un alcance concreto.</p>
            <a class="btn btn--primary" href="{{ route('servicio.' . $guia['servicio']) }}">Ver el servicio relacionado</a>
        </aside>
        <nav class="guide-related" aria-label="Otras guías">
            <h2>Otras preguntas antes de contratar</h2>
            @foreach(config('guias') as $slug => $item)
                @if(!request()->routeIs('guia') || request()->route('slug') !== $slug)
                    <a class="text-link" href="{{ route('guia', ['slug' => $slug]) }}">{{ $item['nombre'] }} →</a>
                @endif
            @endforeach
        </nav>
    </div>
</article>
@endsection
