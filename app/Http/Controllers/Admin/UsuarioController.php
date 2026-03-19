<?php

namespace App\Http\Controllers\Admin;

use App\Enums\RolUsuario;
use App\Http\Controllers\Controller;
use App\Models\Demo;
use App\Models\Ingreso;
use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    /**
     * Listado de usuarios con búsqueda.
     */
    public function index(Request $request)
    {
        $busqueda = $request->query('busqueda');

        $query = Usuario::orderBy('creado_en', 'desc');

        if ($busqueda) {
            $busqueda = trim($busqueda);
            $query->where(function ($q) use ($busqueda) {
                $q->where('nombre', 'like', "%{$busqueda}%")
                    ->orWhere('correo', 'like', "%{$busqueda}%");
            });
        }

        $usuarios = $query->paginate(15)->withQueryString();

        if ($request->expectsJson()) {
            return response()->json($usuarios);
        }

        return view('admin.usuarios.index', [
            'usuarios' => $usuarios,
            'busqueda' => $busqueda,
        ]);
    }

    /**
     * Detalle de usuario.
     */
    public function show(Usuario $usuario)
    {
        $tareasAsignadas = \App\Models\Tarea::where('asignado_a', $usuario->id)->count();
        $tareasCreadas = \App\Models\Tarea::where('creado_por', $usuario->id)->count();
        $ingresos = Ingreso::with('cliente')->where('cliente_id', $usuario->id)->orderBy('fecha', 'desc')->get();
        $demos = Demo::orderBy('titulo')->get(['id', 'titulo', 'slug', 'visibilidad']);

        return view('admin.usuarios.show', [
            'usuario' => $usuario,
            'tareasAsignadas' => $tareasAsignadas,
            'tareasCreadas' => $tareasCreadas,
            'ingresos' => $ingresos,
            'demos' => $demos,
        ]);
    }

    /**
     * Crear usuario (AJAX).
     */
    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre'         => 'required|string|max:255',
            'apellido'       => 'nullable|string|max:255',
            'correo'         => 'required|email|max:255|unique:usuarios,correo',
            'contrasena'     => 'required|string|min:8|max:255',
            'rol'            => 'required|in:admin,usuario',
            'dni_cif'        => 'nullable|string|max:20',
            'telefono'       => 'nullable|string|max:20',
            'direccion'      => 'nullable|string|max:500',
            'codigo_postal'  => 'nullable|string|max:10',
            'ciudad'         => 'nullable|string|max:100',
            'provincia'      => 'nullable|string|max:100',
        ]);

        $usuario = Usuario::create($datos);

        if ($request->expectsJson()) {
            return response()->json($usuario, 201);
        }

        return redirect()->route('admin.usuarios')->with('exito', 'Usuario creado correctamente.');
    }

    /**
     * Actualizar usuario (AJAX).
     */
    public function update(Request $request, Usuario $usuario)
    {
        $datos = $request->validate([
            'nombre'         => 'sometimes|required|string|max:255',
            'apellido'       => 'nullable|string|max:255',
            'correo'         => 'sometimes|required|email|max:255|unique:usuarios,correo,' . $usuario->id,
            'contrasena'     => 'nullable|string|min:8|max:255',
            'rol'            => 'sometimes|required|in:admin,usuario',
            'dni_cif'        => 'nullable|string|max:20',
            'telefono'       => 'nullable|string|max:20',
            'direccion'      => 'nullable|string|max:500',
            'codigo_postal'  => 'nullable|string|max:10',
            'ciudad'         => 'nullable|string|max:100',
            'provincia'      => 'nullable|string|max:100',
        ]);

        if (empty($datos['contrasena'])) {
            unset($datos['contrasena']);
        }

        $usuario->update($datos);

        return response()->json($usuario);
    }

    /**
     * Eliminar usuario (AJAX).
     */
    public function destroy(Request $request, Usuario $usuario)
    {
        // No permitir eliminarse a sí mismo
        if ($usuario->id === $request->user()->id) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'No puedes eliminar tu propia cuenta.'], 422);
            }
            return back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $usuario->delete();

        if ($request->expectsJson()) {
            return response()->json(['ok' => true]);
        }

        return redirect()->route('admin.usuarios')->with('exito', 'Usuario eliminado.');
    }
}
