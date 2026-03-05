<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
})->name('landing');

Route::get('/proyecto', function () {
    return view('partials.project_detail'); 
})->name('project.detail');

Route::get('/info', function () {
    return view('agregados.info'); 
})->name('info');

Route::get('/contacto', function () {
    return view('agregados.contacto'); 
})->name('contacto');


Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 4. Ruta del Dashboard
Route::get('/dashboard/main', function () {
    return view('dashboard.main');
})->middleware('auth')->name('dashboard.main');

Route::get('/registro', function () {
    return view('dashboard.registro');
})->name('registro.index');

Route::post('/registro', [AuthController::class, 'store'])->name('registro.store');

Route::get('/proyecto/{id}', [ProjectController::class, 'show'])->name('project.main');

Route::get('/dashboard/opciones', function () {
    return view('dashboard.opciones');
})->middleware('auth')->name('dashboard.opciones');