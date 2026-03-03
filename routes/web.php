<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/proyecto', function () {
    return view('partials.project_detail'); 
})->name('project.detail');

Route::get('/info', function () {
    return view('agregados.info'); 
})->name('info');

Route::get('/contacto', function () {
    return view('agregados.contacto'); 
})->name('contacto');

Route::get('/proyecto/{id}', [ProjectController::class, 'show'])->name('project.main');