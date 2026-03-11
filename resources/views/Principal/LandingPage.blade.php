@extends('layouts.app')

@section('content')

<!-- Extraemos la configuración de la BD -->
@php
    $config = \App\Models\Configuracion::first();
@endphp

@include('dashboard.login.login')

@include('dashboard.login.registro')

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

    /* Clase para controlar el tamaño del logo real */
    .logo-img-landing {
        height: 45px; /* Puedes subir o bajar este valor para hacer el logo más grande o pequeño */
        width: auto;
        object-fit: contain;
    }

    /* Estilos para los iconos de redes */
    .social-links-akira a {
        color: #111;
        font-size: 1.2rem;
        transition: opacity 0.3s ease;
    }
    .social-links-akira a:hover {
        opacity: 0.6;
    }
</style>

<div id="landing-view" class="vh-100 d-flex flex-column">
    <header class="container-fluid py-4 px-5 d-flex justify-content-between align-items-center bg-white">
        
        <div class="logo-wrapper d-flex flex-column align-items-center" 
             onclick="abrirLogin()" 
             style="cursor: pointer;" 
             title="Acceso Administración">
            
            <!-- AQUÍ ESTÁ EL LOGO REAL REEMPLAZANDO A LOS TRIÁNGULOS -->
            <img src="{{ asset('images/logo_akiraka.png') }}" alt="Logo Akiraka" class="logo-img-landing">
            
            <span class="logo-brand-text mt-1">ESTUDIO AKIRAKA</span>
        </div>

        <nav class="d-none d-lg-flex align-items-center gap-4">
            
            <a href="{{ route('project.detail') }}" class="nav-link-akira">PROYECTOS</a>
            <a href="{{ route('info') }}" class="nav-link-akira">INFORMACIÓN</a>
            <a href="{{ route('contacto') }}" class="nav-link-akira">CONTACTO</a>
            
            <div class="social-links-akira d-flex gap-3 ms-3">
                <!-- Instagram Dinámico -->
                <a href="{{ $config->instagram ?? 'https://www.instagram.com/' }}" target="_blank" title="Instagram">
                    <i class="fa-brands fa-instagram"></i>
                </a>

                <!-- Facebook Dinámico (Agregado de la BD) -->
                <a href="{{ $config->facebook ?? 'https://www.facebook.com/' }}" target="_blank" title="Facebook">
                    <i class="fa-brands fa-facebook-f"></i>
                </a>

                <!-- WhatsApp Dinámico (Toma el teléfono, le quita espacios/signos y genera el link wa.me) -->
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $config->telefono ?? '527221655901') }}" target="_blank" title="WhatsApp">
                    <i class="fa-brands fa-whatsapp"></i>
                </a>
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