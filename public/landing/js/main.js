/* ===================================================
   DaloWeb — Landing Page Scripts
   Pure JavaScript · No dependencies
   =================================================== */

(function () {
  "use strict";

  /* ---------- DOM refs ---------- */
  const header = document.getElementById("header");
  const hamburger = document.getElementById("hamburger");
  const nav = document.getElementById("nav");
  const navLinks = document.querySelectorAll(".nav__link");
  const contactForm = document.getElementById("contactForm");
  const formFeedback = document.getElementById("formFeedback");
  const floatingCta = document.getElementById("floatingCta");
  const contactSection = document.getElementById("contacto");

  /* ==========================================================
     1. Mobile menu toggle
     ========================================================== */
  function closeMenu() {
    nav.classList.remove("is-open");
    hamburger.classList.remove("is-active");
    hamburger.setAttribute("aria-expanded", "false");
    document.body.style.overflow = "";
  }

  hamburger.addEventListener("click", function () {
    const isOpen = nav.classList.toggle("is-open");
    hamburger.classList.toggle("is-active");
    hamburger.setAttribute("aria-expanded", isOpen);
    document.body.style.overflow = isOpen ? "hidden" : "";
  });

  // Close mobile menu when clicking the overlay (outside the drawer)
  nav.addEventListener("click", function (e) {
    if (e.target === nav) closeMenu();
  });

  // Close mobile menu on link click
  navLinks.forEach(function (link) {
    link.addEventListener("click", function () {
      closeMenu();
    });
  });

  // Close mobile menu on mobile CTA click
  var mobileCta = nav.querySelector(".nav__cta-mobile");
  if (mobileCta) {
    mobileCta.addEventListener("click", function () {
      closeMenu();
    });
  }

  /* ==========================================================
     2. Shrink header on scroll
     ========================================================== */
  var lastScroll = 0;
  window.addEventListener(
    "scroll",
    function () {
      var scrollY = window.pageYOffset;
      if (scrollY > 50) {
        header.style.boxShadow = "0 2px 16px rgba(0,0,0,.08)";
      } else {
        header.style.boxShadow = "";
      }
      // Floating CTA bar
      if (floatingCta && contactSection) {
        var contactTop = contactSection.offsetTop;
        if (scrollY > 600 && scrollY < contactTop - window.innerHeight) {
          floatingCta.classList.add("is-visible");
        } else {
          floatingCta.classList.remove("is-visible");
        }
      }
      lastScroll = scrollY;
    },
    { passive: true },
  );

  /* ==========================================================
     3. Active nav link on scroll (Intersection Observer)
     ========================================================== */
  var sections = document.querySelectorAll("section[id]");

  var observerOptions = {
    root: null,
    rootMargin: "-40% 0px -60% 0px",
    threshold: 0,
  };

  var sectionObserver = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        var id = entry.target.getAttribute("id");
        navLinks.forEach(function (link) {
          link.classList.toggle(
            "active",
            link.getAttribute("href") === "#" + id,
          );
        });
      }
    });
  }, observerOptions);

  sections.forEach(function (section) {
    sectionObserver.observe(section);
  });

  /* ==========================================================
     4. Scroll reveal
     ========================================================== */
  // Add .reveal to all major section children
  var revealSelectors = [
    ".card",
    ".process__step",
    ".project-card",
    ".price-card",
    ".why-card",
    ".testimonial",
    ".contact-form",
    ".contact__info",
    ".hero__content",
    ".hero__visual",
    ".stat",
  ];

  revealSelectors.forEach(function (selector) {
    var elements = document.querySelectorAll(selector);
    var count = elements.length;
    var baseDelay = count > 8 ? 0.04 : 0.1;
    elements.forEach(function (el, index) {
      el.classList.add("reveal");
      el.style.transitionDelay = index * baseDelay + "s";
    });
  });

  var revealElements = document.querySelectorAll(".reveal");

  var revealObserver = new IntersectionObserver(
    function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add("is-visible");
          revealObserver.unobserve(entry.target);
        }
      });
    },
    {
      root: null,
      rootMargin: "0px 0px -80px 0px",
      threshold: 0.1,
    },
  );

  revealElements.forEach(function (el) {
    revealObserver.observe(el);
  });

  /* ==========================================================
     5. Contact form — basic client-side validation + UX feedback
     ========================================================== */
  contactForm.addEventListener("submit", function (e) {
    e.preventDefault();

    var nombre = contactForm.elements.nombre;
    var email = contactForm.elements.email;
    var tipo = contactForm.elements.tipo;
    var mensaje = contactForm.elements.mensaje;

    // Simple validation
    if (!nombre.value.trim()) {
      showFeedback("Por favor, introduce tu nombre.", true);
      nombre.focus();
      return;
    }

    var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email.value.trim())) {
      showFeedback("Introduce un email válido.", true);
      email.focus();
      return;
    }

    if (!tipo.value) {
      showFeedback("Selecciona un tipo de proyecto.", true);
      tipo.focus();
      return;
    }

    if (!mensaje.value.trim()) {
      showFeedback("Escribe un mensaje breve sobre tu proyecto.", true);
      mensaje.focus();
      return;
    }

    // Simulated success (replace with real endpoint)
    showFeedback("¡Mensaje enviado! Te contactaremos pronto.", false);
    contactForm.reset();
  });

  function showFeedback(text, isError) {
    formFeedback.textContent = text;
    formFeedback.style.color = isError ? "#e74c3c" : "var(--color-accent)";

    // Auto-clear after 5s
    setTimeout(function () {
      formFeedback.textContent = "";
    }, 5000);
  }

  /* ==========================================================
     7. Smooth scroll for CTA buttons (fallback for older browsers)
     ========================================================== */
  document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
    anchor.addEventListener("click", function (e) {
      var targetId = this.getAttribute("href");
      if (targetId === "#") return;
      var target = document.querySelector(targetId);
      if (target) {
        e.preventDefault();
        target.scrollIntoView({ behavior: "smooth" });
      }
    });
  });

  /* ==========================================================
     8. Typewriter effect
     ========================================================== */
  var typewriterEl = document.getElementById("typewriter");
  if (typewriterEl) {
    var words = [
      "webs profesionales",
      "apps m\u00F3viles",
      "sistemas de reservas",
      "software a medida",
    ];
    var wordIndex = 0;
    var charIndex = 0;
    var isDeleting = false;

    function typeWrite() {
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
      setTimeout(typeWrite, delay);
    }

    typeWrite();
  }

  /* ==========================================================
     9. Animated counters
     ========================================================== */
  var counters = document.querySelectorAll(".stat__number");
  if (counters.length) {
    var counterObserver = new IntersectionObserver(
      function (entries) {
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
                el.textContent = isDecimal
                  ? target.toFixed(1) + " \u2605"
                  : "+" + target;
              }
            }

            requestAnimationFrame(animate);
            counterObserver.unobserve(el);
          }
        });
      },
      { threshold: 0.5 },
    );

    counters.forEach(function (counter) {
      counterObserver.observe(counter);
    });
  }
})();
