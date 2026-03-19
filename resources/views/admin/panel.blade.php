@extends('admin.layouts.app')

@section('titulo', 'Dashboard')

@section('contenido')
<div class="dashboard-cards">
    {{-- Tareas --}}
    <div class="dash-card">
        <div class="dash-card__header">
            <div class="dash-card__icon dash-card__icon--tareas">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>
                    <rect x="9" y="3" width="6" height="4" rx="1"/><path d="M9 14l2 2 4-4"/></svg>
            </div>
        </div>
        <div class="dash-card__value">{{ $totalTareas }}</div>
        <div class="dash-card__label">Tareas totales</div>
    </div>

    {{-- Usuarios --}}
    <div class="dash-card">
        <div class="dash-card__header">
            <div class="dash-card__icon dash-card__icon--usuarios">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
            </div>
        </div>
        <div class="dash-card__value">{{ $totalUsuarios }}</div>
        <div class="dash-card__label">Usuarios registrados</div>
    </div>

    {{-- Ingresos --}}
    <div class="dash-card">
        <div class="dash-card__header">
            <div class="dash-card__icon dash-card__icon--ingresos">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24"><path d="M17.2 7A7 7 0 0 0 7 12a7 7 0 0 0 10.2 5M5 10h8M5 14h8"/></svg>
            </div>
        </div>
        <div class="dash-card__value">{{ number_format($totalIngresos, 2, ',', '.') }} €</div>
        <div class="dash-card__label">Ingresos totales</div>
    </div>

    {{-- Gastos --}}
    <div class="dash-card">
        <div class="dash-card__header">
            <div class="dash-card__icon dash-card__icon--gastos">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24"><path d="M17.2 7A7 7 0 0 0 7 12a7 7 0 0 0 10.2 5M5 10h8M5 14h8"/></svg>
            </div>
        </div>
        <div class="dash-card__value">{{ number_format($totalGastos, 2, ',', '.') }} €</div>
        <div class="dash-card__label">Gastos totales</div>
    </div>

    {{-- Demos --}}
    <div class="dash-card">
        <div class="dash-card__header">
            <div class="dash-card__icon dash-card__icon--demos">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2"/>
                    <path d="M8 21h8M12 17v4"/></svg>
            </div>
        </div>
        <div class="dash-card__value">{{ $totalDemos }}</div>
        <div class="dash-card__label">Demos</div>
    </div>
</div>

<div class="placeholder-msg">
    <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5"
         viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>
    <h3>Bienvenido al panel de administración</h3>
    <p>Selecciona una sección en el menú lateral para empezar a trabajar.</p>
</div>
@endsection
