@extends('layouts.app')

@section('content')
<div class="dash-admin-view">
    <style>
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

        /* ================= SIDEBAR ================= */
        .sidebar {
            width: 260px;
            background-color: #1c1c1c;
            color: #fff;
            padding: 25px;
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .logo-sidebar {
            width: 80px;
            border-radius: 50%;
            margin-bottom: 10px;
            background-color: #fff;
            padding: 5px;
        }

        .nav-link {
            color: #fff;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #2c2c2c;
            border-radius: 8px;
            padding: 10px 15px;
            transition: all 0.3s ease;
            text-decoration: none;
            font-family: Arial, sans-serif;
            font-size: 0.9rem;
        }

        .nav-link:hover, .nav-link.active { 
            background-color: #4b4b4b; 
        }

        .icon-badge {
            background-color: #10b981;
            color: #fff;
            border-radius: 4px;
            padding: 2px 6px;
            font-size: 0.75rem;
        }

        /* ================= MAIN CONTENT & TABLE ================= */
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
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .header-section h1 { font-size: 2.2rem; font-weight: 700; margin: 0; }

        /* Estilos de la tabla de equipo */
        .equipo-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 12px;
        }

        .equipo-row {
            background: #fff;
            outline: 1px solid #eee;
            transition: all 0.3s ease;
        }

        .equipo-row:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .equipo-row td {
            padding: 20px;
            vertical-align: middle;
        }

        .img-avatar {
            width: 55px;
            height: 55px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .biografia-text {
            color: #666;
            font-size: 0.85rem;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .btn-action-minimal {
            background: none;
            border: none;
            color: #111;
            text-transform: uppercase;
            font-size: 0.7rem;
            font-weight: bold;
            letter-spacing: 1px;
            text-decoration: underline;
            margin-right: 15px;
            cursor: pointer;
        }

        .btn-add-new {
            background: #111;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 0.8rem;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
    </style>

    <div class="dashboard-container">
<aside class="sidebar">
            <div>
                <div class="text-center">
                    <img src="{{ asset('images/logo_akiraka.png') }}" alt="Logo" class="logo-img">
                    <p class="small mb-4 text-uppercase" style="letter-spacing: 2px;">Akiraka Estudio</p>
                </div>
                
                <ul class="nav flex-column" style="list-style: none; padding: 0;">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard.main') }}"><i class="fas fa-home me-2"></i> Inicio <span class="icon-badge"></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-pencil-alt me-2"></i> Proyectos <span class="icon-badge"></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('dashboard.quienes_somos') }}"><i class="fas fa-globe me-2"></i> Quienes somos <span class="icon-badge"></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-newspaper me-2"></i> Publicaciones <span class="icon-badge"></a>
                    </li>
                    <li class="nav-item">
                         <a href="{{ route('mensajes') }}" class="nav-link active"><i class="fas fa-globe me-2"></i>Mensajes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard.opciones') }}"><i class="fas fa-cog me-2"></i> Opciones <span class="icon-badge"></a>
                    </li>
                </ul>
            </div>
            
            <div>
                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="nav-link border-top pt-3 w-100 text-start" style="background:none; border:none; cursor:pointer;">
                        <i class="fas fa-sign-out-alt me-2"></i> Salir <span class="icon-badge">
                    </button>
                </form>
                <div class="mt-4 text-center" style="font-size: 0.75rem; color: #888;">
                    <p class="mb-0">© {{ date('Y') }} AKIRAKA ESTUDIO</p>
                </div>
            </div>
        </aside>

        <main class="main-content">
            <div class="header-section">
                <div>
                    <h1>Nuestro Equipo</h1>
                    <p>Gestión de perfiles profesionales para el portafolio público.</p>
                </div>
                <button class="btn-add-new" onclick="abrirRegistro()">+ Añadir Miembro</button>
            </div>

            <table class="equipo-table">
                <thead>
                    <tr style="text-align: left; color: #888; font-size: 0.75rem; letter-spacing: 2px; text-transform: uppercase;">
                        <th style="padding-left: 20px;">Miembro</th>
                        <th>Biografía Profesional</th>
                        <th style="text-align: right; padding-right: 20px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="equipo-row">
                        <td style="width: 250px;">
                            <div class="d-flex align-items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name=Akira+Kameta&background=111&color=fff" class="img-avatar">
                                <div>
                                    <div style="font-weight: bold; font-size: 1rem;">Akira Kameta</div>
                                    <div style="font-size: 0.75rem; color: #888; font-family: Arial;">Director Creativo</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <p class="biografia-text">
                                Arquitecto especializado en diseño minimalista y sostenible, con más de 10 años de experiencia en proyectos residenciales de alta gama.
                            </p>
                        </td>
                        <td style="text-align: right;">
                            <button class="btn-action-minimal">Editar</button>
                            <button class="btn-action-minimal" style="color: #d9534f;">Eliminar</button>
                        </td>
                    </tr>

                    <tr class="equipo-row">
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name=Luis+Roberto&background=111&color=fff" class="img-avatar">
                                <div>
                                    <div style="font-weight: bold;">Luis Roberto Flores</div>
                                    <div style="font-size: 0.75rem; color: #888; font-family: Arial;">Admin / Desarrollador</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <p class="biografia-text">
                                Ingeniero encargado de la infraestructura digital y automatización de procesos para el despacho.
                            </p>
                        </td>
                        <td style="text-align: right;">
                            <button class="btn-action-minimal">Editar</button>
                            <button class="btn-action-minimal" style="color: #d9534f;">Eliminar</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </main>
    </div>
</div>
@endsection