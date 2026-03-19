<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\PanelController;
use App\Http\Controllers\Admin\TareaController;
use App\Http\Controllers\Admin\UsuarioController;

use App\Http\Controllers\Admin\FacturacionController;
use App\Http\Controllers\Admin\DemoController;

// --- Landing ---
Route::get('/', [InicioController::class, 'index'])->name('inicio');
Route::post('/contacto', [ContactoController::class, 'enviar'])->name('contacto');

// --- Auth ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'mostrar'])->name('login');
    Route::post('/login', [LoginController::class, 'autenticar']);
});
Route::post('/logout', [LoginController::class, 'salir'])->middleware('auth')->name('logout');

// --- Admin ---
Route::prefix('admin')
    ->middleware(['auth', \App\Http\Middleware\VerificarAdmin::class])
    ->name('admin.')
    ->group(function () {
        Route::get('/', [PanelController::class, 'index'])->name('panel');

        // Tareas (Kanban)
        Route::get('/tareas', [TareaController::class, 'index'])->name('tareas');
        Route::post('/tareas', [TareaController::class, 'store'])->name('tareas.store');
        Route::get('/tareas/{tarea}', [TareaController::class, 'show'])->name('tareas.show');
        Route::put('/tareas/{tarea}', [TareaController::class, 'update'])->name('tareas.update');
        Route::patch('/tareas/{tarea}/mover', [TareaController::class, 'mover'])->name('tareas.mover');
        Route::delete('/tareas/{tarea}', [TareaController::class, 'destroy'])->name('tareas.destroy');
        Route::post('/tareas/{tarea}/comentarios', [TareaController::class, 'agregarComentario'])->name('tareas.comentarios.store');
        Route::delete('/tareas/{tarea}/comentarios/{comentario}', [TareaController::class, 'eliminarComentario'])->name('tareas.comentarios.destroy');

        // Usuarios
        Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios');
        Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
        Route::get('/usuarios/{usuario}', [UsuarioController::class, 'show'])->name('usuarios.show');
        Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update'])->name('usuarios.update');
        Route::delete('/usuarios/{usuario}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');

        // Facturación
        Route::get('/facturacion', [FacturacionController::class, 'index'])->name('facturacion');
        Route::post('/facturacion/gastos', [FacturacionController::class, 'storeGasto'])->name('facturacion.gastos.store');
        Route::put('/facturacion/gastos/{gasto}', [FacturacionController::class, 'updateGasto'])->name('facturacion.gastos.update');
        Route::delete('/facturacion/gastos/{gasto}', [FacturacionController::class, 'destroyGasto'])->name('facturacion.gastos.destroy');
        Route::post('/facturacion/ingresos', [FacturacionController::class, 'storeIngreso'])->name('facturacion.ingresos.store');
        Route::put('/facturacion/ingresos/{ingreso}', [FacturacionController::class, 'updateIngreso'])->name('facturacion.ingresos.update');
        Route::delete('/facturacion/ingresos/{ingreso}', [FacturacionController::class, 'destroyIngreso'])->name('facturacion.ingresos.destroy');

        // Demos
        Route::get('/demos', [DemoController::class, 'index'])->name('demos');
        Route::post('/demos', [DemoController::class, 'store'])->name('demos.store');
        Route::put('/demos/{demo}', [DemoController::class, 'update'])->name('demos.update');
        Route::patch('/demos/{demo}/visibilidad', [DemoController::class, 'toggleVisibilidad'])->name('demos.visibilidad');
        Route::delete('/demos/{demo}', [DemoController::class, 'destroy'])->name('demos.destroy');
        Route::post('/demos/sincronizar', [DemoController::class, 'sincronizar'])->name('demos.sincronizar');
    });

// --- Demos públicas ---
Route::get('/demo/{slug}/{path?}', [DemoController::class, 'mostrar'])
    ->where('path', '.*')
    ->middleware(\App\Http\Middleware\AccesoDemo::class)
    ->name('demo.mostrar');
