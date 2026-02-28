<?php

use Illuminate\Support\Facades\Route;

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

