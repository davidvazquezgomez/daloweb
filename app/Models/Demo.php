<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Demo extends Model
{
    protected $table = 'demos';

    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $fillable = [
        'titulo',
        'slug',
        'descripcion',
        'tipo',
        'ruta_carpeta',
        'miniatura',
        'tecnologias',
        'visibilidad',
        'activa',
        'creado_por',
    ];

    protected $casts = [
        'tecnologias' => 'array',
        'activa' => 'boolean',
    ];

    public function creador(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'creado_por');
    }

    public function esPublica(): bool
    {
        return $this->visibilidad === 'publica' && $this->activa;
    }
}
