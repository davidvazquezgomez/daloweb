@extends('public.layout')
@section('contenido')
<section class="section legal-page">
    <div class="container narrow">
        <h1>Política de privacidad</h1>
        <p class="lead">Información sobre el tratamiento de datos al utilizar www.daloweb.es y contactar con DaloWeb.</p>
        <h2>1. Responsable del tratamiento</h2>
        <dl class="legal-data">
            <dt>Nombre comercial</dt><dd>{{ config('legal.marca') }}</dd>
            <dt>Responsable: nombre o razón social</dt><dd>{{ config('legal.titular') }}</dd>
            <dt>NIF</dt><dd>{{ config('legal.nif') }}</dd>
            <dt>Domicilio</dt><dd>{{ config('legal.domicilio') }}</dd>
            <dt>Contacto para protección de datos</dt><dd>{{ config('legal.correo_privacidad') }}</dd>
            <dt>Delegado de protección de datos, si procede</dt><dd>{{ config('legal.delegado') }}</dd>
        </dl>
        <h2>2. Datos y finalidades</h2>
        <p>El formulario solicita nombre, correo electrónico, tipo de proyecto y mensaje. También puede acompañar la solicitud el pack seleccionado y una categoría de procedencia, como acceso directo, buscador o referido. Esta categoría no incluye la URL original, consultas de búsqueda ni identificadores de campaña.</p>
        <p>Se utilizan para responder a tu consulta, preparar la propuesta solicitada y gestionar las comunicaciones relacionadas con ella. Los campos obligatorios son necesarios para tramitar la consulta. Evita incluir datos sensibles o datos de otras personas que no sean necesarios.</p>
        <p>La solicitud se envía al correo de DaloWeb. Si está habilitado el seguimiento interno de oportunidades, también se registra para gestionar su estado y la propuesta. Las notas de gestión deben limitarse a lo necesario para atender la solicitud.</p>
        <h2>3. Base jurídica</h2>
        <p>Cuando solicitas una propuesta para contratar servicios, el tratamiento responde a las medidas precontractuales solicitadas por ti. Para otras consultas, la base jurídica y el interés legítimo aplicable, en su caso, son: {{ config('legal.bases_consultas') }}. Las obligaciones legales se atenderán cuando resulten aplicables. El formulario no suscribe al usuario a comunicaciones comerciales.</p>
        <h2>4. Conservación</h2>
        <p>Plazos o criterios de conservación de consultas, correos, oportunidades y copias de seguridad: {{ config('legal.conservacion') }}. Si se contrata un servicio, la conservación de la documentación contractual y fiscal deberá ajustarse a las obligaciones legales correspondientes.</p>
        <h2>5. Destinatarios y proveedores</h2>
        <p>Los proveedores que prestan alojamiento y correo pueden tratar datos para prestar esos servicios. Proveedor de alojamiento: {{ config('legal.hosting') }}. Proveedor de correo: {{ config('legal.correo_proveedor') }}. Otros encargados, destinatarios y condiciones aplicables: {{ config('legal.destinatarios') }}.</p>
        <p>Transferencias internacionales, ubicación de los proveedores y garantías aplicables, si procede: {{ config('legal.transferencias') }}.</p>
        <h2>6. Derechos</h2>
        <p>Puedes solicitar acceso, rectificación, supresión, limitación, oposición y portabilidad cuando se cumplan sus requisitos, así como retirar el consentimiento cuando sea la base del tratamiento. Dirige tu solicitud al contacto de protección de datos indicado arriba. Podrá solicitarse la información proporcionada y necesaria para verificar tu identidad.</p>
        <p>Si consideras que tus derechos no han sido atendidos, puedes presentar una reclamación ante la <a href="https://www.aepd.es/derechos-y-deberes/conoce-tus-derechos">Agencia Española de Protección de Datos</a>.</p>
        <h2>7. Cookies, registros y medición</h2>
        <p>El sitio utiliza cookies técnicas de sesión y mecanismos de seguridad para mantener la sesión y proteger formularios. En la portada, la preferencia de idioma puede guardarse en el almacenamiento local del navegador. Puedes eliminarla desde sus ajustes.</p>
        <p>Los eventos de interacción preparados por la web no incluyen el nombre, el correo ni el contenido del mensaje. Su integración con un proveedor de analítica debe documentarse antes de activarla. Proveedores, base jurídica y condiciones de medición, si se habilita: {{ config('legal.analitica') }}.</p>
        <p>El servidor puede generar registros técnicos para operar y proteger el servicio. Datos registrados, acceso y conservación: {{ config('legal.registros') }}. Decisiones automatizadas o elaboración de perfiles, si procede: {{ config('legal.decisiones') }}.</p>
        <h2>8. Seguridad y cambios</h2>
        <p>El acceso a la gestión de oportunidades requiere autenticación y permisos de administrador. Las condiciones de tratamiento deben revisarse cuando cambien los servicios o proveedores.</p>
        <p>Más información sobre el titular en el <a href="{{ route('aviso-legal') }}">aviso legal</a>.</p>
        @if(in_array('XXXXXXX', config('legal'), true))<p class="small-print">Los campos XXXXXXX están pendientes de completar.</p>@endif
    </div>
</section>
@endsection
