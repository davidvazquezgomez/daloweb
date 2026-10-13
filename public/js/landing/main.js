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
  var floatingCta = document.getElementById("floatingCta");
  var contactSection = document.getElementById("contacto");
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
    updateFloatingCta();
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
      var backgrounds = document.querySelectorAll('main, .footer, .floating-cta, .header__inner > :not(.nav):not(.hamburger)');
      isolatedElements = Array.prototype.map.call(backgrounds, function (element) {
        var item = { element: element, inert: element.inert };
        element.inert = true;
        return item;
      });
      nav.querySelector('a').focus();
      updateFloatingCta();
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

  function updateFloatingCta() {
    if (!floatingCta || !contactSection) return;
    var viewport = window.visualViewport;
    var viewportTop = viewport ? viewport.offsetTop : 0;
    var viewportBottom = viewportTop + (viewport ? viewport.height : window.innerHeight);
    var inputFocused = document.activeElement && document.activeElement.matches('input, select, textarea');
    var menuOpen = nav && nav.classList.contains('is-open');
    var formRect = contactSection.getBoundingClientRect();
    var contactVisible = formRect.top < viewportBottom && formRect.bottom > viewportTop;
    var ctaVisible = Array.prototype.some.call(document.querySelectorAll('main a[href="#contacto"], .header__cta'), function (cta) {
      var rect = cta.getBoundingClientRect();
      return rect.width > 0 && rect.height > 0 && rect.top < viewportBottom && rect.bottom > viewportTop;
    });
    var visible = window.scrollY > 600 && !inputFocused && !menuOpen && !contactVisible && !ctaVisible;
    floatingCta.classList.toggle('is-visible', visible);
    floatingCta.inert = !visible;
    floatingCta.setAttribute('aria-hidden', String(!visible));
  }
  window.addEventListener('resize', updateFloatingCta);
  document.addEventListener('focusin', updateFloatingCta);
  document.addEventListener('focusout', function () { requestAnimationFrame(updateFloatingCta); });
  window.addEventListener('languageChanged', updateFloatingCta);
  if (window.visualViewport) {
    window.visualViewport.addEventListener('resize', updateFloatingCta);
    window.visualViewport.addEventListener('scroll', updateFloatingCta);
  }
  updateFloatingCta();

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
      updateFloatingCta();
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
    var revealSelectors = [".card", ".process__step", ".project-card", ".price-card", ".why-card", ".testimonial", ".contact-form", ".contact__info", ".hero__content", ".hero__visual", ".stat"];
    
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
      }
    });
  }

  /* ==========================================================
     7. Animated counters
     ========================================================== */
  if (window.IntersectionObserver && !reducedMotion.matches) {
    var counters = document.querySelectorAll(".stat__number");
    var counterObserver = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          var el = entry.target;
          var target = parseFloat(el.getAttribute("data-target"));
          var isDecimal = el.hasAttribute("data-decimal");
          var duration = 2000;
          var startTime = null;
          
          function animate(currentTime) {
            if (!startTime) startTime = currentTime;
            var progress = Math.min((currentTime - startTime) / duration, 1);
            var eased = 1 - Math.pow(1 - progress, 3);
            var current = target * eased;
            if (isDecimal) { 
              el.textContent = current.toFixed(1); 
            } else { 
              el.textContent = "+" + Math.floor(current); 
            }
            if (progress < 1 && !reducedMotion.matches) {
              requestAnimationFrame(animate); 
            } else { 
              el.textContent = isDecimal ? target.toFixed(1) + " \u2605" : "+" + target; 
            }
          }
          requestAnimationFrame(animate);
          counterObserver.unobserve(el);
        }
      });
    }, { threshold: 0.5 });

    for (var o = 0; o < counters.length; o++) {
      counterObserver.observe(counters[o]);
    }
  }

  window.addEventListener('pageshow', function () {
    if (contactForm) {
      contactForm.dataset.submitting = 'false';
      contactForm.querySelector('button[type="submit"]').disabled = false;
    }
    updateFloatingCta();
  });
})();
