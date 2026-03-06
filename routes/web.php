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

Route::get('/dashboard/opciones', function () {
    return view('dashboard.opciones');
})->middleware('auth')->name('dashboard.opciones.opciones');

Route::get('/dashboard/opciones', [OpcionesController::class, 'index'])->middleware('auth')->name('dashboard.opciones.opciones');

// Recibir el formulario de perfil admin (Actualizar nombre/contraseña)
Route::put('/dashboard/opciones/perfil', [OpcionesController::class, 'updatePerfil'])->middleware('auth')->name('opciones.perfil.update');

// Recibir el formulario de datos públicos
Route::put('/dashboard/opciones/publicos', [OpcionesController::class, 'updatePublicos'])->middleware('auth')->name('opciones.publicos.update');

//QUIENES SOMOS
Route::get('/dashboard/quienes-somos', [EquipoController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard.equipo.quienes_somos');

Route::post('/dashboard/equipo', [EquipoController::class, 'store'])
    ->middleware('auth')
    ->name('equipo.store');

Route::put('/dashboard/equipo/{id}', [EquipoController::class, 'update'])
    ->middleware('auth')
    ->name('equipo.update');

Route::delete('/dashboard/equipo/{id}', [EquipoController::class, 'destroy'])
    ->middleware('auth')
    ->name('equipo.destroy');

Route::resource('equipo', EquipoController::class)->names([
    'index' => 'dashboard.quienes_somos',]);

///dash mensajes 
Route::get('/mensajes', function () {
    return view('dashboard.mensajes');
})->name('mensajes');

// USUARIOS
Route::get('/dashboard/usuarios', [UsuarioController::class, 'index'])
    ->middleware('auth')
    ->name('usuarios.index');

Route::put('/dashboard/usuarios/{id}/rol', [UsuarioController::class, 'updateRol'])
    ->middleware('auth')
    ->name('usuarios.updateRol');

Route::get('/dashboard/mensajes', [MensajesController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard.mensajes');
