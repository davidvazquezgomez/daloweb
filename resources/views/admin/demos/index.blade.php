@extends('admin.layouts.app')

@section('titulo', 'Demos')

@push('css')
<link rel="stylesheet" href="{{ asset('css/demos.css') }}">
@endpush

@section('contenido')
<div class="demos-header">
    <h2>Demos</h2>
    <div class="demos-header__actions">
        <button type="button" class="btn btn--ghost btn--sm" onclick="Demos.sincronizar()" id="btnSincronizar">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M23 4v6h-6M1 20v-6h6" />
                <path d="M3.51 9a9 9 0 0114.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0020.49 15" />
            </svg>
            Sincronizar
        </button>
        <button type="button" class="btn btn--accent btn--sm" onclick="Demos.abrirModal()">+ Nueva demo</button>
    </div>
</div>

{{-- Galería --}}
<div class="demos-grid" id="demosGrid">
    @forelse($demos as $d)
    <div class="demo-card" data-id="{{ $d->id }}">
        <div class="demo-card__thumb">
            @if($d->miniatura)
            <img src="{{ asset($d->miniatura) }}" alt="{{ $d->titulo }}">
            @else
            <div class="demo-card__placeholder">
                <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <rect x="2" y="3" width="20" height="14" rx="2" />
                    <path d="M8 21h8M12 17v4" />
                </svg>
            </div>
            @endif
            <span class="demo-card__badge demo-card__badge--{{ $d->visibilidad }}">
                {{ $d->visibilidad === 'publica' ? 'Pública' : 'Privada' }}
            </span>
        </div>
        <div class="demo-card__body">
            <h4 class="demo-card__title">{{ $d->titulo }}</h4>
            <span class="badge badge--tipo">{{ $d->tipo }}</span>
            @if($d->tecnologias)
            <div class="demo-card__techs">
                @foreach($d->tecnologias as $tech)
                <span class="demo-card__tech">{{ $tech }}</span>
                @endforeach
            </div>
            @endif
        </div>
        <div class="demo-card__actions">
            <a href="/demo/{{ $d->slug }}" target="_blank" class="btn btn--sm btn--ghost" title="Ver demo">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6" />
                    <polyline points="15 3 21 3 21 9" />
                    <line x1="10" y1="14" x2="21" y2="3" />
                </svg>
            </a>
            <button type="button" class="btn btn--sm btn--ghost" onclick="Demos.editar({{ $d->id }})" title="Editar">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" />
                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                </svg>
            </button>
            <button type="button" class="btn btn--sm {{ $d->visibilidad === 'publica' ? 'btn--success' : 'btn--ghost' }}" onclick="Demos.toggleVisibilidad({{ $d->id }})" title="Cambiar visibilidad">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                    <circle cx="12" cy="12" r="3" />
                </svg>
            </button>
            <button type="button" class="btn btn--sm btn--danger" onclick="Demos.eliminar({{ $d->id }}, '{{ addslashes($d->titulo) }}')" title="Eliminar">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2" />
                </svg>
            </button>
        </div>
    </div>
    @empty
    <p class="demos-empty text-soft" id="demosEmpty">No hay demos registradas.</p>
    @endforelse
</div>

{{-- Modal crear/editar --}}
<div class="modal-overlay" id="modalDemo" style="display:none">
    <div class="modal">
        <div class="modal__header">
            <h3 id="modalDemoTitle">Nueva demo</h3>
            <button type="button" class="modal__close" onclick="Demos.cerrarModal()">&times;</button>
        </div>
        <form id="formDemo" onsubmit="return Demos.guardar(event)">
            <input type="hidden" id="demoId" value="">
            <div class="modal__body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="demoTitulo">Título *</label>
                        <input type="text" id="demoTitulo" required maxlength="255" class="form-input">
                    </div>
                    <div class="form-group">
                        <label for="demoSlug">Slug *</label>
                        <input type="text" id="demoSlug" required maxlength="255" class="form-input" pattern="[a-z0-9\-]+" title="Solo minúsculas, números y guiones">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="demoTipo">Tipo *</label>
                        <select id="demoTipo" class="form-input" required>
                            <option value="web">Web</option>
                            <option value="componente">Componente</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="demoVisibilidad">Visibilidad *</label>
                        <select id="demoVisibilidad" class="form-input" required>
                            <option value="privada">Privada</option>
                            <option value="publica">Pública</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="demoCarpeta">Carpeta en /demo/ *</label>
                    <input type="text" id="demoCarpeta" required maxlength="500" class="form-input" placeholder="nombre-carpeta">
                </div>
                <div class="form-group">
                    <label for="demoMiniatura">Ruta miniatura</label>
                    <input type="text" id="demoMiniatura" maxlength="500" class="form-input" placeholder="img/demos/mi-demo.png">
                </div>
                <div class="form-group">
                    <label for="demoTecnologias">Tecnologías <small class="text-soft">(separadas por coma)</small></label>
                    <input type="text" id="demoTecnologias" class="form-input" placeholder="HTML, CSS, JavaScript">
                </div>
                <div class="form-group">
                    <label for="demoDescripcion">Descripción</label>
                    <textarea id="demoDescripcion" rows="3" maxlength="5000" class="form-input"></textarea>
                </div>
                <div id="demoErrores" class="alert alert--danger" style="display:none"></div>
            </div>
            <div class="modal__footer">
                <button type="button" class="btn btn--ghost" onclick="Demos.cerrarModal()">Cancelar</button>
                <button type="submit" class="btn btn--accent" id="btnGuardarDemo">Crear</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script>
    const DEMOS_DATA = @json($demos);
</script>
<script src="{{ asset('js/demos.js') }}"></script>
@endpush