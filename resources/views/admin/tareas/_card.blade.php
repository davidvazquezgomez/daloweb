<div class="kanban__card kanban__card--{{ $tarea->estado->value }}" data-id="{{ $tarea->id }}" onclick="Kanban.abrirDetalle({{ $tarea->id }})">
    <div class="kanban__card-title">{{ $tarea->titulo }}</div>
    @if($tarea->asignado)
    <span class="kanban__card-asignado">@ {{ $tarea->asignado->nombre }}</span>
    @endif
    <div class="kanban__card-footer">
        @if($tarea->fecha_limite)
        <span class="kanban__card-fecha">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect x="3" y="4" width="18" height="18" rx="2" />
                <path d="M16 2v4M8 2v4M3 10h18" />
            </svg>
            {{ $tarea->fecha_limite->format('d M') }}
        </span>
        @endif
        @if($tarea->comentarios->count() > 0)
        <span class="kanban__card-comments">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z" />
            </svg>
            {{ $tarea->comentarios->count() }}
        </span>
        @endif
    </div>
</div>