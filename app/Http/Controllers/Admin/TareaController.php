<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ComentarioTarea;
use App\Models\Tarea;
use App\Models\Usuario;
use Illuminate\Http\Request;

class TareaController extends Controller
{
    /**
     * Vista Kanban con las 3 columnas.
     */
    public function index()
    {
        $tareas = Tarea::with(['asignado', 'comentarios'])
            ->orderBy('posicion')
            ->get()
            ->groupBy('estado');

        $usuarios = Usuario::orderBy('nombre')->get();

        return view('admin.tareas.index', [
            'pendientes'  => $tareas->get('pendiente', collect()),
            'enProgreso'  => $tareas->get('en_progreso', collect()),
            'completadas' => $tareas->get('completado', collect()),
            'usuarios'    => $usuarios,
        ]);
    }

    /**
     * Detalle de tarea (AJAX).
     */
    public function show(Tarea $tarea)
    {
        $tarea->load(['asignado', 'comentarios.usuario']);
        return response()->json($tarea);
    }

    /**
     * Crear tarea (AJAX).
     */
    public function store(Request $request)
    {
        $datos = $request->validate([
            'titulo'       => 'required|string|max:255',
            'descripcion'  => 'nullable|string|max:5000',
            'estado'       => 'required|in:pendiente,en_progreso,completado',
            'asignado_a'   => 'nullable|exists:usuarios,id',
            'fecha_limite'  => 'nullable|date',
        ]);

        $maxPos = Tarea::where('estado', $datos['estado'])->max('posicion') ?? -1;

        $tarea = Tarea::create([
            ...$datos,
            'posicion'   => $maxPos + 1,
            'creado_por' => $request->user()->id,
        ]);

        $tarea->load('asignado', 'comentarios');

        return response()->json($tarea, 201);
    }

    /**
     * Actualizar tarea (AJAX).
     */
    public function update(Request $request, Tarea $tarea)
    {
        $datos = $request->validate([
            'titulo'       => 'sometimes|required|string|max:255',
            'descripcion'  => 'nullable|string|max:5000',
            'estado'       => 'sometimes|in:pendiente,en_progreso,completado',
            'asignado_a'   => 'nullable|exists:usuarios,id',
            'fecha_limite'  => 'nullable|date',
        ]);

        // Si cambia de estado, reposicionar
        if (isset($datos['estado']) && $datos['estado'] !== $tarea->estado->value) {
            // Compactar columna origen
            Tarea::where('estado', $tarea->estado->value)
                ->where('posicion', '>', $tarea->posicion)
                ->decrement('posicion');

            // Colocar al final de la columna destino
            $maxPos = Tarea::where('estado', $datos['estado'])->max('posicion') ?? -1;
            $datos['posicion'] = $maxPos + 1;
        }

        $tarea->update($datos);
        $tarea->load('asignado', 'comentarios');

        return response()->json($tarea);
    }

    /**
     * Mover tarea (drag & drop): cambiar estado + reordenar posiciones.
     */
    public function mover(Request $request, Tarea $tarea)
    {
        $datos = $request->validate([
            'estado'  => 'required|in:pendiente,en_progreso,completado',
            'posicion' => 'required|integer|min:0',
        ]);

        $estadoAnterior = $tarea->estado->value;
        $nuevoEstado = $datos['estado'];
        $nuevaPos = $datos['posicion'];

        // Si cambia de columna, compactar la columna origen
        if ($estadoAnterior !== $nuevoEstado) {
            Tarea::where('estado', $estadoAnterior)
                ->where('posicion', '>', $tarea->posicion)
                ->decrement('posicion');
        } else {
            // Misma columna: ajustar posiciones entre origen y destino
            if ($tarea->posicion < $nuevaPos) {
                Tarea::where('estado', $nuevoEstado)
                    ->where('id', '!=', $tarea->id)
                    ->whereBetween('posicion', [$tarea->posicion + 1, $nuevaPos])
                    ->decrement('posicion');
            } else {
                Tarea::where('estado', $nuevoEstado)
                    ->where('id', '!=', $tarea->id)
                    ->whereBetween('posicion', [$nuevaPos, $tarea->posicion - 1])
                    ->increment('posicion');
            }
        }

        // Si viene de otra columna, hacer hueco en la destino
        if ($estadoAnterior !== $nuevoEstado) {
            Tarea::where('estado', $nuevoEstado)
                ->where('posicion', '>=', $nuevaPos)
                ->increment('posicion');
        }

        $tarea->update([
            'estado'   => $nuevoEstado,
            'posicion' => $nuevaPos,
        ]);

        return response()->json(['ok' => true]);
    }

    /**
     * Eliminar tarea (AJAX).
     */
    public function destroy(Tarea $tarea)
    {
        $estado = $tarea->estado->value;
        $posicion = $tarea->posicion;

        $tarea->delete();

        // Compactar posiciones
        Tarea::where('estado', $estado)
            ->where('posicion', '>', $posicion)
            ->decrement('posicion');

        return response()->json(['ok' => true]);
    }

    /**
     * Añadir comentario a una tarea (AJAX).
     */
    public function agregarComentario(Request $request, Tarea $tarea)
    {
        $datos = $request->validate([
            'contenido' => 'required|string|max:2000',
        ]);

        $comentario = $tarea->comentarios()->create([
            'usuario_id' => $request->user()->id,
            'contenido'  => $datos['contenido'],
        ]);

        $comentario->load('usuario');

        return response()->json($comentario, 201);
    }

    /**
     * Eliminar comentario (AJAX).
     */
    public function eliminarComentario(Tarea $tarea, ComentarioTarea $comentario)
    {
        if ($comentario->tarea_id !== $tarea->id) {
            abort(404);
        }

        $comentario->delete();

        return response()->json(['ok' => true]);
    }
}
