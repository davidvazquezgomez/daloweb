<div class="pack-overview" aria-label="Resumen de web y reservas">
    <a href="#pack-web"><span data-i18n="plan_1_title">Web Profesional</span><strong><small data-i18n="price_from">Desde</small> {{ config('comercial.packs.web-profesional.precio') }} €</strong><span data-i18n="pack_web_for">Para presentar tu negocio</span></a>
    <a href="#pack-reservas"><span data-i18n="plan_2_title">Sistema de Reservas</span><strong><small data-i18n="price_from">Desde</small> {{ config('comercial.packs.sistema-reservas.precio') }} €</strong><span data-i18n="pack_booking_for">Para organizar tus citas</span></a>
</div>
<p class="small-print" data-i18n="pack_price_note">Precios de partida. Confirmamos alcance, impuestos, costes externos y renovaciones en el presupuesto antes de contratar.</p>
<div class="pricing__grid pricing__grid--web">
    <article class="price-card" id="pack-web">
        <div class="price-card__header">
            <h3 class="price-card__title" data-i18n="plan_1_title">Web Profesional</h3>
            <p data-i18n="pack_web_for">Para presentar tu negocio</p>
            <div class="price-card__price"><span data-i18n="price_from">Desde</span><strong>{{ config('comercial.packs.web-profesional.precio') }} €</strong></div>
        </div>
        <ul class="price-card__features">
            <li data-i18n="plan_1_f2">Hasta 3 páginas</li>
            <li data-i18n="plan_1_f1">Diseño personalizado y responsive</li>
            <li data-i18n="plan_1_f3">Optimización SEO básica</li>
            <li data-i18n="plan_1_f5">Certificado SSL incluido</li>
            <li data-i18n="plan_1_f6">Una semana desde materiales y alcance aprobados</li>
        </ul>
        <a href="{{ ($esPortada ?? false) ? '#contacto' : route('inicio', ['pack' => 'web-profesional']) . '#contacto' }}" class="btn btn--primary btn--full" data-pack="web-profesional" data-tipo="web" data-i18n="pack_web_cta">Quiero una web profesional</a>
        <a class="text-link" href="{{ route('servicio.diseno-web') }}" data-i18n="pack_scope_link">Consultar el alcance del servicio →</a>
    </article>
    <article class="price-card" id="pack-reservas">
        <div class="price-card__header">
            <h3 class="price-card__title" data-i18n="plan_2_title">Sistema de Reservas</h3>
            <p data-i18n="pack_booking_for">Para organizar tus citas</p>
            <div class="price-card__price"><span data-i18n="price_from">Desde</span><strong>{{ config('comercial.packs.sistema-reservas.precio') }} €</strong></div>
        </div>
        <ul class="price-card__features">
            <li data-i18n="plan_2_f1">Calendario de reservas online</li>
            <li data-i18n="plan_2_f3">Panel de gestión de citas</li>
            <li data-i18n="pack_booking_emails">Confirmación por email y recordatorios</li>
            <li data-i18n="plan_2_f4">Integrable en tu web actual</li>
            <li data-i18n="plan_2_f6">Soporte 2 meses incluido</li>
        </ul>
        <a href="{{ ($esPortada ?? false) ? '#contacto' : route('inicio', ['pack' => 'sistema-reservas']) . '#contacto' }}" class="btn btn--primary btn--full" data-pack="sistema-reservas" data-tipo="reservas" data-i18n="pack_booking_cta">Quiero gestionar reservas</a>
        <a class="text-link" href="{{ route('servicio.sistemas-de-reservas') }}" data-i18n="pack_scope_link">Consultar el alcance del servicio →</a>
    </article>
</div>
<div class="custom-offers">
    <article class="custom-project price-card">
        <h3 class="price-card__title" data-i18n="plan_3_title">App Móvil</h3>
        <p><span data-i18n="price_from">Desde</span> <strong>{{ config('comercial.packs.app-movil.precio') }} €</strong></p>
        <p data-i18n="pack_app_scope">iOS y Android, diseño UI/UX y panel según el alcance acordado. Definimos funciones, publicación y costes de terceros antes de empezar.</p>
        <a href="{{ ($esPortada ?? false) ? '#contacto' : route('inicio', ['pack' => 'app-movil']) . '#contacto' }}" class="btn btn--outline" data-pack="app-movil" data-tipo="app" data-i18n="pack_app_cta">Cuéntanos tu idea de app</a>
        <a class="text-link" href="{{ route('servicio.desarrollo-apps') }}" data-i18n="pack_scope_link">Consultar el alcance del servicio →</a>
    </article>
    <article class="custom-project">
        <h3 data-i18n="service_4_title">Aplicaciones a medida</h3>
        <p><strong data-i18n="pack_custom_price">Presupuesto por alcance</strong></p>
        <p data-i18n="pack_custom_scope">Paneles, gestión e integraciones para tu operativa. Definimos una primera fase con entregables y condiciones claras.</p>
        <a class="btn btn--outline" href="{{ route('servicio.software-a-medida') }}" data-i18n="pack_custom_cta">Explorar software a medida</a>
    </article>
</div>
