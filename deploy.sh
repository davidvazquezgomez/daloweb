#!/usr/bin/env bash
set -euo pipefail
cd "$(dirname "$0")"
PHP_BIN="${PHP_BIN:-php}"

if [[ ! -f .env || ! -f vendor/autoload.php ]]; then
    echo 'Falta .env o vendor/. Consultar DEPLOY.md antes de desplegar.' >&2
    exit 1
fi

mkdir -p bootstrap/cache storage/logs storage/framework/views storage/framework/sessions storage/framework/cache/data storage/app/demos

# No sobrescribe .env, no rota APP_KEY ni importa/modifica la BD.
# No limpia la caché de datos: evita depender de tablas cache inexistentes.
"$PHP_BIN" artisan config:clear
"$PHP_BIN" artisan app:check-production
"$PHP_BIN" artisan optimize
echo 'Configuración, rutas y vistas optimizadas. Revisar /, /login, /up y el formulario.'
