@extends('layouts.app')

@section('content')

@include('dashboard.login')

@include('dashboard.registro')

<style>
    /* Estilos del botón de entrada con animación de pulso */
    .btn-enter {
        display: inline-block;
        padding: 10px 30px;
        border: 1px solid #fff;
        background-color: rgba(255, 255, 255, 0.1); 
        backdrop-filter: blur(8px); /* Efecto cristal para estilo arquitecto */
        transition: transform 0.3s ease-in-out, background-color 0.3s;
        letter-spacing: 2px;
        font-size: 0.85rem;
    }

    .btn-enter:hover {
        transform: scale(1.05); /* Efecto de crecimiento suave */
        background-color: rgba(255, 255, 255, 0.9);
        color: #111 !important;
    }

    /* Estilos para que el logo se sienta como un botón */
    .logo-wrapper {
        transition: opacity 0.3s;
    }
    .logo-wrapper:hover {
        opacity: 0.7;
    }
</style>

<div id="landing-view" class="vh-100 d-flex flex-column">
    <header class="container-fluid py-4 px-5 d-flex justify-content-between align-items-center bg-white">
        
        <div class="logo-wrapper d-flex flex-column align-items-center" 
             onclick="abrirLogin()" 
             style="cursor: pointer;" 
             title="Acceso Administración">
            <div class="custom-logo-triangles">
                <div class="tri-1"></div>
                <div class="tri-2"></div>
                <div class="tri-3"></div>
            </div>
            <span class="logo-brand-text mt-1">ESTUDIO AKIRAKA</span>
        </div>

        <nav class="d-none d-lg-flex align-items-center gap-4">
            <a href="#" class="nav-link-akira">ABOUT</a>
            <a href="{{ route('project.detail') }}" class="nav-link-akira">PROYECTOS</a>
            <a href="{{ route('info') }}" class="nav-link-akira">INFORMACIÓN</a>
            <a href="{{ route('contacto') }}" class="nav-link-akira">CONTACTO</a>
            
            <div class="social-links-akira d-flex gap-2 ms-3">
                <a href="https://www.instagram.com/akiraka.estudio/" target="_blank">
                    <i class="fa-brands fa-instagram"></i>
                </a>
                <a href="#"><i class="fa-brands fa-whatsapp"></i></a>
            </div>
        </nav>
    </header>

    <div class="landing-hero-image flex-grow-1">
        <div class="enter-overlay d-flex align-items-center justify-content-center h-100">
            <a href="{{ route('project.detail') }}" class="btn-enter text-decoration-none text-white">
                EXPLORAR ESTUDIO
            </a>
        </div>
    </div>
</div>
@endsection