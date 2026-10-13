@extends('public.layout')

@section('contenido')
            <!-- ========== HERO ========== -->
            <section class="hero" id="inicio">
                <div class="container hero__inner">
                    <div class="hero__content">
                        <h1 class="hero__title"><span class="hero__title-highlight" data-i18n="hero_title_main">Webs profesionales</span> <span data-i18n="hero_title_suffix">y reservas online para tu negocio</span>
                        </h1>
                        <p class="hero__subtitle" data-i18n="hero_subtitle">Diseño web profesional, apps móviles para iOS y Android, sistemas de
                            reservas online y software a medida. </p>
                        <div class="hero__buttons">
                            <a href="#contacto" class="btn btn--primary btn--lg" data-i18n="hero_cta_primary">Quiero mi web</a>
                            <a href="#proyectos" class="btn btn--outline btn--lg" data-i18n="hero_cta_secondary">Ver proyectos</a>
                        </div>
                    </div>

                    <div class="hero__visual">
                        <figure class="hero-project">
                            <a href="{{ route('proyectos') }}">
                                <img src="{{ asset('img/proyectos/around-sevilla.png') }}" alt="Web de Around Sevilla Tours, un proyecto de DaloWeb" width="703" height="360" fetchpriority="high">
                            </a>
                            <figcaption><strong>Around Sevilla Tours</strong><span data-i18n="hero_project_caption">Un proyecto de diseño web para visitas turísticas.</span></figcaption>
                        </figure>
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
                            <a class="service-link" href="{{ route('servicio.diseno-web') }}" data-i18n="service_details">Ver servicio →</a>
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
                            <a class="service-link" href="{{ route('servicio.desarrollo-apps') }}" data-i18n="service_details">Ver servicio →</a>
                        </article>
                        <article class="card">
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
                            <a class="service-link" href="{{ route('servicio.sistemas-de-reservas') }}" data-i18n="service_details">Ver servicio →</a>
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
                            <a class="service-link" href="{{ route('servicio.software-a-medida') }}" data-i18n="service_details">Ver servicio →</a>
                        </article>
                    </div>
                </div>
            </section>
            <section class="section projects" id="proyectos">
                <div class="container">
                    <h2 class="section__title" data-i18n="projects_title">Proyectos destacados</h2>
                    <p class="section__subtitle" data-i18n="projects_subtitle">Webs, aplicaciones y herramientas de gestión que hemos presentado en DaloWeb.</p>
                    <div class="projects__grid">
                        @foreach(['around-sevilla', 'fuerza-neta', 'neto-comercial'] as $slug)
                            @include('public.partials.proyecto', ['proyecto' => config('proyectos.' . $slug), 'traducible' => true])
                        @endforeach
                    </div>
                    <a class="btn btn--outline section-action" href="{{ route('proyectos') }}" data-i18n="projects_all">Ver todos los proyectos</a>
                </div>
            </section>

            <section class="section pricing" id="precios">
                <div class="container">
                    <h2 class="section__title" data-i18n="pricing_title">Nuestros precios</h2>
                    <p class="section__subtitle" data-i18n="pricing_subtitle">Elige una web, un sistema de reservas o un proyecto a medida según lo que necesitas.</p>
                    @include('public.partials.packs', ['esPortada' => true])
                    <a class="text-link" href="{{ route('precios') }}" data-i18n="pricing_full">Ver precios y qué confirmar antes de contratar →</a>
                </div>
            </section>

            <section class="section process" id="proceso">
                <div class="container">
                    <h2 class="section__title" data-i18n="process_title">Así ponemos en marcha tu proyecto</h2>
                    <div class="process__timeline">
                        <article class="process__step"><span class="process__number">01</span><h3 data-i18n="process_step_1_title">Definimos el alcance</h3><p data-i18n="process_step_1_text">Revisamos tus necesidades y acordamos páginas, funcionalidades, materiales y presupuesto.</p></article>
                        <article class="process__step"><span class="process__number">02</span><h3 data-i18n="process_step_2_title">Diseñamos y revisamos</h3><p data-i18n="process_step_2_text">Preparamos la solución y revisamos contigo el contenido y las funciones acordadas.</p></article>
                        <article class="process__step"><span class="process__number">03</span><h3 data-i18n="process_step_3_title">Publicamos y entregamos</h3><p data-i18n="process_step_3_text">Comprobamos el funcionamiento y concretamos los accesos y el soporte de tu proyecto.</p></article>
                    </div>
                    <p class="small-print" data-i18n="process_highlight">Entrega orientativa del pack web: una semana desde la recepción de materiales y la aprobación del alcance. Confirmamos el calendario en tu presupuesto.</p>
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
                            <p class="why-card__text" data-i18n="why_4_text">Adaptamos cada solución al alcance acordado y a las necesidades de tu negocio.</p>
                        </article>
                    </div>
                </div>
            </section>
@include('public.partials.faq', ['traducible' => true])


            <!-- ========== CONTACTO ========== -->
            <section class="section contact" id="contacto">
                <div class="container">
                    <h2 class="section__title" data-i18n="contact_title">Cuéntanos tu proyecto</h2>
                    <p class="section__subtitle" data-i18n="contact_subtitle">Cuéntanos qué necesitas y acordamos el siguiente paso.</p>

                    <div class="contact-layout">
                        <aside class="contact-intro">
                            <h3 data-i18n="contact_next_title">Qué pasa después</h3>
                            <p data-i18n="contact_next_text">Revisamos lo que necesitas y te proponemos un alcance y presupuesto antes de empezar.</p>
                            <p data-i18n="contact_prepare">Si ya tienes una web, incluye su dirección en el mensaje. Puedes contarnos qué quieres mejorar o qué función necesitas.</p>
                            <p><strong>Email:</strong> <a href="mailto:hola@daloweb.es">hola@daloweb.es</a></p>
                        </aside>
                    <form class="contact-form" id="contactForm" action="{{ route('contacto') }}" method="POST">
                        @csrf
                        @php
                            $packSolicitado = old('pack', $packPredeterminado ?? '');
                            $packAnterior = is_string($packSolicitado) ? $packSolicitado : '';
                            $packElegido = config('comercial.packs')[$packAnterior] ?? null;
                        @endphp
                        <input type="hidden" id="pack" name="pack" value="{{ $packElegido ? $packAnterior : '' }}">
                        <div class="contact-form__group contact-form__group--full" id="selectedPack" @if(!$packElegido) hidden @endif>
                            <p><span data-i18n="form_selected_pack">Pack elegido:</span> <strong id="selectedPackName">{{ $packElegido['nombre'] ?? '' }}</strong></p>
                        </div>
                        @error('pack') <p class="contact-form__error contact-form__group--full" role="alert">{{ $message }}</p> @enderror
                        <div class="contact-form__group">
                            <label for="nombre" data-i18n="form_name">Nombre</label>
                            <input type="text" id="nombre" name="nombre" autocomplete="name" value="{{ old('nombre') }}" maxlength="100" placeholder="Tu nombre" data-i18n-placeholder="form_name_placeholder" required @error('nombre') aria-invalid="true" aria-describedby="nombre-error" @enderror>
                            @error('nombre') <p class="contact-form__error" id="nombre-error" role="alert">{{ $message }}</p> @enderror
                        </div>
                        <div class="contact-form__group">
                            <label for="email" data-i18n="form_email">Email</label>
                            <input type="email" id="email" name="email" autocomplete="email" value="{{ old('email') }}" maxlength="150" placeholder="tu@email.com" data-i18n-placeholder="form_email_placeholder" required @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                            @error('email') <p class="contact-form__error" id="email-error" role="alert">{{ $message }}</p> @enderror
                        </div>
                        <div class="contact-form__group contact-form__group--full">
                            <label for="tipo" data-i18n="form_type">Tipo de proyecto</label>
                            <select id="tipo" name="tipo" required @error('tipo') aria-invalid="true" aria-describedby="tipo-error" @enderror>
                                <option value="" disabled @selected(!old('tipo', $packElegido['tipo'] ?? $tipoPredeterminado ?? '')) data-i18n="form_type_default">Selecciona una opción</option>
                                <option value="web" @selected(old('tipo', $packElegido['tipo'] ?? $tipoPredeterminado ?? '') === 'web') data-i18n="form_type_web">Página web</option>
                                <option value="app" @selected(old('tipo', $packElegido['tipo'] ?? $tipoPredeterminado ?? '') === 'app') data-i18n="form_type_app">App móvil</option>
                                <option value="reservas" @selected(old('tipo', $packElegido['tipo'] ?? $tipoPredeterminado ?? '') === 'reservas') data-i18n="form_type_reservas">Sistema de reservas</option>
                                <option value="medida" @selected(old('tipo', $packElegido['tipo'] ?? $tipoPredeterminado ?? '') === 'medida') data-i18n="form_type_medida">Aplicación a medida</option>
                            </select>
                            @error('tipo') <p class="contact-form__error" id="tipo-error" role="alert">{{ $message }}</p> @enderror
                        </div>
                        <div class="contact-form__group contact-form__group--full">
                            <label for="mensaje" data-i18n="form_message">Mensaje</label>
                            <textarea id="mensaje" name="mensaje" rows="5" maxlength="2000" placeholder="Cuéntanos qué necesitas…" data-i18n-placeholder="form_message_placeholder" required @error('mensaje') aria-invalid="true" aria-describedby="mensaje-error" @enderror>{{ old('mensaje') }}</textarea>
                            @error('mensaje') <p class="contact-form__error" id="mensaje-error" role="alert">{{ $message }}</p> @enderror
                        </div>
                        <div class="contact-form__group contact-form__group--full">
                            <button type="submit" class="btn btn--primary btn--lg btn--full" data-i18n="form_submit">Enviar mensaje</button>
                        </div>
                        <div class="contact-form__feedback" id="formFeedback" aria-live="polite">
                            @if(session('success'))
                                <p role="status">{{ session('success') }}</p>
                            @elseif(session('error'))
                                <p role="alert">{{ session('error') }}</p>
                            @elseif($errors->any())
                                <p role="alert">{{ $errors->first() }}</p>
                            @endif
                        </div>
                    </form>
                    </div>
                </div>
            </section>
@endsection
