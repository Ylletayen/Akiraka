@extends('layouts.app')

@section('content')
<!-- Extraemos la configuración directamente desde la BD -->
@php
    $config = \App\Models\Configuracion::first();
    // Limpiamos el teléfono para el link de WhatsApp
    $whatsappPhone = preg_replace('/[^0-9]/', '', $config->telefono ?? '527221655901');
@endphp

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    /* ================= ESTILOS BASE DEL ESTUDIO (OG STYLE) ================= */
    .akira-container {
        max-width: 1100px; 
        margin: 0 auto;
        padding: 50px 20px;
        font-family: "Georgia", "Times New Roman", serif;
        color: #1a1a1a;
    }

    .site-header-main {
        margin-bottom: 60px;
    }

    .contact-label {
        font-weight: bold;
        display: block;
        margin-bottom: 5px;
        font-size: 1rem;
        color: #1a1a1a;
    }

    .contact-value-reset {
        text-decoration: none;
        color: #666; 
        font-size: 0.95rem;
        transition: color 0.3s;
        line-height: 1.6;
        cursor: pointer;
        display: block;
    }

    .contact-value-reset:hover {
        color: #000;
        text-decoration: underline;
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
        width: 60px;
    }

    /* ================= SECCIÓN DEL MAPA ================= */
    .map-section {
        margin-top: 60px;
        border-top: 1px solid #eee;
        padding-top: 40px;
    }

    .map-wrapper {
        width: 100%;
        height: 500px;
        filter: grayscale(100%);
        border: 1px solid #111;
        transition: filter 0.5s ease;
        margin-bottom: 20px;
    }

    .map-wrapper:hover { filter: grayscale(0%); }

    /* ================= REDES SOCIALES (ICONOS) ================= */
    .social-group-section {
        margin-top: 50px;
        border-top: 1px solid #eee;
        padding-top: 30px;
        text-align: center;
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
        margin: 0 8px;
        transition: all 0.3s ease;
    }

    .social-btn-circle:hover {
        background-color: #1a1a1a;
        color: #fff;
    }

    /* ================= ESTILOS DEL MODAL (NUEVA FUNCIÓN) ================= */
    .modal-overlay-contact {
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.8);
        backdrop-filter: blur(8px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 10000;
    }
    .modal-overlay-contact.active { display: flex; }

    .modal-contact-box {
        background: #fff;
        width: 90%;
        max-width: 450px;
        padding: 50px 40px;
        position: relative;
        box-shadow: 0 30px 60px rgba(0,0,0,0.3);
    }

    .btn-close-modal {
        position: absolute; top: 20px; right: 20px;
        background: none; border: none; font-size: 1.2rem; color: #888; cursor: pointer;
    }

    .modal-contact-title { font-family: "Georgia", serif; letter-spacing: 2px; text-transform: uppercase; font-size: 1.2rem; margin-bottom: 10px; text-align: center; }
    .modal-contact-subtitle { font-size: 0.8rem; color: #888; text-align: center; margin-bottom: 30px; }

    .contact-options-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .option-circle-btn {
        display: flex; flex-direction: column; align-items: center; gap: 10px;
        padding: 20px; border: 1px solid #eee; text-decoration: none; color: #111;
        transition: all 0.3s; cursor: pointer;
    }
    .option-circle-btn:hover { background: #f9f9f9; border-color: #111; }
    .option-circle-btn i { font-size: 1.8rem; }
    .option-circle-btn span { font-size: 0.7rem; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }

    #formMensajeDirecto { display: none; }
    .form-input-contact {
        width: 100%; padding: 12px 0; border: none; border-bottom: 1px solid #eee;
        margin-bottom: 20px; outline: none; font-family: inherit; transition: border-color 0.3s;
    }
    .form-input-contact:focus { border-bottom-color: #111; }
    .btn-submit-contact {
        width: 100%; padding: 15px; background: #111; color: #fff; border: none;
        text-transform: uppercase; letter-spacing: 2px; font-size: 0.75rem; cursor: pointer;
    }
</style>

<div class="akira-container">
    <!-- CABECERA OG -->
    <header class="site-header-main">
        <a href="{{ route('project.detail') }}" style="text-decoration: none; color: inherit;">Estudio Akiraka, </a>
        <a href="{{ route('info') }}" style="text-decoration: none; color: inherit;">Info</a>, 
        <a href="{{ route('contacto') }}" style="text-decoration: none; color: #1a1a1a; font-weight: bold;">Contacto</a>.
    </header>

    @if(session('success'))
        <div class="alert alert-dark" style="font-family: Arial; text-align: center; margin-bottom: 30px; font-size: 0.8rem; letter-spacing: 1px;">
            {{ session('success') }}
        </div>
    @endif

    <p class="contact-instruction">Si deseas contactarnos, por favor selecciona una de las opciones a continuación.</p>

    <!-- COLUMNAS DE CORREOS (DINÁMICAS) -->
    <div class="row contact-group-section mb-5">
        <div class="col-md-4 mb-4">
            <span class="contact-label">Proyectos y Eventos</span>
            <div class="contact-value-reset" onclick="abrirModalContacto('{{ $config->correo_contacto ?? 'akiraka.estudio@gmail.com' }}')">
                {{ $config->correo_contacto ?? 'akiraka.estudio@gmail.com' }}
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <span class="contact-label">Prensa</span>
            <div class="contact-value-reset" onclick="abrirModalContacto('{{ $config->correo_prensa ?? 'proyectos@akirakastudio.com' }}')">
                {{ $config->correo_prensa ?? 'proyectos@akirakastudio.com' }}
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <span class="contact-label">Oportunidades laborales</span>
            <div class="contact-value-reset" onclick="abrirModalContacto('{{ $config->correo_laboral_1 ?? 'dirección@akirakastudio.com' }}')">
                {{ $config->correo_laboral_1 ?? 'dirección@akirakastudio.com' }}<br>
                {{ $config->correo_laboral_2 ?? 'studio@akirakastudio.com' }}
            </div>
        </div>
    </div>

    <!-- SECCIÓN DE UBICACIÓN -->
    <div class="location-group border-top pt-4 mb-5">
        <div class="row">
            <div class="col-md-6 mb-3">
                <span class="location-year-label">Teléfono</span>
                <span class="contact-value-reset"><br>{{ $config->telefono ?? '+52 722 165 5901' }}</span>
            </div>
            <div class="col-md-6 mb-3">
                <span class="location-year-label">Dirección</span>
                <span class="contact-value-reset"><br>{!! nl2br(e($config->direccion ?? "Parque Santa María 10,\nValle de Bravo, Méx.")) !!}</span>
            </div>
        </div>
    </div>

    <!-- MAPA -->
    <div class="map-section">
        <span class="contact-label mb-3" style="text-transform: uppercase; font-size: 0.75rem; letter-spacing: 2px;">Ubicación / Espacio Odisea</span>
        <div class="map-wrapper">
            <iframe width="100%" height="100%" frameborder="0" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d467.4589255627712!2d-100.1363651761623!3d19.189445173707252!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x85cd647e9a15bc7b%3A0x59426b73189edf87!2sEspacio%20Odisea!5e0!3m2!1ses-419!2smx!4v1709673000000!5m2!1ses-419!2smx" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </div>

    <!-- REDES SOCIALES (CON WHATSAPP AGREGADO) -->
    <div class="social-group-section">
        <span class="social-label-heading">Síguenos</span>
        <a href="{{ $config->instagram ?? 'https://www.instagram.com/' }}" target="_blank" class="social-btn-circle" title="Instagram"><i class="bi bi-instagram"></i></a>
        <a href="{{ $config->facebook ?? 'https://www.facebook.com/' }}" target="_blank" class="social-btn-circle" title="Facebook"><i class="bi bi-facebook"></i></a>
        <!-- WhatsApp Dinámico -->
        <a href="https://wa.me/{{ $whatsappPhone }}" target="_blank" class="social-btn-circle" title="WhatsApp"><i class="bi bi-whatsapp"></i></a>
    </div>
</div>

<!-- MODAL DE CONTACTO (FUSIÓN CON EL CÓDIGO DEL COMPAÑERO) -->
<div id="modalContactoElegir" class="modal-overlay-contact">
    <div class="modal-contact-box">
        <button class="btn-close-modal" onclick="cerrarModalContacto()">✕</button>
        
        <h3 class="modal-contact-title">Contacto</h3>
        <p class="modal-contact-subtitle">¿Cómo prefieres comunicarte con nosotros?</p>

        <div class="contact-options-grid" id="opcionesContactoGrid">
            <a href="#" id="btnMailto" class="option-circle-btn">
                <i class="bi bi-envelope-at"></i>
                <span>Enviar Email</span>
            </a>
            <div class="option-circle-btn" onclick="mostrarFormularioDirecto()">
                <i class="bi bi-chat-left-text"></i>
                <span>Mensaje Directo</span>
            </div>
        </div>

        <form id="formMensajeDirecto" action="{{ route('contacto.mensaje.store') }}" method="POST">
            @csrf
            <input type="hidden" name="departamento_email" id="inputDepartamento" value="">
            
            <input type="text" name="nombre" class="form-input-contact" placeholder="Tu Nombre" required>
            <input type="email" name="correo" class="form-input-contact" placeholder="Tu Correo" required>
            <input type="text" name="asunto" class="form-input-contact" placeholder="Asunto" required>
            <textarea name="mensaje" class="form-input-contact" rows="4" placeholder="Escribe tu mensaje aquí..." required></textarea>
            
            <button type="submit" class="btn-submit-contact">Enviar al Estudio</button>
            <div style="text-align: center; margin-top: 15px;">
                <a href="javascript:void(0)" onclick="volverOpcionesContacto()" style="color: #888; text-decoration: underline; font-size: 0.8rem;">← Volver a opciones</a>
            </div>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('modalContactoElegir');
    const btnMailto = document.getElementById('btnMailto');
    const gridOpciones = document.getElementById('opcionesContactoGrid');
    const formDirecto = document.getElementById('formMensajeDirecto');
    const inputDept = document.getElementById('inputDepartamento');

    function abrirModalContacto(correoDestino) {
        modal.classList.add('active');
        btnMailto.href = "mailto:" + correoDestino;
        inputDept.value = correoDestino; 
    }

    function cerrarModalContacto() {
        modal.classList.remove('active');
        setTimeout(() => { volverOpcionesContacto(); }, 300); 
    }

    function mostrarFormularioDirecto() {
        gridOpciones.style.display = 'none';
        formDirecto.style.display = 'block';
    }

    function volverOpcionesContacto() {
        formDirecto.style.display = 'none';
        gridOpciones.style.display = 'grid';
        formDirecto.reset(); 
    }
</script>
@endsection