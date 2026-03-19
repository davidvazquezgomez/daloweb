@extends('admin.layouts.app')

@section('titulo', 'Usuarios')

@push('css')
<link rel="stylesheet" href="{{ asset('css/usuarios.css') }}">
@endpush

@section('contenido')
<div class="usuarios-header">
    <h2>Usuarios</h2>
    <button type="button" class="btn btn--accent" onclick="Usuarios.abrirModalCrear()">+ Nuevo usuario</button>
</div>

{{-- Barra de búsqueda --}}
<div class="usuarios-search">
    <input type="text" id="busquedaUsuarios" placeholder="Buscar por nombre o correo..." class="form-input" autocomplete="off">
</div>

{{-- Mensajes flash --}}
@if(session('exito'))
<div class="alert alert--success">{{ session('exito') }}</div>
@endif
@if(session('error'))
<div class="alert alert--danger">{{ session('error') }}</div>
@endif

{{-- Tabla de usuarios --}}
<div class="tabla-wrapper">
    <table class="tabla">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Último acceso</th>
                <th>Registro</th>
                <th></th>
            </tr>
        </thead>
        <tbody id="tablaUsuariosBody">
            @forelse($usuarios as $u)
            <tr>
                <td>
                    <a href="{{ route('admin.usuarios.show', $u->id) }}" class="usuario-link">
                        <span class="usuario-avatar">{{ strtoupper(mb_substr($u->nombre, 0, 1)) }}</span>
                        {{ $u->nombre }}
                    </a>
                </td>
                <td class="text-soft">{{ $u->correo }}</td>
                <td>
                    <span class="badge badge--{{ $u->rol->value }}">{{ $u->rol->value === 'admin' ? 'Admin' : 'Usuario' }}</span>
                </td>
                <td class="text-soft">{{ $u->ultimo_acceso ? $u->ultimo_acceso->format('d M Y H:i') : 'Nunca' }}</td>
                <td class="text-soft">{{ $u->creado_en ? $u->creado_en->format('d M Y') : '-' }}</td>
                <td>
                    @if($u->id !== auth()->id())
                    <a href="{{ route('admin.usuarios.show', $u->id) }}" class="btn btn--sm btn--ghost" title="Editar">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" />
                            <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                        </svg>
                    </a>
                    <button type="button" class="btn btn--sm btn--danger" onclick="Usuarios.eliminar({{ $u->id }}, '{{ addslashes($u->nombre) }}')" title="Eliminar">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2" />
                        </svg>
                    </button>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center text-soft">No se encontraron usuarios.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Paginación --}}
<div id="paginacionUsuarios">
@if($usuarios->hasPages())
<div class="paginacion">
    {{ $usuarios->links('admin.usuarios._paginacion') }}
</div>
@endif
</div>

{{-- Modal Crear usuario --}}
<div class="modal-overlay" id="modalUsuario" style="display:none">
    <div class="modal">
        <div class="modal__header">
            <h3>Nuevo usuario</h3>
            <button type="button" class="modal__close" onclick="Usuarios.cerrarModal()">&times;</button>
        </div>
        <form id="formUsuario" onsubmit="return Usuarios.crear(event)">
            <div class="modal__body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="uNombre">Nombre *</label>
                        <input type="text" id="uNombre" required maxlength="255" class="form-input">
                    </div>
                    <div class="form-group">
                        <label for="uApellido">Apellido</label>
                        <input type="text" id="uApellido" maxlength="255" class="form-input">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="uCorreo">Correo electrónico *</label>
                        <input type="email" id="uCorreo" required maxlength="255" class="form-input">
                    </div>
                    <div class="form-group">
                        <label for="uContrasena">Contraseña * <small>(mín. 8)</small></label>
                        <input type="password" id="uContrasena" required minlength="8" maxlength="255" class="form-input">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="uDniCif">DNI / CIF</label>
                        <input type="text" id="uDniCif" maxlength="20" class="form-input">
                    </div>
                    <div class="form-group">
                        <label for="uTelefono">Teléfono</label>
                        <input type="text" id="uTelefono" maxlength="20" class="form-input">
                    </div>
                </div>
                <div class="form-group">
                    <label for="uDireccion">Dirección</label>
                    <input type="text" id="uDireccion" maxlength="500" class="form-input">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="uCodigoPostal">Código postal</label>
                        <input type="text" id="uCodigoPostal" maxlength="10" class="form-input">
                    </div>
                    <div class="form-group">
                        <label for="uCiudad">Ciudad</label>
                        <input type="text" id="uCiudad" maxlength="100" class="form-input">
                    </div>
                    <div class="form-group">
                        <label for="uProvincia">Provincia</label>
                        <input type="text" id="uProvincia" maxlength="100" class="form-input">
                    </div>
                </div>
                <div class="form-group">
                    <label for="uRol">Rol</label>
                    <select id="uRol" class="form-input">
                        <option value="usuario">Usuario</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div id="usuarioErrores" class="alert alert--danger" style="display:none"></div>
            </div>
            <div class="modal__footer">
                <button type="button" class="btn btn--ghost" onclick="Usuarios.cerrarModal()">Cancelar</button>
                <button type="submit" class="btn btn--accent">Crear usuario</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script>const AUTH_ID = {{ auth()->id() }};</script>
<script src="{{ asset('js/usuarios.js') }}"></script>
@endpush