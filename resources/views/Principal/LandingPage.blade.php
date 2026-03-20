@extends('layouts.app')

@section('content')
@php
    $config = \App\Models\Configuracion::first();
    
    $mediaUrl = $config && $config->landing_hero_image 
                 ? asset('storage/' . $config->landing_hero_image) 
                 : 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&q=80&w=1920';
                 
    // Detectamos si es un video MP4 para cambiar la estructura HTML
    $isVideo = preg_match('/\.(mp4|webm)$/i', $mediaUrl);
@endphp

@include('dashboard.login.login')
@include('dashboard.login.registro')

<style>
    /* ================= ESTILOS GENERALES Y BOTÓN ================= */
    .btn-enter { display: inline-block; padding: 10px 30px; border: 1px solid #fff; background-color: rgba(255, 255, 255, 0.1); backdrop-filter: blur(8px); transition: all 0.3s ease; letter-spacing: 2px; font-size: 0.85rem; }
    .btn-enter:hover { transform: scale(1.05); background-color: rgba(255, 255, 255, 0.9); color: #111 !important; }
    
    /* Quitamos el forzado a blanco y le damos un tono gris por defecto al contenedor */
    .logo-wrapper { transition: opacity 0.3s; cursor: pointer; color: #555555; }
    .logo-wrapper:hover { opacity: 0.7; }
    
    /* Quitamos el filter: invert() para que tu imagen PNG conserve su gris original */
    .logo-img-landing { height: 45px; width: auto; object-fit: contain; }
    
    /* ENLACES DEL MENÚ FLOTANTE */
    .nav-link-akira { color: #fff !important; text-decoration: none; font-size: 0.85rem; letter-spacing: 1px; transition: opacity 0.3s; }
    .nav-link-akira:hover { opacity: 0.6; }
    
    .social-links-akira a { color: #fff; font-size: 1.2rem; transition: opacity 0.3s ease; }
    .social-links-akira a:hover { opacity: 0.6; }

    /* ================= CONTENEDOR HERO FULL SCREEN ================= */
    .landing-hero-container {
        position: absolute;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        overflow: hidden;
        z-index: 1; /* Fondo base */
    }

    /* SI ES IMAGEN O GIF */
    .hero-image-bg {
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }

    /* SI ES VIDEO */
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

    /* CAPA OSCURA QUE CUBRE TODA LA PANTALLA PARA QUE EL TEXTO SEA LEGIBLE */
    .enter-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.4); /* Nivel de oscuridad (0.4 es ideal) */
        z-index: 10;
    }

    /* HEADER FLOTANTE Y TRANSPARENTE */
    .header-floating {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        background: transparent !important;
        z-index: 20; /* Siempre encima del video y la capa oscura */
    }
</style>

<div id="landing-view" class="vh-100 position-relative">
    
    <header class="header-floating py-4 px-5 d-flex justify-content-between align-items-center">
        <div class="logo-wrapper d-flex flex-column align-items-center" onclick="abrirLogin()" title="Acceso Administración">
            <img src="{{ asset('images/logosinbgakira.png') }}" alt="Logo Akiraka" class="logo-img-landing">
            <!-- Texto conservando su color original (sin forzar blanco) -->
            <span class="logo-brand-text mt-1 fw-bold" style="font-size: 0.75rem; letter-spacing: 2px;">ESTUDIO AKIRAKA</span>
        </div>

        <nav class="d-none d-lg-flex align-items-center gap-4">
            <a href="{{ route('project.detail') }}" class="nav-link-akira">PROYECTOS</a>
            <a href="{{ route('info') }}" class="nav-link-akira">INFORMACIÓN</a>
            <a href="{{ route('contacto') }}" class="nav-link-akira">CONTACTO</a>
            
            <div class="social-links-akira d-flex gap-3 ms-3">
                <a href="{{ $config->instagram ?? '#' }}" target="_blank"><i class="fa-brands fa-instagram"></i></a>
                <a href="{{ $config->facebook ?? '#' }}" target="_blank"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $config->telefono ?? '527221655901') }}" target="_blank"><i class="fa-brands fa-whatsapp"></i></a>
            </div>
        </nav>
    </header>

    <div class="landing-hero-container {{ !$isVideo ? 'hero-image-bg' : '' }}" 
         @if(!$isVideo) style="background-image: url('{{ $mediaUrl }}');" @endif>
        
        @if($isVideo)
            <video autoplay loop muted playsinline class="hero-video-bg">
                <source src="{{ $mediaUrl }}" type="video/mp4">
            </video>
        @endif

        <div class="enter-overlay d-flex align-items-center justify-content-center">
            <a href="{{ route('project.detail') }}" class="btn-enter text-decoration-none text-white">
                EXPLORAR ESTUDIO
            </a>
        </div>
    </div>
</div>
@endsection