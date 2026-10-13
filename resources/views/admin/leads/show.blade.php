@extends('admin.layouts.app')
@section('titulo', 'Detalle de oportunidad')
@push('css')<link rel="stylesheet" href="{{ asset('css/leads.css') }}">@endpush
@section('contenido')
@php
    $valorAnterior = function ($campo, $valor) {
        $valor = old($campo, $valor);
        return is_scalar($valor) ? $valor : '';
    };
@endphp
<a href="{{ route('admin.leads') }}">← Volver a oportunidades</a>
@if(session('success'))<p class="lead-notice" role="status">{{ session('success') }}</p>@endif
@if($errors->any())<div class="lead-notice" role="alert">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif
<div class="lead-detail">
    <section class="lead-box">
        <h3>{{ $lead->nombre }}</h3><p><a href="mailto:{{ $lead->email }}">{{ $lead->email }}</a></p>
        <p>Servicio: {{ ucfirst($lead->tipo) }} @if($lead->pack) · {{ config('comercial.packs.' . $lead->pack . '.nombre') }} @endif</p>
        <p>Correo: {{ $lead->correo_estado }}</p>
        <h4>Solicitud original</h4><p class="lead-message">{{ $lead->mensaje }}</p>
        <p>Contribución: {{ $lead->margen !== null ? number_format($lead->margen, 2, ',', '.') . ' €' : 'Pendiente de venta ganada y coste' }}</p>
    </section>
    <form class="lead-box lead-edit" method="POST" action="{{ route('admin.leads.update', $lead) }}">
        @csrf @method('PUT')
        <label>Estado<select name="estado">@foreach(config('leads.estados') as $key => $label)<option value="{{ $key }}" @selected(old('estado', $lead->estado) === $key)>{{ $label }}</option>@endforeach</select></label>
        <label>Origen<select name="origen">@foreach(config('leads.origenes') as $key => $label)<option value="{{ $key }}" @selected(old('origen', $lead->origen) === $key)>{{ $label }}</option>@endforeach</select></label>
        <label>Presupuesto (€)<input type="number" name="presupuesto" min="0" step="0.01" value="{{ $valorAnterior('presupuesto', $lead->presupuesto) }}"></label>
        <label>Venta (€)<input type="number" name="venta" min="0" step="0.01" value="{{ $valorAnterior('venta', $lead->venta) }}"></label>
        <label>Coste (€)<input type="number" name="coste" min="0" step="0.01" value="{{ $valorAnterior('coste', $lead->coste) }}"></label>
        <p class="lead-help">Registra importes comparables con el mismo criterio de impuestos. Deja el coste vacío si no lo conoces; cero significa un coste confirmado de 0 €.</p>
        <label>Notas internas<textarea name="notas" rows="5" maxlength="5000">{{ $valorAnterior('notas', $lead->notas) }}</textarea></label>
        <button type="submit" class="lead-button">Guardar seguimiento</button>
    </form>
</div>
<details class="lead-box lead-delete"><summary>Eliminar oportunidad</summary><p>Elimina los datos de esta oportunidad. No modifica las facturas ni los correos enviados.</p><form action="{{ route('admin.leads.destroy', $lead) }}" method="POST">@csrf @method('DELETE')<button class="lead-button lead-button--danger" type="submit">Eliminar definitivamente</button></form></details>
@endsection
