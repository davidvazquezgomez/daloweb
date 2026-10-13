@extends('public.layout')
@section('contenido')
<section class="section legal-page">
    <div class="container narrow">
        <h1>Aviso legal</h1>
        <p class="lead">Información del titular y condiciones de uso de www.daloweb.es.</p>
        <h2>1. Identificación del titular</h2>
        <dl class="legal-data">
            <dt>Nombre comercial</dt><dd>{{ config('legal.marca') }}</dd>
            <dt>Nombre o razón social del titular</dt><dd>{{ config('legal.titular') }}</dd>
            <dt>NIF</dt><dd>{{ config('legal.nif') }}</dd>
            <dt>Domicilio</dt><dd>{{ config('legal.domicilio') }}</dd>
            <dt>Correo de contacto del titular</dt><dd>{{ config('legal.correo') }}</dd>
            <dt>Datos registrales, si procede</dt><dd>{{ config('legal.registro') }}</dd>
            <dt>Autorización, colegio profesional y normativa de actividad regulada, si procede</dt><dd>{{ config('legal.actividad_regulada') }}</dd>
        </dl>
        <h2>2. Objeto del sitio</h2>
        <p>DaloWeb presenta servicios de diseño web, sistemas de reservas, aplicaciones móviles y software a medida. El formulario permite realizar consultas y solicitar propuestas. Enviar una consulta no implica contratar un servicio.</p>
        <h2>3. Precios y contratación</h2>
        <p>Los precios publicados son referencias de partida. El presupuesto debe concretar alcance, impuestos, costes externos, revisiones, soporte, pagos y renovaciones antes de la contratación. Las funcionalidades adicionales se valoran conforme al alcance acordado.</p>
        <h2>4. Uso del sitio</h2>
        <p>Utiliza el sitio de forma lícita y facilita información veraz al contactar. No está permitido interferir en su funcionamiento, intentar acceder sin autorización a áreas privadas ni utilizar el formulario para mensajes fraudulentos o ajenos a su finalidad.</p>
        <h2>5. Propiedad intelectual</h2>
        <p>Los textos, diseños, código, marcas e imágenes están sujetos a los derechos de sus respectivos titulares. Las referencias y capturas de proyectos no transfieren derechos sobre los activos de clientes o terceros. Su reutilización debe respetar las licencias y autorizaciones aplicables.</p>
        <h2>6. Enlaces y disponibilidad</h2>
        <p>Los enlaces a proyectos y otros sitios permiten consultar información de terceros, sujetos a sus propias condiciones. DaloWeb puede actualizar contenidos o interrumpir temporalmente el acceso por mantenimiento. La responsabilidad se determinará conforme a la normativa aplicable.</p>
        <h2>7. Privacidad</h2>
        <p>Consulta la <a href="{{ route('privacidad') }}">política de privacidad</a> para conocer cómo se trata la información facilitada al contactar.</p>
        <h2>8. Normativa y resolución de controversias</h2>
        <p>Se aplicará la normativa que corresponda a la relación con el usuario, respetando los derechos y las reglas de competencia de los consumidores. Puedes dirigir consultas o reclamaciones al contacto del titular indicado arriba.</p>
        @if(in_array('XXXXXXX', config('legal'), true))<p class="small-print">Los campos XXXXXXX están pendientes de completar.</p>@endif
    </div>
</section>
@endsection
