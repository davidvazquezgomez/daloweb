<header class="header" id="header">
            <div class="container header__inner">
                <a href="{{ route('inicio') }}" class="logo" aria-label="DaloWeb inicio">Dalo<span>Web</span></a>

                <nav class="nav" id="nav" aria-label="Navegación principal">
                    <div class="nav__drawer">
                        <ul class="nav__list">
                            <li><a href="{{ ($esPortada ?? false) ? '#servicios' : route('inicio') . '#servicios' }}" class="nav__link" data-i18n="nav_services">Servicios</a></li>
                            <li><a href="{{ ($esPortada ?? false) ? '#proyectos' : route('inicio') . '#proyectos' }}" class="nav__link" data-i18n="nav_projects">Proyectos</a></li>
                            <li><a href="{{ route('precios') }}" class="nav__link" data-i18n="nav_pricing">Precios</a></li>
                            <li><a href="{{ ($esPortada ?? false) ? '#nosotros' : route('inicio') . '#nosotros' }}" class="nav__link" data-i18n="nav_about">Sobre nosotros</a></li>
                        </ul>
                        <a href="{{ ($esPortada ?? false) ? '#contacto' : route('inicio') . '#contacto' }}" class="btn btn--primary nav__cta-mobile" data-i18n="cta_button">Pide tu presupuesto</a>
                    </div>
                </nav>

                <a href="{{ ($esPortada ?? false) ? '#contacto' : route('inicio') . '#contacto' }}" class="btn btn--primary header__cta" data-i18n="cta_button">Pide tu presupuesto</a>

                @if($traducible ?? false)
                <div class="lang-selector" id="lang-selector">
                    <button type="button" class="lang-btn" id="lang-btn" aria-label="Seleccionar idioma" aria-expanded="false" aria-controls="lang-dropdown">
                        <span class="lang-flag">🇪🇸</span>
                    </button>
                    <ul class="lang-dropdown" id="lang-dropdown">
                        <li><button type="button" data-lang="es" lang="es"><span class="lang-flag">🇪🇸</span> Español</button></li>
                        <li><button type="button" data-lang="en" lang="en"><span class="lang-flag">EN</span> English</button></li>
                    </ul>
                </div>

                @endif

                <button type="button" class="hamburger" id="hamburger" aria-label="Abrir menú" aria-expanded="false" aria-controls="nav">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </header>
