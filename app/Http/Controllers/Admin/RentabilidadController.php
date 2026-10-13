<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RentabilidadController extends Controller
{
    public function index(Request $request)
    {
        $datos = [];
        $resultado = null;
        if ($request->isMethod('post')) {
            $datos = $request->validate([
                'horas' => 'required|numeric|min:0|max:100000|decimal:0,2',
                'coste_hora' => 'required|numeric|min:0|max:100000|decimal:0,2',
                'directos' => 'required|numeric|min:0|max:1000000|decimal:0,2',
                'soporte' => 'required|numeric|min:0|max:1000000|decimal:0,2',
                'captacion' => 'required|numeric|min:0|max:1000000|decimal:0,2',
                'margen' => 'required|numeric|min:0|max:99.9|decimal:0,2',
                'precio_neto' => 'nullable|numeric|min:0|max:100000000|decimal:0,2',
            ]);
            // Costes en céntimos; precio mínimo redondeado hacia arriba.
            $coste = (int) round($datos['horas'] * $datos['coste_hora'] * 100);
            foreach (['directos', 'soporte', 'captacion'] as $campo) {
                $coste += (int) round($datos[$campo] * 100);
            }
            $numerador = $coste * 10000;
            $divisor = 10000 - (int) round($datos['margen'] * 100);
            $minimo = intdiv($numerador, $divisor) + ($numerador % $divisor ? 1 : 0);
            $precio = isset($datos['precio_neto']) ? (int) round($datos['precio_neto'] * 100) : null;
            $resultado = [
                'coste' => $coste / 100, 'minimo' => $minimo / 100,
                'contribucion' => $precio !== null ? ($precio - $coste) / 100 : null,
                'margen' => $precio > 0 ? 100 * ($precio - $coste) / $precio : null,
                'diferencia' => $precio !== null ? ($precio - $minimo) / 100 : null,
            ];
        }
        return view('admin.rentabilidad', compact('datos', 'resultado'));
    }
}
