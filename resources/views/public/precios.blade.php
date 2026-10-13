@extends('public.layout')

@section('contenido')
<section class="section pricing">
    <div class="container">
        <h1>Precios para elegir el siguiente paso de tu negocio</h1>
        <p class="lead">Una web para presentar tus servicios, un sistema para organizar citas o un proyecto a medida. Estas son las referencias de partida; el presupuesto concreta lo que necesitas.</p>
        @include('public.partials.packs')
    </div>
</section>
<section class="section service-body">
    <div class="container narrow">
        <h2>Qué debes tener claro en tu presupuesto</h2>
        <ul class="scope-list">
            <li>Páginas, pantallas, funciones y número de revisiones incluidas.</li>
            <li>Materiales necesarios y desde cuándo empieza el calendario de entrega.</li>
            <li>Impuestos, dominio, hosting, licencias y costes de proveedores, con sus renovaciones.</li>
            <li>Soporte incluido, mantenimiento posterior y cómo se valoran nuevas funcionalidades.</li>
            <li>Titularidad de cuentas y activos, condiciones de pago y entrega.</li>
        </ul>
        <p>Una tienda online, una nueva integración o más pantallas cambian el alcance. Solicitarlas expresamente permite comparar presupuestos sobre el mismo trabajo.</p>
        <a class="btn btn--primary" href="{{ route('inicio') }}#contacto">Solicitar un presupuesto para mi proyecto</a>
    </div>
</section>
@include('public.partials.faq')
@include('public.partials.servicios-relacionados')
@endsection
