@extends('layouts.app')

@section('content')
<div class="dash-admin-view">
    <style>
        /* ================= ESTILOS BASE DEL DASHBOARD ================= */
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

        /* ================= SIDEBAR (Diseño original) ================= */
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

        .sidebar img.logo-sidebar {
            width: 80px;
            border-radius: 50%;
            margin-bottom: 10px;
            background-color: #fff; /* Fondo blanco si el logo es oscuro */
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
            font-weight: 500;
            text-decoration: none;
            font-family: "Helvetica Neue", Arial, sans-serif; /* Las opciones del menú suelen ser sans-serif para leerse mejor */
            font-size: 0.9rem;
        }

        .nav-link:hover, .nav-link.active { 
            background-color: #4b4b4b; 
            color: #fff;
        }

        .icon-badge {
            background-color: #10b981; /* Rayito verde de Akiraka */
            color: #fff;
            border-radius: 4px;
            padding: 2px 6px;
            font-size: 0.75rem;
        }

        /* ================= MAIN CONTENT ================= */
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
            margin-bottom: 40px;
        }

        .header-section h1 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .header-section p {
            color: #666;
            font-style: italic;
        }

        /* ================= ESTILOS PROPIOS DE OPCIONES ================= */
        .options-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
        }

        @media (max-width: 992px) {
            .options-grid {
                grid-template-columns: 1fr;
            }
        }

        .options-card {
            background: #fff;
            border: 1px solid #eaeaea;
            border-radius: 8px;
            padding: 30px;
            position: relative;
            overflow: hidden;
        }

        /* Línea superior decorativa para las tarjetas */
        .options-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background-color: #111;
        }

        .options-card h3 {
            font-size: 1.4rem;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .options-card p.subtitle {
            font-size: 0.9rem;
            color: #888;
            margin-bottom: 25px;
            font-family: "Helvetica Neue", Arial, sans-serif;
        }

        .form-group {
            margin-bottom: 20px;
            font-family: "Helvetica Neue", Arial, sans-serif;
        }

        .form-group label {
            display: block;
            font-size: 0.75rem;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #555;
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 0.95rem;
            background-color: #fafafa;
            transition: all 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: #111;
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(17, 17, 17, 0.1);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 80px;
        }

        .divider {
            height: 1px;
            background-color: #eaeaea;
            margin: 25px 0;
        }

        .btn-save {
            display: block;
            width: 100%;
            padding: 15px;
            background-color: #111;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-family: "Helvetica Neue", Arial, sans-serif;
        }

        .btn-save:hover {
            background-color: #333;
        }

        .btn-outline {
            background-color: transparent;
            color: #111;
            border: 2px solid #111;
        }

        .btn-outline:hover {
            background-color: #f8f8f8;
            color: #000;
        }
    </style>

    <div class="dashboard-container">
        <!-- ================= SIDEBAR ================= -->
        <aside class="sidebar">
            <div>
                <div class="text-center">
                    <img src="{{ asset('logo.png') }}" alt="Logo" class="logo-sidebar">
                    <p class="small mb-4 text-uppercase" style="letter-spacing: 2px;">Akiraka Estudio</p>
                </div>
                
                <ul class="nav flex-column" style="list-style: none; padding: 0;">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard.main') }}"><i class="fas fa-home me-2"></i> Inicio <span class="icon-badge">⚡</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-pencil-alt me-2"></i> Proyectos <span class="icon-badge">⚡</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-users me-2"></i> Quienes somos <span class="icon-badge">⚡</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-newspaper me-2"></i> Publicaciones <span class="icon-badge">⚡</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-envelope me-2"></i> Mensajes <span class="icon-badge">⚡</span></a>
                    </li>
                    <li class="nav-item">
                        <!-- Botón Activo actualizado con la ruta -->
                        <a class="nav-link active" href="{{ route('dashboard.opciones') }}"><i class="fas fa-cog me-2"></i> Opciones <span class="icon-badge">⚡</span></a>
                    </li>
                </ul>
            </div>
            
            <div>
                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="nav-link border-top pt-3 w-100 text-start" style="background:none; border:none; cursor:pointer;">
                        <i class="fas fa-sign-out-alt me-2"></i> Salir <span class="icon-badge">⚡</span>
                    </button>
                </form>
                <div class="mt-4 text-center" style="font-size: 0.75rem; color: #888;">
                    <p class="mb-1 text-uppercase">Akiraka Estudio</p>
                    <p class="mb-0">© {{ date('Y') }} Derechos reservados</p>
                </div>
            </div>
        </aside>

        <!-- ================= MAIN CONTENT ================= -->
        <main class="main-content">
            
            <div class="header-section">
                <h1>Opciones del Sistema</h1>
                <p>Configuración de cuenta administrativa e información pública del sitio.</p>
            </div>

            <div class="options-grid">
                
                <!-- TARJETA 1: PERFIL DE ADMINISTRADOR -->
                <div class="options-card">
                    <h3>Perfil de Administrador</h3>
                    <p class="subtitle">Actualiza tus credenciales de acceso</p>

                    <!-- Formulario para actualizar el Admin logueado -->
                    <form action="#" method="POST">
                        @csrf
                        @method('PUT') <!-- Usualmente se usa PUT para actualizar -->
                        
                        <div class="form-group">
                            <label>Nombre Mostrado</label>
                            <input type="text" name="nombre" class="form-control" value="{{ Auth::user()->nombre ?? 'Akira Kameta Miyamoto' }}" required>
                        </div>

                        <div class="form-group">
                            <label>Correo de Acceso (Login)</label>
                            <input type="email" name="correo" class="form-control" value="{{ Auth::user()->correo ?? 'administracion@akirakastudio.com' }}" required>
                        </div>

                        <div class="divider"></div>
                        <p class="subtitle mb-3" style="font-weight: bold; color: #111;">Cambio de Contraseña</p>

                        <div class="form-group">
                            <label>Contraseña Actual</label>
                            <input type="password" name="password_actual" class="form-control" placeholder="••••••••">
                        </div>

                        <div class="form-group">
                            <label>Nueva Contraseña</label>
                            <input type="password" name="password_nueva" class="form-control" placeholder="••••••••">
                        </div>

                        <button type="submit" class="btn-save mt-4">Guardar Perfil</button>
                    </form>
                </div>

                <!-- TARJETA 2: DATOS PÚBLICOS DEL SITIO -->
                <div class="options-card">
                    <h3 style="color: #444;">Datos Públicos</h3>
                    <p class="subtitle">Información visible para los clientes en la web</p>

                    <form action="#" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div style="display: flex; gap: 15px;">
                            <div class="form-group" style="flex: 1;">
                                <label>Teléfono Público</label>
                                <input type="text" name="telefono" class="form-control" value="722 165 5901">
                            </div>

                            <div class="form-group" style="flex: 1;">
                                <label>Correo de Contacto</label>
                                <input type="email" name="correo_contacto" class="form-control" value="akiraka.estudio@gmail.com">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Dirección del Estudio</label>
                            <textarea name="direccion" class="form-control" rows="2">Parque Santa María 10, Santa María Ahuacatlán, 51200 Valle de Bravo, Estado de México</textarea>
                        </div>

                        <div class="form-group">
                            <label>Enlace de Instagram</label>
                            <input type="url" name="instagram" class="form-control" value="https://www.instagram.com/">
                        </div>

                        <button type="submit" class="btn-save btn-outline mt-4" style="margin-top: 55px;">Actualizar Datos Públicos</button>
                    </form>
                </div>

            </div>

        </main>
    </div>
</div>
@endsection