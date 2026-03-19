<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gasto;
use App\Models\Ingreso;

class PanelController extends Controller
{
    public function index()
    {
        return view('admin.panel', [
            'totalTareas' => \App\Models\Tarea::count(),
            'totalUsuarios' => \App\Models\Usuario::count(),
            'totalIngresos' => Ingreso::whereYear('fecha', now()->year)->sum('importe'),
            'totalGastos' => Gasto::whereYear('fecha', now()->year)->sum('importe'),
            'totalDemos' => \App\Models\Demo::count(),
        ]);
    }
}
