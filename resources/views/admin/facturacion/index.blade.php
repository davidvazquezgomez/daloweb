@extends('admin.layouts.app')

@section('titulo', 'Facturación')

@push('css')
<link rel="stylesheet" href="{{ asset('css/facturacion.css') }}">
@endpush

@section('contenido')
<div class="facturacion-header">
    <h2>Facturación</h2>
    <div class="facturacion-header__actions">
        <select id="filtroAnio" class="form-input form-input--inline" onchange="Facturacion.cambiarAnio(this.value)">
            @foreach($aniosDisponibles as $a)
            <option value="{{ $a }}" {{ $a == $anio ? 'selected' : '' }}>{{ $a }}</option>
            @endforeach
        </select>
    </div>
</div>

{{-- Resumen --}}
<div class="facturacion-resumen">
    <div class="resumen-card resumen-card--ingresos">
        <span class="resumen-card__label">Ingresos</span>
        <span class="resumen-card__value" id="totalIngresos">{{ number_format($totalIngresos, 2, ',', '.') }} €</span>
    </div>
    <div class="resumen-card resumen-card--gastos">
        <span class="resumen-card__label">Gastos</span>
        <span class="resumen-card__value" id="totalGastos">{{ number_format($totalGastos, 2, ',', '.') }} €</span>
    </div>
    <div class="resumen-card resumen-card--balance">
        <span class="resumen-card__label">Balance</span>
        <span class="resumen-card__value" id="totalBalance">{{ number_format($totalIngresos - $totalGastos, 2, ',', '.') }} €</span>
    </div>
</div>

{{-- Gráfica --}}
<div class="facturacion-chart">
    <canvas id="chartMensual" height="260"></canvas>
</div>

{{-- Tablas --}}
<div class="facturacion-grid">
    {{-- Ingresos --}}
    <div class="facturacion-seccion">
        <div class="facturacion-seccion__header">
            <h3>Ingresos</h3>
            <button type="button" class="btn btn--accent btn--sm" onclick="Facturacion.abrirModal('ingreso')">+ Nuevo</button>
        </div>
        <div class="tabla-wrapper">
            <table class="tabla">
                <thead>
                    <tr>
                        <th>Concepto</th>
                        <th>Cliente</th>
                        <th>Tipo</th>
                        <th>Importe</th>
                        <th>Fecha</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="tablaIngresos">
                    @forelse($ingresos as $i)
                    <tr data-id="{{ $i->id }}">
                        <td>{{ $i->concepto }}</td>
                        <td class="text-soft">{{ $i->cliente ? trim($i->cliente->nombre . ' ' . $i->cliente->apellido) : '-' }}</td>
                        <td><span class="badge badge--tipo">{{ $i->tipo }}</span></td>
                        <td class="importe importe--positivo">{{ number_format($i->importe, 2, ',', '.') }} €</td>
                        <td class="text-soft">{{ $i->fecha->format('d M Y') }}</td>
                        <td class="acciones-cell">
                            <button type="button" class="btn btn--sm btn--ghost" onclick="Facturacion.editar('ingreso', {{ $i->id }})" title="Editar">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" />
                                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                                </svg>
                            </button>
                            <button type="button" class="btn btn--sm btn--danger" onclick="Facturacion.eliminar('ingreso', {{ $i->id }})" title="Eliminar">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-soft">Sin ingresos registrados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Gastos --}}
    <div class="facturacion-seccion">
        <div class="facturacion-seccion__header">
            <h3>Gastos</h3>
            <button type="button" class="btn btn--accent btn--sm" onclick="Facturacion.abrirModal('gasto')">+ Nuevo</button>
        </div>
        <div class="tabla-wrapper">
            <table class="tabla">
                <thead>
                    <tr>
                        <th>Concepto</th>
                        <th>Categoría</th>
                        <th>Importe</th>
                        <th>Fecha</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="tablaGastos">
                    @forelse($gastos as $g)
                    <tr data-id="{{ $g->id }}">
                        <td>
                            {{ $g->concepto }}
                            @if($g->recurrente)
                            <span class="badge badge--recurrente" title="Recurrente">↻</span>
                            @endif
                        </td>
                        <td><span class="badge badge--cat">{{ $g->categoria }}</span></td>
                        <td class="importe importe--negativo">{{ number_format($g->importe, 2, ',', '.') }} €</td>
                        <td class="text-soft">{{ $g->fecha->format('d M Y') }}</td>
                        <td class="acciones-cell">
                            <button type="button" class="btn btn--sm btn--ghost" onclick="Facturacion.editar('gasto', {{ $g->id }})" title="Editar">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" />
                                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                                </svg>
                            </button>
                            <button type="button" class="btn btn--sm btn--danger" onclick="Facturacion.eliminar('gasto', {{ $g->id }})" title="Eliminar">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-soft">Sin gastos registrados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Ingreso --}}
<div class="modal-overlay" id="modalIngreso" style="display:none">
    <div class="modal">
        <div class="modal__header">
            <h3 id="modalIngresoTitle">Nuevo ingreso</h3>
            <button type="button" class="modal__close" onclick="Facturacion.cerrarModal('ingreso')">&times;</button>
        </div>
        <form id="formIngreso" onsubmit="return Facturacion.guardar('ingreso', event)">
            <input type="hidden" id="ingresoId" value="">
            <div class="modal__body">
                <div class="form-group">
                    <label for="ingresoConcepto">Concepto *</label>
                    <input type="text" id="ingresoConcepto" required maxlength="255" class="form-input">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="ingresoClienteId">Cliente</label>
                        <select id="ingresoClienteId" class="form-input">
                            <option value="">— Sin cliente —</option>
                            @foreach($usuarios as $u)
                            <option value="{{ $u->id }}">{{ $u->nombre }} {{ $u->apellido }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="ingresoTipo">Tipo *</label>
                        <select id="ingresoTipo" class="form-input" required>
                            <option value="web">Web</option>
                            <option value="componente">Componente</option>
                            <option value="app">App</option>
                            <option value="reservas">Reservas</option>
                            <option value="medida">A medida</option>
                            <option value="otro">Otro</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="ingresoImporte">Importe (€) *</label>
                        <input type="number" id="ingresoImporte" required min="0.01" step="0.01" class="form-input">
                    </div>
                    <div class="form-group">
                        <label for="ingresoFecha">Fecha *</label>
                        <input type="date" id="ingresoFecha" required class="form-input">
                    </div>
                </div>
                <div class="form-group">
                    <label for="ingresoFactura">Nº Factura</label>
                    <input type="text" id="ingresoFactura" maxlength="50" class="form-input">
                </div>
                <div class="form-group">
                    <label for="ingresoNotas">Notas</label>
                    <textarea id="ingresoNotas" rows="2" maxlength="5000" class="form-input"></textarea>
                </div>
                <div id="ingresoErrores" class="alert alert--danger" style="display:none"></div>
            </div>
            <div class="modal__footer">
                <button type="button" class="btn btn--ghost" onclick="Facturacion.cerrarModal('ingreso')">Cancelar</button>
                <button type="submit" class="btn btn--accent" id="btnGuardarIngreso">Crear</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Gasto --}}
<div class="modal-overlay" id="modalGasto" style="display:none">
    <div class="modal">
        <div class="modal__header">
            <h3 id="modalGastoTitle">Nuevo gasto</h3>
            <button type="button" class="modal__close" onclick="Facturacion.cerrarModal('gasto')">&times;</button>
        </div>
        <form id="formGasto" onsubmit="return Facturacion.guardar('gasto', event)">
            <input type="hidden" id="gastoId" value="">
            <div class="modal__body">
                <div class="form-group">
                    <label for="gastoConcepto">Concepto *</label>
                    <input type="text" id="gastoConcepto" required maxlength="255" class="form-input">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="gastoCategoria">Categoría *</label>
                        <select id="gastoCategoria" class="form-input" required>
                            <option value="dominio">Dominio</option>
                            <option value="servidor">Servidor</option>
                            <option value="software">Software</option>
                            <option value="otros">Otros</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="gastoImporte">Importe (€) *</label>
                        <input type="number" id="gastoImporte" required min="0.01" step="0.01" class="form-input">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group" id="gastoFechaGroup">
                        <label for="gastoFecha">Fecha *</label>
                        <input type="date" id="gastoFecha" required class="form-input">
                    </div>
                    <div class="form-group form-group--check">
                        <label class="check-label">
                            <input type="checkbox" id="gastoRecurrente" onchange="Facturacion.toggleRecurrente()">
                            Gasto recurrente <small class="text-soft">(se crea uno por cada mes del año)</small>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="gastoNotas">Notas</label>
                    <textarea id="gastoNotas" rows="2" maxlength="5000" class="form-input"></textarea>
                </div>
                <div id="gastoErrores" class="alert alert--danger" style="display:none"></div>
            </div>
            <div class="modal__footer">
                <button type="button" class="btn btn--ghost" onclick="Facturacion.cerrarModal('gasto')">Cancelar</button>
                <button type="submit" class="btn btn--accent" id="btnGuardarGasto">Crear</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
    const FACTURACION_DATA = {
        mensual: @json($mensual),
        gastos: @json($gastos),
        ingresos: @json($ingresos),
        usuarios: @json($usuarios),
    };
</script>
<script src="{{ asset('js/facturacion.js') }}"></script>
@endpush