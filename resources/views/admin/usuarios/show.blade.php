@extends('admin.layouts.app')

@section('titulo', $usuario->nombre)

@push('css')
<link rel="stylesheet" href="{{ asset('css/usuarios.css') }}">
@endpush

@section('contenido')
<div class="usuarios-header">
    <a href="{{ route('admin.usuarios') }}" class="btn btn--ghost btn--sm">&larr; Volver</a>
    <button type="button" class="btn btn--accent btn--sm" onclick="Usuarios.abrirModalEditar()">Editar usuario</button>
</div>

<div class="perfil">
    <div class="perfil__header">
        <div class="perfil__avatar">{{ strtoupper(mb_substr($usuario->nombre, 0, 1)) }}</div>
        <div class="perfil__info">
            <h2>{{ $usuario->nombre }} {{ $usuario->apellido }}</h2>
            <p class="text-soft">{{ $usuario->correo }}</p>
            <span class="badge badge--{{ $usuario->rol->value }}">{{ $usuario->rol->value === 'admin' ? 'Admin' : 'Usuario' }}</span>
        </div>
        @if($usuario->id !== auth()->id())
        <button type="button" class="btn btn--danger btn--sm perfil__delete" onclick="Usuarios.eliminar({{ $usuario->id }}, '{{ addslashes($usuario->nombre) }}')">
            Eliminar usuario
        </button>
        @endif
    </div>

    <div class="perfil__stats">
        <div class="perfil__stat">
            <span class="perfil__stat-value">{{ $usuario->creado_en ? $usuario->creado_en->format('d M Y') : '-' }}</span>
            <span class="perfil__stat-label">Fecha de registro</span>
        </div>
        <div class="perfil__stat">
            <span class="perfil__stat-value">{{ $usuario->ultimo_acceso ? $usuario->ultimo_acceso->format('d M Y H:i') : 'Nunca' }}</span>
            <span class="perfil__stat-label">Último acceso</span>
        </div>
        @if($usuario->rol === \App\Enums\RolUsuario::Admin)
        <div class="perfil__stat">
            <span class="perfil__stat-value">{{ $tareasAsignadas }}</span>
            <span class="perfil__stat-label">Tareas asignadas</span>
        </div>
        <div class="perfil__stat">
            <span class="perfil__stat-value">{{ $tareasCreadas }}</span>
            <span class="perfil__stat-label">Tareas creadas</span>
        </div>
        @endif
    </div>
</div>

@if($usuario->rol !== \App\Enums\RolUsuario::Admin)
{{-- Datos personales (solo clientes/usuarios) --}}
<div class="perfil-seccion">
    <h3 class="perfil-seccion__titulo">Datos personales</h3>
    <div class="perfil__stats">
        <div class="perfil__stat">
            <span class="perfil__stat-value">{{ $usuario->dni_cif ?? '-' }}</span>
            <span class="perfil__stat-label">DNI / CIF</span>
        </div>
        <div class="perfil__stat">
            <span class="perfil__stat-value">{{ $usuario->telefono ?? '-' }}</span>
            <span class="perfil__stat-label">Teléfono</span>
        </div>
        <div class="perfil__stat">
            <span class="perfil__stat-value">{{ $usuario->direccion ?? '-' }}</span>
            <span class="perfil__stat-label">Dirección</span>
        </div>
        <div class="perfil__stat">
            <span class="perfil__stat-value">{{ collect([$usuario->codigo_postal, $usuario->ciudad, $usuario->provincia])->filter()->implode(', ') ?: '-' }}</span>
            <span class="perfil__stat-label">Localidad</span>
        </div>
    </div>
</div>

{{-- Ventas realizadas al usuario --}}
<div class="perfil-seccion">
    <h3 class="perfil-seccion__titulo">Ventas realizadas</h3>
    <div class="tabla-wrapper">
        <table class="tabla">
            <thead>
                <tr>
                    <th>Concepto</th>
                    <th>Tipo</th>
                    <th>Importe</th>
                    <th>Fecha</th>
                    <th>Nº Factura</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ingresos as $i)
                <tr>
                    <td>{{ $i->concepto }}</td>
                    <td><span class="badge badge--tipo">{{ $i->tipo }}</span></td>
                    <td class="importe importe--positivo">{{ number_format($i->importe, 2, ',', '.') }} €</td>
                    <td class="text-soft">{{ $i->fecha->format('d M Y') }}</td>
                    <td class="text-soft">{{ $i->numero_factura ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-soft">Sin ventas registradas para este usuario.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($ingresos->count())
    <div class="perfil-seccion__total">
        Total: <strong>{{ number_format($ingresos->sum('importe'), 2, ',', '.') }} €</strong>
    </div>
    @endif
</div>

{{-- Visibilidad de demos --}}
<div class="perfil-seccion">
    <h3 class="perfil-seccion__titulo">Acceso a Demos</h3>
    <div class="demos-permisos">
        @forelse($demos as $d)
        @if($d->visibilidad === 'publica')
        <div class="demo-permiso">
            <span class="demo-permiso__nombre">{{ $d->titulo }}</span>
            <span class="demo-card__badge demo-card__badge--publica">Pública</span>
        </div>
        @endif
        @empty
        <p class="text-soft">No hay demos registradas.</p>
        @endforelse
    </div>
    <p class="text-soft perfil-seccion__nota">Este usuario solo puede ver las demos con visibilidad pública.</p>
</div>
@endif

{{-- Modal Editar usuario --}}
<div class="modal-overlay" id="modalEditarUsuario" style="display:none">
    <div class="modal">
        <div class="modal__header">
            <h3>Editar usuario</h3>
            <button type="button" class="modal__close" onclick="Usuarios.cerrarModalEditar()">&times;</button>
        </div>
        <form id="formEditarUsuario" onsubmit="return Usuarios.actualizar(event)">
            <div class="modal__body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="eNombre">Nombre *</label>
                        <input type="text" id="eNombre" required maxlength="255" class="form-input" value="{{ $usuario->nombre }}">
                    </div>
                    <div class="form-group">
                        <label for="eApellido">Apellido</label>
                        <input type="text" id="eApellido" maxlength="255" class="form-input" value="{{ $usuario->apellido }}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="eCorreo">Correo electrónico *</label>
                        <input type="email" id="eCorreo" required maxlength="255" class="form-input" value="{{ $usuario->correo }}">
                    </div>
                    <div class="form-group">
                        <label for="eContrasena">Nueva contraseña <small>(dejar vacío para no cambiar)</small></label>
                        <input type="password" id="eContrasena" minlength="8" maxlength="255" class="form-input">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="eDniCif">DNI / CIF</label>
                        <input type="text" id="eDniCif" maxlength="20" class="form-input" value="{{ $usuario->dni_cif }}">
                    </div>
                    <div class="form-group">
                        <label for="eTelefono">Teléfono</label>
                        <input type="text" id="eTelefono" maxlength="20" class="form-input" value="{{ $usuario->telefono }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="eDireccion">Dirección</label>
                    <input type="text" id="eDireccion" maxlength="500" class="form-input" value="{{ $usuario->direccion }}">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="eCodigoPostal">Código postal</label>
                        <input type="text" id="eCodigoPostal" maxlength="10" class="form-input" value="{{ $usuario->codigo_postal }}">
                    </div>
                    <div class="form-group">
                        <label for="eCiudad">Ciudad</label>
                        <input type="text" id="eCiudad" maxlength="100" class="form-input" value="{{ $usuario->ciudad }}">
                    </div>
                    <div class="form-group">
                        <label for="eProvincia">Provincia</label>
                        <input type="text" id="eProvincia" maxlength="100" class="form-input" value="{{ $usuario->provincia }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="eRol">Rol</label>
                    <select id="eRol" class="form-input">
                        <option value="usuario" {{ $usuario->rol->value === 'usuario' ? 'selected' : '' }}>Usuario</option>
                        <option value="admin" {{ $usuario->rol->value === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
                <div id="editarErrores" class="alert alert--danger" style="display:none"></div>
            </div>
            <div class="modal__footer">
                <button type="button" class="btn btn--ghost" onclick="Usuarios.cerrarModalEditar()">Cancelar</button>
                <button type="submit" class="btn btn--accent">Guardar cambios</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script>
    const USUARIO_ID = {
        {
            $usuario - > id
        }
    };
</script>
<script src="{{ asset('js/usuarios.js') }}"></script>
@endpush