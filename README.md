# DaloWeb — Webs, Apps y Software a Medida

Plataforma web de **DaloWeb**, agencia digital especializada en diseño web profesional, aplicaciones móviles, sistemas de reservas online y software a medida. Incluye landing page pública y panel de administración completo.

## Stack tecnológico

| Capa | Tecnología |
|------|------------|
| Backend | Laravel 12 · PHP 8.3 |
| Base de datos | MariaDB |
| Frontend | HTML5 · CSS3 · JavaScript vanilla (sin Node.js) |
| Auth | Laravel Sanctum · Sesiones en BD |
| Librerías CDN | SortableJS (Kanban) · Chart.js (Facturación) |
| Email | Laravel Mail + SMTP |
| Hosting | Plesk · Apache · HTTPS|

## Estructura del proyecto

```
daloweb/
├── app/
│   ├── Enums/                          # RolUsuario, EstadoTarea
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   └── LoginController.php
│   │   │   ├── Admin/
│   │   │   │   ├── PanelController.php         # Dashboard
│   │   │   │   ├── TareaController.php         # Kanban CRUD + comentarios
│   │   │   │   ├── UsuarioController.php       # Gestión usuarios
│   │   │   │   ├── FacturacionController.php   # Gastos + Ingresos
│   │   │   │   └── DemoController.php          # Demos CRUD + auto-registro
│   │   │   ├── ContactoController.php          # Envío email landing
│   │   │   └── InicioController.php            # Landing page
│   │   └── Middleware/
│   │       ├── VerificarAdmin.php              # Protección rutas admin
│   │       └── AccesoDemo.php                  # Control acceso demos
│   ├── Mail/
│   │   └── CorreoContacto.php                  # Mailable formulario contacto
│   └── Models/
│       ├── Usuario.php
│       ├── Tarea.php
│       ├── ComentarioTarea.php
│       ├── Gasto.php
│       ├── Ingreso.php
│       └── Demo.php
├── database/
│   └── daloweb.sql                             # Esquema completo (8 tablas + seed admin)
├── resources/views/
│   ├── auth/login.blade.php
│   ├── admin/
│   │   ├── layouts/app.blade.php               # Layout panel (sidebar + topbar)
│   │   ├── panel.blade.php                     # Dashboard con cards resumen
│   │   ├── tareas/index.blade.php              # Tablero Kanban
│   │   ├── usuarios/
│   │   │   ├── index.blade.php                 # Listado + búsqueda
│   │   │   └── show.blade.php                  # Detalle usuario
│   │   ├── facturacion/index.blade.php         # Gráfica + tablas
│   │   └── demos/index.blade.php               # Galería de demos
│   ├── emails/contacto.blade.php
│   └── landing.blade.php
├── public/
│   ├── css/
│   │   ├── landing.css                         # Estilos landing
│   │   ├── admin.css                           # Estilos panel admin
│   │   ├── kanban.css · usuarios.css           # Estilos por módulo
│   │   ├── facturacion.css · demos.css
│   ├── js/
│   │   ├── landing/main.js · languages.js      # JS landing (i18n, animaciones)
│   │   ├── kanban.js                           # Drag & drop SortableJS
│   │   ├── usuarios.js                         # AJAX usuarios
│   │   ├── facturacion.js                      # Chart.js + CRUD
│   │   └── demos.js                            # Galería + sincronización
│   └── img/
├── storage/app/demos/                          # Archivos de demos (protegidos)
├── routes/web.php                              # Todas las rutas (37 endpoints)
├── deploy.sh                                   # Script de despliegue
├── DEPLOY.md                                   # Guía despliegue Plesk
├── ANALISIS_PANEL_ADMIN.md                     # Documento de análisis completo
└── .env.example                                # Plantilla variables de entorno
```

## Landing page (pública)

| Sección | Descripción |
|---------|-------------|
| Hero | Presentación con efecto typewriter y mockups animados |
| Servicios | Webs, apps móviles, sistemas de reservas, software a medida |
| Cómo trabajamos | Proceso en 4 pasos: análisis, diseño, personalización, publicación |
| Estadísticas | Proyectos entregados, valoración de clientes, años de experiencia |
| Proyectos destacados | Casos de éxito con capturas y resultados |
| Tecnologías | Marquee animado con el stack tecnológico |
| Por qué DaloWeb | Diferenciadores de la agencia |
| Precios | Planes transparentes para cada tipo de proyecto |
| Contacto | Formulario con envío real por SMTP a hola@daloweb.es |

- Tema: siempre dark (sin toggle light/dark)
- Idiomas: ES, EN, PT, IT, FR (sistema i18n con `data-i18n`)
- Efecto neón en títulos

## Panel de administración

Acceso en `/admin` — solo usuarios con rol `admin`.

### Dashboard
Cards de resumen con totales en tiempo real: tareas, usuarios, ingresos del año, gastos del año.

### Tareas (Kanban)
- Tablero con 3 columnas: Pendiente → En Progreso → Completado
- Drag & drop con SortableJS para mover tareas entre columnas
- CRUD completo: crear, editar, eliminar tareas
- Sistema de comentarios por tarea
- Asignación de tareas a usuarios

### Usuarios
- Listado con búsqueda en tiempo real
- Creación de usuarios (solo admins crean cuentas, sin registro público)
- Edición de datos personales y rol
- Vista detalle con secciones condicionales según rol:
  - Admin: tareas asignadas/creadas
  - Usuario: datos personales, ventas asociadas, acceso a demos públicas
- Eliminación con protección (no puede eliminarse a sí mismo)

### Facturación
- Resumen: total ingresos, total gastos, balance
- Gráfica mensual de barras (ingresos vs gastos) con Chart.js
- Tablas de gastos e ingresos con CRUD completo
- Filtro por año
- Categorías de gastos: dominio, servidor, software, otros
- Tipos de ingreso: web, componente, app, reservas, medida, otro
- Gastos recurrentes: genera 12 registros mensuales automáticamente
- Ingresos vinculados a clientes (FK a usuarios)

### Demos
- Galería con tarjetas (miniatura, título, tipo, tecnologías, visibilidad)
- Auto-registro: al cargar la página detecta carpetas nuevas en `storage/app/demos/` y las registra como demos privadas
- Toggle visibilidad pública ↔ privada por demo
- Botón sincronizar para forzar escaneo
- Eliminar demo borra también la carpeta y archivos del servidor
- Ruta pública `/demo/{slug}` con middleware de control de acceso:
  - Pública → accesible por cualquiera
  - Privada → solo admins logueados (404 para el resto)
- Archivos servidos desde storage (no public) con detección MIME y protección path-traversal

## Base de datos

8 tablas en MariaDB (naming en español):

| Tabla | Descripción |
|-------|-------------|
| `usuarios` | Usuarios del sistema (con campos de facturación) |
| `sessions` | Sesiones de Laravel |
| `personal_access_tokens` | Tokens Sanctum |
| `tareas` | Tareas del Kanban |
| `comentarios_tareas` | Comentarios en tareas |
| `gastos` | Gastos de la empresa |
| `ingresos` | Ingresos (con FK a cliente) |
| `demos` | Catálogo de demos |

Esquema completo en `database/daloweb.sql`. Admin por defecto: `admin@daloweb.es` / `admin1234`.

## Despliegue

Ver [DEPLOY.md](DEPLOY.md) para la guía completa de despliegue en Plesk.

```bash
# Resumen rápido
cp .env.example .env        # Configurar credenciales
composer install --no-dev    # Instalar dependencias
php artisan key:generate     # Generar clave
mysql -u user -p daloweb < database/daloweb.sql  # Importar BD
bash deploy.sh               # Optimizar caches y permisos
```

DocumentRoot en Plesk → `public/`

## Seguridad

- Contraseñas hasheadas con bcrypt (12 rondas)
- CSRF en todos los formularios
- Middleware `VerificarAdmin` en rutas admin
- Middleware `AccesoDemo` para demos privadas
- SQL injection protegido (Eloquent ORM)
- XSS protegido (Blade escapa por defecto)
- Demos en `storage/` (no accesibles directamente)
- Path-traversal check en servicio de archivos de demos
- Sesiones httpOnly + SameSite=Lax
- Rate limiting en login

## Licencia

Todos los derechos reservados © DaloWeb 2026.

## Historial de cambios

### Panel Admin (Marzo 2026)
- Migración a Laravel 12 con PHP 8.3
- Sistema de autenticación completo (login, middleware, sesiones en BD)
- Módulo Tareas: Kanban con drag & drop, comentarios, asignación
- Módulo Usuarios: CRUD, búsqueda, perfiles con secciones condicionales
- Módulo Facturación: gastos/ingresos, gráficas Chart.js, gastos recurrentes
- Módulo Demos: galería, auto-registro, visibilidad, middleware acceso, servicio de archivos desde storage
- Dashboard con resumen en tiempo real
- Formulario de contacto funcional con SMTP
- Tema siempre dark (eliminado toggle light/dark)
- Preparación despliegue: .env.example, deploy.sh, DEPLOY.md

### Landing (versión anterior)
- Sistema de traducción embebido (PT, EN, FR, IT, ES)
- Efecto neón en títulos
- Banner NEW para sistema de reservas
- Ajuste de precios: plan 200€ reducido a 3 páginas (header, contacto y footer incluidos)
- Stack tecnológico actualizado