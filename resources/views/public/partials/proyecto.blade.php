<article class="project-card">
    <a href="{{ $proyecto['url'] }}" target="_blank" rel="noopener noreferrer" class="project-card__preview" aria-label="Visitar {{ $proyecto['nombre'] }} (se abre en otra pestaña)">
        <div class="preview-frame preview-frame--{{ $proyecto['tipo'] === 'App móvil' ? 'phone' : 'browser' }}">
            <div class="preview-frame__img">
                <img src="{{ asset('img/proyectos/' . $proyecto['imagen']) }}" alt="Captura de {{ $proyecto['nombre'] }}" loading="lazy">
            </div>
        </div>
    </a>
    <div class="project-card__info">
        <span class="project-card__badge" @if($traducible ?? false) data-i18n="{{ $proyecto['tipo'] === 'Web' ? 'project_badge_web' : ($proyecto['tipo'] === 'App móvil' ? 'project_badge_app' : 'project_badge_custom') }}" @endif>{{ $proyecto['tipo'] }}</span>
        <h3 class="project-card__title">{{ $proyecto['nombre'] }}</h3>
        <p class="project-card__desc" @if($traducible ?? false) data-i18n="{{ $proyecto['i18n'] }}" @endif>{{ $proyecto['descripcion'] }}</p>
        <a href="{{ $proyecto['url'] }}" target="_blank" rel="noopener noreferrer" class="project-card__link" @if($traducible ?? false) data-i18n="visit_web" @endif>Visitar proyecto →</a>
    </div>
</article>
