@extends('layouts.app')

@section('content')
@php
    $config = \App\Models\Configuracion::first();
@endphp

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    /* ================= ESTILOS BASE DEL ESTUDIO ================= */
    .akira-container { max-width: 1100px; margin: 0 auto; padding: 50px 20px; font-family: "Georgia", "Times New Roman", serif; color: #1a1a1a; }
    .akira-link-reset { text-decoration: none; color: inherit; font-size: 1rem; transition: opacity 0.3s ease; }
    .akira-link-reset:hover { opacity: 0.6; color: #000; }
    .brand-name-header { font-size: 1.1rem; font-weight: normal; }
    .nav-link-bold { font-weight: bold; }
    .contact-label { font-weight: bold; display: block; margin-bottom: 2px; font-size: 1rem; color: #1a1a1a; }
    
    .contact-value-reset { text-decoration: none; color: #666; font-size: 0.95rem; transition: color 0.3s; line-height: 1.5; cursor: pointer; }
    .contact-value-reset:hover { color: #000; text-decoration: underline; }
    
    .contact-instruction { font-style: italic; font-size: 0.95rem; margin-bottom: 40px; color: #666; }
    .location-year-label { color: #ccc; font-size: 0.85rem; margin-right: 15px; display: inline-block; width: 40px; }

    /* ================= SECCIÓN DEL MAPA ================= */
    .map-section { margin-top: 60px; border-top: 1px solid #eee; padding-top: 40px; }
    .map-wrapper { width: 100%; height: 500px; filter: grayscale(100%); border: 1px solid #111; transition: filter 0.5s ease; margin-bottom: 20px; }
    .map-wrapper:hover { filter: grayscale(0%); }
    .map-caption { font-size: 0.85rem; color: #888; letter-spacing: 0.5px; display: flex; align-items: center; gap: 8px; }

    /* ================= REDES SOCIALES ================= */
    .social-group-section { margin-top: 50px; border-top: 1px solid #eee; padding-top: 30px; text-align: center; }
    .social-label-heading { font-weight: bold; display: block; margin-bottom: 15px; font-size: 1rem; color: #1a1a1a; }
    .social-btn-circle { display: inline-flex; align-items: center; justify-content: center; width: 45px; height: 45px; border-radius: 50%; border: 1px solid #1a1a1a; color: #1a1a1a; text-decoration: none; font-size: 1.2rem; margin-right: 10px; transition: all 0.3s ease; }
    .social-btn-circle:hover { background-color: #1a1a1a; color: #fff; }

    /* ================= NUEVO: MODAL DE CONTACTO ================= */
    .modal-overlay-contact { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(8px); display: none; justify-content: center; align-items: center; z-index: 10000; opacity: 0; transition: opacity 0.3s ease; }
    .modal-overlay-contact.active { display: flex; opacity: 1; }
    .modal-contact-box { background: #fff; padding: 40px; width: 100%; max-width: 500px; border-radius: 12px; box-shadow: 0 15px 40px rgba(0,0,0,0.1); text-align: center; font-family: Arial, sans-serif; position: relative; }
    .btn-close-modal { position: absolute; top: 15px; right: 20px; font-size: 1.5rem; cursor: pointer; background: none; border: none; color: #888; transition: color 0.3s; }
    .btn-close-modal:hover { color: #111; }
    .modal-contact-title { font-family: "Garamond", serif; font-size: 2rem; margin-bottom: 5px; color: #111; }
    .modal-contact-subtitle { color: #666; font-size: 0.9rem; margin-bottom: 30px; }
    .contact-options-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px; }
    .option-circle-btn { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 20px; border: 1px solid #eee; border-radius: 12px; cursor: pointer; transition: all 0.3s ease; background: #fafafa; color: #333; text-decoration: none; }
    .option-circle-btn i { font-size: 2rem; margin-bottom: 10px; color: #111; }
    .option-circle-btn span { font-size: 0.85rem; font-weight: bold; }
    .option-circle-btn:hover, .option-circle-btn.selected { background: #111; color: #fff; border-color: #111; transform: translateY(-3px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
    .option-circle-btn:hover i, .option-circle-btn.selected i { color: #fff; }

    /* Formulario Directo Oculto */
    #formMensajeDirecto { display: none; text-align: left; }
    .form-input-contact { width: 100%; padding: 12px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 6px; font-family: Arial, sans-serif; font-size: 0.9rem; }
    .form-input-contact:focus { outline: none; border-color: #111; }
    .btn-submit-contact { width: 100%; padding: 12px; background: #111; color: #fff; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; transition: background 0.3s; }
    .btn-submit-contact:hover { background: #333; }
</style>

<div class="akira-container">
    <header class="site-header-main mb-5">
        <a href="{{ route('project.detail') }}" style="text-decoration: none; color: inherit; font-weight: normal;">Estudio Akiraka, </a>
        <a href="{{ route('info') }}" style="text-decoration: none; color: inherit;">Info</a>, 
        <a href="{{ route('contacto') }}" style="text-decoration: none; color: #1a1a1a; font-weight: bold;">Contacto</a>.
    </header>

    @if(session('success'))
        <div class="alert alert-dark" style="font-family: Arial; text-align: center; margin-bottom: 30px;">
            {{ session('success') }}
        </div>
    @endif

    <p class="contact-instruction">Si deseas contactarnos, por favor selecciona una de las opciones a continuación.</p>

    <div class="row contact-group-section mb-5">
        <div class="col-md-4 mb-4">
            <span class="contact-label">Proyectos y Eventos</span>
            <div class="contact-value-reset" onclick="abrirModalContacto('{{ $config->correo_contacto ?? 'akiraka.estudio@gmail.com' }}')">
                {{ $config->correo_contacto ?? 'akiraka.estudio@gmail.com' }}<br>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <span class="contact-label">Prensa</span>
            <div class="contact-value-reset" onclick="abrirModalContacto('proyectos@akirakastudio.com')">proyectos@akirakastudio.com</div>
        </div>

        <div class="col-md-4 mb-4">
            <span class="contact-label">Oportunidades laborales</span>
            <div class="contact-value-reset" onclick="abrirModalContacto('dirección@akirakastudio.com')">
                dirección@akirakastudio.com<br>studio@akirakastudio.com
            </div>
        </div>
    </div>

    <div class="location-group border-top pt-4 mb-5">
        <div class="row">
            <div class="col-md-6 mb-3">
                <span class="location-year-label">Teléfono</span>
                <span class="contact-value-reset"><br>{{ $config->telefono ?? '+52 722 165 5901' }}</span>
            </div>
            
            <div class="col-md-6 mb-3">
                <span class="location-year-label">Dirección</span>
                <span class="contact-value-reset"><br>{!! nl2br(e($config->direccion ?? 'Parque Santa María 10, Valle de Bravo, Méx.')) !!}</span>
            </div>
        </div>
    </div>

    <div class="map-section">
        <span class="contact-label mb-3" style="text-transform: uppercase; font-size: 0.75rem; letter-spacing: 2px;">Ubicación / Espacio Odisea</span>
        <div class="map-wrapper">
            <iframe width="100%" height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d467.4589255627712!2d-100.1363651761623!3d19.189445173707252!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x85cd647e9a15bc7b%3A0x59426b73189edf87!2sEspacio%20Odisea!5e0!3m2!1ses-419!2smx!4v1709673000000!5m2!1ses-419!2smx" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </div>

    <div class="social-group-section">
        <span class="social-label-heading">Síguenos</span>
        <a href="{{ $config->instagram ?? 'https://www.instagram.com/' }}" target="_blank" class="social-btn-circle" title="Instagram"><i class="bi bi-instagram"></i></a>
        <a href="{{ $config->facebook ?? 'https://www.facebook.com/' }}" target="_blank" class="social-btn-circle" title="Facebook"><i class="bi bi-facebook"></i></a>
    </div>
</div>

<div id="modalContactoElegir" class="modal-overlay-contact">
    <div class="modal-contact-box">
        <button class="btn-close-modal" onclick="cerrarModalContacto()">✕</button>
        
        <h3 class="modal-contact-title">Mensaje</h3>
        <p class="modal-contact-subtitle">¿Deseas contactarnos? Selecciona una opción.</p>

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
            <input type="hidden" name="departamento" id="inputDepartamento" value="">
            
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
        // Prepara el enlace nativo de correo por si escogen esa opción
        btnMailto.href = "mailto:" + correoDestino;
        // Guarda la info oculta para el form por si escogen el mensaje directo
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