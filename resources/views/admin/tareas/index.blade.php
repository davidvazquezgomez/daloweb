@extends('admin.layouts.app')

@section('titulo', 'Tareas')

@push('css')
<link rel="stylesheet" href="{{ asset('css/kanban.css') }}">
@endpush

@section('contenido')
<div class="kanban-header">
    <h2>Tareas</h2>
    <button type="button" class="btn btn--accent" onclick="Kanban.abrirModalCrear()">+ Nueva tarea</button>
</div>

<div class="kanban" id="kanban">
    {{-- Columna Pendiente --}}
    <div class="kanban__col" data-estado="pendiente">
        <div class="kanban__col-header kanban__col-header--pendiente">
            <span class="kanban__col-dot kanban__col-dot--pendiente"></span>
            Pendiente
            <span class="kanban__col-count" data-count="pendiente">{{ $pendientes->count() }}</span>
        </div>
        <div class="kanban__cards" data-estado="pendiente">
            @foreach($pendientes as $tarea)
            @include('admin.tareas._card', ['tarea' => $tarea])
            @endforeach
        </div>
    </div>

    {{-- Columna En Progreso --}}
    <div class="kanban__col" data-estado="en_progreso">
        <div class="kanban__col-header kanban__col-header--progreso">
            <span class="kanban__col-dot kanban__col-dot--progreso"></span>
            En Progreso
            <span class="kanban__col-count" data-count="en_progreso">{{ $enProgreso->count() }}</span>
        </div>
        <div class="kanban__cards" data-estado="en_progreso">
            @foreach($enProgreso as $tarea)
            @include('admin.tareas._card', ['tarea' => $tarea])
            @endforeach
        </div>
    </div>

    {{-- Columna Completado --}}
    <div class="kanban__col" data-estado="completado">
        <div class="kanban__col-header kanban__col-header--completado">
            <span class="kanban__col-dot kanban__col-dot--completado"></span>
            Completado
            <span class="kanban__col-count" data-count="completado">{{ $completadas->count() }}</span>
        </div>
        <div class="kanban__cards" data-estado="completado">
            @foreach($completadas as $tarea)
            @include('admin.tareas._card', ['tarea' => $tarea])
            @endforeach
        </div>
    </div>
</div>

{{-- Modal Crear / Editar tarea --}}
<div class="modal-overlay" id="modalTarea" style="display:none">
    <div class="modal">
        <div class="modal__header">
            <h3 id="modalTareaTitle">Nueva tarea</h3>
            <button type="button" class="modal__close" onclick="Kanban.cerrarModal()">&times;</button>
        </div>
        <form id="formTarea" onsubmit="return Kanban.guardarTarea(event)">
            <input type="hidden" id="tareaId" value="">
            <div class="modal__body">
                <div class="form-group">
                    <label for="tareaTitulo">Título *</label>
                    <input type="text" id="tareaTitulo" required maxlength="255" class="form-input">
                </div>
                <div class="form-group">
                    <label for="tareaDescripcion">Descripción</label>
                    <textarea id="tareaDescripcion" rows="3" maxlength="5000" class="form-input"></textarea>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="tareaEstado">Estado</label>
                        <select id="tareaEstado" class="form-input">
                            <option value="pendiente">Pendiente</option>
                            <option value="en_progreso">En Progreso</option>
                            <option value="completado">Completado</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tareaAsignado">Asignado a</label>
                        <select id="tareaAsignado" class="form-input">
                            <option value="">Sin asignar</option>
                            @foreach($usuarios as $u)
                            <option value="{{ $u->id }}">{{ $u->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="tareaFecha">Fecha límite</label>
                    <input type="date" id="tareaFecha" class="form-input">
                </div>
            </div>
            <div class="modal__footer">
                <button type="button" class="btn btn--ghost" onclick="Kanban.cerrarModal()">Cancelar</button>
                <button type="submit" class="btn btn--accent" id="btnGuardarTarea">Crear</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Detalle tarea (con comentarios) --}}
<div class="modal-overlay" id="modalDetalle" style="display:none">
    <div class="modal modal--detalle">
        <div class="modal__header">
            <h3 id="detalleTitulo"></h3>
            <div class="modal__actions">
                <button type="button" class="btn btn--sm btn--ghost" onclick="Kanban.editarDesdeDetalle()" title="Editar">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" />
                        <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                    </svg>
                </button>
                <button type="button" class="btn btn--sm btn--danger" onclick="Kanban.eliminarTarea()" title="Eliminar">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2" />
                    </svg>
                </button>
                <button type="button" class="modal__close" onclick="Kanban.cerrarDetalle()">&times;</button>
            </div>
        </div>
        <div class="modal__body">
            <div class="detalle-meta">
                <span class="detalle-badge" id="detalleEstado"></span>
                <span id="detalleAsignado"></span>
                <span id="detalleFecha"></span>
            </div>
            <p id="detalleDescripcion" class="detalle-desc"></p>

            <hr class="detalle-sep">

            <div class="comentarios">
                <h4>Comentarios</h4>
                <div id="listaComentarios"></div>
                <form class="comentario-form" onsubmit="return Kanban.agregarComentario(event)">
                    <textarea id="nuevoComentario" placeholder="Escribe un comentario..." rows="2" maxlength="2000" class="form-input" required></textarea>
                    <button type="submit" class="btn btn--accent btn--sm">Enviar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
<script src="{{ asset('js/kanban.js') }}"></script>
@endpush