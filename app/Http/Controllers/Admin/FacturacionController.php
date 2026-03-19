<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gasto;
use App\Models\Ingreso;
use App\Models\Usuario;
use Illuminate\Http\Request;

class FacturacionController extends Controller
{
    public function index(Request $request)
    {
        $anio = $request->query('anio', now()->year);

        $gastos = Gasto::whereYear('fecha', $anio)->orderBy('fecha', 'desc')->get();
        $ingresos = Ingreso::with('cliente')->whereYear('fecha', $anio)->orderBy('fecha', 'desc')->get();

        $totalGastos = $gastos->sum('importe');
        $totalIngresos = $ingresos->sum('importe');

        // Datos mensuales para la gráfica
        $mensual = [];
        for ($m = 1; $m <= 12; $m++) {
            $mensual[] = [
                'mes' => $m,
                'ingresos' => $ingresos->filter(fn($i) => $i->fecha->month === $m)->sum('importe'),
                'gastos' => $gastos->filter(fn($g) => $g->fecha->month === $m)->sum('importe'),
            ];
        }

        if ($request->expectsJson()) {
            return response()->json([
                'gastos' => $gastos,
                'ingresos' => $ingresos,
                'totalGastos' => $totalGastos,
                'totalIngresos' => $totalIngresos,
                'mensual' => $mensual,
                'anio' => (int) $anio,
            ]);
        }

        $aniosDisponibles = collect()
            ->merge(Gasto::selectRaw('YEAR(fecha) as anio')->distinct()->pluck('anio'))
            ->merge(Ingreso::selectRaw('YEAR(fecha) as anio')->distinct()->pluck('anio'))
            ->push(now()->year)
            ->unique()
            ->sort()
            ->values();

        $usuarios = Usuario::orderBy('nombre')->get(['id', 'nombre', 'apellido']);

        return view('admin.facturacion.index', compact(
            'gastos',
            'ingresos',
            'totalGastos',
            'totalIngresos',
            'mensual',
            'anio',
            'aniosDisponibles',
            'usuarios'
        ));
    }

    // ── Gastos ─────────────────────────────────────────

    public function storeGasto(Request $request)
    {
        $esRecurrente = $request->boolean('recurrente');

        $datos = $request->validate([
            'concepto'  => 'required|string|max:255',
            'categoria' => 'required|in:dominio,servidor,software,otros',
            'importe'   => 'required|numeric|min:0.01|max:9999999.99',
            'fecha'     => $esRecurrente ? 'nullable|date' : 'required|date',
            'recurrente' => 'sometimes|boolean',
            'notas'     => 'nullable|string|max:5000',
        ]);

        $datos['recurrente'] = $esRecurrente;
        $datos['creado_por'] = $request->user()->id;

        if ($esRecurrente) {
            $anio = $request->query('anio', now()->year);
            $gastos = [];
            for ($m = 1; $m <= 12; $m++) {
                $datos['fecha'] = sprintf('%d-%02d-01', $anio, $m);
                $gastos[] = Gasto::create($datos);
            }
            return response()->json($gastos, 201);
        }

        $gasto = Gasto::create($datos);

        return response()->json($gasto, 201);
    }

    public function updateGasto(Request $request, Gasto $gasto)
    {
        $datos = $request->validate([
            'concepto'  => 'sometimes|required|string|max:255',
            'categoria' => 'sometimes|required|in:dominio,servidor,software,otros',
            'importe'   => 'sometimes|required|numeric|min:0.01|max:9999999.99',
            'fecha'     => 'sometimes|required|date',
            'recurrente' => 'sometimes|boolean',
            'notas'     => 'nullable|string|max:5000',
        ]);

        if ($request->has('recurrente')) {
            $datos['recurrente'] = $request->boolean('recurrente');
        }

        $gasto->update($datos);

        return response()->json($gasto);
    }

    public function destroyGasto(Gasto $gasto)
    {
        $gasto->delete();
        return response()->json(null, 204);
    }

    // ── Ingresos ───────────────────────────────────────

    public function storeIngreso(Request $request)
    {
        $datos = $request->validate([
            'concepto'       => 'required|string|max:255',
            'cliente_id'     => 'nullable|exists:usuarios,id',
            'tipo'           => 'required|in:web,componente,app,reservas,medida,otro',
            'importe'        => 'required|numeric|min:0.01|max:9999999.99',
            'fecha'          => 'required|date',
            'numero_factura' => 'nullable|string|max:50',
            'notas'          => 'nullable|string|max:5000',
        ]);

        $datos['creado_por'] = $request->user()->id;

        $ingreso = Ingreso::create($datos);
        $ingreso->load('cliente');

        return response()->json($ingreso, 201);
    }

    public function updateIngreso(Request $request, Ingreso $ingreso)
    {
        $datos = $request->validate([
            'concepto'       => 'sometimes|required|string|max:255',
            'cliente_id'     => 'nullable|exists:usuarios,id',
            'tipo'           => 'sometimes|required|in:web,componente,app,reservas,medida,otro',
            'importe'        => 'sometimes|required|numeric|min:0.01|max:9999999.99',
            'fecha'          => 'sometimes|required|date',
            'numero_factura' => 'nullable|string|max:50',
            'notas'          => 'nullable|string|max:5000',
        ]);

        $ingreso->update($datos);
        $ingreso->load('cliente');

        return response()->json($ingreso);
    }

    public function destroyIngreso(Ingreso $ingreso)
    {
        $ingreso->delete();
        return response()->json(null, 204);
    }
}
