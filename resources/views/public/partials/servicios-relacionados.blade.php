<section class="section related-services">
    <div class="container">
        <h2>Explora nuestras soluciones</h2>
        <div class="related-services__links">
            @foreach(config('servicios') as $slug => $item)
                @if(!request()->routeIs('servicio.' . $slug))
                    <a href="{{ route('servicio.' . $slug) }}">{{ $item['nombre'] }} →</a>
                @endif
            @endforeach
        </div>
    </div>
</section>
