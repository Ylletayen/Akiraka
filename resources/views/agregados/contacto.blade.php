@extends('layouts.app')

@section('content')
<style>
    .akira-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 50px 20px;
        font-family: "Georgia", "Times New Roman", serif;
        color: #333;
    }
    .contact-instruction {
        font-style: italic;
        font-size: 0.95rem;
        margin-bottom: 40px;
    }
    .contact-section {
        margin-bottom: 35px;
    }
    .contact-label {
        font-weight: bold;
        display: block;
        margin-bottom: 5px;
        font-size: 1rem;
    }
    .contact-value {
        text-decoration: none;
        color: #333;
        font-size: 0.95rem;
    }
    .location-group {
        margin-top: 50px;
    }
    .location-item {
        margin-bottom: 25px;
    }
</style>

<div class="akira-container">
    <div class="akira-header mb-5">
        <a href="{{ route('project.detail') }}" style="text-decoration: none; color: inherit;">
    <strong>Estudio Akiraka</strong></a>
         <span style="color: #ccc;">Noticias, Obras, Info, Contacto.</span>
    </div>

    <p class="contact-instruction">Si deseas contactarnos, por favor envía un correo a la dirección designada.</p>

    <div class="contact-section">
        <span class="contact-label">Proyectos y Eventos</span>
        <a href="mailto:info@estudioakiraka.com" class="contact-value">info@estudioakiraka.com</a>
    </div>

    <div class="contact-section">
        <span class="contact-label">Prensa</span>
        <a href="mailto:press@estudioakiraka.com" class="contact-value">press@estudioakiraka.com</a>
    </div>

    <div class="contact-section">
        <span class="contact-label">Oportunidades laborales</span>
        <a href="mailto:hr@estudioakiraka.com" class="contact-value">hr@estudioakiraka.com</a>
    </div>

    <div class="location-group">
        <div class="location-item">
            <span class="contact-label">Ciudad de México</span>
            <span class="contact-value">+52 (55) 0000 0000</span>
        </div>

        <div class="location-item">
            <span class="contact-label">Nueva York</span>
            <span class="contact-value">+1 (212) 000 0000</span>
        </div>
    </div>
</div>
@endsection