<?php

namespace App\Models;

use App\Enums\EstadoTarea;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tarea extends Model
{
    protected $table = 'tareas';

    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $fillable = [
        'titulo',
        'descripcion',
        'estado',
        'posicion',
        'asignado_a',
        'creado_por',
        'fecha_limite',
    ];

    protected $casts = [
        'estado' => EstadoTarea::class,
        'fecha_limite' => 'date',
        'posicion' => 'integer',
    ];

    public function asignado(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'asignado_a');
    }

    public function creador(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'creado_por');
    }

    public function comentarios(): HasMany
    {
        return $this->hasMany(ComentarioTarea::class, 'tarea_id')->orderBy('creado_en', 'desc');
    }
}
