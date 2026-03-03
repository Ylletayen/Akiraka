@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    /* Estilos generales del contenedor - similar al ancho de la imagen de referencia */
    .akira-container {
        max-width: 1100px; 
        margin: 0 auto;
        padding: 50px 20px;
        font-family: "Georgia", "Times New Roman", serif;
        color: #1a1a1a;
    }

    /* Estilo general para todos los enlaces tipo texto */
    .akira-link-reset {
        text-decoration: none;
        color: inherit;
        font-size: 1rem;
        transition: opacity 0.3s ease;
    }

    .akira-link-reset:hover {
        opacity: 0.6; /* Efecto sutil al pasar el mouse */
        color: #000;
    }

    /* Estilo para el nombre de la marca en el header */
    .brand-name-header {
        font-size: 1.1rem;
        font-weight: normal; /* Asegurar que el nombre del estudio no esté en negrita */
    }

    /* Clase específica para poner solo un enlace en negrita */
    .nav-link-bold {
        font-weight: bold;
    }

    /* Estilo para las etiquetas de contacto (Proyectos, Prensa, etc.) */
    .contact-label {
        font-weight: bold;
        display: block;
        margin-bottom: 2px;
        font-size: 1rem;
        color: #1a1a1a;
    }

    /* Estilo para los valores (correos, teléfonos) - gris intermedio */
    .contact-value-reset {
        text-decoration: none;
        color: #666; 
        font-size: 0.95rem;
        transition: color 0.3s;
    }

    .contact-value-reset:hover {
        color: #000;
    }

    /* Estilo para la instrucción superior - cursiva minimalista */
    .contact-instruction {
        font-style: italic;
        font-size: 0.95rem;
        margin-bottom: 40px;
        color: #666;
    }

    /* Estilo para los años o abreviaturas de ubicación (gris claro) */
    .location-year-label {
        color: #ccc;
        font-size: 0.85rem;
        margin-right: 15px;
        display: inline-block;
        width: 40px;
    }

    /* --- ESTILOS DE REDES SOCIALES (ICONOS CIRCULARES) --- */
    .social-group-section {
        margin-top: 50px;
        border-top: 1px solid #eee;
        padding-top: 30px;
    }

    .social-label-heading {
        font-weight: bold;
        display: block;
        margin-bottom: 15px;
        font-size: 1rem;
        color: #1a1a1a;
    }

    /* Botón circular minimalista para redes sociales */
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
        font-size: 1.2rem; /* Tamaño del icono */
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
        <a href="{{ route('project.detail') }}" class="akira-link-reset me-1">
            <span class="brand-name-header">Akiraka Studio,</span>
        </a>
        
        <a href="{{ route('info') }}" class="akira-link-reset me-1">Info ,</a>
        <a href="{{ route('contacto') }}" class="akira-link-reset nav-link-bold">Contacto</a>
    </header>

    <p class="contact-instruction">Si deseas contactarnos, por favor envía un correo a la dirección designada.</p>

    <div class="row contact-group-section mb-5">
        <div class="col-md-4 mb-4">
            <span class="contact-label">Proyectos y Eventos</span>
            <a href="mailto:info@estudioakiraka.com" class="contact-value-reset">info@estudioakiraka.com</a>
        </div>

        <div class="col-md-4 mb-4">
            <span class="contact-label">Prensa</span>
            <a href="mailto:press@estudioakiraka.com" class="contact-value-reset">press@estudioakiraka.com</a>
        </div>

        <div class="col-md-4 mb-4">
            <span class="contact-label">Oportunidades laborales</span>
            <a href="mailto:hr@estudioakiraka.com" class="contact-value-reset">hr@estudioakiraka.com</a>
        </div>
    </div>

    <div class="location-group border-top pt-4 mb-5">
        <div class="row">
            <div class="col-md-6 mb-3">
                <span class="location-year-label">CDMX</span>
                <span class="contact-value-reset">+52 (55) 0000 0000</span>
            </div>
            <div class="col-md-6 mb-3">
                <span class="location-year-label">NY</span>
                <span class="contact-value-reset">+1 (212) 000 0000</span>
            </div>
        </div>
    </div>

    <div class="social-group-section text-center">
        <span class="social-label-heading">Síguenos</span>
        
        <a href="https://www.instagram.com/akiraka.estudio/" target="_blank" class="social-btn-circle" title="Instagram">
            <i class="bi bi-instagram"></i>
        </a>
        
        <a href="https://www.facebook.com/profile.php?id=61568259411265&locale=es_LA" target="_blank" class="social-btn-circle" title="Facebook">
            <i class="bi bi-facebook"></i>
        </a>
    </div>
</div>
@endsection