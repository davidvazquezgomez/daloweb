<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('titulo', 'Panel') — DaloWeb Admin</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('img/favicon/favicon.svg') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @stack('css')
</head>

<body>
    <div class="admin">
        <!-- SIDEBAR -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar__header">
                <a href="{{ route('admin.panel') }}" class="sidebar__logo">Dalo<span>Web</span></a>
            </div>

            <nav class="sidebar__nav">
                <a href="{{ route('admin.panel') }}" class="sidebar__link {{ request()->routeIs('admin.panel') ? 'sidebar__link--active' : '' }}">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                        <polyline points="9 22 9 12 15 12 15 22" />
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.tareas') }}" class="sidebar__link {{ request()->routeIs('admin.tareas*') ? 'sidebar__link--active' : '' }}">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="18" height="18" rx="2" />
                        <path d="M3 9h18" />
                        <path d="M9 21V9" />
                    </svg>
                    Tareas
                </a>
                <a href="{{ route('admin.usuarios') }}" class="sidebar__link {{ request()->routeIs('admin.usuarios*') ? 'sidebar__link--active' : '' }}">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 00-3-3.87" />
                        <path d="M16 3.13a4 4 0 010 7.75" />
                    </svg>
                    Usuarios
                </a>
                <a href="{{ route('admin.facturacion') }}" class="sidebar__link {{ request()->routeIs('admin.facturacion*') ? 'sidebar__link--active' : '' }}">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17.2 7A7 7 0 0 0 6 12a7 7 0 0 0 11.2 5" />
                        <line x1="4" y1="10" x2="13" y2="10" />
                        <line x1="4" y1="14" x2="13" y2="14" />
                    </svg>
                    Facturación
                </a>
                <a href="{{ route('admin.demos') }}" class="sidebar__link {{ request()->routeIs('admin.demos*') ? 'sidebar__link--active' : '' }}">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="3" width="20" height="14" rx="2" />
                        <path d="M8 21h8" />
                        <path d="M12 17v4" />
                    </svg>
                    Demos
                </a>

                <div class="sidebar__divider"></div>

                <a href="{{ route('inicio') }}" class="sidebar__link" target="_blank">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="2" y1="12" x2="22" y2="12" />
                        <path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z" />
                    </svg>
                    Ir a la web
                </a>
                <form method="POST" action="{{ route('logout') }}" class="sidebar__logout-form">
                    @csrf
                    <button type="submit" class="sidebar__link sidebar__link--logout">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4" />
                            <polyline points="16 17 21 12 16 7" />
                            <line x1="21" y1="12" x2="9" y2="12" />
                        </svg>
                        Cerrar sesión
                    </button>
                </form>
            </nav>
        </aside>

        <!-- MAIN -->
        <div class="main">
            <!-- TOPBAR -->
            <header class="topbar">
                <button class="topbar__hamburger" id="sidebar-toggle" aria-label="Abrir menú">
                    <span></span><span></span><span></span>
                </button>
                <h2 class="topbar__title">@yield('titulo', 'Dashboard')</h2>
                <div class="topbar__user">
                    <span class="topbar__avatar">{{ strtoupper(substr(Auth::user()->nombre, 0, 2)) }}</span>
                    <span class="topbar__name">{{ Auth::user()->nombre }}</span>
                </div>
            </header>

            <!-- CONTENIDO -->
            <main class="content">
                @yield('contenido')
            </main>

            <!-- FOOTER -->
            <footer class="admin-footer">
                &copy; {{ date('Y') }} DaloWeb. Panel de administración.
            </footer>
        </div>
    </div>

    @stack('js')
    <script>
        document.getElementById('sidebar-toggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('sidebar--open');
        });
    </script>
</body>

</html>