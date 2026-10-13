@extends('public.layout')

@section('contenido')
<section class="section">
    <div class="container">
        <h1>Proyectos de webs, apps y software</h1>
        <p class="lead">Una muestra de los trabajos que presentamos en DaloWeb. Consulta las capturas y visita cada proyecto para conocer su enfoque.</p>
        <div class="projects__grid">
            @foreach(config('proyectos') as $proyecto)
                @include('public.partials.proyecto', ['traducible' => false])
            @endforeach
        </div>
        <a class="btn btn--primary section-action" href="{{ route('inicio') }}#contacto">Hablemos de tu proyecto</a>
    </div>
</section>
@include('public.partials.servicios-relacionados')
@endsection
