@php
    $imagen = config('imagenes')[$archivo];
    $prioritaria = $prioritaria ?? false;
    $srcset = [];
    foreach ($imagen['webp'] as $ancho => $ruta) {
        $srcset[] = asset('img/proyectos/' . $ruta) . ' ' . $ancho . 'w';
    }
@endphp
<picture>
    <source type="image/webp" sizes="{{ $sizes }}" srcset="{{ implode(', ', $srcset) }}">
    <img src="{{ asset('img/proyectos/' . $archivo) }}" alt="{{ $alt }}" width="{{ $imagen['width'] }}" height="{{ $imagen['height'] }}" loading="{{ $prioritaria ? 'eager' : 'lazy' }}" decoding="async" @if($prioritaria) fetchpriority="high" @endif>
</picture>
