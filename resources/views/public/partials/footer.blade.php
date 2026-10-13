<footer class="footer">
            <div class="container footer__inner">
                <div class="footer__brand">
                    <a href="{{ route('inicio') }}" class="logo logo--footer">Dalo<span>Web</span></a>
                </div>

                <nav class="footer__links" aria-label="Enlaces del pie">
                    <ul>
                        <li><a href="{{ route('inicio') }}" data-i18n="nav_home">Inicio</a></li>
                        <li><a href="{{ ($esPortada ?? false) ? '#servicios' : route('inicio') . '#servicios' }}" data-i18n="nav_services">Servicios</a></li>
                        <li><a href="{{ route('proyectos') }}" data-i18n="nav_projects">Proyectos</a></li>
                        <li><a href="{{ ($esPortada ?? false) ? '#nosotros' : route('inicio') . '#nosotros' }}" data-i18n="nav_about">Sobre nosotros</a></li>
                        <li><a href="{{ route('precios') }}" data-i18n="nav_pricing">Precios</a></li>
                        <li><a href="{{ ($esPortada ?? false) ? '#contacto' : route('inicio') . '#contacto' }}" data-i18n="nav_contact">Contacto</a></li>
                        <li><span data-i18n="footer_legal">Aviso legal</span></li>
                        <li><span data-i18n="footer_privacy">Política de privacidad</span></li>
                        <li><a href="{{ route('login') }}" data-i18n="footer_admin">Administración</a></li>
                    </ul>
                </nav>

                <p class="footer__copy" data-i18n="footer_copy">&copy; 2026 DaloWeb. Todos los derechos reservados.</p>
            </div>
        </footer>
