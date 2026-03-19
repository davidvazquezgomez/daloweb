<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gasto extends Model
{
    protected $table = 'gastos';

    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $fillable = [
        'concepto',
        'categoria',
        'importe',
        'fecha',
        'recurrente',
        'notas',
        'creado_por',
    ];

    protected $casts = [
        'importe' => 'decimal:2',
        'fecha' => 'date',
        'recurrente' => 'boolean',
    ];

    public function creador(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'creado_por');
    }
}
