/* Consentimiento básico: Google no se carga hasta aceptar las cookies analíticas. */
(function () {
  'use strict';
  var measurementId = 'G-TT318T6SZZ';
  var storageKey = 'daloweb_cookie_consent';
  var version = 1;
  var lifetime = 180 * 24 * 60 * 60 * 1000;
  var banner = document.getElementById('cookieBanner');
  var previousFocus;
  var loaded = false;
  var consent = null;
  var expiryTimer;
  window['ga-disable-' + measurementId] = true;

  function readConsent() {
    try {
      var saved = JSON.parse(localStorage.getItem(storageKey));
      if (saved && saved.version === version && Number.isFinite(saved.expires)
          && saved.expires > Date.now() && saved.expires <= Date.now() + lifetime
          && (saved.choice === 'accepted' || saved.choice === 'rejected')) return saved;
    } catch (error) { /* El almacenamiento puede no estar disponible. */ }
    return null;
  }

  function clearAnalyticsCookies() {
    var domains = window.location.hostname.split('.');
    document.cookie.split(';').forEach(function (entry) {
      var name = entry.trim().split('=')[0];
      if (!/^_ga(?:_|$)/.test(name)) return;
      var expired = name + '=; Max-Age=0; path=/; SameSite=Lax';
      document.cookie = expired;
      for (var i = 0; i < domains.length - 1; i++) {
        var domain = domains.slice(i).join('.');
        document.cookie = expired + '; domain=' + domain;
        document.cookie = expired + '; domain=.' + domain;
      }
    });
  }

  function startAnalytics() {
    if (loaded) return;
    loaded = true;
    window['ga-disable-' + measurementId] = false;
    window.dataLayer = window.dataLayer || [];
    window.gtag = function () { window.dataLayer.push(arguments); };
    window.gtag('consent', 'default', {
      analytics_storage: 'denied', ad_storage: 'denied',
      ad_user_data: 'denied', ad_personalization: 'denied'
    });
    window.gtag('consent', 'update', { analytics_storage: 'granted' });
    window.gtag('js', new Date());
    var canonical = document.querySelector('link[rel="canonical"]');
    var location = new URL(canonical ? canonical.href : window.location.href);
    location.search = '';
    location.hash = '';
    var referrer = '';
    try {
      if (document.referrer) {
        var url = new URL(document.referrer);
        referrer = url.origin + url.pathname;
      }
    } catch (error) { /* Ignorar referencias inválidas. */ }
    window.gtag('config', measurementId, {
      page_location: location.href, page_referrer: referrer,
      allow_google_signals: false, allow_ad_personalization_signals: false,
      cookie_expires: 63072000, cookie_update: false
    });
    var script = document.createElement('script');
    script.async = true;
    script.src = 'https://www.googletagmanager.com/gtag/js?id=' + measurementId;
    document.head.appendChild(script);
  }

  function applyConsent() {
    window.clearTimeout(expiryTimer);
    if (consent) {
      // setTimeout admite como máximo unos 24 días; revisar también en páginas abiertas mucho tiempo.
      expiryTimer = window.setTimeout(function () {
        if (consent && consent.expires <= Date.now()) consent = null;
        applyConsent();
      }, Math.min(Math.max(consent.expires - Date.now(), 1), 2147483647));
    }
    if (consent && consent.choice === 'accepted') {
      startAnalytics();
    } else {
      window['ga-disable-' + measurementId] = true;
      clearAnalyticsCookies();
      // Descargar la etiqueta evita también los pings sin cookies tras retirar el permiso.
      if (loaded) window.location.reload();
    }
    if (banner) banner.hidden = !!consent;
  }

  document.querySelectorAll('[data-cookie-settings]').forEach(function (button) {
    button.hidden = false;
    button.addEventListener('click', function () {
      previousFocus = button;
      banner.hidden = false;
      document.getElementById('cookieTitle').focus();
    });
  });
  document.querySelectorAll('[data-cookie-choice]').forEach(function (button) {
    button.addEventListener('click', function () {
      consent = { version: version, choice: button.dataset.cookieChoice, expires: Date.now() + lifetime };
      try { localStorage.setItem(storageKey, JSON.stringify(consent)); } catch (error) { /* Solo esta página. */ }
      applyConsent();
      if (previousFocus) previousFocus.focus();
    });
  });
  window.addEventListener('storage', function (event) {
    if (event.key === storageKey || event.key === null) {
      consent = readConsent();
      applyConsent();
    }
  });
  window.addEventListener('pageshow', function (event) {
    if (event.persisted) {
      consent = readConsent();
      applyConsent();
    }
  });
  window.addEventListener('daloweb:analytics', function (event) {
    if (!consent || consent.choice !== 'accepted' || !loaded) return;
    if (consent.expires <= Date.now()) {
      consent = null;
      applyConsent();
      return;
    }
    var payload = event.detail;
    var fields = {};
    ['page', 'origin', 'service', 'pack', 'location', 'reason', 'channel'].forEach(function (key) {
      if (typeof payload[key] === 'string') fields[key] = payload[key];
    });
    window.gtag('event', payload.event, fields);
  });
  consent = readConsent();
  applyConsent();
})();
