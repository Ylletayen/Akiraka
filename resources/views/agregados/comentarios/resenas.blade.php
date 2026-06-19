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

    // Si tu controlador no manda la variable $resenas, o viene vacía, usamos los datos de prueba
    $resenasFinales = (isset($resenas) && count($resenas) > 0) ? $resenas : $mockResenas;
@endphp

<style>
    /* ================= COMPLEMENTOS DE DISEÑO GENERAL ================= */
    body {
        background-color: #fafafa;
    }
    
    /* Mantenemos tus clases originales del Landing para el Header Navbar */
    .header-floating {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        background: #111111 !important; /* Le ponemos fondo oscuro fijo para que resalte en esta vista */
        z-index: 20;
    }
    .logo-wrapper { color: #fff; }
    .logo-img-landing { height: 45px; width: auto; object-fit: contain; }
    
    .nav-link-akira { 
        color: #fff !important; 
        text-decoration: none; 
        font-size: 0.85rem; 
        letter-spacing: 1px; 
        transition: opacity 0.3s; 
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
    }
    .nav-link-akira:hover { opacity: 0.6; }
    .social-links-akira a { color: #fff; font-size: 1.2rem; transition: opacity 0.3s ease; }
    .social-links-akira a:hover { opacity: 0.6; }

    /* ================= HERO SECCIÓN / CONTENEDOR PRINCIPAL ================= */
    .content-wrapper-reviews {
        padding-top: 140px; /* Margen para que no tape el Navbar de arriba */
        min-height: 100vh;
    }

    .header-section {
        border-bottom: 1px solid #eaeaea;
        padding-bottom: 30px;
        margin-bottom: 50px;
        text-align: center;
    }
    .header-section h1 {
        font-size: 2.3rem;
        font-weight: 700;
        margin-bottom: 12px;
        color: #111;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }
    .header-section p {
        color: #666;
        font-size: 1rem;
        max-width: 600px;
        margin: 0 auto 25px auto;
        line-height: 1.5;
    }

    /* ================= GRID DE TARJETAS DE RESEÑAS ================= */
    .reviews-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 30px;
        margin-bottom: 60px;
    }

    .review-card {
        background: #fff;
        border: 1px solid #eaeaea;
        border-radius: 0px; /* Mantenemos el estilo ortogonal minimalista de Akira */
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
    .reviewer-info {
        display: flex;
        flex-direction: column;
    }
    .reviewer-name {
        font-weight: 700;
        font-size: 1.05rem;
        color: #111;
        margin: 0;
        letter-spacing: 0.5px;
    }
    .review-time {
        font-size: 0.75rem;
        color: #999;
        margin-top: 3px;
    }

    .star-rating {
        color: #eab308;
        font-size: 1rem;
        letter-spacing: 2px;
    }
    .star-empty {
        color: #eaeaea;
    }

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
        padding: 12px 35px;
        text-decoration: none;
        font-weight: bold;
        letter-spacing: 1px;
        text-transform: uppercase;
        font-size: 0.8rem;
        transition: background 0.3s;
        border: none;
    }
    .btn-leave-review:hover {
        background: #333;
        color: #fff;
    }
</style>

<header class="header-floating py-4 px-5 d-flex justify-content-between align-items-center">
    <div class="logo-wrapper d-flex flex-column align-items-center">
        <img src="{{ asset('images/logosinbgakira.png') }}" alt="Logo Akiraka" class="logo-img-landing">
        <span class="logo-brand-text mt-1 fw-bold" style="font-size: 0.75rem; letter-spacing: 2px; color: #fff;">ESTUDIO AKIRAKA</span>
    </div>

    <nav class="d-none d-lg-flex align-items-center gap-4">
        <a href="{{ route('project.detail') }}" class="nav-link-akira">PROYECTOS</a>
        <a href="{{ route('info') }}" class="nav-link-akira">INFORMACIÓN</a>
        <a href="{{ route('resenas.index') }}" class="nav-link-akira" style="border-bottom: 1px solid #fff;">RESEÑAS</a>
        <a href="{{ route('contacto') }}" class="nav-link-akira">CONTACTO</a>
        
        @guest
            <a onclick="abrirLogin()" class="nav-link-akira">LOGIN</a>
        @endguest

        @auth
            <a href="{{ route('dashboard.main') }}" class="nav-link-akira">
                <i class="bi bi-speedometer2"></i> PANEL ADMIN
            </a>
        @endauth
        
        <div class="social-links-akira d-flex gap-3 ms-3">
            <a href="#" target="_blank"><i class="fa-brands fa-instagram"></i></a>
            <a href="#" target="_blank"><i class="fa-brands fa-facebook-f"></i></a>
            <a href="#" target="_blank"><i class="fa-brands fa-whatsapp"></i></a>
        </div>
    </nav>
</header>

<div class="container content-wrapper-reviews">
    
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

{{-- MODAL INTEGRADO PARA AGREGAR RESEÑAS --}}
@include('dashboard.login.login') {{-- Conservamos tus modales globales por si se ejecutan desde el nav --}}
@include('dashboard.login.registro')

{{-- Aquí mandamos a llamar al modal interactivo que hicimos en el paso anterior --}}
{{-- Puedes dejar la estructura del modal pegada aquí abajo para pruebas rápidas o usar un @include --}}
@endsection