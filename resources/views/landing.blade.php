<!DOCTYPE html>
<html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description"
            content="DaloWeb — Agencia digital. Creamos webs profesionales, apps móviles, sistemas de reservas y software a medida para hacer crecer tu negocio.">
        <title>DaloWeb — Webs, Apps y Software a Medida</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('img/favicon/favicon.svg') }}">
        <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
        <script src="{{ asset('js/landing/languages.js') }}" defer></script>
        <script src="{{ asset('js/landing/main.js') }}" defer></script>
    </head>

    <body>

        <!-- ========== HEADER ========== -->
        <header class="header" id="header">
            <div class="container header__inner">
                <a href="#inicio" class="logo" aria-label="DaloWeb inicio">Dalo<span>Web</span></a>

                <nav class="nav" id="nav" aria-label="Navegación principal">
                    <div class="nav__drawer">
                        <ul class="nav__list">
                            <li><a href="#inicio" class="nav__link" data-i18n="nav_home">Inicio</a></li>
                            <li><a href="#servicios" class="nav__link" data-i18n="nav_services">Servicios</a></li>
                            <li><a href="#proyectos" class="nav__link" data-i18n="nav_projects">Proyectos</a></li>
                            <li><a href="#tecnologias" class="nav__link" data-i18n="nav_tech">Tecnologías</a></li>
                            <li><a href="#nosotros" class="nav__link" data-i18n="nav_about">Sobre nosotros</a></li>
                            <li><a href="#precios" class="nav__link" data-i18n="nav_pricing">Precios</a></li>
                            <li><a href="#contacto" class="nav__link" data-i18n="nav_contact">Contacto</a></li>
                        </ul>
                        <a href="#contacto" class="btn btn--primary nav__cta-mobile" data-i18n="cta_button">Pide tu presupuesto</a>
                    </div>
                </nav>

                <a href="#contacto" class="btn btn--primary header__cta" data-i18n="cta_button">Pide tu presupuesto</a>

                <div class="lang-selector" id="lang-selector">
                    <button class="lang-btn" id="lang-btn" aria-label="Seleccionar idioma">
                        <span class="lang-flag">🇪🇸</span>
                    </button>
                    <ul class="lang-dropdown" id="lang-dropdown">
                        <li data-lang="es"><span class="lang-flag">ES</span></li>
                        <li data-lang="en"><span class="lang-flag">🇬🇧</span></li>
                        <li data-lang="pt"><span class="lang-flag">🇵🇹</span></li>
                        <li data-lang="it"><span class="lang-flag">🇮🇹</span></li>
                        <li data-lang="fr"><span class="lang-flag">🇫🇷</span></li>
                    </ul>
                </div>

                <a href="{{ route('login') }}" class="theme-toggle" aria-label="Acceso administración" title="Acceso administración">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                </a>

                <button class="hamburger" id="hamburger" aria-label="Abrir menú" aria-expanded="false">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </header>

        <main>
            <!-- ========== HERO ========== -->
            <section class="hero" id="inicio">
                <div class="container hero__inner">
                    <div class="hero__content">
                        <h1 class="hero__title"><span data-i18n="hero_title_prefix">Creamos</span> <span class="typewriter" id="typewriter"></span> <span data-i18n="hero_title_suffix">que hacen
                            <em>crecer tu negocio</em></span>
                        </h1>
                        <p class="hero__subtitle" data-i18n="hero_subtitle">Diseño web profesional, apps móviles para iOS y Android, sistemas de
                            reservas online y software a medida. </p>
                            <p class="hero__subtitle" data-i18n="hero_subtitle2">Todo lo que necesitas para digitalizarte.</p>
                        <div class="hero__buttons">
                            <a href="#contacto" class="btn btn--primary btn--lg" data-i18n="hero_cta_primary">Quiero mi web</a>
                            <a href="#proyectos" class="btn btn--outline btn--lg" data-i18n="hero_cta_secondary">Ver proyectos</a>
                        </div>
                    </div>

                    <div class="hero__visual">
                        <!-- Mockup simulado: Web -->
                        <div class="mockup mockup--web">
                            <div class="mockup__bar">
                                <span class="dot dot--red"></span>
                                <span class="dot dot--yellow"></span>
                                <span class="dot dot--green"></span>
                            </div>
                            <div class="mockup__body">
                                <div class="mockup__nav-placeholder"></div>
                                <div class="mockup__hero-placeholder"></div>
                                <div class="mockup__cards-placeholder">
                                    <div class="mockup__card-sm"></div>
                                    <div class="mockup__card-sm"></div>
                                    <div class="mockup__card-sm"></div>
                                </div>
                            </div>
                        </div>
                        <!-- Mockup simulado: App -->
                        <div class="mockup mockup--app">
                            <div class="mockup__notch"></div>
                            <div class="mockup__app-body">
                                <div class="mockup__app-header"></div>
                                <div class="mockup__app-list">
                                    <div class="mockup__app-item"></div>
                                    <div class="mockup__app-item"></div>
                                    <div class="mockup__app-item"></div>
                                </div>
                                <div class="mockup__app-fab">+</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <div class="wave-separator">
                <svg viewBox="0 0 1440 60" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0,60 C320,0 720,0 1080,30 C1280,48 1400,55 1440,60 L1440,60 L0,60Z" fill="currentColor" />
                </svg>
            </div>

            <!-- ========== SERVICIOS ========== -->
            <section class="section services" id="servicios">
                <div class="container">
                    <h2 class="section__title" data-i18n="services_title">Nuestros servicios</h2>
                    <p class="section__subtitle" data-i18n="services_subtitle">Soluciones digitales completas para impulsar tu negocio al siguiente
                        nivel.</p>

                    <div class="services__grid">
                        <article class="card">
                            <div class="card__icon">
                                <svg viewBox="0 0 24 24" width="40" height="40" fill="none" stroke="currentColor"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="3" width="20" height="14" rx="2" />
                                    <path d="M8 21h8" />
                                    <path d="M12 17v4" />
                                </svg>
                            </div>
                            <h3 class="card__title" data-i18n="service_1_title">Páginas web profesionales</h3>
                            <p class="card__text" data-i18n="service_1_text">Diseñamos y desarrollamos sitios web rápidos, modernos y optimizados
                                para SEO que convierten visitantes en clientes.</p>
                        </article>
                        <article class="card">
                            <div class="card__icon">
                                <svg viewBox="0 0 24 24" width="40" height="40" fill="none" stroke="currentColor"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="5" y="2" width="14" height="20" rx="3" />
                                    <path d="M12 18h.01" />
                                </svg>
                            </div>
                            <h3 class="card__title" data-i18n="service_2_title">Aplicaciones móviles</h3>
                            <p class="card__text" data-i18n="service_2_text">Apps nativas e híbridas para iOS y Android con interfaces intuitivas y
                                rendimiento excepcional.</p>
                        </article>
                        <article class="card">
                            <div class="price-card__badge" data-i18n="badge_new">Nuevo</div>
                            <div class="card__icon">
                                <svg viewBox="0 0 24 24" width="40" height="40" fill="none" stroke="currentColor"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="2" />
                                    <path d="M16 2v4" />
                                    <path d="M8 2v4" />
                                    <path d="M3 10h18" />
                                    <path d="M8 14h.01" />
                                    <path d="M12 14h.01" />
                                    <path d="M16 14h.01" />
                                    <path d="M8 18h.01" />
                                    <path d="M12 18h.01" />
                                </svg>
                            </div>
                            <h3 class="card__title" data-i18n="service_3_title">Sistemas de reservas online</h3>
                            <p class="card__text" data-i18n="service_3_text">Plataformas de reservas integradas con tu web y tus herramientas, para
                                automatizar citas y aumentar ventas.</p>
                        </article>
                        <article class="card">
                            <div class="card__icon">
                                <svg viewBox="0 0 24 24" width="40" height="40" fill="none" stroke="currentColor"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 2L2 7l10 5 10-5-10-5z" />
                                    <path d="M2 17l10 5 10-5" />
                                    <path d="M2 12l10 5 10-5" />
                                </svg>
                            </div>
                            <h3 class="card__title" data-i18n="service_4_title">Aplicaciones a medida</h3>
                            <p class="card__text" data-i18n="service_4_text">Paneles internos, pequeños ERP, integraciones con APIs y herramientas
                                personalizadas para tu operativa diaria.</p>
                        </article>
                    </div>
                </div>
            </section>

            <!-- ========== CÓMO TRABAJAMOS ========== -->
            <section class="section process" id="proceso">
                <div class="container">
                    <h2 class="section__title" data-i18n="process_title">Tu web lista en días, no en meses</h2>
                    <p class="section__subtitle" data-i18n="process_subtitle">Mientras otras agencias tardan semanas en empezar, nosotros estudiamos
                        tu negocio antes de que nos contactes.</p>

                    <div class="process__timeline">
                        <article class="process__step">
                            <div class="process__number">01</div>
                            <h3 class="process__title" data-i18n="process_step_1_title">Estudiamos tu negocio</h3>
                            <p class="process__text" data-i18n="process_step_1_text">Antes de la primera reunión ya hemos analizado tu sector, tu
                                competencia y tus necesidades. Llegamos con los deberes hechos.</p>
                        </article>
                        <article class="process__step">
                            <div class="process__number">02</div>
                            <h3 class="process__title" data-i18n="process_step_2_title">Diseño funcional listo</h3>
                            <p class="process__text" data-i18n="process_step_2_text">Te presentamos un diseño funcional basado en nuestro análisis. No
                                es un boceto: es una web navegable que ya puedes probar.</p>
                        </article>
                        <article class="process__step">
                            <div class="process__number">03</div>
                            <h3 class="process__title" data-i18n="process_step_3_title">Personalizamos juntos</h3>
                            <p class="process__text" data-i18n="process_step_3_text">Ajustamos colores, textos, imágenes y los pequeños detalles que
                                hacen única tu marca. Tú decides, nosotros ejecutamos.</p>
                        </article>
                        <article class="process__step">
                            <div class="process__number">04</div>
                            <h3 class="process__title" data-i18n="process_step_4_title">Online y operativa</h3>
                            <p class="process__text" data-i18n="process_step_4_text">Publicamos tu web con dominio, hosting y SSL configurados. En
                                varios días tienes tu negocio visible en internet.</p>
                        </article>
                    </div>

                    <div class="process__highlight">
                        <span class="process__highlight-icon">
                            <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                            </svg>
                        </span>
                        <p data-i18n="process_highlight"><strong>De media, entregamos una web profesional en 3 días.</strong> Otras agencias necesitan
                            entre 4 y 8 semanas para lo mismo.</p>
                    </div>
                </div>
            </section>

            <!-- ========== ESTADÍSTICAS ========== -->
            <section class="section stats" id="stats">
                <div class="container">
                    <div class="stats__grid">
                        <div class="stat">
                            <span class="stat__number" data-target="50">0</span>
                            <span class="stat__label" data-i18n="stat_1_label">Proyectos entregados</span>
                        </div>
                        <div class="stat">
                            <span class="stat__number" data-target="4.8" data-decimal="true">0</span>
                            <span class="stat__label" data-i18n="stat_2_label">Valoración de nuestros clientes</span>
                        </div>
                        <div class="stat">
                            <span class="stat__number" data-target="15">0</span>
                            <span class="stat__suffix" data-i18n="stat_3_suffix">años</span>
                            <span class="stat__label" data-i18n="stat_3_label">De experiencia en el sector</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ========== PROYECTOS ========== -->
            <section class="section projects" id="proyectos">
                <div class="container">
                    <h2 class="section__title" data-i18n="projects_title">Proyectos destacados</h2>
                    <p class="section__subtitle" data-i18n="projects_subtitle">Algunos casos de éxito que hablan por nosotros.</p>
                    <div class="projects__grid">
                        <article class="project-card">
                            <a href="https://www.aroundsevillatours.es" target="_blank" rel="noopener noreferrer"
                                class="project-card__preview project-card__preview--web">
                                <div class="preview-frame preview-frame--browser">
                                    <div class="preview-frame__bar"><span></span><span></span><span></span></div>
                                    <div class="preview-frame__img">
                                        <img src="{{ asset('img/proyectos/around-sevilla.png') }}" alt="Captura de Around Sevilla Tours" loading="lazy">
                                    </div>
                                </div>
                            </a>
                            <div class="project-card__info">
                                <div class="project-card__badge" data-i18n="project_badge_web">Web</div>
                                <h3 class="project-card__title">Around Sevilla Tours</h3>
                                <p class="project-card__desc" data-i18n="project_1_desc">Agencia de visitas turísticas en Sevilla.</p>
                                <p class="project-card__result" data-i18n="project_1_result">+45 % de reservas online en 3 meses</p>
                                <a href="https://www.aroundsevillatours.es" target="_blank" rel="noopener noreferrer"
                                    class="project-card__link" data-i18n="visit_web">Visitar web &rarr;</a>
                            </div>
                        </article>
                        <article class="project-card">
                            <a href="https://www.alvarezabogados.es" target="_blank" rel="noopener noreferrer"
                                class="project-card__preview project-card__preview--web">
                                <div class="preview-frame preview-frame--browser">
                                    <div class="preview-frame__bar"><span></span><span></span><span></span></div>
                                    <div class="preview-frame__img">
                                        <img src="{{ asset('img/proyectos/alvarez-abogados.png') }}" alt="Captura de Alvarez Abogados" loading="lazy">
                                    </div>
                                </div>
                            </a>
                            <div class="project-card__info">
                                <div class="project-card__badge" data-i18n="project_badge_web">Web</div>
                                <h3 class="project-card__title">Alvarez Abogados</h3>
                                <p class="project-card__desc" data-i18n="project_2_desc">Despacho de abogados especializado en diversas áreas legales.</p>
                                <p class="project-card__result" data-i18n="project_2_result">+30 % de clientes satisfechos en 6 meses</p>
                                <a href="https://www.alvarezabogados.es" target="_blank" rel="noopener noreferrer"
                                    class="project-card__link" data-i18n="visit_web">Visitar web &rarr;</a>
                            </div>
                        </article>
                        <article class="project-card">
                            <a href="https://www.montequip.es" target="_blank" rel="noopener noreferrer"
                                class="project-card__preview project-card__preview--web">
                                <div class="preview-frame preview-frame--browser">
                                    <div class="preview-frame__bar"><span></span><span></span><span></span></div>
                                    <div class="preview-frame__img">
                                        <img src="{{ asset('img/proyectos/montequip.png') }}" alt="Captura de Montequip" loading="lazy">
                                    </div>
                                </div>
                            </a>
                            <div class="project-card__info">
                                <div class="project-card__badge" data-i18n="project_badge_web">Web</div>
                                <h3 class="project-card__title">Montequip</h3>
                                <p class="project-card__desc" data-i18n="project_3_desc">Distribuidor, montaje e instalación de mobilario.</p>
                                <p class="project-card__result" data-i18n="project_3_result">+30% clientes potenciales contactando a través de la web en el último año</p>
                                <a href="https://www.montequip.es" target="_blank" rel="noopener noreferrer"
                                    class="project-card__link" data-i18n="visit_web">Visitar web &rarr;</a>
                            </div>
                        </article>
                        <article class="project-card">
                            <a href="https://www.fuerzaneta.es" target="_blank" rel="noopener noreferrer"
                                class="project-card__preview project-card__preview--web">
                                <div class="preview-frame preview-frame--phone">
                                    <div class="preview-frame__notch"></div>
                                    <div class="preview-frame__img">
                                        <img src="{{ asset('img/proyectos/fuerza-neta.png') }}" alt="Captura de Fuerza Neta App" loading="lazy">
                                    </div>
                                </div>
                            </a>
                            <div class="project-card__info">
                                <div class="project-card__badge" data-i18n="project_badge_app">App móvil</div>
                                <h3 class="project-card__title">Fuerza Neta</h3>
                                <p class="project-card__desc" data-i18n="project_4_desc">App de Fuerza para que los Entrenadores puedan planificar entrenamientos a sus atletas.</p>
                                <p class="project-card__result" data-i18n="project_4_result">+500 atletas activos</p>
                                <a href="https://www.fuerzaneta.es" target="_blank" rel="noopener noreferrer"
                                    class="project-card__link" data-i18n="see_app">Ver app &rarr;</a>
                            </div>
                        </article>
                        <article class="project-card">
                            <a href="https://www.netocomercial.es" target="_blank" rel="noopener noreferrer"
                                class="project-card__preview project-card__preview--web">
                                <div class="preview-frame preview-frame--browser">
                                    <div class="preview-frame__bar"><span></span><span></span><span></span></div>
                                    <div class="preview-frame__img">
                                        <img src="{{ asset('img/proyectos/neto-comercial.png') }}" alt="Captura de Neto Comercial" loading="lazy">
                                    </div>
                                </div>
                            </a>
                            <div class="project-card__info">
                                <div class="project-card__badge" data-i18n="project_badge_custom">Aplicación a Medida</div>
                                <h3 class="project-card__title">Neto Comercial</h3>
                                <p class="project-card__desc" data-i18n="project_5_desc">Sistema de gestión de clientes, ventas y facturas para llevar el control de la empresa.</p>
                                <p class="project-card__result" data-i18n="project_5_result">+Control de las facturas pendientes o impagos</p>
                                <a href="https://www.netocomercial.es" target="_blank" rel="noopener noreferrer"
                                    class="project-card__link" data-i18n="visit_web">Visitar web &rarr;</a>
                            </div>
                        </article>
                        <article class="project-card">
                            <a href="https://www.budgetix.es" target="_blank" rel="noopener noreferrer"
                                class="project-card__preview project-card__preview--app">
                                <div class="preview-frame preview-frame--phone">
                                    <div class="preview-frame__notch"></div>
                                    <div class="preview-frame__img">
                                        <img src="{{ asset('img/proyectos/budgetix.png') }}" alt="Captura de Budgetix App" loading="lazy">
                                    </div>
                                </div>
                            </a>
                            <div class="project-card__info">
                                <div class="project-card__badge" data-i18n="project_badge_app">App móvil</div>
                                <h3 class="project-card__title">Budgetix</h3>
                                <p class="project-card__desc" data-i18n="project_6_desc">Aplicación móvil para gestión de finanzas personales con la cual conseguir controlar y optimizar tus ahorros.</p>
                                <p class="project-card__result" data-i18n="project_6_result">Mejoras mensuales en la aplicación.</p>
                                <a href="https://www.budgetix.es" target="_blank" rel="noopener noreferrer"
                                    class="project-card__link" data-i18n="see_app">Ver app &rarr;</a>
                            </div>
                        </article>
                    </div>
                </div>
            </section>

            <!-- ========== TECNOLOGÍAS ========== -->
            <section class="section technologies" id="tecnologias">
                <div class="container">
                    <h2 class="section__title" data-i18n="tech_title">Tecnologías que usamos</h2>
                    <p class="section__subtitle" data-i18n="tech_subtitle">Herramientas modernas y probadas para cada proyecto.</p>

                    <div class="marquee">
                        <div class="marquee__track">
                            <span class="chip">HTML5</span>
                            <span class="chip">CSS3</span>
                            <span class="chip">JavaScript</span>
                            <span class="chip">TypeScript</span>
                            <span class="chip">React</span>
                            <span class="chip">Vue.js</span>
                            <span class="chip">Node.js</span>
                            <span class="chip">PHP</span>
                            <span class="chip">Laravel</span>
                            <span class="chip">HTML5</span>
                            <span class="chip">CSS3</span>
                            <span class="chip">JavaScript</span>
                            <span class="chip">TypeScript</span>
                            <span class="chip">React</span>
                            <span class="chip">Vue.js</span>
                            <span class="chip">Node.js</span>
                            <span class="chip">PHP</span>
                            <span class="chip">Laravel</span>
                            <span class="chip">Vanilla</span>
                        </div>
                    </div>
                    <div class="marquee marquee--reverse">
                        <div class="marquee__track">
                            <span class="chip">Python</span>
                            <span class="chip">SQL</span>
                            <span class="chip">MongoDB</span>
                            <span class="chip">REST APIs</span>
                            <span class="chip">Flutter</span>
                            <span class="chip">React Native</span>
                            <span class="chip">Docker</span>
                            <span class="chip">Git</span>
                            <span class="chip">Figma</span>
                            <span class="chip">Python</span>
                            <span class="chip">SQL</span>
                            <span class="chip">MongoDB</span>
                            <span class="chip">REST APIs</span>
                            <span class="chip">Flutter</span>
                            <span class="chip">React Native</span>
                            <span class="chip">Docker</span>
                            <span class="chip">Git</span>
                            <span class="chip">Figma</span>
                            <span class="chip">Microservices</span>
                            <span class="chip">.Net</span>
                            <span class="chip">Kotlin</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ========== POR QUÉ DALOWEB ========== -->
            <section class="section why-us" id="nosotros">
                <div class="container">
                    <h2 class="section__title" data-i18n="why_title">¿Por qué DaloWeb?</h2>
                    <p class="section__subtitle" data-i18n="why_subtitle">Lo que nos diferencia de otras agencias.</p>

                    <div class="why-us__grid">
                        <article class="why-card">
                            <div class="why-card__icon">
                                <svg viewBox="0 0 24 24" width="36" height="36" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 20V10" /><path d="M18 20V4" /><path d="M6 20v-4" />
                                </svg>
                            </div>
                            <h3 class="why-card__title" data-i18n="why_1_title">Diseño orientado a conversión</h3>
                            <p class="why-card__text" data-i18n="why_1_text">Cada decisión de diseño busca generar resultados medibles para tu negocio.</p>
                        </article>
                        <article class="why-card">
                            <div class="why-card__icon">
                                <svg viewBox="0 0 24 24" width="36" height="36" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                                </svg>
                            </div>
                            <h3 class="why-card__title" data-i18n="why_2_title">Rendimiento y seguridad</h3>
                            <p class="why-card__text" data-i18n="why_2_text">Webs ultrarrápidas con buenas prácticas de seguridad desde el primer día.</p>
                        </article>
                        <article class="why-card">
                            <div class="why-card__icon">
                                <svg viewBox="0 0 24 24" width="36" height="36" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                                    <circle cx="9" cy="7" r="4" />
                                    <path d="M23 21v-2a4 4 0 00-3-3.87" />
                                    <path d="M16 3.13a4 4 0 010 7.75" />
                                </svg>
                            </div>
                            <h3 class="why-card__title" data-i18n="why_3_title">Soporte cercano y transparente</h3>
                            <p class="why-card__text" data-i18n="why_3_text">Comunicación directa, sin intermediarios. Sabrás en todo momento el estado de tu proyecto.</p>
                        </article>
                        <article class="why-card">
                            <div class="why-card__icon">
                                <svg viewBox="0 0 24 24" width="36" height="36" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94L6.7 20.2a2.12 2.12 0 01-3-3l6.83-6.83a6 6 0 017.94-7.94l-3.76 3.77z" />
                                </svg>
                            </div>
                            <h3 class="why-card__title" data-i18n="why_4_title">Soluciones a medida</h3>
                            <p class="why-card__text" data-i18n="why_4_text">No usamos plantillas genéricas. Cada proyecto se construye desde cero según tus necesidades.</p>
                        </article>
                    </div>
                </div>
            </section>

            <!-- ========== PRECIOS ========== -->
            <section class="section pricing" id="precios">
                <div class="container">
                    <h2 class="section__title" data-i18n="pricing_title">Nuestros precios</h2>
                    <p class="section__subtitle" data-i18n="pricing_subtitle">Planes transparentes adaptados a cada tipo de proyecto. Sin letra pequeña.</p>

                    <div class="pricing__grid">
                        <article class="price-card">
                            <div class="price-card__header">
                                <div class="price-card__icon">
                                    <svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="2" y="3" width="20" height="14" rx="2" /><path d="M8 21h8" /><path d="M12 17v4" />
                                    </svg>
                                </div>
                                <h3 class="price-card__title" data-i18n="plan_1_title">Web Profesional</h3>
                                <div class="price-card__price"><span data-i18n="price_from">Desde</span> <strong>200 €</strong></div>
                            </div>
                            <ul class="price-card__features">
                                <li data-i18n="plan_1_f1">Diseño personalizado y responsive</li>
                                <li data-i18n="plan_1_f2">Hasta 5 secciones / páginas</li>
                                <li data-i18n="plan_1_f3">Optimización SEO básica</li>
                                <li data-i18n="plan_1_f5">Certificado SSL incluido</li>
                                <li data-i18n="plan_1_f6">Entrega en una semana</li>
                            </ul>
                            <a href="#contacto" class="btn btn--outline btn--full" data-i18n="price_cta">Solicitar presupuesto</a>
                        </article>

                        <article class="price-card">
                            <div class="price-card__header">
                                <div class="price-card__icon">
                                    <svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="4" width="18" height="18" rx="2" /><path d="M16 2v4" /><path d="M8 2v4" /><path d="M3 10h18" />
                                    </svg>
                                </div>
                                <h3 class="price-card__title" data-i18n="plan_2_title">Sistema de Reservas</h3>
                                <div class="price-card__price"><span data-i18n="price_from">Desde</span> <strong>600 €</strong></div>
                            </div>
                            <ul class="price-card__features">
                                <li data-i18n="plan_2_f1">Calendario de reservas online</li>
                                <li data-i18n="plan_2_f2">Confirmación por email automática</li>
                                <li data-i18n="plan_2_f3">Panel de gestión de citas</li>
                                <li data-i18n="plan_2_f4">Integrable en tu web actual</li>
                                <li data-i18n="plan_2_f5">Recordatorios automáticos</li>
                                <li data-i18n="plan_2_f6">Soporte 2 meses incluido</li>
                            </ul>
                            <a href="#contacto" class="btn btn--outline btn--full" data-i18n="price_cta">Solicitar presupuesto</a>
                        </article>

                        <article class="price-card price-card--featured">
                            <div class="price-card__badge" data-i18n="most_requested">Más solicitado</div>
                            <div class="price-card__header">
                                <div class="price-card__icon">
                                    <svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="5" y="2" width="14" height="20" rx="3" /><path d="M12 18h.01" />
                                    </svg>
                                </div>
                                <h3 class="price-card__title" data-i18n="plan_3_title">App Móvil</h3>
                                <div class="price-card__price"><span data-i18n="price_from">Desde</span> <strong>800 €</strong></div>
                            </div>
                            <ul class="price-card__features">
                                <li data-i18n="plan_3_f1">iOS y Android (multiplataforma)</li>
                                <li data-i18n="plan_3_f2">Diseño UI/UX personalizado</li>
                                <li data-i18n="plan_3_f3">Panel de administración</li>
                                <li data-i18n="plan_3_f4">Soporte 3 meses incluido</li>
                            </ul>
                            <a href="#contacto" class="btn btn--primary btn--full" data-i18n="price_cta">Solicitar presupuesto</a>
                        </article>
                    </div>

                    <div class="process__highlight">
                        <span class="process__highlight-icon">
                            <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                            </svg>
                        </span>
                        <p data-i18n="pricing_highlight">¿Tienes un proyecto con necesidades especiales? Cuéntanoslo y te haremos un presupuesto a medida sin compromiso.</p>
                    </div>
                </div>
            </section>

            <!-- ========== TESTIMONIOS ========== -->
            <section class="section testimonials">
                <div class="container">
                    <h2 class="section__title" data-i18n="testimonials_title">Lo que dicen nuestros clientes</h2>

                    <div class="testimonials__grid">
                        <blockquote class="testimonial">
                            <p class="testimonial__text" data-i18n="testimonial_1_text">"Desde que lanzamos la nueva web, nuestras reservas online se han disparado. El equipo de DaloWeb entendió nuestro negocio desde el minuto uno."</p>
                            <footer class="testimonial__author">
                                <div class="testimonial__avatar">AG</div>
                                <div class="testimonial__author-info">
                                    <strong data-i18n="testimonial_1_author">Ana García</strong>
                                    <span data-i18n="testimonial_1_role">Directora, Restaurante La Marina</span>
                                </div>
                            </footer>
                        </blockquote>
                        <blockquote class="testimonial">
                            <p class="testimonial__text" data-i18n="testimonial_2_text">"Necesitábamos una app que fuera sencilla para nuestros socios y DaloWeb lo clavó. El soporte post‑lanzamiento es inmejorable."</p>
                            <footer class="testimonial__author">
                                <div class="testimonial__avatar">CR</div>
                                <div class="testimonial__author-info">
                                    <strong data-i18n="testimonial_2_author">Carlos Ruiz</strong>
                                    <span data-i18n="testimonial_2_role">CEO, FitClub Cádiz</span>
                                </div>
                            </footer>
                        </blockquote>
                        <blockquote class="testimonial">
                            <p class="testimonial__text" data-i18n="testimonial_3_text">"El panel a medida que nos desarrollaron nos ahorra horas de trabajo cada semana. Mejor inversión imposible."</p>
                            <footer class="testimonial__author">
                                <div class="testimonial__avatar">LM</div>
                                <div class="testimonial__author-info">
                                    <strong data-i18n="testimonial_3_author">Laura Méndez</strong>
                                    <span data-i18n="testimonial_3_role">Operaciones, Logística Sur Express</span>
                                </div>
                            </footer>
                        </blockquote>
                    </div>
                </div>
            </section>

            <!-- ========== CONTACTO ========== -->
            <section class="section contact" id="contacto">
                <div class="container">
                    <h2 class="section__title" data-i18n="contact_title">Cuéntanos tu proyecto</h2>
                    <p class="section__subtitle" data-i18n="contact_subtitle">Rellena el formulario y te responderemos en menos de 24 h.</p>

                    <form class="contact-form" id="contactForm" action="{{ route('contacto') }}" method="POST" novalidate>
                        @csrf
                        <div class="contact-form__group">
                            <label for="nombre" data-i18n="form_name">Nombre</label>
                            <input type="text" id="nombre" name="nombre" placeholder="Tu nombre" data-i18n-placeholder="form_name_placeholder" required>
                        </div>
                        <div class="contact-form__group">
                            <label for="email" data-i18n="form_email">Email</label>
                            <input type="email" id="email" name="email" placeholder="tu@email.com" data-i18n-placeholder="form_email_placeholder" required>
                        </div>
                        <div class="contact-form__group">
                            <label for="tipo" data-i18n="form_type">Tipo de proyecto</label>
                            <select id="tipo" name="tipo" required>
                                <option value="" disabled selected data-i18n="form_type_default">Selecciona una opción</option>
                                <option value="web" data-i18n="form_type_web">Página web</option>
                                <option value="app" data-i18n="form_type_app">App móvil</option>
                                <option value="reservas" data-i18n="form_type_reservas">Sistema de reservas</option>
                                <option value="medida" data-i18n="form_type_medida">Aplicación a medida</option>
                            </select>
                        </div>
                        <div class="contact-form__group contact-form__group--full">
                            <label for="mensaje" data-i18n="form_message">Mensaje</label>
                            <textarea id="mensaje" name="mensaje" rows="5" placeholder="Cuéntanos qué necesitas…" data-i18n-placeholder="form_message_placeholder" required></textarea>
                        </div>
                        <div class="contact-form__group contact-form__group--full">
                            <button type="submit" class="btn btn--primary btn--lg btn--full" data-i18n="form_submit">Enviar mensaje</button>
                        </div>
                        <div class="contact-form__feedback" id="formFeedback" aria-live="polite"></div>
                    </form>

                    <div class="contact__info">
                        <p><strong>Email:</strong> hola@daloweb.es</p>
                    </div>
                </div>
            </section>
        </main>

        <!-- ========== FOOTER ========== -->
        <footer class="footer">
            <div class="container footer__inner">
                <div class="footer__brand">
                    <a href="#inicio" class="logo logo--footer">Dalo<span>Web</span></a>
                </div>

                <nav class="footer__links" aria-label="Enlaces del pie">
                    <ul>
                        <li><a href="#inicio" data-i18n="nav_home">Inicio</a></li>
                        <li><a href="#servicios" data-i18n="nav_services">Servicios</a></li>
                        <li><a href="#proyectos" data-i18n="nav_projects">Proyectos</a></li>
                        <li><a href="#nosotros" data-i18n="nav_about">Sobre nosotros</a></li>
                        <li><a href="#precios" data-i18n="nav_pricing">Precios</a></li>
                        <li><a href="#contacto" data-i18n="nav_contact">Contacto</a></li>
                        <li><a href="#" data-i18n="footer_legal">Aviso legal</a></li>
                        <li><a href="#" data-i18n="footer_privacy">Política de privacidad</a></li>
                    </ul>
                </nav>

                <p class="footer__copy" data-i18n="footer_copy">&copy; 2026 DaloWeb. Todos los derechos reservados.</p>
            </div>
        </footer>

        <!-- CTA flotante -->
        <div class="floating-cta" id="floatingCta">
            <div class="container floating-cta__inner">
                <p class="floating-cta__text" data-i18n="floating_cta_text">¿Listo para impulsar tu negocio?</p>
                <a href="#contacto" class="btn btn--primary btn--sm" data-i18n="cta_button">Pide tu presupuesto</a>
            </div>
        </div>

    </body>

</html>
