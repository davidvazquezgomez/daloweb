@extends('public.layout')

@section('contenido')
<section class="section">
    <div class="container">
        <h1>Guías para preparar tu proyecto digital</h1>
        <p class="lead">Entiende el alcance antes de contratar y lleva una lista clara de necesidades a tu primera conversación.</p>
        <div class="guide-grid">
            @foreach(config('guias') as $slug => $guia)
                <article class="guide-card">
                    <h2><a href="{{ route('guia', ['slug' => $slug]) }}">{{ $guia['h1'] }}</a></h2>
                    <p>{{ $guia['intro'] }}</p>
                    <a class="text-link" href="{{ route('guia', ['slug' => $slug]) }}">Leer guía →</a>
                </article>
            @endforeach
        </div>
    </div>
</section>
@include('public.partials.servicios-relacionados')
@endsection
