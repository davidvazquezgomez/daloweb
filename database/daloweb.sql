-- ============================================================
-- DaloWeb — Esquema completo de base de datos
-- MariaDB / MySQL · UTF-8 mb4
-- ============================================================

CREATE DATABASE IF NOT EXISTS `daloweb`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE `daloweb`;

-- ------------------------------------------------------------
-- Tabla: usuarios
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `usuarios` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nombre` VARCHAR(255) NOT NULL,
    `apellido` VARCHAR(255) DEFAULT NULL,
    `correo` VARCHAR(255) NOT NULL,
    `contrasena` VARCHAR(255) NOT NULL,
    `rol` ENUM('admin','usuario') NOT NULL DEFAULT 'usuario',
    `dni_cif` VARCHAR(20) DEFAULT NULL,
    `telefono` VARCHAR(20) DEFAULT NULL,
    `direccion` VARCHAR(500) DEFAULT NULL,
    `codigo_postal` VARCHAR(10) DEFAULT NULL,
    `ciudad` VARCHAR(100) DEFAULT NULL,
    `provincia` VARCHAR(100) DEFAULT NULL,
    `correo_verificado` TIMESTAMP NULL DEFAULT NULL,
    `token_recuerdo` VARCHAR(100) DEFAULT NULL,
    `ultimo_acceso` TIMESTAMP NULL DEFAULT NULL,
    `creado_en` TIMESTAMP NULL DEFAULT NULL,
    `actualizado_en` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `usuarios_correo_unique` (`correo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Tabla: sessions (sesiones de Laravel)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `sessions` (
    `id` VARCHAR(255) NOT NULL,
    `user_id` BIGINT UNSIGNED DEFAULT NULL,
    `ip_address` VARCHAR(45) DEFAULT NULL,
    `user_agent` TEXT DEFAULT NULL,
    `payload` LONGTEXT NOT NULL,
    `last_activity` INT NOT NULL,
    PRIMARY KEY (`id`),
    KEY `sessions_user_id_index` (`user_id`),
    KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Tabla: personal_access_tokens (Sanctum)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `tokenable_type` VARCHAR(255) NOT NULL,
    `tokenable_id` BIGINT UNSIGNED NOT NULL,
    `name` TEXT NOT NULL,
    `token` VARCHAR(64) NOT NULL,
    `abilities` TEXT DEFAULT NULL,
    `last_used_at` TIMESTAMP NULL DEFAULT NULL,
    `expires_at` TIMESTAMP NULL DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
    KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`, `tokenable_id`),
    KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Tabla: tareas (Kanban)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tareas` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `titulo` VARCHAR(255) NOT NULL,
    `descripcion` TEXT DEFAULT NULL,
    `estado` ENUM('pendiente','en_progreso','completado') NOT NULL DEFAULT 'pendiente',
    `posicion` INT UNSIGNED NOT NULL DEFAULT 0,
    `asignado_a` BIGINT UNSIGNED DEFAULT NULL,
    `creado_por` BIGINT UNSIGNED NOT NULL,
    `fecha_limite` DATE DEFAULT NULL,
    `creado_en` TIMESTAMP NULL DEFAULT NULL,
    `actualizado_en` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `tareas_estado_posicion_index` (`estado`, `posicion`),
    KEY `tareas_asignado_a_index` (`asignado_a`),
    KEY `tareas_creado_por_index` (`creado_por`),
    CONSTRAINT `tareas_asignado_a_foreign` FOREIGN KEY (`asignado_a`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL,
    CONSTRAINT `tareas_creado_por_foreign` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Tabla: comentarios_tareas
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `comentarios_tareas` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `tarea_id` BIGINT UNSIGNED NOT NULL,
    `usuario_id` BIGINT UNSIGNED NOT NULL,
    `contenido` TEXT NOT NULL,
    `creado_en` TIMESTAMP NULL DEFAULT NULL,
    `actualizado_en` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `comentarios_tareas_tarea_id_index` (`tarea_id`),
    CONSTRAINT `comentarios_tareas_tarea_id_foreign` FOREIGN KEY (`tarea_id`) REFERENCES `tareas` (`id`) ON DELETE CASCADE,
    CONSTRAINT `comentarios_tareas_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Datos iniciales
-- ============================================================

-- ------------------------------------------------------------
-- Tabla: gastos
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `gastos` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `concepto` VARCHAR(255) NOT NULL,
    `categoria` ENUM('dominio','servidor','software','otros') NOT NULL DEFAULT 'otros',
    `importe` DECIMAL(10,2) NOT NULL,
    `fecha` DATE NOT NULL,
    `recurrente` TINYINT(1) NOT NULL DEFAULT 0,
    `notas` TEXT DEFAULT NULL,
    `creado_por` BIGINT UNSIGNED NOT NULL,
    `creado_en` TIMESTAMP NULL DEFAULT NULL,
    `actualizado_en` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `gastos_fecha_index` (`fecha`),
    KEY `gastos_categoria_index` (`categoria`),
    CONSTRAINT `gastos_creado_por_foreign` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Tabla: ingresos
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `ingresos` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `concepto` VARCHAR(255) NOT NULL,
    `cliente_id` BIGINT UNSIGNED DEFAULT NULL,
    `tipo` ENUM('web','componente','app','reservas','medida','otro') NOT NULL DEFAULT 'web',
    `importe` DECIMAL(10,2) NOT NULL,
    `fecha` DATE NOT NULL,
    `numero_factura` VARCHAR(50) DEFAULT NULL,
    `notas` TEXT DEFAULT NULL,
    `creado_por` BIGINT UNSIGNED NOT NULL,
    `creado_en` TIMESTAMP NULL DEFAULT NULL,
    `actualizado_en` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `ingresos_fecha_index` (`fecha`),
    KEY `ingresos_tipo_index` (`tipo`),
    CONSTRAINT `ingresos_creado_por_foreign` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
    CONSTRAINT `ingresos_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Datos iniciales
-- ============================================================

-- ------------------------------------------------------------
-- Tabla: demos
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `demos` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `titulo` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL,
    `descripcion` TEXT DEFAULT NULL,
    `tipo` ENUM('web','componente') NOT NULL DEFAULT 'web',
    `ruta_carpeta` VARCHAR(500) NOT NULL,
    `miniatura` VARCHAR(500) DEFAULT NULL,
    `tecnologias` JSON DEFAULT NULL,
    `visibilidad` ENUM('publica','privada') NOT NULL DEFAULT 'privada',
    `activa` TINYINT(1) NOT NULL DEFAULT 1,
    `creado_por` BIGINT UNSIGNED NOT NULL,
    `creado_en` TIMESTAMP NULL DEFAULT NULL,
    `actualizado_en` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `demos_slug_unique` (`slug`),
    KEY `demos_visibilidad_index` (`visibilidad`),
    CONSTRAINT `demos_creado_por_foreign` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Datos iniciales
-- ============================================================

-- Admin por defecto (contraseña: admin1234)
INSERT INTO `usuarios` (`id`, `nombre`, `correo`, `contrasena`, `rol`, `creado_en`, `actualizado_en`)
VALUES (1, 'Admin', 'admin@daloweb.es',
        '$2y$12$mnlb1pdgX4vNKjM/Gvtt/O/WSE0qH8JdI2wSJ2vXj6ypqm/rsIU66',
        'admin', NOW(), NOW())
ON DUPLICATE KEY UPDATE `id` = `id`;
