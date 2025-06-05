<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FormularioController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\BloqueController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\PiezaController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Redirect::route('login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/formulario', [FormularioController::class, 'index'])->name('formulario');
    Route::post('/formulario/store', [FormularioController::class, 'store'])->name('formulario.store');

    Route::get('/proyectos', [ProyectoController::class, 'index'])->name('proyectos');
    Route::get('/bloques', [BloqueController::class, 'index'])->name('bloques');
    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios');
    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes');

    // Piezas routes
    Route::get('/piezas', [PiezaController::class, 'index'])->name('piezas.index');
    Route::post('/piezas', [PiezaController::class, 'store'])->name('piezas.store');
    Route::put('/piezas/{pieza}', [PiezaController::class, 'update'])->name('piezas.update');
    Route::delete('/piezas/{pieza}', [PiezaController::class, 'destroy'])->name('piezas.destroy');
});
