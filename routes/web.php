<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OpcionesController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\MensajesController;


Route::get('/', function () {
    return view('welcome');
})->name('landing');

Route::get('/proyecto', function () {
    return view('partials.project_detail'); 
})->name('project.detail');

Route::get('/info', function () {
    return view('agregados.informacion.info'); 
})->name('info');

Route::get('/contacto', function () {
    return view('agregados.contacto.contacto'); 
})->name('contacto');

Route::get('/login', function () {
    return view('dashboard.login.login');
})->name('login.form');

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 4. Ruta del Dashboard
Route::get('/dashboard/main', function () {
    return view('dashboard.dash.main');
})->middleware('auth')->name('dashboard.main');

Route::get('/registro', function () {
    return view('dashboard.login.registro');
})->name('registro.index');

Route::post('/registro', [AuthController::class, 'store'])->name('registro.store');

Route::get('/proyecto/{id}', [ProjectController::class, 'show'])->name('project.main');

Route::get('/dashboard/opciones', [OpcionesController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard.opciones');

Route::put('/dashboard/opciones/perfil', [OpcionesController::class, 'updatePerfil'])
    ->middleware('auth')
    ->name('opciones.perfil.update');

Route::put('/dashboard/opciones/publicos', [OpcionesController::class, 'updatePublicos'])
    ->middleware('auth')
    ->name('opciones.publicos.update');

// QUIENES SOMOS
Route::get('/dashboard/quienes-somos', [EquipoController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard.equipo.quienes_somos'); // <-- Aquí está la magia, ya coincide con tu vista

Route::post('/dashboard/equipo', [EquipoController::class, 'store'])
    ->middleware('auth')
    ->name('equipo.store');

Route::put('/dashboard/equipo/{id}', [EquipoController::class, 'update'])
    ->middleware('auth')
    ->name('equipo.update');

Route::delete('/dashboard/equipo/{id}', [EquipoController::class, 'destroy'])
    ->middleware('auth')
    ->name('equipo.destroy');
    

///dash mensajes 
Route::get('/mensajes', function () {
    return view('dashboard.mensajes.mensajes');
})->name('mensajes');

// USUARIOS
Route::prefix('dashboard')->middleware('auth')->group(function () {
    
    // El nombre de esta ruta DEBE coincidir con el route('dashboard.usuarios') de tu sidebar
    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('dashboard.usuarios');
    
    // Rutas para el modal de crear, actualizar y eliminar
    Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
    Route::put('/usuarios/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');
    Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');
});

