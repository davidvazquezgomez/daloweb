<section class="section faq" id="preguntas">
    <div class="container narrow">
        <h2 class="section__title" data-i18n="faq_title">Antes de empezar</h2>
        @foreach(config('comercial.faq') as $item)
            <details>
                <summary @if($traducible ?? false) data-i18n="{{ $item['key'] }}_q" @endif>{{ $item['pregunta'] }}</summary>
                <p @if($traducible ?? false) data-i18n="{{ $item['key'] }}_a" @endif>{{ $item['respuesta'] }}</p>
            </details>
        @endforeach
    </div>
</section>
