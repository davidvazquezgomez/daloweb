<?php

return [
    'packs' => [
        'web-profesional' => ['tipo' => 'web', 'nombre' => 'Web Profesional', 'precio' => 200, 'servicio' => 'diseno-web'],
        'sistema-reservas' => ['tipo' => 'reservas', 'nombre' => 'Sistema de Reservas', 'precio' => 600, 'servicio' => 'sistemas-de-reservas'],
        'app-movil' => ['tipo' => 'app', 'nombre' => 'App Móvil', 'precio' => 800, 'servicio' => 'desarrollo-apps'],
    ],
    'faq' => [
        ['key' => 'faq_scope', 'pregunta' => '¿Qué incluye el precio de partida?', 'respuesta' => 'La web contempla hasta 3 páginas; el sistema de reservas se define para gestionar citas e integrarse en tu web. Antes de empezar, el presupuesto confirma funcionalidades, revisiones, impuestos y gastos externos.'],
        ['key' => 'faq_materials', 'pregunta' => '¿Qué tengo que preparar?', 'respuesta' => 'Datos del negocio, servicios, logo, textos e imágenes con permiso de uso. Para reservas necesitamos además horarios, capacidad y reglas de las citas. Si te falta material, lo valoramos antes de comenzar.'],
        ['key' => 'faq_delivery', 'pregunta' => '¿Cuándo puede estar lista mi web?', 'respuesta' => 'La entrega orientativa del pack web es de una semana desde la recepción de materiales y la aprobación del alcance. Las integraciones y proyectos más amplios tienen un calendario propio.'],
        ['key' => 'faq_renewal', 'pregunta' => '¿Hay gastos de renovación o mantenimiento?', 'respuesta' => 'El presupuesto debe especificar dominio, hosting, licencias, servicios externos y sus renovaciones, además del soporte incluido. El mantenimiento continuado y las nuevas funcionalidades se acuerdan por separado.'],
    ],
];
