<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClienteController;

// RUTAS PÚBLICAS (Cualquiera puede entrar)
Route::post('/login', [AuthController::class, 'login']);

// RUTAS PROTEGIDAS (Solo entran con Token de Sanctum válido)
Route::middleware('auth:sanctum')->group(function () {
    
    // Obtener perfil del usuario logueado
    Route::get('/perfil', function (Request $request) {
        return $request->user();
    });

    // Proteger las 5 rutas de clientes (Opcional, pero recomendado)
    Route::apiResource('clientes', ClienteController::class);
});