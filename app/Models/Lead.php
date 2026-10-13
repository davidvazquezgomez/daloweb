<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = ['nombre', 'email', 'tipo', 'pack', 'origen', 'mensaje', 'estado', 'correo_estado', 'presupuesto', 'venta', 'coste', 'notas'];

    protected function casts(): array
    {
        return ['presupuesto' => 'decimal:2', 'venta' => 'decimal:2', 'coste' => 'decimal:2'];
    }

    public function getMargenAttribute(): ?float
    {
        return $this->estado === 'ganado' && $this->venta !== null && $this->coste !== null
            ? round((float) $this->venta - (float) $this->coste, 2) : null;
    }
}
