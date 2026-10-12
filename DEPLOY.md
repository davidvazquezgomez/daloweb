# Producción en Plesk

**Sin acceso a terminal:** seguir [SUBIR_PLESK.md](SUBIR_PLESK.md). El paquete preparado incluye `vendor/` y `.env`; basta extraerlo, completar credenciales y borrar cachés desde el administrador de archivos.

## Causa confirmada del 500

El log facilitado del 12/10/2026 registra `MissingAppKeyException: No application encryption key has been specified`. Laravel está leyendo una `APP_KEY` vacía; puede faltar en `.env` o estar vacía en `bootstrap/cache/config.php`.

En la terminal de Plesk, usando la misma versión de PHP que el dominio:

```bash
cd /var/www/vhosts/daloweb.es/httpdocs
php artisan config:clear
```

Si `.env` tiene una clave válida, conservarla. Si `APP_KEY` está vacía, generar una una sola vez:

```bash
php artisan key:generate --force
php artisan config:cache
```

Si ya había clave, ejecutar únicamente `config:cache` después de `config:clear`. No rotar una clave existente: invalidaría cookies y datos cifrados con ella. La clave no debe subirse a Git ni compartirse.

Si falta `.env`, copiar primero `.env.example` y completar las credenciales reales. No sobrescribir un `.env` existente.

## Instalación inicial

1. Configurar el DocumentRoot del dominio en Plesk como `httpdocs/public`. No servir la raíz del repositorio. Laravel requiere PHP >= 8.2 y sus extensiones habituales, además de `pdo_mysql` para MariaDB. Comprobar que el PHP de terminal y PHP-FPM tienen versión y extensiones adecuadas.
2. Instalar las dependencias desde `composer.lock`:

   ```bash
   composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction
   composer check-platform-reqs --no-dev
   ```

   Si `php` apunta a una versión antigua, usar el binario configurado en Plesk, por ejemplo `/opt/plesk/php/8.3/bin/php`, también para ejecutar Composer.

3. Crear `.env` solo si falta:

   ```bash
   cp .env.example .env
   ```

   Completar `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` y SMTP. Mantener `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://www.daloweb.es` y `SESSION_SECURE_COOKIE=true`. Si la contraseña contiene espacios o `#`, escribirla entre comillas.

4. Crear la base de datos y su usuario desde Plesk. En una instalación nueva, importar `database/daloweb.sql` dentro de esa BD con phpMyAdmin, o:

   ```bash
   mysql -h 127.0.0.1 -u USUARIO -p NOMBRE_BD < database/daloweb.sql
   ```

   El SQL ya no fuerza el nombre `daloweb` ni intenta crear bases de datos. En una BD existente, hacer copia y revisar su esquema antes de importar. No hay migraciones del esquema de negocio ni un seeder funcional: `artisan migrate` o `db:seed` no sustituyen esta importación.

5. Dar permisos de escritura al usuario PHP-FPM del dominio sobre `storage/` y `bootstrap/cache/`. Ejecutar como propietario de la suscripción; con propietario y grupo correctos, usar permisos 775 para directorios y 664 para archivos de esas carpetas. No usar 777. Los permisos de CLI no garantizan permisos para PHP-FPM.
6. Limpiar configuración y generar `APP_KEY` solo si no existe, como se indica arriba.
7. Ejecutar:

   ```bash
   bash deploy.sh
   ```

   Para seleccionar el PHP de Plesk:

   ```bash
   PHP_BIN=/opt/plesk/php/8.3/bin/php bash deploy.sh
   ```

   El script comprueba clave, entorno, permisos y tablas antes de optimizar. No instala dependencias, cambia contraseñas, genera claves ni importa SQL.

## Sesiones, caché y correo

La plantilla usa `SESSION_DRIVER=file`, `CACHE_STORE=file` y `QUEUE_CONNECTION=sync`, adecuados para este despliegue en un servidor. La portada no necesita la BD para abrir. El panel sí la necesita. Si se conserva `SESSION_DRIVER=database`, debe existir `sessions` y funcionar la conexión.

El SQL no incluye `cache`, `cache_locks` ni `jobs`. No seleccionar drivers de BD para caché/colas sin crear sus tablas. Después de cambiar `.env`, ejecutar `config:clear` y volver a optimizar. Para varios servidores, usar almacenamiento de sesiones/caché compartido.

El formulario exige SMTP y solo muestra éxito cuando Laravel termina el envío. Configurar `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_FROM_ADDRESS` y `MAIL_SCHEME`. Puerto 587 suele usar `smtp` con STARTTLS; 465 usa `smtps`. Confirmar las opciones con el proveedor. El driver `log` no entrega correos. Una respuesta SMTP satisfactoria no garantiza llegada a la bandeja de entrada; probar un envío y revisar spam/SPF/DKIM del dominio.

## Verificación

- `/`: HTTP 200, estilos, imágenes, menú móvil y cambio de idioma.
- `/login`: HTTP 200; `/admin` sin sesión redirige al login.
- `/up`: salud del arranque; no prueba BD, SMTP ni APP_KEY por sí solo.
- `php artisan app:check-production`: configuración y tablas, sin mostrar secretos.
- Formulario: probar datos inválidos, un envío real y la recepción en `hola@daloweb.es`.
- Login: probar credenciales incorrectas, recordar sesión y acceso al panel.

Si sigue el 500, mirar el error más reciente de `storage/logs/laravel*.log` (la plantilla usa logs diarios) y los logs de PHP-FPM/Apache en Plesk. No activar debug público. `SQLSTATE` suele indicar conexión/esquema; `Permission denied`, permisos; errores de autoload, dependencias. Eliminar de despliegues cualquier `public/hot` local.

## Pendientes detectados en la revisión

- Cambiar la contraseña inicial del administrador incluida en el SQL antes de abrir el panel al público.
- Completar los enlaces de aviso legal y privacidad, actualmente `href="#"`, y el contenido aplicable al negocio/formulario.
- Incorporar migraciones versionadas para futuras actualizaciones del esquema.
- Añadir etiquetas canonical, Open Graph y sitemap si se quiere mejorar indexación y compartir enlaces.
- Revisar las demos: `/demo/{slug}` sin barra final puede resolver mal recursos relativos; el servicio de archivos debe rechazar directorios y limitar las carpetas configurables a `storage/app/demos/`.
- Completar comprobación visual en navegador móvil/escritorio tras recuperar producción. Esta revisión no certifica entrega SMTP ni todos los CRUD del panel con datos reales.

Referencia oficial: https://laravel.com/docs/12.x/deployment
