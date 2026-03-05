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
            align-items: stretch; /* Sidebar se adapta al alto del main content */
        }

        /* Sidebar adaptativa */
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

        .sidebar img {
            width: 80px;
            border-radius: 50%;
            margin-bottom: 10px;
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
        }

        .nav-link:hover { background-color: #3a3a3a; text-decoration: none; }

        .icon-badge {
            background-color: #555;
            color: #fff;
            border-radius: 4px;
            padding: 2px 6px;
            font-size: 0.75rem;
        }

        /* Main content */
        .main-content {
            flex-grow: 1;
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .header-welcome h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .header-welcome p {
            font-size: 1rem;
            color: #555;
            max-width: 700px;
            margin: auto;
            margin-bottom: 40px;
        }

        .stats-cards {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            justify-content: center;
            margin-bottom: 40px;
        }

        .stat-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            padding: 20px;
            width: 250px;
            text-align: center;
        }

        .stat-card h3 { font-size: 1.5rem; margin-bottom: 10px; color: #111; }
        .stat-card p { color: #555; font-size: 0.9rem; }

        .chart-placeholder {
            background: #e0e0e0;
            height: 150px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #555;
            margin-bottom: 15px;
        }

        .projects-section {
            margin-top: 40px;
        }

        .projects-section h5 {
            font-weight: 700;
            border-bottom: 2px solid #ccc;
            padding-bottom: 6px;
            margin-bottom: 20px;
            font-size: 1.3rem;
        }

        .project-card {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }

        .project-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .project-card small {
            display: block;
            padding: 10px;
            font-weight: 500;
            color: #111;
        }

        .btn-back-center {
            display: inline-block;
            padding: 20px 60px;
            border: 2px solid #111;
            background: transparent;
            color: #111;
            text-decoration: none;
            font-size: 1.1rem;
            letter-spacing: 3px;
            margin-top: 40px;
            transition: all 0.4s ease;
            border-radius: 8px;
        }

        .btn-back-center:hover {
            background-color: #111;
            color: #fff;
            transform: scale(1.05);
        }
        .logo-img {
            width: 30px;
            height: 40px;
            object-fit: cover;
        }
    </style>

    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar text-center">
            <div>
           <img src="{{ asset('images/logo_akiraka.png') }}" alt="Logo" class="logo-img">
                <p class="small mb-4">Akiraka Estudio</p>
                <ul class="nav flex-column text-center">
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-home me-2 "></i> Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-pencil-alt me-2"></i> Proyectos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard.quienes_somos') ? 'active' : '' }}" 
                        href="{{ route('dashboard.quienes_somos') }}"><i class="fas fa-globe me-2"></i> Quienes somos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-envelope me-2"></i> Contacto</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-comments me-2"></i> Mensajes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard.opciones') }}">
                            <i class="fas fa-globe me-2"></i> Opciones</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-link border-top pt-3 w-100 text-start" style="background:none; border:none;">
                        <i class="fas fa-sign-out-alt me-2"></i> Salir <span class="icon-badge">
                    </button>
                </form>
                <div class="mt-4 text-center small">
                    
                    <p>Akiraka<br>Dirección<br>Derechos reservados</p>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="header-welcome text-center">
                <h1>Bienvenido(a)</h1>
                <p>“No diseñamos casas, construimos el escenario de tus mejores recuerdos.”</p>
            </div>

            <div class="stats-cards">
                <div class="stat-card">
                    <div class="chart-placeholder">Gráfico</div>
                    <h3>Materiales</h3>
                    <p>Más solicitados este mes</p>
                </div>
                <div class="stat-card">
                    <div class="chart-placeholder">Barras</div>
                    <h3>Ganancias</h3>
                    <p>Ganancias y pérdidas recientes</p>
                </div>
            </div>

            <div class="projects-section">
                <h5>Proyectos en proceso</h5>
                <div class="row">
                    <div class="col-md-4 project-card">
                        <small>Remodelación oficina...</small>
                        <img src="{{ asset('images/oficina.jpg') }}">
                    </div>
                    <div class="col-md-4 project-card">
                        <small>Construcción consultorio...</small>
                        <img src="{{ asset('images/consultorio.jpg') }}">
                    </div>
                    <div class="col-md-4 project-card">
                        <small>Remodelación cocina...</small>
                        <img src="{{ asset('images/cocina.jpg') }}">
                    </div>
                </div>
            </div>

            <div class="projects-section">
                <h5>Proyectos futuros</h5>
                <div class="row">
                    <div class="col-md-6 project-card">
                        <small>Remodelación Centro Turístico</small>
                        <img src="{{ asset('images/turismo.jpg') }}" style="filter: grayscale(100%);">
                    </div>
                    <div class="col-md-6 project-card">
                        <small>Casa moderna</small>
                        <img src="{{ asset('images/casa.jpg') }}" style="filter: grayscale(100%);">
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection