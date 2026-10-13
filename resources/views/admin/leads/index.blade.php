@extends('admin.layouts.app')
@section('titulo', 'Oportunidades')
@push('css')<link rel="stylesheet" href="{{ asset('css/leads.css') }}">@endpush
@section('contenido')
@if(session('success'))<p class="lead-notice" role="status">{{ session('success') }}</p>@endif
@if(!config('leads.captura'))<p class="lead-notice">La captura automática está desactivada. Su activación queda pendiente de configurar privacidad y conservación.</p>@endif
@if(!$ready)
    <div class="lead-box"><h3>Seguimiento preparado</h3><p>Para habilitar esta sección, aplicar la migración de oportunidades durante el despliegue. La web pública sigue funcionando sin esta tabla.</p></div>
@else
    <form method="GET" class="lead-filters">
        <label>Estado<select name="estado"><option value="">Todos</option>@foreach(config('leads.estados') as $key => $label)<option value="{{ $key }}" @selected(($filters['estado'] ?? '') === $key)>{{ $label }}</option>@endforeach</select></label>
        <label>Origen<select name="origen"><option value="">Todos</option>@foreach(config('leads.origenes') as $key => $label)<option value="{{ $key }}" @selected(($filters['origen'] ?? '') === $key)>{{ $label }}</option>@endforeach</select></label>
        <button class="lead-button" type="submit">Filtrar</button><a href="{{ route('admin.leads') }}">Limpiar</a>
    </form>
    <div class="lead-metrics">
        <div class="lead-box"><strong>{{ $metrics->total }}</strong><span>Oportunidades del filtro</span></div>
        <div class="lead-box"><strong>{{ $metrics->ganados ?? 0 }}</strong><span>Ventas ganadas</span></div>
        <div class="lead-box"><strong>{{ ($metrics->ganados + $metrics->perdidos) ? number_format(100 * $metrics->ganados / ($metrics->ganados + $metrics->perdidos), 1, ',', '.') . ' %' : '—' }}</strong><span>Cierre entre ganadas y perdidas</span></div>
        <div class="lead-box"><strong>{{ $metrics->con_coste ? number_format($metrics->contribucion, 2, ',', '.') . ' €' : '—' }}</strong><span>Contribución de {{ $metrics->con_coste ?? 0 }} ventas con coste registrado</span></div>
    </div>
    <p class="lead-help">La contribución es venta menos coste registrado; las ventas sin coste no se incluyen. Los filtros afectan a las métricas.</p>
    <div class="lead-list">
        @forelse($leads as $lead)
            <article class="lead-box lead-row">
                <div><a href="{{ route('admin.leads.show', $lead) }}"><strong>{{ $lead->nombre }}</strong></a><p>{{ $lead->email }}</p><small>{{ $lead->created_at->copy()->timezone('Europe/Madrid')->format('d/m/Y H:i') }}</small></div>
                <div><strong>{{ config('leads.estados.' . $lead->estado) }}</strong><p>{{ config('leads.origenes.' . $lead->origen) }}</p></div>
                <div><span>{{ config('comercial.packs.' . $lead->pack . '.nombre', ucfirst($lead->tipo)) }}</span><p>Correo: {{ $lead->correo_estado }}</p><a href="{{ route('admin.leads.show', $lead) }}">Abrir oportunidad →</a></div>
            </article>
        @empty
            <div class="lead-box"><p>No hay oportunidades con estos filtros.</p></div>
        @endforelse
    </div>
    <nav class="lead-pagination" aria-label="Paginación">
        @if($leads->previousPageUrl())<a href="{{ $leads->previousPageUrl() }}">← Anterior</a>@endif
        <span>Página {{ $leads->currentPage() }} de {{ $leads->lastPage() }}</span>
        @if($leads->nextPageUrl())<a href="{{ $leads->nextPageUrl() }}">Siguiente →</a>@endif
    </nav>
@endif
@endsection
