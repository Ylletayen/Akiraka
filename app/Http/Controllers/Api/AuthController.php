<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validamos usando 'correo' en lugar de 'email'
        $request->validate([
            'correo' => 'required|email',
            'password' => 'required'
        ]);

        // 2. Buscamos al usuario por su columna 'correo'
        $user = User::where('correo', $request->correo)->first();

        // 3. Verificamos si el usuario existe y si la contraseña coincide
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['mensaje' => 'Credenciales incorrectas'], 401);
        }

        // 4. Creamos el token mágico de Sanctum
        $token = $user->createToken('Token_Calculadora')->plainTextToken;

        // 5. Devolvemos la respuesta al subdominio
        return response()->json([
            'mensaje' => 'Login exitoso',
            'access_token' => $token,
            'user' => $user
        ], 200);
    }
}