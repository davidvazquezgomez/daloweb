<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Demo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DemoController extends Controller
{
    public function index(Request $request)
    {
        // Auto-registrar carpetas nuevas
        $this->autoRegistrar($request->user()->id);

        $demos = Demo::orderBy('creado_en', 'desc')->get();

        if ($request->expectsJson()) {
            return response()->json(['demos' => $demos]);
        }

        return view('admin.demos.index', compact('demos'));
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'titulo'       => 'required|string|max:255',
            'slug'         => 'required|string|max:255|unique:demos,slug|regex:/^[a-z0-9\-]+$/',
            'descripcion'  => 'nullable|string|max:5000',
            'tipo'         => 'required|in:web,componente',
            'ruta_carpeta' => 'required|string|max:500',
            'miniatura'    => 'nullable|string|max:500',
            'tecnologias'  => 'nullable|array',
            'tecnologias.*' => 'string|max:50',
            'visibilidad'  => 'required|in:publica,privada',
        ]);

        $datos['activa'] = true;
        $datos['creado_por'] = $request->user()->id;

        $demo = Demo::create($datos);

        return response()->json($demo, 201);
    }

    public function update(Request $request, Demo $demo)
    {
        $datos = $request->validate([
            'titulo'       => 'sometimes|required|string|max:255',
            'slug'         => 'sometimes|required|string|max:255|regex:/^[a-z0-9\-]+$/|unique:demos,slug,' . $demo->id,
            'descripcion'  => 'nullable|string|max:5000',
            'tipo'         => 'sometimes|required|in:web,componente',
            'miniatura'    => 'nullable|string|max:500',
            'tecnologias'  => 'nullable|array',
            'tecnologias.*' => 'string|max:50',
            'visibilidad'  => 'sometimes|required|in:publica,privada',
        ]);

        $demo->update($datos);

        return response()->json($demo);
    }

    public function toggleVisibilidad(Demo $demo)
    {
        $demo->visibilidad = $demo->visibilidad === 'publica' ? 'privada' : 'publica';
        $demo->save();

        return response()->json($demo);
    }

    public function destroy(Demo $demo)
    {
        $carpeta = storage_path('app/demos/' . $demo->ruta_carpeta);
        if (File::isDirectory($carpeta)) {
            File::deleteDirectory($carpeta);
        }

        $demo->delete();
        return response()->json(null, 204);
    }

    public function sincronizar(Request $request)
    {
        $nuevas = $this->autoRegistrar($request->user()->id);
        $demos = Demo::orderBy('creado_en', 'desc')->get();

        return response()->json([
            'demos' => $demos,
            'registradas' => count($nuevas),
        ]);
    }

    /**
     * Sirve el index.html o un recurso de la demo (CSS, JS, imágenes…).
     */
    public function mostrar(Request $request, string $slug, string $path = 'index.html')
    {
        $demo = $request->attributes->get('demo');
        $basePath = realpath(storage_path('app/demos/' . $demo->ruta_carpeta));
        $ruta = realpath($basePath . '/' . $path);

        // Evitar path-traversal
        if (!$basePath || !$ruta || !str_starts_with($ruta, $basePath . DIRECTORY_SEPARATOR) && $ruta !== $basePath) {
            abort(404);
        }

        if (!File::exists($ruta)) {
            abort(404);
        }

        $mime = $this->detectarMime($ruta);

        return response(File::get($ruta), 200, ['Content-Type' => $mime]);
    }

    /**
     * Escanea storage/app/demos/ en busca de subcarpetas con index.html no registradas.
     */
    private function escanearCarpetas(array $registradas): array
    {
        $demoPath = storage_path('app/demos');
        $nuevas = [];

        if (!File::isDirectory($demoPath)) {
            return $nuevas;
        }

        $directorios = File::directories($demoPath);
        foreach ($directorios as $dir) {
            $nombre = basename($dir);
            if (!in_array($nombre, $registradas) && File::exists($dir . '/index.html')) {
                $nuevas[] = $nombre;
            }
        }

        return $nuevas;
    }

    /**
     * Auto-registra carpetas nuevas como demos privadas y elimina demos huérfanas.
     */
    private function autoRegistrar(int $userId): array
    {
        $registradas = Demo::pluck('ruta_carpeta')->toArray();
        $nuevas = $this->escanearCarpetas($registradas);

        foreach ($nuevas as $carpeta) {
            $slug = Str::slug($carpeta);

            // Evitar slug duplicado
            if (Demo::where('slug', $slug)->exists()) {
                $slug .= '-' . time();
            }

            Demo::create([
                'titulo' => Str::of($carpeta)->replace('-', ' ')->title()->toString(),
                'slug' => $slug,
                'tipo' => 'web',
                'ruta_carpeta' => $carpeta,
                'visibilidad' => 'privada',
                'activa' => true,
                'creado_por' => $userId,
            ]);
        }

        // Desactivar demos cuya carpeta ya no existe
        $demoPath = storage_path('app/demos');
        Demo::all()->each(function (Demo $demo) use ($demoPath) {
            $ruta = $demoPath . '/' . $demo->ruta_carpeta . '/index.html';
            if (!File::exists($ruta)) {
                $demo->update(['activa' => false]);
            }
        });

        return $nuevas;
    }

    private function detectarMime(string $ruta): string
    {
        $ext = strtolower(pathinfo($ruta, PATHINFO_EXTENSION));

        return match ($ext) {
            'html', 'htm' => 'text/html',
            'css'         => 'text/css',
            'js'          => 'application/javascript',
            'json'        => 'application/json',
            'png'         => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            'gif'         => 'image/gif',
            'svg'         => 'image/svg+xml',
            'ico'         => 'image/x-icon',
            'webp'        => 'image/webp',
            'woff'        => 'font/woff',
            'woff2'       => 'font/woff2',
            'ttf'         => 'font/ttf',
            'xml'         => 'application/xml',
            default       => 'application/octet-stream',
        };
    }
}
