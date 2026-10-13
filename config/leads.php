<?php

return [
    'captura' => env('LEADS_CAPTURE_ENABLED', false),
    'estados' => ['nuevo' => 'Nuevo', 'contactado' => 'Contactado', 'presupuestado' => 'Presupuestado', 'ganado' => 'Ganado', 'perdido' => 'Perdido'],
    'origenes' => ['directo' => 'Directo', 'organico' => 'Búsqueda orgánica', 'publicidad' => 'Publicidad', 'redes' => 'Redes sociales', 'email' => 'Email', 'referido' => 'Referido', 'otro' => 'Otro'],
];
