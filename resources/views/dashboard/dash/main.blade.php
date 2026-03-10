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

        /* ================= MAIN CONTENT (Inicio) ================= */
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

        .logo-img {
            width: 30px;
            height: 40px;
            object-fit: cover;
        }
    </style>

    <div class="dashboard-container">
        
        <!-- ================= INCLUIR SIDEBAR CENTRALIZADO ================= -->
        @include('partials.sidebar')

        <!-- ================= MAIN CONTENT ================= -->
        <main class="main-content">

        <!-- EL TOPBAR LIMPIO VA AQUÍ ARRIBA -->
            @include('partials.topbar')
            
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