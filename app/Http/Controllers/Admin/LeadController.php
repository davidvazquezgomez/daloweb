<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $ready = Schema::hasTable('leads');
        $filters = $request->validate([
            'estado' => ['nullable', 'string', Rule::in(array_keys(config('leads.estados')))],
            'origen' => ['nullable', 'string', Rule::in(array_keys(config('leads.origenes')))],
        ]);
        $query = $ready ? Lead::query()->when($filters['estado'] ?? null, fn ($q, $v) => $q->where('estado', $v))
            ->when($filters['origen'] ?? null, fn ($q, $v) => $q->where('origen', $v)) : null;
        $metrics = $ready ? (clone $query)->selectRaw("COUNT(*) AS total, SUM(CASE WHEN estado = 'ganado' THEN 1 ELSE 0 END) AS ganados, SUM(CASE WHEN estado = 'perdido' THEN 1 ELSE 0 END) AS perdidos, SUM(CASE WHEN estado = 'ganado' AND venta IS NOT NULL AND coste IS NOT NULL THEN venta - coste ELSE 0 END) AS contribucion, SUM(CASE WHEN estado = 'ganado' AND venta IS NOT NULL AND coste IS NOT NULL THEN 1 ELSE 0 END) AS con_coste")->first() : null;

        return view('admin.leads.index', [
            'ready' => $ready, 'leads' => $ready ? $query->latest()->paginate(20)->withQueryString() : null,
            'metrics' => $metrics, 'filters' => $filters,
        ]);
    }

    public function show(Lead $lead)
    {
        return view('admin.leads.show', compact('lead'));
    }

    public function update(Request $request, Lead $lead)
    {
        $data = $request->validate([
            'estado' => ['required', 'string', Rule::in(array_keys(config('leads.estados')))],
            'origen' => ['required', 'string', Rule::in(array_keys(config('leads.origenes')))],
            'presupuesto' => 'nullable|numeric|decimal:0,2|min:0|max:9999999999.99',
            'venta' => 'nullable|required_if:estado,ganado|numeric|decimal:0,2|min:0|max:9999999999.99',
            'coste' => 'nullable|numeric|decimal:0,2|min:0|max:9999999999.99',
            'notas' => 'nullable|string|max:5000',
        ]);
        $lead->update($data);

        return redirect()->route('admin.leads.show', $lead)->with('success', 'Oportunidad actualizada.');
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();

        return redirect()->route('admin.leads')->with('success', 'Oportunidad eliminada.');
    }
}
