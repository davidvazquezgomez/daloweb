/* Eventos de primera parte. Integración disponible mediante daloweb:analytics.
   No carga proveedores, escribe cookies ni transmite campos del formulario. */
(function () {
  'use strict';
  var services = ['web', 'reservas', 'app', 'medida'];
  var packs = ['web-profesional', 'sistema-reservas', 'app-movil'];
  var canonical = document.querySelector('link[rel="canonical"]');
  var page = canonical ? new URL(canonical.href).pathname : '/';
  var origins = ['directo', 'organico', 'publicidad', 'redes', 'email', 'referido', 'otro'];
  var pageOrigin = origins.indexOf(document.body.dataset.origen) !== -1 ? document.body.dataset.origen : 'directo';
  window.dalowebEvents = [];

  // Propagar únicamente la categoría, sin campañas, identificadores ni consultas originales.
  if (pageOrigin !== 'directo') {
    Array.prototype.forEach.call(document.querySelectorAll('a[href]'), function (link) {
      if (link.getAttribute('href').charAt(0) === '#') return;
      var url = new URL(link.href, window.location.href);
      if (url.origin !== window.location.origin || /^\/(admin|login|logout|demo)(\/|$)/.test(url.pathname)) return;
      url.searchParams.set('origen', pageOrigin);
      link.href = url.href;
    });
  }

  function emit(event, fields) {
    var payload = { event: event, page: page, origin: origins.indexOf(fields && fields.origin) !== -1 ? fields.origin : pageOrigin };
    fields = fields || {};
    if (services.indexOf(fields.service) !== -1) payload.service = fields.service;
    if (packs.indexOf(fields.pack) !== -1) payload.pack = fields.pack;
    if (['hero', 'header', 'pricing', 'contact', 'footer', 'content'].indexOf(fields.location) !== -1) payload.location = fields.location;
    if (['validation', 'delivery'].indexOf(fields.reason) !== -1) payload.reason = fields.reason;
    if (fields.channel === 'email') payload.channel = 'email';
    if (typeof fields.id === 'string' && /^[0-9a-f-]{36}$/i.test(fields.id)) payload.id = fields.id;
    window.dalowebEvents.push(payload);
    if (window.dalowebEvents.length > 100) window.dalowebEvents.shift();
    window.dispatchEvent(new CustomEvent('daloweb:analytics', { detail: payload }));
  }

  function locationFor(element) {
    if (element.closest('.header')) return 'header';
    if (element.closest('.hero, .service-hero')) return 'hero';
    if (element.closest('.pricing')) return 'pricing';
    if (element.closest('#contacto')) return 'contact';
    if (element.closest('.footer')) return 'footer';
    return 'content';
  }

  document.addEventListener('click', function (event) {
    var element = event.target.closest('a, button');
    if (!element) return;
    var fields = { location: locationFor(element), service: element.dataset.tipo, pack: element.dataset.pack };
    if (element.matches('a.btn, button[type="submit"]')) emit('cta_click', fields);
    if (element.dataset.pack) emit('select_pack', fields);
    if (element.matches('a[href^="mailto:"]')) emit('contact_click', { location: fields.location, channel: 'email' });
  });

  var form = document.getElementById('contactForm');
  if (form) {
    var started = false;
    function formStarted() {
      if (started) return;
      started = true;
      emit('form_start', { service: form.elements.tipo.value, pack: form.elements.pack.value });
    }
    form.addEventListener('input', formStarted);
    form.addEventListener('change', formStarted);
  }

  var status = document.getElementById('contactEvent');
  if (status) {
    var confirmation = JSON.parse(status.textContent);
    if (confirmation.event === 'lead_submit_success' || confirmation.event === 'lead_submit_error') {
      emit(confirmation.event, confirmation);
    }
  }
})();
