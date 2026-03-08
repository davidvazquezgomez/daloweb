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

  /* ==========================================================
     1. Mobile menu toggle
     ========================================================== */
  function closeMenu() {
    if (!nav) return;
    nav.classList.remove("is-open");
    if (hamburger) {
      hamburger.classList.remove("is-active");
      hamburger.setAttribute("aria-expanded", "false");
    }
    document.body.style.overflow = "";
  }

  if (hamburger && nav) {
    hamburger.addEventListener("click", function () {
      var isOpen = nav.classList.toggle("is-open");
      hamburger.classList.toggle("is-active");
      hamburger.setAttribute("aria-expanded", isOpen);
      document.body.style.overflow = isOpen ? "hidden" : "";
    });

    nav.addEventListener("click", function (e) {
      if (e.target === nav) closeMenu();
    });

    for (var i = 0; i < navLinks.length; i++) {
      navLinks[i].addEventListener("click", closeMenu);
    }

    var mobileCta = nav.querySelector(".nav__cta-mobile");
    if (mobileCta) {
      mobileCta.addEventListener("click", closeMenu);
    }
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
      if (floatingCta && contactSection) {
        var contactTop = contactSection.offsetTop;
        if (scrollY > 600 && scrollY < contactTop - window.innerHeight) {
          floatingCta.classList.add("is-visible");
        } else {
          floatingCta.classList.remove("is-visible");
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
  if (window.IntersectionObserver) {
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
    contactForm.addEventListener("submit", function (e) {
      e.preventDefault();
      var nombre = contactForm.elements.nombre;
      var email = contactForm.elements.email;
      var tipo = contactForm.elements.tipo;
      var mensaje = contactForm.elements.mensaje;

      var currentLangData = window.translations[window.currentLang || 'es'];

      if (!nombre.value.trim() || !email.value.trim() || !tipo.value || !mensaje.value.trim()) {
        showFeedback(currentLangData.form_error || "Error", true);
        return;
      }

      showFeedback(currentLangData.form_success || "Success", false);
      contactForm.reset();
    });
  }

  function showFeedback(text, isError) {
    if (!formFeedback) return;
    formFeedback.textContent = text;
    formFeedback.style.color = isError ? "#e74c3c" : "var(--color-accent)";
    setTimeout(function () { formFeedback.textContent = ""; }, 5000);
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
        target.scrollIntoView({ behavior: "smooth" });
      }
    });
  }

  /* ==========================================================
     7. Typewriter effect
     ========================================================== */
  var typewriterEl = document.getElementById("typewriter");
  var wordIndex = 0;
  var charIndex = 0;
  var isDeleting = false;
  var typeWriteInterval = null;

  function typeWrite() {
    if (!typewriterEl) return;
    
    var lang = window.currentLang || 'es';
    if (!window.translations || !window.translations[lang]) return;
    
    var words = window.translations[lang].typewriter_words;
    if (!words) return;

    var current = words[wordIndex];
    if (isDeleting) {
      charIndex--;
      typewriterEl.textContent = current.substring(0, charIndex);
    } else {
      charIndex++;
      typewriterEl.textContent = current.substring(0, charIndex);
    }
    
    var delay = isDeleting ? 40 : 80;
    if (!isDeleting && charIndex === current.length) {
      delay = 2000;
      isDeleting = true;
    } else if (isDeleting && charIndex === 0) {
      isDeleting = false;
      wordIndex = (wordIndex + 1) % words.length;
      delay = 400;
    }
    typeWriteInterval = setTimeout(typeWrite, delay);
  }

  // Handle language change for typewriter
  window.addEventListener('languageChanged', function() {
    if (typeWriteInterval) {
      clearTimeout(typeWriteInterval);
    }
    wordIndex = 0;
    charIndex = 0;
    isDeleting = false;
    typeWrite();
  });

  /* ==========================================================
     8. Animated counters
     ========================================================== */
  if (window.IntersectionObserver) {
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
            if (progress < 1) { 
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

  /* ==========================================================
     9. Theme toggle
     ========================================================== */
  var themeToggle = document.getElementById("theme-toggle");
  if (themeToggle) {
    var sunIcon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>';
    var moonIcon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>';
    
    var currentTheme = localStorage.getItem('theme');
    if (currentTheme === 'light') { 
      document.body.classList.add('light-theme'); 
      themeToggle.innerHTML = moonIcon; 
    } else { 
      themeToggle.innerHTML = sunIcon; 
    }
    
    themeToggle.addEventListener('click', function() { 
      document.body.classList.toggle('light-theme'); 
      var isLight = document.body.classList.contains('light-theme'); 
      themeToggle.innerHTML = isLight ? moonIcon : sunIcon; 
      localStorage.setItem('theme', isLight ? 'light' : 'dark'); 
    });
  }

  // Initial typewriter start
  typeWrite();
})();
