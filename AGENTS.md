# DaloWeb

- Runtime: PHP 8.5 (`~8.5.0` en Composer), Laravel 12 y PHPUnit 13. Mantener esta rama Laravel; no introducir una migracion mayor como efecto secundario.
- Leer `README.md` y conservar arquitectura, middleware, sesiones y contratos de rutas. No cambiar esquemas, datos ni `.env` sin autorizacion.
- En macOS, ejecutar con `herd php` (PHP 8.5.12 comprobado). Composer: `herd php "$(command -v composer)"`; comprobar lock e instalacion con auditorias separadas y `check-platform-reqs`.
- Prueba PHP aislada: `APP_ENV=testing APP_CONFIG_CACHE=/tmp/php85-daloweb-no-config-cache.php DB_CONNECTION=sqlite DB_DATABASE=:memory: MAIL_MAILER=array CACHE_STORE=array herd php vendor/bin/phpunit --bootstrap vendor/autoload.php tests/Feature/ProductionReadinessTest.php --fail-on-deprecation --fail-on-phpunit-deprecation`.
- Prueba JavaScript: `node --test tests/cookie-consent.test.mjs`. No introducir npm/bundles: el frontend no tiene build.
- No usar la base real, enviar SMTP, ejecutar migraciones ni sobrescribir la cache de configuracion de despliegue durante pruebas. Informar comando, resumen y `EXIT_CODE`.