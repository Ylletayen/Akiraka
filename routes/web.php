<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/proyecto', function () {
    return view('partials.project_detail'); 
})->name('project.detail');