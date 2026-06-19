@extends('layouts.app')

@section('content')
@php
    $config = \App\Models\Configuracion::first();
    
    // Obtenemos el fondo dinámico (imagen o video) tal cual lo hace Contactos
    $mediaUrl = $config && $config->landing_hero_image 
                 ? asset('storage/' . $config->landing_hero_image) 
                 : 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&q=80&w=1920';
                 
    $isVideo = preg_match('/\.(mp4|webm)$/i', $mediaUrl);

    // --- DATOS DE PRUEBA TEMPORALES ---
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
    /* ================= ESTILOS GLOBALES Y COLUMNAS MULTIMEDIA ================= */
    body { background-color: #fafafa; }

    .side-media {
        position: fixed;
        top: 0;
        width: 14vw;
        height: 100vh;
        z-index: -1;
        overflow: hidden;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        filter: blur(6px) opacity(0.45) grayscale(20%); 
        transition: filter 0.8s ease;
    }

    .side-left { 
        left: 0; 
        -webkit-mask-image: linear-gradient(to right, black 30%, transparent 100%);
        mask-image: linear-gradient(to right, black 30%, transparent 100%);
    }
    
    .side-right { 
        right: 0; 
        -webkit-mask-image: linear-gradient(to left, black 30%, transparent 100%);
        mask-image: linear-gradient(to left, black 30%, transparent 100%);
    }
    
    .hero-video-bg {
        position: absolute;
        top: 50%;
        left: 50%;
        min-width: 100%;
        min-height: 100%;
        width: auto;
        height: auto;
        transform: translateX(-50%) translateY(-50%);
        object-fit: cover;
    }

    @media (max-width: 1100px) {
        .side-media { display: none !important; }
    }

    /* ================= CONTENEDOR PRINCIPAL (IDÉNTICO A CONTACTOS) ================= */
    .akira-container { 
        max-width: 780px; /* Regresamos al ancho súper elegante y angosto */
        margin: 0 auto; 
        padding: 50px 30px;
        font-family: "Georgia", "Times New Roman", serif; /* Fuente editorial para todo */
        color: #333; 
        position: relative;
        z-index: 1; 
    }

    /* ================= HEADER / MENU TIPOGRÁFICO ================= */
    .site-header-main { margin-bottom: 60px; }

    .nav-link-akira {
        position: relative;
        display: inline-block;
        padding-bottom: 2px;
        text-decoration: none !important;
        color: #8c8c8c;
        transition: color 0.3s ease;
        font-family: "Georgia", "Times New Roman", serif;
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
    
    .nav-link-akira:hover { color: #111111; }
    .nav-link-akira:hover::after { width: 100%; }

    .active-link { font-weight: bold !important; color: #111111 !important; }
    .active-link::after { width: 100% !important; }

    /* ================= BOTÓN FLOTANTE REGRESAR ================= */
    .btn-flotante-regresar {
        position: fixed;
        bottom: clamp(25px, 5vh, 45px); 
        left: clamp(30px, 5vw, 60px);
        font-size: 0.90rem;
        color: #111111 !important; 
        text-decoration: none !important; 
        z-index: 9999;
        background: rgba(255, 255, 255, 0.03); 
        backdrop-filter: blur(25px); 
        -webkit-backdrop-filter: blur(25px); 
        border: 1px solid rgba(0, 0, 0, 0.1); 
        padding: 12px 32px; 
        border-radius: 50px; 
        opacity: 0.6; 
        transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        font-family: "Georgia", "Times New Roman", serif;
        letter-spacing: 1.5px; 
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05); 
    }
    .btn-flotante-regresar:hover {
        opacity: 1; 
        background: rgba(255, 255, 255, 0.5); 
        border-color: rgba(0, 0, 0, 0.3);
        transform: translateX(-5px) translateY(-2px); 
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15); 
    }

    /* ================= SECCIÓN DE RESEÑAS ================= */
    .header-section {
        border-bottom: 1px solid #eaeaea;
        padding-bottom: 20px;
        margin-bottom: 40px;
    }
    .header-section h1 {
        font-size: 2rem;
        margin-bottom: 10px;
        color: #111;
        letter-spacing: 0.02em;
        font-weight: normal; /* Se ajusta a la familia Serif elegantemente */
    }
    .header-section p {
        color: #666;
        font-size: 0.95rem;
        line-height: 1.6;
        font-style: italic; /* Toque editorial */
    }

    .reviews-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 25px;
        margin-bottom: 60px;
    }

    .review-card {
        background: transparent;
        border: 1px solid #eaeaea;
        padding: 30px;
        transition: transform 0.3s ease, border-color 0.3s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .review-card:hover {
        transform: translateY(-3px);
        border-color: #ccc;
    }

    .review-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 18px;
    }
    .reviewer-info { display: flex; flex-direction: column; }
    .reviewer-name { font-weight: bold; font-size: 1rem; color: #111; margin: 0; }
    .review-time { font-size: 0.75rem; color: #999; margin-top: 3px; font-family: Arial, sans-serif; letter-spacing: 0.5px; text-transform: uppercase; }

    .star-rating { color: #eab308; font-size: 0.9rem; letter-spacing: 2px; }
    .star-empty { color: #eaeaea; }

    .review-body {
        font-size: 0.95rem;
        color: #444;
        line-height: 1.7;
        flex-grow: 1;
        margin-bottom: 25px;
        font-style: italic;
    }

    .review-footer {
        border-top: 1px solid #f5f5f5;
        padding-top: 15px;
        font-size: 0.75rem;
        color: #aaa;
        display: flex;
        justify-content: space-between;
        font-family: Arial, sans-serif; /* Detalles técnicos en sans-serif para contrastar */
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .btn-leave-review {
        display: inline-block;
        background: #111;
        color: #fff;
        padding: 12px 30px;
        text-decoration: none;
        letter-spacing: 2px;
        text-transform: uppercase;
        font-size: 0.75rem;
        transition: background 0.3s ease, transform 0.2s ease;
        border: none;
        margin-top: 15px;
        font-family: Arial, sans-serif;
    }
    .btn-leave-review:hover { 
        background: #333; 
        color: #fff; 
        transform: translateY(-2px); 
    }
</style>


{{-- ================= COLUMNAS MULTIMEDIA ================= --}}
<div class="side-media side-left {{ !$isVideo ? 'hero-image-bg' : '' }}" @if(!$isVideo) style="background-image: url('{{ $mediaUrl }}');" @endif>
    @if($isVideo)
        <video autoplay loop muted playsinline class="hero-video-bg"><source src="{{ $mediaUrl }}" type="video/mp4"></video>
    @endif
</div>

<div class="side-media side-right {{ !$isVideo ? 'hero-image-bg' : '' }}" @if(!$isVideo) style="background-image: url('{{ $mediaUrl }}'); transform: scaleX(-1);" @endif>
    @if($isVideo)
        <video autoplay loop muted playsinline class="hero-video-bg" style="transform: translateX(-50%) translateY(-50%) scaleX(-1);"><source src="{{ $mediaUrl }}" type="video/mp4"></video>
    @endif
</div>

{{-- ================= BOTÓN DE REGRESO FLOTANTE ================= --}}
<a href="{{ route('landing') }}" class="btn-flotante-regresar">← regresar</a>


{{-- ================= CONTENEDOR CENTRAL ================= --}}
<div class="akira-container">
    
    {{-- MENÚ TIPOGRÁFICO EXACTO AL DE CONTACTOS --}}
    <header class="site-header-main">
        <a href="{{ route('project.detail') ?? '#' }}" class="nav-link-akira {{ request()->routeIs('project.detail') ? 'active-link' : '' }}">Estudio Akiraka ,</a>
        <a href="{{ route('info') ?? '#' }}" class="nav-link-akira {{ request()->routeIs('info') ? 'active-link' : '' }}">Info ,</a>
        <a href="{{ route('resenas.index') ?? '#' }}" class="nav-link-akira {{ request()->routeIs('resenas.index') ? 'active-link' : '' }}">Reseñas ,</a>
        <a href="{{ route('contacto') ?? '#' }}" class="nav-link-akira {{ request()->routeIs('contacto') ? 'active-link' : '' }}">Contacto</a>
    </header>

    @if(session('success'))
        <div style="background: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9; padding: 15px; text-align: center; margin-bottom: 30px; font-family: Arial; font-size: 0.9rem; letter-spacing: 1px;">
            {{ session('success') }}
        </div>
    @endif

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
@include('Principal.cita')

@endsection