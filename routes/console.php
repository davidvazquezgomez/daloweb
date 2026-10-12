<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Schema;

Artisan::command('app:check-production', function () {
    $failed = false;
    $check = function (bool $ok, string $message) use (&$failed) {
        $ok ? $this->info("OK: {$message}") : $this->error("ERROR: {$message}");
        $failed = $failed || !$ok;
    };

    try {
        Crypt::encryptString('deployment-check');
        $check(true, 'APP_KEY válida (valor oculto).');
    } catch (\Throwable $e) {
        $check(false, 'APP_KEY ausente o inválida. Configurar .env y ejecutar config:clear. Generar clave solo si no existe.');
    }

    $check(app()->environment('production'), 'APP_ENV=production.');
    $check(!config('app.debug'), 'APP_DEBUG=false.');
    $check(str_starts_with(config('app.url'), 'https://'), 'APP_URL usa HTTPS.');
    $check((bool) config('session.secure'), 'SESSION_SECURE_COOKIE=true.');

    foreach (['bootstrap/cache', 'storage/logs', 'storage/framework/views', 'storage/framework/sessions', 'storage/framework/cache/data'] as $directory) {
        $check(is_dir(base_path($directory)) && is_writable(base_path($directory)), "{$directory} existe y es escribible para el usuario CLI.");
    }

    $tables = ['usuarios', 'tareas', 'comentarios_tareas', 'gastos', 'ingresos', 'demos', 'personal_access_tokens'];
    try {
        foreach ($tables as $table) {
            $check(Schema::hasTable($table), "Tabla {$table} disponible en la BD configurada.");
        }
        if (config('session.driver') === 'database') {
            $check(Schema::connection(config('session.connection'))->hasTable(config('session.table')), 'Tabla de sesiones disponible.');
        }
        if (config('cache.default') === 'database') {
            $store = config('cache.stores.database');
            foreach (['table', 'lock_table'] as $key) {
                $connection = $key === 'lock_table' ? ($store['lock_connection'] ?? $store['connection']) : $store['connection'];
                $check(Schema::connection($connection)->hasTable($store[$key]), "Tabla de caché {$store[$key]} disponible.");
            }
        }
        if (config('queue.default') === 'database') {
            $queue = config('queue.connections.database');
            $check(Schema::connection($queue['connection'])->hasTable($queue['table']), 'Tabla de trabajos disponible.');
        }
    } catch (\Throwable $e) {
        $check(false, 'No se pudo comprobar la BD. Revisar conexión, credenciales, extensiones PHP y esquema.');
    }

    $check(config('mail.default') === 'smtp' && (bool) config('mail.mailers.smtp.host'), 'SMTP seleccionado y MAIL_HOST configurado (no comprueba entrega).');
    $this->comment('Comprobar también permisos del usuario PHP-FPM, DocumentRoot=public/ y envío real del formulario.');

    return $failed ? 1 : 0;
})->purpose('Comprueba configuración de producción sin mostrar secretos ni modificar la BD.');
