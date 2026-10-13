@extends('public.layout')

@section('contenido')
<section class="section service-hero">
    <div class="container service-hero__grid">
        <div>
            <p class="eyebrow">{{ $servicio['nombre'] }}</p>
            <h1>{{ $servicio['h1'] }}</h1>
            <p class="lead">{{ $servicio['intro'] }}</p>
            <a class="btn btn--primary btn--lg" data-tipo="{{ $servicio['tipo'] }}" @if($servicio['pack']) data-pack="{{ $servicio['pack'] }}" @endif href="{{ route('inicio', $servicio['pack'] ? ['pack' => $servicio['pack']] : ['tipo' => $servicio['tipo']]) }}#contacto">Cuéntanos qué necesitas</a>
        </div>
        <aside class="service-summary" aria-label="Resumen del servicio">
            <h2>¿Para quién es?</h2>
            <p>{{ $servicio['para'] }}</p>
            @if($servicio['pack'])
                <p class="service-summary__price">Desde <strong>{{ config('comercial.packs.' . $servicio['pack'] . '.precio') }} €</strong></p>
            @else
                <p class="service-summary__price"><strong>Presupuesto por alcance</strong></p>
            @endif
            <p class="small-print">Antes de contratar confirmamos alcance, impuestos, costes externos y renovaciones.</p>
            <a href="{{ route('precios') }}">Revisar precios y condiciones →</a>
        </aside>
    </div>
</section>
<section class="section service-body">
    <div class="container service-body__grid">
        <div>
            <h2>Qué contempla el servicio</h2>
            <ul class="scope-list">@foreach($servicio['incluye'] as $item)<li>{{ $item }}</li>@endforeach</ul>
            <h2>Cómo definimos el alcance</h2>
            <p>{{ $servicio['alcance'] }}</p>
            <h2>Qué necesitamos para empezar</h2>
            <p>{{ $servicio['materiales'] }}</p>
        </div>
        @if(isset($servicio['proyecto']))
            <aside>
                <h2>Un trabajo relacionado</h2>
                @include('public.partials.proyecto', ['proyecto' => config('proyectos.' . $servicio['proyecto']), 'traducible' => false])
                <a class="text-link" href="{{ route('proyectos') }}">Consultar todos los proyectos →</a>
            </aside>
        @endif
    </div>
</section>
<section class="section faq">
    <div class="container narrow">
        <h2>Preguntas antes de contratar</h2>
        @foreach($servicio['faq'] as $item)
            <details><summary>{{ $item['pregunta'] }}</summary><p>{{ $item['respuesta'] }}</p></details>
        @endforeach
    </div>
</section>
@include('public.partials.servicios-relacionados')
@endsection
