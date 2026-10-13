/* ===================================================
   DaloWeb — Landing Page Scripts
   Pure JavaScript · No dependencies
   =================================================== */

(function () {
  "use strict";

  /* ---------- DOM refs ---------- */
  var header = document.getElementById("header");
  var hamburger = document.getElementById("hamburger");
  var nav = document.getElementById("nav");
  var navLinks = document.querySelectorAll(".nav__link");
  var contactForm = document.getElementById("contactForm");
  var formFeedback = document.getElementById("formFeedback");
  var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
  var desktopMenu = window.matchMedia('(min-width: 1100px)');
  var isolatedElements = [];
  var previousOverflow = '';

  /* ==========================================================
     1. Mobile menu toggle
     ========================================================== */
  function closeMenu(restoreFocus) {
    if (!nav) return;
    var wasOpen = nav.classList.contains('is-open');
    nav.classList.remove("is-open");
    if (hamburger) {
      hamburger.classList.remove("is-active");
      hamburger.setAttribute("aria-expanded", "false");
    }
    isolatedElements.forEach(function (item) { item.element.inert = item.inert; });
    isolatedElements = [];
    if (wasOpen) document.body.style.overflow = previousOverflow;
    if (wasOpen && restoreFocus !== false && hamburger) hamburger.focus();
  }

  if (hamburger && nav) {
    hamburger.addEventListener("click", function () {
      if (nav.classList.contains('is-open')) {
        closeMenu();
        return;
      }
      previousOverflow = document.body.style.overflow;
      nav.classList.add('is-open');
      hamburger.classList.add('is-active');
      hamburger.setAttribute('aria-expanded', 'true');
      document.body.style.overflow = 'hidden';
      var backgrounds = document.querySelectorAll('main, .footer, .header__inner > :not(.nav):not(.hamburger)');
      isolatedElements = Array.prototype.map.call(backgrounds, function (element) {
        var item = { element: element, inert: element.inert };
        element.inert = true;
        return item;
      });
      nav.querySelector('a').focus();
    });

    nav.addEventListener("click", function (e) {
      if (e.target === nav) closeMenu();
    });

    for (var i = 0; i < navLinks.length; i++) {
      navLinks[i].addEventListener("click", function () { closeMenu(); });
    }

    var mobileCta = nav.querySelector(".nav__cta-mobile");
    if (mobileCta) {
      mobileCta.addEventListener("click", function () { closeMenu(); });
    }
    document.addEventListener('keydown', function (e) {
      if (!nav.classList.contains('is-open')) return;
      if (e.key === 'Escape') {
        e.preventDefault();
        closeMenu();
      } else if (e.key === 'Tab') {
        var controls = [hamburger].concat(Array.prototype.slice.call(nav.querySelectorAll('a[href], button')));
        var first = controls[0];
        var last = controls[controls.length - 1];
        if (e.shiftKey && document.activeElement === first) {
          e.preventDefault(); last.focus();
        } else if (!e.shiftKey && document.activeElement === last) {
          e.preventDefault(); first.focus();
        }
      }
    });
    desktopMenu.addEventListener('change', function (e) {
      if (e.matches) closeMenu(false);
    });
  }

  /* ==========================================================
     2. Shrink header on scroll
     ========================================================== */
  window.addEventListener(
    "scroll",
    function () {
      var scrollY = window.pageYOffset || document.documentElement.scrollTop;
      if (header) {
        if (scrollY > 50) {
          header.style.boxShadow = "0 2px 16px rgba(0,0,0,.08)";
        } else {
          header.style.boxShadow = "";
        }
      }
    },
    { passive: true }
  );

  /* ==========================================================
     3. Active nav link on scroll
     ========================================================== */
  if (window.IntersectionObserver) {
    var sectionObserver = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          var id = entry.target.getAttribute("id");
          for (var j = 0; j < navLinks.length; j++) {
            var link = navLinks[j];
            link.classList.toggle("active", link.getAttribute("href") === "#" + id);
          }
        }
      });
    }, { rootMargin: "-40% 0px -60% 0px" });

    var sections = document.querySelectorAll("section[id]");
    for (var k = 0; k < sections.length; k++) {
      sectionObserver.observe(sections[k]);
    }
  }

  /* ==========================================================
     4. Scroll reveal
     ========================================================== */
  if (window.IntersectionObserver && !reducedMotion.matches) {
    var revealSelectors = [".card", ".process__step", ".project-card", ".price-card", ".why-card", ".contact-form", ".hero__content", ".hero__visual"];
    
    var revealObserver = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add("is-visible");
          revealObserver.unobserve(entry.target);
        }
      });
    }, { rootMargin: "0px 0px -80px 0px", threshold: 0.1 });

    revealSelectors.forEach(function (selector) {
      var elements = document.querySelectorAll(selector);
      for (var m = 0; m < elements.length; m++) {
        var el = elements[m];
        el.classList.add("reveal");
        el.style.transitionDelay = m * 0.05 + "s";
        revealObserver.observe(el);
      }
    });
  }

  /* ==========================================================
     5. Contact form
     ========================================================== */
  if (contactForm) {
    var packInput = contactForm.elements.pack;
    var selectedPack = document.getElementById('selectedPack');
    var selectedPackName = document.getElementById('selectedPackName');
    var packButtons = document.querySelectorAll('[data-pack]');
    function updatePackSummary() {
      var button = Array.prototype.find.call(packButtons, function (item) { return item.getAttribute('data-pack') === packInput.value; });
      selectedPack.hidden = !button;
      selectedPackName.textContent = button ? button.closest('.price-card').querySelector('.price-card__title').textContent : '';
    }
    Array.prototype.forEach.call(packButtons, function (button) {
      button.addEventListener('click', function () {
        packInput.value = button.getAttribute('data-pack');
        contactForm.elements.tipo.value = button.getAttribute('data-tipo');
        updatePackSummary();
      });
    });
    contactForm.elements.tipo.addEventListener('change', function () {
      packInput.value = '';
      updatePackSummary();
    });
    window.addEventListener('languageChanged', updatePackSummary);
    updatePackSummary();
    contactForm.addEventListener("submit", function (e) {
      e.preventDefault();
      if (contactForm.dataset.submitting === 'true') return;
      var nombre = contactForm.elements.nombre;
      var email = contactForm.elements.email;
      var tipo = contactForm.elements.tipo;
      var mensaje = contactForm.elements.mensaje;

      var currentLangData = window.translations && window.translations[window.currentLang || 'es'] || {};

      if (!nombre.value.trim() || !email.value.trim() || !tipo.value || !mensaje.value.trim()) {
        showFeedback(currentLangData.form_error || "Error", true);
        [nombre, email, tipo, mensaje].filter(function (field) { return !field.value.trim(); })[0].focus();
        return;
      }

      // El éxito lo confirma Laravel después de enviar el correo por SMTP.
      contactForm.dataset.submitting = 'true';
      contactForm.querySelector('button[type="submit"]').disabled = true;
      contactForm.submit();
    });
  }

  function showFeedback(text, isError) {
    if (!formFeedback) return;
    formFeedback.textContent = text;
    formFeedback.style.color = isError ? "#e74c3c" : "var(--color-accent)";
  }

  /* ==========================================================
     6. Smooth scroll
     ========================================================== */
  var anchors = document.querySelectorAll('a[href^="#"]');
  for (var n = 0; n < anchors.length; n++) {
    anchors[n].addEventListener("click", function (e) {
      var targetId = this.getAttribute("href");
      if (targetId === "#") return;
      var target = document.querySelector(targetId);
      if (target) {
        e.preventDefault();
        target.scrollIntoView({ behavior: reducedMotion.matches ? "auto" : "smooth" });
        if (this.classList.contains('skip-link')) target.focus({ preventScroll: true });
      }
    });
  }

  window.addEventListener('pageshow', function () {
    if (contactForm) {
      contactForm.dataset.submitting = 'false';
      contactForm.querySelector('button[type="submit"]').disabled = false;
    }
  });
})();
