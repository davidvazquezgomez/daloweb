@extends('admin.layouts.app')
@section('titulo', 'Rentabilidad de presupuestos')
@push('css')<link rel="stylesheet" href="{{ asset('css/leads.css') }}">@endpush
@section('contenido')
<p class="lead-help">Estima el precio antes de impuestos a partir de tus costes. Incluye administración, revisiones y pruebas en las horas; reparte los gastos generales en el coste por hora. Los cálculos no se guardan ni modifican los precios públicos.</p>
<div class="lead-detail">
    <section class="lead-box">
        <h3>Costes del alcance presupuestado</h3>
        <p class="lead-help">Completa todos los costes. Introduce 0 solo cuando sepas que no hay gasto. Usa el mismo periodo y alcance para soporte y renovaciones.</p>
        <form method="POST" action="{{ route('admin.rentabilidad') }}" class="lead-edit">
            @csrf
            @php
                $campos = ['horas' => ['Horas de trabajo', 100000], 'coste_hora' => ['Coste interno por hora (€)', 100000], 'directos' => ['Costes externos y renovaciones incluidos (€)', 1000000], 'soporte' => ['Coste del soporte comprometido (€)', 1000000], 'captacion' => ['Coste de captación por venta (€)', 1000000], 'margen' => ['Margen objetivo sobre el precio (%)', 99.9], 'precio_neto' => ['Precio a evaluar antes de impuestos (€), opcional', 100000000]];
            @endphp
            @foreach($campos as $campo => [$etiqueta, $maximo])
                @php $valor = old($campo, $datos[$campo] ?? ''); $valor = is_scalar($valor) ? $valor : ''; @endphp
                <label for="{{ $campo }}">{{ $etiqueta }}
                    <input id="{{ $campo }}" name="{{ $campo }}" type="number" inputmode="decimal" min="0" max="{{ $maximo }}" step="0.01" value="{{ $valor }}" @required($campo !== 'precio_neto') @if($errors->has($campo)) aria-invalid="true" aria-describedby="error-{{ $campo }}" @endif>
                </label>
                @error($campo)<p id="error-{{ $campo }}" role="alert">{{ $message }}</p>@enderror
            @endforeach
            <p class="lead-help">El margen es (precio − coste) / precio. Un margen del 40 % requiere dividir el coste entre 0,60.</p>
            <button class="lead-button" type="submit">Calcular presupuesto</button>
            <a href="{{ route('admin.rentabilidad') }}">Limpiar cálculo</a>
        </form>
    </section>
    <section class="lead-box" aria-labelledby="resultado-titulo">
        <h3 id="resultado-titulo">Resultado antes de impuestos</h3>
        @if($resultado)
            <dl class="pricing-results">
                <dt>Coste total previsto</dt><dd>{{ number_format($resultado['coste'], 2, ',', '.') }} €</dd>
                <dt>Precio mínimo para el margen objetivo</dt><dd>{{ number_format($resultado['minimo'], 2, ',', '.') }} €</dd>
                @if($resultado['contribucion'] !== null)
                    <dt>Contribución al precio evaluado</dt><dd>{{ number_format($resultado['contribucion'], 2, ',', '.') }} €</dd>
                    <dt>Margen al precio evaluado</dt><dd>{{ $resultado['margen'] !== null ? number_format($resultado['margen'], 2, ',', '.') . ' %' : 'No calculable con precio cero' }}</dd>
                    <dt>Diferencia frente al mínimo</dt><dd>{{ number_format($resultado['diferencia'], 2, ',', '.') }} €</dd>
                @endif
            </dl>
            <p class="lead-help">El mínimo se redondea al céntimo superior. La contribución depende de que hayas incluido todos los costes; no equivale al beneficio contable.</p>
        @else
            <p>Completa los costes para obtener una estimación.</p>
        @endif
        <h3>Precios de partida publicados</h3>
        <ul>@foreach(config('comercial.packs') as $pack)<li>{{ $pack['nombre'] }}: {{ number_format($pack['precio'], 0, ',', '.') }} €</li>@endforeach</ul>
        <p class="lead-help">Pendiente confirmar su tratamiento fiscal. Para compararlos, introduce arriba el importe neto que corresponda y el mismo alcance.</p>
        <h3>Antes de enviar el presupuesto</h3>
        <ul><li>Confirmar entregables, exclusiones y revisiones.</li><li>Detallar impuestos, pagos y gastos recurrentes.</li><li>Acordar soporte, cobertura y propiedad de los activos.</li><li>Fijar calendario desde la recepción de materiales.</li></ul>
    </section>
</div>
@endsection
