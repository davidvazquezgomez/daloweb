@extends('public.layout')
@section('contenido')
<section class="section legal-page">
    <div class="container narrow">
        <h1>Política de cookies</h1>
        <p class="lead">Qué cookies y almacenamiento del navegador utiliza DaloWeb y cómo puedes decidir sobre su uso.</p>
        <p>Última actualización: 13 de octubre de 2026.</p>
        <h2>1. Responsable</h2>
        <p>Nombre comercial: {{ config('legal.marca') }}. Titular: {{ config('legal.titular') }}. NIF: {{ config('legal.nif') }}. Domicilio: {{ config('legal.domicilio') }}. Contacto para protección de datos: {{ config('legal.correo_privacidad') }}.</p>
        <h2>2. Qué son las cookies</h2>
        <p>Las cookies son pequeños archivos que se guardan en tu navegador. Permiten mantener sesiones o medir el uso de una web. El almacenamiento local permite guardar preferencias en el navegador; también lo describimos aquí.</p>
        <h2>3. Cookies y almacenamiento que utilizamos</h2>
        <div class="cookie-table-wrapper" role="region" aria-label="Detalle de cookies y almacenamiento" tabindex="0">
            <table class="cookie-table">
                <caption>Cookies y preferencias de DaloWeb</caption>
                <thead><tr><th scope="col">Nombre</th><th scope="col">Proveedor y tipo</th><th scope="col">Finalidad</th><th scope="col">Duración</th></tr></thead>
                <tbody>
                    <tr><th scope="row">{{ config('session.cookie') }}</th><td>DaloWeb · Técnica</td><td>Mantener la sesión, mensajes del formulario y acceso al área privada.</td><td>{{ config('session.expire_on_close') ? 'Hasta cerrar el navegador' : config('session.lifetime') . ' minutos de inactividad' }}.</td></tr>
                    <tr><th scope="row">XSRF-TOKEN</th><td>DaloWeb · Técnica</td><td>Proteger las solicitudes contra falsificaciones (CSRF).</td><td>{{ config('session.expire_on_close') ? 'Hasta cerrar el navegador' : config('session.lifetime') . ' minutos' }}.</td></tr>
                    <tr><th scope="row">lang</th><td>DaloWeb · Preferencia en almacenamiento local</td><td>Recordar el idioma que seleccionas en la portada.</td><td>Hasta que cambies el idioma o borres el almacenamiento del navegador.</td></tr>
                    <tr><th scope="row">daloweb_cookie_consent</th><td>DaloWeb · Consentimiento en almacenamiento local</td><td>Recordar la aceptación o el rechazo, la versión y el vencimiento de la elección.</td><td>180 días. Después volveremos a pedir tu decisión.</td></tr>
                    <tr><th scope="row">_ga</th><td>Google Analytics · Analítica</td><td>Distinguir navegadores para elaborar estadísticas de visitas.</td><td>Hasta 2 años desde su creación; puede borrarse antes al retirar el consentimiento.</td></tr>
                    <tr><th scope="row">_ga_TT318T6SZZ</th><td>Google Analytics · Analítica</td><td>Mantener el estado de la sesión de Analytics.</td><td>Hasta 2 años desde su creación; puede borrarse antes al retirar el consentimiento.</td></tr>
                </tbody>
            </table>
        </div>
        <p>Las cookies técnicas y las preferencias solicitadas por ti permiten prestar el servicio y recordar tus decisiones. Las cookies analíticas solo se activan si pulsas «Aceptar analíticas». Rechazarlas no impide navegar ni contactar con nosotros.</p>
        <h2>4. Google Analytics y datos tratados</h2>
        <p>Utilizamos Google Analytics 4, proporcionado por Google Ireland Limited, Gordon House, Barrow Street, Dublín 4, Irlanda, con el identificador G-TT318T6SZZ. Su finalidad es conocer las páginas visitadas y las interacciones, como los clics en servicios y el envío de solicitudes, para mejorar la web. La base jurídica es tu consentimiento.</p>
        <p>Google puede tratar identificadores de cookies, información del navegador y dispositivo e interacciones. Los eventos personalizados de DaloWeb no envían nombres, correos ni el contenido de los mensajes. La dirección de página que configuramos excluye los parámetros de consulta y el fragmento. La integración mantiene desactivadas las señales de Google y la personalización publicitaria.</p>
        <p>La etiqueta de Google se carga únicamente después de aceptar. No enviamos a Google los eventos personalizados anteriores a esa aceptación. La retirada del consentimiento detiene la medición futura y elimina las cookies de Analytics accesibles desde este sitio; no elimina automáticamente los datos ya tratados.</p>
        <p>El uso del servicio puede implicar tratamiento por Google y transferencias internacionales, incluidos Estados Unidos, conforme a las condiciones y garantías aplicables al servicio. Consulta <a href="https://policies.google.com/privacy?hl=es">la política de privacidad de Google</a>, <a href="https://policies.google.com/technologies/partner-sites?hl=es">cómo utiliza Google los datos de sitios asociados</a> y <a href="https://business.safety.google/adsprocessorterms/">sus condiciones de tratamiento de datos</a>.</p>
        <h2>5. Aceptar, rechazar o retirar el consentimiento</h2>
        <p>El banner ofrece «Aceptar analíticas» y «Rechazar analíticas». Navegar o cerrar una página no equivale a aceptar. Puedes modificar tu decisión en cualquier momento mediante «Cambiar cookies» en el pie de página o con el siguiente botón. Si retiras un consentimiento activo, la página se recargará para detener la etiqueta de Google.</p>
        <button type="button" class="cookie-choice" data-cookie-settings hidden>Cambiar cookies</button>
        <p>Conservamos tu elección durante 180 días en este navegador. Si borras el almacenamiento, usas otro navegador o actualizamos la versión del consentimiento, volveremos a solicitarla. Si tu navegador bloquea el almacenamiento, la decisión solo podrá mantenerse en la página actual.</p>
        <h2>6. Gestión desde el navegador y derechos</h2>
        <p>Puedes eliminar las cookies y el almacenamiento local desde los ajustes de privacidad de tu navegador, y bloquear su uso. Bloquear las cookies técnicas puede afectar a los formularios o al acceso al área privada. También puedes consultar el <a href="https://tools.google.com/dlpage/gaoptout?hl=es">complemento de inhabilitación de Google Analytics</a>.</p>
        <p>Para conocer tus derechos y cómo ejercerlos, consulta nuestra <a href="{{ route('privacidad') }}">política de privacidad</a>. Puedes presentar una reclamación ante la <a href="https://www.aepd.es/">Agencia Española de Protección de Datos</a>.</p>
        @if(in_array('XXXXXXX', config('legal'), true))<p class="small-print">Los datos del titular señalados con XXXXXXX están pendientes de completar.</p>@endif
    </div>
</section>
@endsection
