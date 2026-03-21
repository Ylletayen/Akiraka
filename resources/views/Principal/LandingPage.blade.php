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
    .btn-enter { display: inline-block; padding: 10px 30px; border: 1px solid #fff; background-color: rgba(255, 255, 255, 0.1); backdrop-filter: blur(8px); transition: all 0.3s ease; letter-spacing: 2px; font-size: 0.85rem; }
    .btn-enter:hover { transform: scale(1.05); background-color: rgba(255, 255, 255, 0.9); color: #111 !important; }
    .logo-wrapper { transition: opacity 0.3s; cursor: pointer; }
    .logo-wrapper:hover { opacity: 0.7; }
    .logo-img-landing { height: 45px; width: auto; object-fit: contain; }
    .social-links-akira a { color: #111; font-size: 1.2rem; transition: opacity 0.3s ease; }
    .social-links-akira a:hover { opacity: 0.6; }

    /* ESTILO DEL CONTENEDOR HERO */
    .landing-hero-container {
        position: relative;
        width: 100%;
        overflow: hidden;
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

    .enter-overlay {
        position: relative;
        z-index: 10;
        /* Oscurecemos un poquito el fondo para que el botón resalte siempre */
        background: rgba(0,0,0,0.25); 
    }
</style>

<div id="landing-view" class="vh-100 d-flex flex-column">
    <header class="container-fluid py-4 px-5 d-flex justify-content-between align-items-center bg-white z-20 position-relative">
        <div class="logo-wrapper d-flex flex-column align-items-center" onclick="abrirLogin()" title="Acceso Administración">
            <img src="{{ asset('images/logo_akiraka.png') }}" alt="Logo Akiraka" class="logo-img-landing">
            <span class="logo-brand-text mt-1">ESTUDIO AKIRAKA</span>
        </div>

        <nav class="d-none d-lg-flex align-items-center gap-4">
            <a href="{{ route('project.detail') }}" class="nav-link-akira">PROYECTOS</a>
            <a href="{{ route('info') }}" class="nav-link-akira">INFORMACIÓN</a>
            <a href="{{ route('contacto') }}" class="nav-link-akira">CONTACTO</a>
            
            <div class="social-links-akira d-flex gap-3 ms-3">
                <a href="{{ $config->instagram ?? '#' }}" target="_blank"><i class="fa-brands fa-instagram"></i></a>
                <a href="{{ $config->facebook ?? 'https://www.facebook.com/profile.php?id=61568259411265&locale=es_LA' }}" target="_blank"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $config->telefono ?? '527221655901') }}" target="_blank"><i class="fa-brands fa-whatsapp"></i></a>
            </div>
        </nav>
    </header>

    <!-- CONTENEDOR HERO FLEXIBLE (Soporta Video o Imagen) -->
    <div class="landing-hero-container flex-grow-1 {{ !$isVideo ? 'hero-image-bg' : '' }}" 
         @if(!$isVideo) style="background-image: url('{{ $mediaUrl }}');" @endif>
        
        @if($isVideo)
            <!-- Renderizamos la etiqueta de Video si es MP4 -->
            <video autoplay loop muted playsinline class="hero-video-bg">
                <source src="{{ $mediaUrl }}" type="video/mp4">
            </video>
        @endif

        <div class="enter-overlay d-flex align-items-center justify-content-center h-100">
            <a href="{{ route('project.detail') }}" class="btn-enter text-decoration-none text-white">
                EXPLORAR ESTUDIO
            </a>
        </div>
    </div>
</div>
@endsection