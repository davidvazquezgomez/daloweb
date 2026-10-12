# Subir a Plesk sin terminal

El paquete `dist/daloweb-produccion.zip` incluye el código, los recursos, las dependencias PHP de producción (`vendor/`) y un `.env` con una nueva `APP_KEY` válida. No contiene cachés de configuración, rutas o vistas generadas en local. No hace falta Node.js, Composer ni terminal en el servidor.

## Instalación sobre la web actual

1. Descargar una copia del `.env` actual desde Plesk, si existe, y hacer copia de los archivos/BD de producción. Si hay una `APP_KEY` válida anterior, conservarla en el nuevo `.env`; el log facilitado indica que Laravel actualmente no está leyendo ninguna clave.
2. Completar el `.env` preparado con los datos reales de Plesk. Como mínimo:

   ```dotenv
   DB_DATABASE=nombre_real_de_la_base_de_datos
   DB_USERNAME=usuario_real_de_la_base_de_datos
   DB_PASSWORD="contraseña_real"
   MAIL_HOST=servidor_smtp_real
   MAIL_PORT=587
   MAIL_SCHEME=smtp
   MAIL_USERNAME=usuario_smtp_real
   MAIL_PASSWORD="contraseña_smtp_real"
   ```

   Para SMTP en puerto 465, usar `MAIL_SCHEME=smtps`. No cambiar la `APP_KEY` generada, salvo para conservar una clave válida del servidor. Mantener `SESSION_DRIVER=file`, `CACHE_STORE=file`, `QUEUE_CONNECTION=sync`, `APP_DEBUG=false` y la URL HTTPS.

   Se puede editar `.env` con el administrador de archivos de Plesk **después de extraer el ZIP**. El ZIP contiene la versión de `.env` del momento en que se generó: si se edita el archivo local después, subir también ese `.env` actualizado. La portada funciona sin credenciales de BD/SMTP; el panel y el envío de correo requieren completarlas.
3. En **Configuración de hosting**, comprobar PHP >= 8.2 (por ejemplo 8.3) y raíz del documento `httpdocs/public`. Mantener el proyecto en `/var/www/vhosts/daloweb.es/httpdocs`.
4. Subir el ZIP **a `httpdocs/`**, extraer su contenido directamente allí y aceptar sustituir los archivos del proyecto. Evitar una subcarpeta extra. Verificar que existen `httpdocs/.env`, `httpdocs/vendor/autoload.php` y `httpdocs/public/index.php`. El ZIP incluye archivos ocultos.
5. **Eliminar el ZIP del servidor tras extraerlo**, ya que contiene `.env` y no debe quedar accesible mediante descarga.
6. La extracción no elimina las cachés viejas. Desde el administrador de archivos, eliminar estos archivos si existen:

   - `httpdocs/bootstrap/cache/config.php` — paso esencial: obliga a leer el nuevo `.env`.
   - `httpdocs/bootstrap/cache/routes-*.php`.
   - `httpdocs/bootstrap/cache/events.php`.
   - `httpdocs/bootstrap/cache/packages.php` y `services.php` — se regeneran con las dependencias actuales.
   - Los archivos `.php` de `httpdocs/storage/framework/views/`.

   Conservar los directorios y sus `.gitignore`. No borrar demos, logs ni datos de usuario. Si OPcache sigue sirviendo código anterior, reiniciar PHP-FPM desde Plesk si está disponible, o pedir al hosting que lo reinicie.
7. Comprobar permisos de escritura para el usuario PHP-FPM sobre `storage/` y `bootstrap/cache/`. Los directorios vacíos necesarios están incluidos en el ZIP.
8. Si la base de datos aún no está creada/importada, crearla desde Plesk y abrir phpMyAdmin, **seleccionar la base correcta** e importar `database/daloweb.sql`. No importar sobre datos existentes sin revisar y hacer copia. El SQL incluye un administrador inicial cuya contraseña debe cambiarse.
9. Abrir `https://www.daloweb.es/`, comprobar estilos, imágenes, menú móvil y `/login`. Probar el formulario y confirmar recepción del correo. El script `deploy.sh` es opcional para instalaciones con terminal; esta instalación no lo necesita.

## Si sigue apareciendo 500

Abrir el log más reciente en `httpdocs/storage/logs/` desde Plesk. La configuración nueva usa archivos diarios `laravel-AAAA-MM-DD.log`. También revisar los registros PHP/Apache del dominio. No activar `APP_DEBUG=true` públicamente.

Si persiste `MissingAppKeyException`, comprobar que `.env` está en `httpdocs/`, su `APP_KEY` no está vacía, el usuario PHP puede leerlo y se ha eliminado `bootstrap/cache/config.php`. Otros errores pueden indicar permisos de escritura, configuración PHP o conexión/esquema de BD.

El ZIP y `.env` son privados y no se suben a Git. Conservar la misma clave en futuras subidas.
