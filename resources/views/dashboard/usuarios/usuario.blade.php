@extends('layouts.app')

@section('content')
<div class="dash-admin-view">
    <style>
        /* Reutilizamos tus estilos principales */
        .dash-admin-view {
            min-height: 100vh;
            background-color: #f8f8f8;
            font-family: "Garamond", "Baskerville", serif;
            color: #111;
            padding: 20px;
            display: flex;
            justify-content: center;
        }
        .dashboard-container {
            display: flex;
            width: 100%;
            max-width: 1400px;
            gap: 20px;
            align-items: stretch;
        }
        .main-content {
            flex-grow: 1;
            background-color: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        .header-section {
            border-bottom: 1px solid #eaeaea;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header-section h1 { font-size: 2.2rem; font-weight: 700; margin: 0; }
        
        /* Estilos específicos para la tabla de usuarios */
        .user-table { width: 100%; border-collapse: separate; border-spacing: 0 12px; }
        .user-row { background: #fff; outline: 1px solid #eee; transition: all 0.3s ease; }
        .user-row:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .user-row td { padding: 15px 20px; vertical-align: middle; }
        
        .role-select {
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid #ddd;
            font-family: Arial, sans-serif;
            font-size: 0.85rem;
            background-color: #fcfcfc;
        }
        .btn-update {
            background: #111; color: #fff; border: none;
            padding: 8px 15px; border-radius: 6px;
            font-size: 0.75rem; letter-spacing: 1px; text-transform: uppercase;
            cursor: pointer; transition: background 0.3s;
        }
        .btn-update:hover { background: #333; }
        .badge-superadmin { background: #ffd700; color: #000; padding: 3px 8px; border-radius: 12px; font-size: 0.7rem; font-family: Arial; font-weight: bold;}
    </style>

    <div class="dashboard-container">
        
        @include('partials.sidebar')

        <main class="main-content">
            <div class="header-section">
                <h1>Gestión de Usuarios y Roles</h1>
                <p>Panel exclusivo de Superadministrador para asignar permisos.</p>
            </div>

            @if(session('success'))
                <div class="alert alert-dark mb-4">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger mb-4">{{ session('error') }}</div>
            @endif

            <table class="user-table">
                <thead>
                    <tr style="text-align: left; color: #888; font-size: 0.75rem; letter-spacing: 2px; text-transform: uppercase;">
                        <th>Usuario</th>
                        <th>Correo</th>
                        <th>Rol Asignado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuarios as $usuario)
                        <tr class="user-row">
                            <td>
                                <div style="font-weight: bold; font-size: 1rem;">
                                    {{ $usuario->nombre }}
                                    @if($usuario->id_usuario === Auth::user()->id_usuario)
                                        <span class="badge-superadmin ml-2">TÚ</span>
                                    @endif
                                </div>
                            </td>
                            <td style="font-family: Arial; font-size: 0.9rem; color: #555;">
                                {{ $usuario->correo }}
                            </td>
                            <td>
                                <form action="{{ route('usuarios.updateRol', $usuario->id_usuario) }}" method="POST" class="d-flex gap-2 align-items-center">
                                    @csrf
                                    @method('PUT')
                                    
                                    <select name="id_rol" class="role-select">
                                        @foreach($roles as $rol)
                                            <option value="{{ $rol->id_rol }}" {{ $usuario->id_rol == $rol->id_rol ? 'selected' : '' }}>
                                                {{ $rol->nombre_rol }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @if($usuario->id_usuario !== Auth::user()->id_usuario)
                                        <button type="submit" class="btn-update">Guardar</button>
                                    @else
                                        <button type="button" class="btn-update" style="opacity: 0.5; cursor: not-allowed;" disabled>Fijo</button>
                                    @endif
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </main>
    </div>
</div>
@endsection