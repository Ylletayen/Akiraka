<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Hash; 
use App\Models\User;                

class AuthController extends Controller
{
   public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['correo' => $credentials['email'], 'password' => $credentials['password']])) {
            
            // FILTRO DE SEGURIDAD: Si el rol es 4 (Pendiente), lo botamos
            if (Auth::user()->id_rol == 4) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Tu cuenta ha sido registrada pero sigue en proceso de validación por un administrador.',
                ]);
            }

            // Si pasa el filtro, lo dejamos entrar normal
            $request->session()->regenerate();
            return redirect()->route('dashboard.main');
        }

        return back()->withErrors([
            'email' => 'Acceso denegado. Verifica tus credenciales.',
        ]);
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'correo' => 'required|email|unique:usuarios,correo',
            'password' => 'required|min:6',
            'foto' => 'nullable|image|max:2048',
        ]);

        $rutaFoto = null;
        if ($request->hasFile('foto')) {
            $rutaFoto = $request->file('foto')->store('perfiles', 'public');
        }

        \App\Models\User::create([
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'password' => Hash::make($request->password),
            'foto' => $rutaFoto,
            'id_rol' => 4, // <-- Le clavamos el rol "Pendiente" por defecto
        ]);

        return redirect()->route('landing')->with('success', 'Usuario registrado con éxito. Tu cuenta está en proceso de validación.');
    }
}