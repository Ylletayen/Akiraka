@extends('layouts.app')

@section('content')
@php
    $config = \App\Models\Configuracion::first();

    $mediaUrl = $config && $config->landing_hero_image
        ? asset('storage/' . $config->landing_hero_image)
        : 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&q=80&w=1920';

    $isVideo = preg_match('/\.(mp4|webm)$/i', $mediaUrl);
@endphp

@include('dashboard.login.login')
@include('dashboard.login.registro')

<!-- LIBRERÍA ANIME.JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>

<style>
    /* =================================================================
       ESTILOS DEL BOTÓN CENTRAL
       ================================================================= */
    .btn-enter {
        display: inline-block;
        padding: 12px 35px;
        border: 1px solid rgba(255, 255, 255, 0.8);
        background-color: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        letter-spacing: 2px;
        font-size: 0.85rem;
        color: #fff;
        text-decoration: none;
    }

    .btn-enter:hover {
        transform: scale(1.05);
        background-color: rgba(255, 255, 255, 0.95);
        color: #111 !important;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }

    /* =================================================================
       ESTILOS DEL HEADER Y NAVEGACIÓN
       ================================================================= */
    .logo-wrapper {
        color: #fff;
        transition: opacity 0.3s ease;
        cursor: pointer;
    }

    .logo-wrapper:hover {
        opacity: 0.7;
    }

    .logo-img-landing {
        height: 45px;
        width: auto;
        object-fit: contain;
    }

    .nav-link-akira {
        color: #fff !important;
        text-decoration: none;
        font-size: 0.85rem;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
    }

    .nav-link-akira:hover {
        opacity: 0.6;
    }

    /* BOTÓN LOGIN "GHOST" ELEGANTE */
    .nav-link-akira.btn-nav-action {
        padding: 8px 20px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 4px;
        background: transparent;
    }

    .nav-link-akira.btn-nav-action:hover {
        transform: translateY(-2px);
        background-color: #fff;
        border-color: #fff;
        color: #111 !important;
        opacity: 1; /* Sobrescribe el hover normal */
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .social-links-akira a {
        color: #fff;
        font-size: 1.2rem;
        transition: all 0.3s ease;
    }

    .social-links-akira a:hover {
        opacity: 0.6;
        transform: translateY(-2px);
    }

    /* =================================================================
       ESTILOS DEL FONDO Y CAPAS
       ================================================================= */
    .landing-hero-container {
        position: absolute;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        overflow: hidden;
        z-index: 1;
    }

    .hero-image-bg {
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
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
        z-index: 0;
    }

    .enter-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.4);
        z-index: 10;
    }

    .header-floating {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        background: transparent;
        z-index: 20;
    }

    /* CLASE VITAL PARA ANIME.JS (Evita que parpadeen antes de animar) */
    .anime-hide {
        opacity: 0;
    }
</style>

<div id="landing-view" class="vh-100 position-relative">

    <header class="header-floating py-4 px-5 d-flex justify-content-between align-items-center">
        <!-- Logo -->
        <div class="logo-wrapper anime-hide d-flex flex-column align-items-center" onclick="abrirLogin()">
            <img src="{{ asset('images/logosinbgakira.png') }}" alt="Logo Akiraka" class="logo-img-landing">
            <span class="logo-brand-text mt-1 fw-bold" style="font-size: 0.75rem; letter-spacing: 2px;">ESTUDIO AKIRAKA</span>
        </div>

        <!-- Navegación -->
        <nav class="d-none d-lg-flex align-items-center gap-4">
            <a href="{{ route('project.detail') }}" class="nav-link-akira anime-hide">PROYECTOS</a>
            <a href="{{ route('info') }}" class="nav-link-akira anime-hide">INFORMACIÓN</a>
            <a href="{{ route('resenas.index') }}" class="nav-link-akira anime-hide">RESEÑAS</a>
            <a href="{{ route('contacto') }}" class="nav-link-akira anime-hide">CONTACTO</a>

            @guest
                <a href="javascript:void(0)" onclick="abrirLogin()" class="nav-link-akira btn-nav-action anime-hide">
                    LOGIN
                </a>
            @endguest

            @auth
                <a href="{{ route('dashboard.main') }}" class="nav-link-akira btn-nav-action anime-hide">
                    <i class="bi bi-speedometer2"></i> PANEL ADMIN
                </a>
            @endauth

            <div class="social-links-akira d-flex gap-3 ms-3">
                <a href="{{ $config->instagram ?? '#' }}" target="_blank" class="anime-hide">
                    <i class="fa-brands fa-instagram"></i>
                </a>
                <a href="{{ $config->facebook ?? '#' }}" target="_blank" class="anime-hide">
                    <i class="fa-brands fa-facebook-f"></i>
                </a>
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $config->telefono ?? '527221655901') }}" target="_blank" class="anime-hide">
                    <i class="fa-brands fa-whatsapp"></i>
                </a>
            </div>
        </nav>
    </header>

    <div class="landing-hero-container anime-hide {{ !$isVideo ? 'hero-image-bg' : '' }}" 
         @if(!$isVideo) style="background-image: url('{{ $mediaUrl }}');" @endif>

        @if($isVideo)
            <video autoplay loop muted playsinline class="hero-video-bg">
                <source src="{{ $mediaUrl }}" type="video/mp4">
            </video>
        @endif

        <div class="enter-overlay d-flex align-items-center justify-content-center">
            <!-- Nota: Al botón central también le ponemos anime-hide para animarlo aparte de la capa oscura -->
            <a href="{{ route('project.detail') }}" class="btn-enter anime-hide">
                EXPLORAR ESTUDIO
            </a>
        </div>
    </div>
</div>

@include('Principal.cita')

<script>
    document.addEventListener("DOMContentLoaded", function () {

        // 1. Animación del Fondo Multimedia (Aparece suavemente y hace un ligero Zoom Out)
        anime({
            targets: '.landing-hero-container',
            opacity: [0, 1],
            scale: [1.05, 1],
            easing: 'easeOutSine',
            duration: 1500
        });

        // 2. Animación del Logo (Cae desde arriba)
        anime({
            targets: '.logo-wrapper',
            translateY: [-30, 0],
            opacity: [0, 1],
            easing: 'easeOutExpo',
            duration: 1500,
            delay: 400
        });

        // 3. Animación de los Enlaces del Menú y Redes (Efecto dominó / Staggering)
        anime({
            targets: ['.nav-link-akira', '.social-links-akira a'],
            translateY: [-20, 0],
            opacity: [0, 1],
            easing: 'easeOutExpo',
            duration: 1000,
            delay: anime.stagger(100, { start: 600 }),
            complete: function (anim) {
                // Al terminar, le quitamos la propiedad transform que inyecta Anime.js 
                // para que el CSS :hover vuelva a funcionar perfectamente.
                anim.animatables.forEach(function (a) {
                    a.target.style.transform = '';
                });
            }
        });

        // 4. Animación del Botón Central ("Explorar Estudio")
        // Entra con un rebote elástico premium justo al terminar el menú
        anime({
            targets: '.btn-enter',
            scale: [0.85, 1],
            opacity: [0, 1],
            easing: 'easeOutElastic(1, .8)', // El toque premium
            duration: 1200,
            delay: 1200,
            complete: function (anim) {
                anim.animatables.forEach(function (a) {
                    a.target.style.transform = '';
                });
            }
        });

    });
</script>
@endsection