@extends('layouts.app')

@section('content')
@php
    // --- DATOS DE PRUEBA TEMPORALES (Ordenados por mejores valorados) ---
    $mockResenas = [
        (object)[
            'nombre_cliente' => 'Arq. Carlos Mendoza',
            'calificacion' => 5,
            'comentario' => 'El desarrollo conceptual de nuestra residencia superó todas las expectativas. El manejo del minimalismo, la luz natural y las texturas es impecable. Un estudio con una visión arquitectónica madura y sumamente elegante.',
            'tiempo' => 'Hace 2 horas',
            'fecha' => '18 Jun, 2026'
        ],
        (object)[
            'nombre_cliente' => 'Sofía Guadarrama',
            'calificacion' => 5,
            'comentario' => 'Súper profesionales en el diseño de nuestro local comercial. Capturaron la esencia de la marca de inmediato y la plasmaron en una distribución espacial brutal. ¡Totalmente recomendados!',
            'tiempo' => 'Hace 3 días',
            'fecha' => '15 Jun, 2026'
        ],
        (object)[
            'nombre_cliente' => 'Ing. Alejandro Ruiz',
            'calificacion' => 4,
            'comentario' => 'Excelente propuesta y atención al detalle en los planos ejecutivos y renders 3D. Nos dio total claridad en los volúmenes antes de iniciar la obra negra. Gran equipo.',
            'tiempo' => 'Hace 1 semana',
            'fecha' => '11 Jun, 2026'
        ]
    ];

    $resenasFinales = (isset($resenas) && count($resenas) > 0) ? $resenas : $mockResenas;
@endphp

<style>
    body {
        background-color: #fafafa;
    }

    /* ================= CONTENEDOR PRINCIPAL ================= */
    .akira-container { 
        max-width: 1000px; /* Un poco más ancho para que quepan bien las tarjetas */
        margin: 0 auto; 
        padding: 60px 30px;
        font-family: "Georgia", "Times New Roman", serif; 
        color: #333; 
        position: relative;
        z-index: 1; 
    }

    /* ================= ESTILOS DE TU HEADER MINIMALISTA ================= */
    .site-header-main { margin-bottom: 70px; text-align: left; }

    .nav-link-akira {
        position: relative;
        display: inline-block;
        padding-bottom: 2px;
        text-decoration: none !important;
        color: #8c8c8c;
        transition: color 0.3s ease;
        font-size: 1.1rem;
    }
    
    .nav-link-akira::after {
        content: '';
        position: absolute;
        width: 0;
        height: 1px;
        bottom: 0;
        left: 0;
        background-color: currentColor;
        transition: width 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
    }
    
    .nav-link-akira:hover {
        color: #111111;
    }
    .nav-link-akira:hover::after {
        width: 100%;
    }

    .active-link {
        font-weight: bold !important;
        color: #111111 !important;
    }
    .active-link::after {
        width: 100% !important;
    }

    /* ================= GRID DE TARJETAS DE RESEÑAS ================= */
    .header-section {
        border-bottom: 1px solid #eaeaea;
        padding-bottom: 20px;
        margin-bottom: 40px;
        font-family: Arial, sans-serif; /* Para contrastar con la tipografía serif del menú */
    }
    .header-section h1 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 10px;
        color: #111;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .header-section p {
        color: #666;
        font-size: 0.95rem;
        max-width: 600px;
        line-height: 1.5;
    }

    .reviews-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 25px;
        margin-bottom: 60px;
        font-family: Arial, sans-serif;
    }

    .review-card {
        background: #fff;
        border: 1px solid #eaeaea;
        border-radius: 0px; 
        padding: 30px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .review-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.03);
    }

    .review-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 18px;
    }
    .reviewer-info { display: flex; flex-direction: column; }
    .reviewer-name { font-weight: 700; font-size: 1.05rem; color: #111; margin: 0; }
    .review-time { font-size: 0.75rem; color: #999; margin-top: 3px; }

    .star-rating { color: #eab308; font-size: 1rem; letter-spacing: 2px; }
    .star-empty { color: #eaeaea; }

    .review-body {
        font-size: 0.92rem;
        color: #444;
        line-height: 1.6;
        flex-grow: 1;
        margin-bottom: 20px;
        font-style: italic;
    }

    .review-footer {
        border-top: 1px solid #f5f5f5;
        padding-top: 15px;
        font-size: 0.75rem;
        color: #999;
        display: flex;
        justify-content: space-between;
    }

    .btn-leave-review {
        display: inline-block;
        background: #111;
        color: #fff;
        padding: 10px 25px;
        text-decoration: none;
        font-weight: bold;
        letter-spacing: 1px;
        text-transform: uppercase;
        font-size: 0.75rem;
        transition: background 0.3s;
        border: none;
        margin-top: 15px;
    }
    .btn-leave-review:hover { background: #333; color: #fff; }
</style>

<div class="akira-container">
    
    {{-- AQUÍ ESTÁ TU MENÚ TIPOGRÁFICO EXACTO CON LA NUEVA PESTAÑA --}}
    <header class="site-header-main">
        <a href="{{ route('project.detail') ?? '#' }}" class="nav-link-akira {{ request()->routeIs('project.detail') ? 'active-link' : '' }}">Estudio Akiraka ,</a>
        <a href="{{ route('info') ?? '#' }}" class="nav-link-akira {{ request()->routeIs('info') ? 'active-link' : '' }}">Info ,</a>
        
        {{-- Aquí metimos la vista actual de reseñas para que se pinte en negro por el active-link --}}
        <a href="{{ route('resenas.index') ?? '#' }}" class="nav-link-akira {{ request()->routeIs('resenas.index') ? 'active-link' : '' }}">Reseñas ,</a>
        
        <a href="{{ route('contacto') ?? '#' }}" class="nav-link-akira {{ request()->routeIs('contacto') ? 'active-link' : '' }}">Contacto</a>
    </header>

    <div class="header-section">
        <h1>Experiencia Akira</h1>
        <p>Las opiniones de nuestros clientes avalan el compromiso y rigor técnico depositados en cada obra, plano e idea conceptualizada por nuestro estudio.</p>
        
        <button onclick="abrirModalResena()" class="btn-leave-review">
            Añadir recomendación
        </button>
    </div>

    <div class="reviews-grid">
        @foreach($resenasFinales as $resena)
            <div class="review-card">
                <div>
                    <div class="review-header">
                        <div class="reviewer-info">
                            <h3 class="reviewer-name">{{ $resena->nombre_cliente }}</h3>
                            <span class="review-time">
                                {{ is_string($resena->tiempo) ? $resena->tiempo : $resena->created_at->diffForHumans() }}
                            </span>
                        </div>
                        
                        <div class="star-rating">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $resena->calificacion)
                                    ★
                                @else
                                    <span class="star-empty">★</span>
                                @endif
                            @endfor
                        </div>
                    </div>

                    <div class="review-body">
                        "{!! e($resena->comentario) !!}"
                    </div>
                </div>

                <div class="review-footer">
                    <span>Publicado:</span>
                    <span>
                        {{ isset($resena->fecha) ? $resena->fecha : $resena->created_at->format('d M, Y') }}
                    </span>
                </div>
            </div>
        @endforeach
    </div>
</div>

@include('dashboard.login.login') 
@include('dashboard.login.registro')
{{-- @include('partials.modal_resena') --}}

@endsection