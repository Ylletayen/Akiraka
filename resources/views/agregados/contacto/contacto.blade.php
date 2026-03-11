@extends('layouts.app')

@section('content')
<!-- Extraemos la configuración directamente desde la BD para no tocar las rutas -->
@php
    $config = \App\Models\Configuracion::first();
@endphp

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    /* ================= ESTILOS BASE DEL ESTUDIO ================= */
    .akira-container {
        max-width: 1100px; 
        margin: 0 auto;
        padding: 50px 20px;
        font-family: "Georgia", "Times New Roman", serif;
        color: #1a1a1a;
    }

    .akira-link-reset {
        text-decoration: none;
        color: inherit;
        font-size: 1rem;
        transition: opacity 0.3s ease;
    }

    .akira-link-reset:hover {
        opacity: 0.6;
        color: #000;
    }

    .brand-name-header {
        font-size: 1.1rem;
        font-weight: normal;
    }

    .nav-link-bold {
        font-weight: bold;
    }

    .contact-label {
        font-weight: bold;
        display: block;
        margin-bottom: 2px;
        font-size: 1rem;
        color: #1a1a1a;
    }

    .contact-value-reset {
        text-decoration: none;
        color: #666; 
        font-size: 0.95rem;
        transition: color 0.3s;
        line-height: 1.5;
    }

    .contact-value-reset:hover {
        color: #000;
    }

    .contact-instruction {
        font-style: italic;
        font-size: 0.95rem;
        margin-bottom: 40px;
        color: #666;
    }

    .location-year-label {
        color: #ccc;
        font-size: 0.85rem;
        margin-right: 15px;
        display: inline-block;
        width: 40px;
    }

    /* ================= SECCIÓN DEL MAPA (DISEÑO ARQUITECTÓNICO) ================= */
    .map-section {
        margin-top: 60px;
        border-top: 1px solid #eee;
        padding-top: 40px;
    }

    .map-wrapper {
        width: 100%;
        height: 500px;
        filter: grayscale(100%); /* Efecto minimalista en blanco y negro */
        border: 1px solid #111;
        transition: filter 0.5s ease;
        margin-bottom: 20px;
    }

    .map-wrapper:hover {
        filter: grayscale(0%); /* El color vuelve al interactuar */
    }

    .map-caption {
        font-size: 0.85rem;
        color: #888;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* ================= REDES SOCIALES (ICONOS CIRCULARES) ================= */
    .social-group-section {
        margin-top: 50px;
        border-top: 1px solid #eee;
        padding-top: 30px;
        text-align: center;
    }

    .social-label-heading {
        font-weight: bold;
        display: block;
        margin-bottom: 15px;
        font-size: 1rem;
        color: #1a1a1a;
    }

    .social-btn-circle {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        border: 1px solid #1a1a1a;
        color: #1a1a1a;
        text-decoration: none;
        font-size: 1.2rem;
        margin-right: 10px;
        transition: all 0.3s ease;
    }

    .social-btn-circle:hover {
        background-color: #1a1a1a;
        color: #fff;
    }
</style>

<div class="akira-container">
    <header class="site-header-main mb-5">
        <a href="{{ route('project.detail') }}" style="text-decoration: none; color: inherit; font-weight: normal;">
        Estudio Akiraka, </a>
        <a href="{{ route('info') }}" style="text-decoration: none; color: inherit;">Info</a>, 
        <a href="{{ route('contacto') }}" style="text-decoration: none; color: #1a1a1a; font-weight: bold;">Contacto</a>.
    </header>

    <p class="contact-instruction">Si deseas contactarnos, por favor envía un correo a la dirección designada.</p>

    <div class="row contact-group-section mb-5">
        <div class="col-md-4 mb-4">
            <span class="contact-label">Proyectos y Eventos</span>
            <div class="contact-value-reset">
                <!-- Mostramos el correo dinámico aquí -->
                <a href="mailto:{{ $config->correo_contacto ?? 'akiraka.estudio@gmail.com' }}" style="color: inherit; text-decoration: none;">
                    {{ $config->correo_contacto ?? 'akiraka.estudio@gmail.com' }}
                </a><br>
                administracion@akirakastudio.com
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <span class="contact-label">Prensa</span>
            <div class="contact-value-reset">proyectos@akirakastudio.com</div>
        </div>

        <div class="col-md-4 mb-4">
            <span class="contact-label">Oportunidades laborales</span>
            <div class="contact-value-reset">
                dirección@akirakastudio.com<br>
                studio@akirakastudio.com
            </div>
        </div>
    </div>

    <div class="location-group border-top pt-4 mb-5">
        <div class="row">
            <div class="col-md-6 mb-3">
                <span class="location-year-label">Teléfono</span>
                <!-- Teléfono dinámico -->
                <span class="contact-value-reset"><br>{{ $config->telefono ?? '+52 722 165 5901' }}</span>
            </div>
            <div class="col-md-6 mb-3">
                <span class="location-year-label">Dirección</span>
                <!-- Dirección dinámica con saltos de línea respetados -->
                <span class="contact-value-reset"><br>{!! nl2br(e($config->direccion ?? 'Parque Santa María 10, Valle de Bravo, Méx.')) !!}</span>
            </div>
        </div>
    </div>

    <div class="map-section">
        <span class="contact-label mb-3" style="text-transform: uppercase; font-size: 0.75rem; letter-spacing: 2px;">Ubicación / Espacio Odisea</span>
        <div class="map-wrapper">
            <iframe 
                width="100%" 
                height="100%" 
                frameborder="0" 
                scrolling="no" 
                marginheight="0" 
                marginwidth="0" 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d467.4589255627712!2d-100.1363651761623!3d19.189445173707252!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x85cd647e9a15bc7b%3A0x59426b73189edf87!2sEspacio%20Odisea!5e0!3m2!1ses-419!2smx!4v1709673000000!5m2!1ses-419!2smx"
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy">
            </iframe>
        </div>
        
        <div class="map-caption">
            <i class="bi bi-geo-alt-fill"></i> 
            <!-- Dirección dinámica pequeña abajo del mapa -->
            {{ $config->direccion ?? 'Parque Santa María 10, Valle de Bravo, Méx.' }}
        </div>
    </div>

    <div class="social-group-section">
        <span class="social-label-heading">Síguenos</span>
        
        <!-- Instagram Dinámico -->
        <a href="{{ $config->instagram ?? 'https://www.instagram.com/' }}" target="_blank" class="social-btn-circle" title="Instagram">
            <i class="bi bi-instagram"></i>
        </a>
        
        <!-- Facebook Dinámico -->
        <a href="{{ $config->facebook ?? 'https://www.facebook.com/' }}" target="_blank" class="social-btn-circle" title="Facebook">
            <i class="bi bi-facebook"></i>
        </a>
    </div>
</div>
@endsection