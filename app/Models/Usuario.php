<?php

namespace App\Models;

use App\Enums\RolUsuario;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens;

    protected $table = 'usuarios';

    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $fillable = [
        'nombre',
        'apellido',
        'correo',
        'contrasena',
        'rol',
        'dni_cif',
        'telefono',
        'direccion',
        'codigo_postal',
        'ciudad',
        'provincia',
    ];

    protected $hidden = [
        'contrasena',
        'token_recuerdo',
    ];

    protected function casts(): array
    {
        return [
            'correo_verificado' => 'datetime',
            'ultimo_acceso' => 'datetime',
            'contrasena' => 'hashed',
            'rol' => RolUsuario::class,
        ];
    }

    // Laravel usa 'password' internamente para auth — redirigimos a 'contrasena'
    public function getAuthPassword(): string
    {
        return $this->contrasena;
    }

    // Laravel usa 'email' para auth — redirigimos a 'correo'
    public function getEmailForPasswordReset(): string
    {
        return $this->correo;
    }

    // Laravel usa 'remember_token' — redirigimos a 'token_recuerdo'
    public function getRememberTokenName(): string
    {
        return 'token_recuerdo';
    }

    public function esAdmin(): bool
    {
        return $this->rol === RolUsuario::Admin;
    }
}
