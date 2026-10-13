<section class="cookie-banner" id="cookieBanner" role="region" aria-labelledby="cookieTitle" hidden>
    <div class="cookie-banner__content">
        <h2 id="cookieTitle" tabindex="-1">Tú decides sobre las cookies</h2>
        <p>Usamos cookies técnicas necesarias para que la web funcione. Con tu permiso, también usamos Google Analytics para medir las visitas y las interacciones. Puedes aceptar o rechazar las cookies analíticas y cambiar tu decisión en cualquier momento desde el pie de página.</p>
        <a href="{{ route('cookies') }}">Leer la política de cookies</a>
    </div>
    <div class="cookie-banner__actions">
        <button type="button" class="cookie-choice" data-cookie-choice="accepted">Aceptar cookies</button>
        <button type="button" class="cookie-choice" data-cookie-choice="rejected">Rechazar cookies</button>
    </div>
</section>
<noscript><p class="container small-print">Google Analytics está desactivado porque JavaScript no está disponible. Consulta la <a href="{{ route('cookies') }}">política de cookies</a>.</p></noscript>
