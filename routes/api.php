<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClienteController;

// Laravel generará automáticamente las 5 rutas RESTful para clientes
Route::apiResource('clientes', ClienteController::class);