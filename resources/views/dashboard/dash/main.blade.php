@extends('layouts.app')

@section('content')
<div class="dash-admin-view">
    <style>
        /* ================= ESTILOS BASE DEL DASHBOARD ================= */
        .dash-admin-view { min-height: 100vh; background-color: #f8f8f8; font-family: "Garamond", "Baskerville", serif; color: #111; padding: 20px; display: flex; justify-content: center; }
        .dashboard-container { display: flex; width: 100%; max-width: 1400px; gap: 20px; align-items: stretch; }
        
        /* ================= MAIN CONTENT ================= */
        .main-content { flex-grow: 1; background-color: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .header-welcome h1 { font-size: 2.5rem; font-weight: 700; margin-bottom: 10px; }
        .header-welcome p { font-size: 1rem; color: #555; max-width: 700px; margin: auto; margin-bottom: 40px; }
        
        .stats-cards { display: flex; gap: 20px; flex-wrap: wrap; justify-content: center; margin-bottom: 40px; }
        .stat-card { background: #fff; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); padding: 20px; width: 250px; text-align: center; }
        .stat-card h3 { font-size: 1.5rem; margin-bottom: 10px; color: #111; font-family: Arial, sans-serif;}
        .stat-card p { color: #555; font-size: 0.9rem; }
        
        .chart-placeholder { background: #111; height: 80px; width: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #fff; margin: 0 auto 15px auto; font-family: Arial, sans-serif; font-size: 1.5rem; }

        .projects-section { margin-top: 40px; }
        .projects-section h5 { font-weight: 700; border-bottom: 2px solid #ccc; padding-bottom: 6px; margin-bottom: 20px; font-size: 1.3rem; }
        
        .project-card { background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.08); margin-bottom: 20px; transition: transform 0.3s ease; }
        .project-card:hover { transform: translateY(-5px); }
        .project-card img { width: 100%; height: 200px; object-fit: cover; }
        .project-card small { display: block; padding: 15px; font-weight: bold; color: #111; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 1px; }
    </style>

    <div class="dashboard-container">
        
        @include('partials.sidebar')

        <main class="main-content">

        @include('partials.topbar')
            
            <div class="header-welcome text-center">
                <h1>Bienvenido(a), {{ Auth::user()->nombre }}</h1>
                <p>“No diseñamos casas, construimos el escenario de tus mejores recuerdos.”</p>
            </div>

            <div class="stats-cards">
                <div class="stat-card">
                    <div class="chart-placeholder">{{ $totalProyectos }}</div>
                    <h3>Proyectos</h3>
                    <p>En el portafolio</p>
                </div>
                <div class="stat-card">
                    <div class="chart-placeholder" style="background: #10b981;">$</div>
                    <h3>${{ number_format($inversionTotal, 2) }}</h3>
                    <p>Inversión total estimada</p>
                </div>
            </div>

            <div class="projects-section">
                <h5>Proyectos en proceso</h5>
                <div class="row">
                    @forelse($proyectosEnProceso as $proyecto)
                        <div class="col-md-4">
                            <div class="project-card">
                                <a href="{{ route('proyectos.historias', $proyecto->id_proyecto) }}" style="text-decoration: none;">
                                    <img src="{{ $proyecto->portada ? asset('storage/' . $proyecto->portada) : 'https://via.placeholder.com/400x200?text=Sin+Imagen' }}">
                                    <small>{{ $proyecto->titulo }}</small>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-4 text-muted" style="font-family: Arial;">
                            No hay proyectos en proceso actualmente.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="projects-section">
                <h5>Proyectos Construidos / Futuros</h5>
                <div class="row">
                    @forelse($proyectosFuturos as $proyecto)
                        <div class="col-md-6">
                            <div class="project-card">
                                <a href="{{ route('proyectos.historias', $proyecto->id_proyecto) }}" style="text-decoration: none;">
                                    <img src="{{ $proyecto->portada ? asset('storage/' . $proyecto->portada) : 'https://via.placeholder.com/600x300?text=Sin+Imagen' }}" style="filter: grayscale(100%);">
                                    <small>{{ $proyecto->titulo }}</small>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-4 text-muted" style="font-family: Arial;">
                            No hay proyectos finalizados o futuros registrados.
                        </div>
                    @endforelse
                </div>
            </div>
        </main>
    </div>
</div>
@endsection