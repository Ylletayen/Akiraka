@extends('layouts.app')

@section('content')
<div class="dash-admin-view">
    <style>
        /* ================= ESTILOS BASE DEL DASHBOARD ================= */
        .dash-admin-view { min-height: 100vh; background-color: #f8f8f8; font-family: "Garamond", "Baskerville", serif; color: #111; padding: 20px; display: flex; justify-content: center; }
        .dashboard-container { display: flex; width: 100%; max-width: 1400px; gap: 20px; align-items: stretch; }
        
        .main-content { flex-grow: 1; background-color: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .header-welcome { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 30px; border-bottom: 1px solid #eaeaea; padding-bottom: 20px; }
        .header-welcome h1 { font-size: 2.2rem; font-weight: 700; margin-bottom: 5px; }
        .header-welcome p { font-size: 1rem; color: #555; margin: 0; font-style: italic; }
        
        .btn-quick { background: #111; color: #fff; padding: 10px 20px; font-family: Arial, sans-serif; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; border-radius: 6px; text-decoration: none; transition: all 0.3s ease; }
        .btn-quick:hover { background: #333; color: #fff; }

        /* ================= STATS CARDS ================= */
        .stats-cards { display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 40px; }
        .stat-card { background: #fff; border: 1px solid #eaeaea; border-radius: 12px; padding: 20px; flex: 1; min-width: 200px; display: flex; align-items: center; gap: 20px; transition: transform 0.3s; }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
        .chart-placeholder { background: #111; height: 60px; width: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #fff; font-family: Arial, sans-serif; font-size: 1.2rem; flex-shrink: 0; }
        .stat-info h3 { font-size: 1.5rem; margin: 0 0 5px 0; color: #111; font-family: Arial, sans-serif; font-weight: bold; }
        .stat-info p { color: #888; font-size: 0.85rem; margin: 0; text-transform: uppercase; letter-spacing: 1px; font-family: Arial, sans-serif; }

        /* ================= GRÁFICAS ================= */
        .charts-row { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 40px; }
        .chart-box { background: #fff; border-radius: 12px; border: 1px solid #eaeaea; padding: 20px; }
        .chart-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .chart-header h5 { font-weight: bold; font-size: 1.1rem; margin: 0; }
        .chart-subtitle { font-family: Arial, sans-serif; font-size: 0.8rem; color: #888; }

        /* ================= GRID DE CONTENIDO ================= */
        .dashboard-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 30px; }
        @media (max-width: 1000px) {
            .dashboard-grid, .charts-row { grid-template-columns: 1fr; }
            .header-welcome { flex-direction: column; align-items: flex-start; gap: 20px; }
        }

        .projects-section h5, .alerts-section h5 { font-weight: 700; border-bottom: 2px solid #111; padding-bottom: 10px; margin-bottom: 20px; font-size: 1.2rem; }
        .project-card { background: #fff; border-radius: 8px; overflow: hidden; border: 1px solid #eaeaea; margin-bottom: 20px; transition: transform 0.3s ease; }
        .project-card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.08); }
        .project-card img { width: 100%; height: 180px; object-fit: cover; }
        .project-card small { display: block; padding: 12px 15px; font-weight: bold; color: #111; font-size: 0.9rem; text-transform: uppercase; font-family: Arial, sans-serif; }

        /* ================= WIDGET CITAS ================= */
        .alert-item { padding: 15px; border-left: 3px solid #10b981; background: #fdfdfd; border-radius: 0 8px 8px 0; margin-bottom: 15px; border: 1px solid #eee; border-left-width: 3px; transition: background 0.3s; }
        .alert-item:hover { background: #f4f4f4; }
        .alert-item-header { display: flex; justify-content: space-between; margin-bottom: 5px; }
        .alert-name { font-weight: bold; font-family: Arial, sans-serif; font-size: 0.9rem; color: #111; }
        .alert-time { font-size: 0.75rem; color: #888; font-family: Arial, sans-serif; }
        .alert-service { font-size: 0.85rem; color: #555; margin: 0; }
    </style>

    <div class="dashboard-container">
        @include('partials.sidebar')

        <main class="main-content">
            @include('partials.topbar')
            
            <div class="header-welcome">
                <div>
                    <h1>Bienvenido(a), {{ Auth::user()->nombre }}</h1>
                    <p>“No diseñamos casas, construimos el escenario de tus mejores recuerdos.”</p>
                </div>
                <div class="quick-actions">
                    <a href="{{ route('proyectos.store') }}" class="btn-quick"><i class="bi bi-plus-lg"></i> Nuevo Proyecto</a>
                </div>
            </div>

            <div class="stats-cards">
                <div class="stat-card">
                    <div class="chart-placeholder">{{ $totalProyectos }}</div>
                    <div class="stat-info">
                        <h3>Obras</h3>
                        <p>En el portafolio</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="chart-placeholder" style="background: #444;">{{ $totalObjetos }}</div>
                    <div class="stat-info">
                        <h3>Objetos</h3>
                        <p>En exhibición</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="chart-placeholder" style="background: #10b981;"><i class="bi bi-journal-bookmark"></i></div>
                    <div class="stat-info">
                        <h3>{{ \App\Models\Publicacion::count() }}</h3>
                        <p>Publicaciones</p>
                    </div>
                </div>
            </div>

            <div class="charts-row">
                <div class="chart-box">
                    <div class="chart-header">
                        <h5>Tráfico de la Página Web</h5>
                        <span class="chart-subtitle">Últimos 7 días</span>
                    </div>
                    <canvas id="dailyChart" height="90"></canvas>
                </div>

                <div class="chart-box">
                    <div class="chart-header">
                        <h5>Tipos de Visitante</h5>
                        <span class="chart-subtitle">{{ number_format($totalVisitasMes) }} este mes</span>
                    </div>
                    <div style="position: relative; height: 180px; width: 100%; display: flex; justify-content: center;">
                        <canvas id="monthlyPieChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="dashboard-grid">
                <div class="projects-wrapper">
                    <div class="projects-section">
                        <h5>Proyectos Destacados</h5>
                        <div class="row">
                            @forelse($proyectosEnProceso->merge($proyectosFuturos)->take(4) as $proyecto)
                                <div class="col-md-6">
                                    <div class="project-card">
                                        <a href="{{ route('proyectos.historias', $proyecto->id_proyecto) }}" style="text-decoration: none;">
                                            <img src="{{ $proyecto->portada ? asset('imagenes/' . $proyecto->portada) : 'https://via.placeholder.com/400x200?text=Sin+Imagen' }}">
                                            <small>{{ $proyecto->titulo }}</small>
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-4 text-muted" style="font-family: Arial; font-size: 0.9rem;">
                                    Aún no hay proyectos registrados.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="alerts-section">
                    <h5>Solicitudes Pendientes</h5>
                    
                    @forelse($citasRecientes as $cita)
                        <div class="alert-item">
                            <div class="alert-item-header">
                                <span class="alert-name">{{ $cita->cliente_nombre }}</span>
                                <span class="alert-time" style="text-transform: capitalize;">{{ \Carbon\Carbon::parse($cita->created_at)->locale('es')->diffForHumans() }}</span>
                            </div>
                            <p class="alert-service">{{ $cita->servicio_nombre }}</p>
                        </div>
                    @empty
                        <div style="text-align: center; padding: 30px 10px; color: #888; font-style: italic; font-size: 0.9rem; border: 1px dashed #ccc; border-radius: 8px;">
                            No hay solicitudes pendientes.<br>¡Todo al día!
                        </div>
                    @endforelse

                    <a href="{{ route('dashboard.citas') }}" style="display: block; text-align: center; font-family: Arial, sans-serif; font-size: 0.85rem; color: #111; margin-top: 20px; font-weight: bold; text-decoration: none;">
                        Ir a la bandeja completa &rarr;
                    </a>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const colorOscuro = '#111111';
        const colorVerde = '#10b981';

        // 1. GRÁFICA SEMANAL
        const ctxDaily = document.getElementById('dailyChart').getContext('2d');
        new Chart(ctxDaily, {
            type: 'line',
            data: {
                labels: @json($labelsSemanales),
                datasets: [{
                    label: 'Visitas únicas',
                    data: @json($visitasSemanales), 
                    borderColor: colorOscuro,
                    backgroundColor: 'rgba(17, 17, 17, 0.05)',
                    borderWidth: 2, tension: 0.4, fill: true,
                    pointBackgroundColor: colorVerde, pointRadius: 4, pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [5, 5] }, ticks: { stepSize: 1 } },
                    x: { grid: { display: false } }
                },
                plugins: { legend: { display: false } }
            }
        });

        // 2. GRÁFICA MENSUAL (Dona)
        const ctxMonthly = document.getElementById('monthlyPieChart').getContext('2d');
        new Chart(ctxMonthly, {
            type: 'doughnut',
            data: {
                labels: ['Nuevos', 'Recurrentes'],
                datasets: [{
                    data: [@json($visitantesNuevos), @json($visitantesRecurrentes)],
                    backgroundColor: [colorOscuro, colorVerde],
                    borderWidth: 0, hoverOffset: 4
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '70%',
                plugins: { legend: { position: 'bottom', labels: { font: { family: 'Arial' } } } }
            }
        });
    });
</script>
@endsection