# DaloWeb — Webs, Apps y Software a Medida

Plataforma web de **DaloWeb**, agencia digital especializada en diseño web profesional, aplicaciones móviles, sistemas de reservas online y software a medida. Incluye landing page pública y panel de administración completo.

## Stack tecnológico

| Capa | Tecnología |
|------|------------|
| Backend | Laravel 12 · PHP 8.3 |
| Base de datos | MariaDB |
| Frontend | HTML5 · CSS3 · JavaScript vanilla (sin Node.js) |
| Auth | Laravel Sanctum · Sesiones en archivos (BD configurable) |
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
cp .env.example .env        # Solo si no existe; configurar credenciales
composer install --no-dev    # Instalar dependencias
php artisan key:generate --force # Solo si APP_KEY está vacía
mysql -u user -p daloweb < database/daloweb.sql  # Importar BD
bash deploy.sh               # Optimizar caches y permisos
```

DocumentRoot en Plesk → `public/`

Diagnóstico sin mostrar secretos: `php artisan app:check-production`.

Pruebas locales (con dependencias de desarrollo):

```bash
vendor/bin/phpunit --bootstrap vendor/autoload.php tests/Feature/ProductionReadinessTest.php
```

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
- Rate limiting en login y contacto

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


## Google Analytics y cookies

Archivos SEO listos para subir: `public/sitemap.xml` y `public/robots.txt`. Copiar ambos a la carpeta pública del servidor, donde deben quedar accesibles como `https://www.daloweb.es/sitemap.xml` y `https://www.daloweb.es/robots.txt`. Enviar la URL del sitemap en Google Search Console y Bing Webmaster Tools.

Para regenerarlos cuando cambien servicios o guías: `php scripts/generar-seo.php`. El generador utiliza `https://www.daloweb.es` por defecto y acepta otro dominio HTTPS como argumento. Los archivos físicos tienen prioridad sobre las rutas de Laravel: regenerarlos y subirlos tras añadir páginas o actualizar las fechas de las guías. Para verificar por etiquetas HTML, configurar `GOOGLE_SITE_VERIFICATION` y `BING_SITE_VERIFICATION` con los valores de cada herramienta y renovar la caché de configuración.

Las páginas públicas integran Google Analytics 4 con `G-TT318T6SZZ` mediante [public/js/landing/cookies.js](public/js/landing/cookies.js). La etiqueta de Google solo se carga tras aceptar las cookies analíticas. El banner permite aceptar o rechazar con la misma presentación; el pie y `/cookies` permiten cambiar la decisión. La retirada desactiva la medición, elimina las cookies `_ga` accesibles y recarga la página. No se integra Analytics en el login ni en el panel de administración.

La elección se conserva 180 días en `localStorage` (`daloweb_cookie_consent`), incluyendo versión y vencimiento. Incrementar `version` en el script cuando cambien las finalidades o proveedores que requieran renovar el consentimiento. Las cookies analíticas tienen un máximo de dos años desde su creación, sin renovación automática. Las señales de Google y la personalización publicitaria permanecen desactivadas. Los eventos personalizados existentes solo se envían después de aceptar, sin reenviar eventos anteriores ni incluir datos del formulario o el identificador interno de la solicitud.

La política está en [resources/views/public/cookies.blade.php](resources/views/public/cookies.blade.php). Completar los datos del titular en `config/legal.php`, revisar las condiciones y garantías del proveedor y comprobar en la propiedad de GA4 los ajustes de conservación y medición mejorada (especialmente formularios y cambios de URL). Estos ajustes externos no se modifican desde el repositorio. Tras desplegar cambios de configuración, ejecutar `php artisan config:cache` y, si se precachean vistas, `php artisan view:cache`.

Validación: `node --test tests/cookie-consent.test.mjs` y `vendor/bin/phpunit tests/Feature/ProductionReadinessTest.php`. Para comprobar la recepción real tras desplegar, usar Tag Assistant o el informe en tiempo real de GA4, aceptando previamente las cookies.

## Seguimiento de oportunidades

Páginas `/aviso-legal` y `/privacidad`: completar los valores `XXXXXXX` en [config/legal.php](config/legal.php) y revisar los textos en `resources/views/public/aviso-legal.blade.php` y `privacidad.blade.php`. Para apartados que no apliquen, indicar expresamente «No aplica» tras comprobarlo. Los textos incluyen los datos informativos descritos por el [artículo 10 de la LSSI](https://www.boe.es/buscar/act.php?id=BOE-A-2002-13758#a10) y la estructura de información de la [AEPD](https://www.aepd.es/derechos-y-deberes/conoce-tus-derechos/derecho-de-informacion). Mientras quede algún marcador, las páginas llevan `noindex, follow`. Tras editar la configuración en producción, renovar la caché con `php artisan config:cache`.

Herramientas comerciales: `/admin/rentabilidad` calcula costes, precio mínimo y contribución antes de impuestos, sin guardar simulaciones ni modificar la oferta pública. Requiere administrador y funciona sin JavaScript ni tablas nuevas. Completar costes reales; no presupone márgenes ni impuestos. Para acordar alcance y recurrencia, usar [la ficha de presupuesto y mantenimiento](PLANTILLA_PRESUPUESTO_Y_MANTENIMIENTO.md).

Panel protegido en `/admin/oportunidades`: estados, origen, presupuesto, venta, coste y notas. La contribución excluye ventas sin coste registrado. Incluye eliminación individual de los datos.

La captura automática está desactivada por defecto. Para preparar la tabla en el despliegue:

```bash
php artisan migrate --path=database/migrations/2026_10_13_000001_create_leads_table.php
```

En producción, Laravel puede requerir `--force` en el proceso de despliegue. No se ha aplicado esta migración a la base real durante la implementación.

Cuando estén acordadas privacidad y conservación, configurar `LEADS_CAPTURE_ENABLED=true` y renovar la caché de configuración. Con la captura desactivada, el contacto sigue enviando correo sin guardar solicitudes en esta tabla. Con la captura activada, se registra la solicitud antes del envío y se distingue correo pendiente, enviado o fallido. Un fallo del correo conserva la oportunidad para gestionarla en el panel.

El origen guarda categorías (directo, orgánico, publicidad, redes, email, referido u otro), sin campañas, consultas, direcciones IP ni URLs de procedencia. La atribución por navegación interna requiere JavaScript; en una entrada directa, el formulario también recibe el origen calculado por el servidor.

Pruebas de la web y del seguimiento, con SQLite en memoria y correo simulado:

```bash
vendor/bin/phpunit --bootstrap vendor/autoload.php tests/Feature
```
