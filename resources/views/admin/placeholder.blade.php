@extends('admin.layouts.app')

@section('titulo', $seccion)

@section('contenido')
<div class="placeholder-msg">
    <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5"
        viewBox="0 0 24 24">
        <path d="M12 6v6l4 2" />
        <circle cx="12" cy="12" r="10" />
    </svg>
    <h3>{{ $seccion }}</h3>
    <p>Este módulo se implementará en una fase posterior.</p>
</div>
@endsection