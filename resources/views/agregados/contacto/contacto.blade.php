@extends('layouts.app')

@section('content')
@php
    $config = \App\Models\Configuracion::first();
    // Limpiamos el teléfono para el link de WhatsApp
    $whatsappPhone = preg_replace('/[^0-9]/', '', $config->telefono ?? '527221655901');
    
    // --- ESTA ES LA LÍNEA QUE DEBES CORREGIR ---
    $mediaUrl = $config && $config->landing_hero_image 
                 ? asset('storage/' . $config->landing_hero_image) // <-- DEBE DECIR 'storage/'
                 : 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&q=80&w=1920';
                 
    // Detectamos si es un video MP4
    $isVideo = preg_match('/\.(mp4|webm)$/i', $mediaUrl);
    // ... lo demás sigue igual ...
@endphp

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" />

<style>
    .side-media {
        position: fixed;
        top: 0;
        width: 14vw;
        height: 100vh;
        z-index: -1;
        overflow: hidden;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        filter: blur(6px) opacity(0.45) grayscale(20%); 
        transition: filter 0.8s ease;
    }

    .side-left { 
        left: 0; 
        -webkit-mask-image: linear-gradient(to right, black 30%, transparent 100%);
        mask-image: linear-gradient(to right, black 30%, transparent 100%);
    }
    
    .side-right { 
        right: 0; 
        -webkit-mask-image: linear-gradient(to left, black 30%, transparent 100%);
        mask-image: linear-gradient(to left, black 30%, transparent 100%);
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
    }

    @media (max-width: 1100px) {
        .side-media { display: none !important; }
    }

    .akira-container { 
        max-width: 780px;
        margin: 0 auto; 
        padding: 50px 30px;
        font-family: "Georgia", "Times New Roman", serif; 
        color: #333; 
        position: relative;
        z-index: 1; 
    }

    .site-header-main { margin-bottom: 60px; }

    .nav-link-akira {
        position: relative;
        display: inline-block;
        padding-bottom: 2px;
        text-decoration: none !important;
        color: #8c8c8c;
        transition: color 0.3s ease;
    }
    
    .nav-link-akira::after {
        content: '';
        position: absolute;
        width: 0;
        height: 1px;
        bottom: 0;
        left: 0;
        background-color: currentColor;
        transition: width 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
    }
    
    .nav-link-akira:hover {
        color: #111111;
    }
    .nav-link-akira:hover::after {
        width: 100%;
    }

    .active-link {
        font-weight: bold !important;
        color: #111111 !important;
    }
    .active-link::after {
        width: 100% !important;
    }

    .contact-label { font-weight: bold; display: block; margin-bottom: 5px; font-size: 1rem; color: #1a1a1a; }
    .contact-value-reset { text-decoration: none; color: #666; font-size: 0.95rem; transition: color 0.3s; line-height: 1.6; cursor: pointer; display: block; }
    .contact-value-reset:hover { color: #000; text-decoration: underline; }
    .contact-instruction { font-style: italic; font-size: 0.95rem; margin-bottom: 40px; color: #666; text-align: center; }
    .location-year-label { color: #ccc; font-size: 0.85rem; margin-right: 15px; display: inline-block; width: 60px; }

    .btn-agendar-cita {
        display: inline-block;
        background: #111; color: #fff; border: none;
        padding: 15px 40px; font-family: Arial, sans-serif; font-size: 0.85rem;
        letter-spacing: 2px; text-transform: uppercase; cursor: pointer;
        transition: background 0.3s ease, transform 0.2s ease;
    }
    .btn-agendar-cita:hover { background: #333; transform: translateY(-2px); }

    .social-group-section { margin-top: 50px; border-top: 1px solid #eee; padding-top: 30px; text-align: center; }
    .social-btn-circle { display: inline-flex; align-items: center; justify-content: center; width: 45px; height: 45px; border-radius: 50%; border: 1px solid #1a1a1a; color: #1a1a1a; text-decoration: none; font-size: 1.2rem; margin: 0 8px; transition: all 0.3s ease; }
    .social-btn-circle:hover { background-color: #1a1a1a; color: #fff; }

    .modal-overlay-contact { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); backdrop-filter: blur(8px); display: none; align-items: center; justify-content: center; z-index: 10000; overflow-y: auto; padding: 20px; }
    .modal-overlay-contact.active { display: flex; }

    .modal-contact-box { background: #fff; width: 100%; max-width: 500px; padding: 50px 40px; position: relative; box-shadow: 0 30px 60px rgba(0,0,0,0.3); }
    .modal-cita-box { max-width: 650px; }

    .btn-close-modal { position: absolute; top: 20px; right: 20px; background: none; border: none; font-size: 1.2rem; color: #888; cursor: pointer; transition: color 0.3s; }
    .btn-close-modal:hover { color: #111; }

    .modal-contact-title { font-family: "Georgia", serif; letter-spacing: 2px; text-transform: uppercase; font-size: 1.2rem; margin-bottom: 10px; text-align: center; }
    .modal-contact-subtitle { font-size: 0.8rem; color: #888; text-align: center; margin-bottom: 30px; }

    .contact-options-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .option-circle-btn { display: flex; flex-direction: column; align-items: center; gap: 10px; padding: 20px; border: 1px solid #eee; text-decoration: none; color: #111; transition: all 0.3s; cursor: pointer; }
    .option-circle-btn:hover { background: #f9f9f9; border-color: #111; }

    .form-input-contact { width: 100%; padding: 12px 0; border: none; border-bottom: 1px solid #eee; margin-bottom: 20px; outline: none; font-family: Arial, sans-serif; transition: border-color 0.3s; background: transparent; }
    .form-input-contact:focus { border-bottom-color: #111; }
    
    .form-label-small { display: block; font-family: Arial, sans-serif; font-size: 0.75rem; letter-spacing: 1px; color: #888; margin-bottom: 5px; text-transform: uppercase; }

    .btn-submit-contact { width: 100%; padding: 15px; background: #111; color: #fff; border: none; text-transform: uppercase; letter-spacing: 2px; font-size: 0.75rem; cursor: pointer; transition: background 0.3s; }
    .btn-submit-contact:hover { background: #333; }

    .btn-flotante-regresar {
        position: fixed;
        bottom: clamp(25px, 5vh, 45px); 
        left: clamp(30px, 5vw, 60px);
        font-size: 0.90rem;
        color: #111111 !important; /* <--- Letras en negro oscuro */
        text-decoration: none !important; 
        z-index: 9999;
        
        background: rgba(255, 255, 255, 0.03); 
        backdrop-filter: blur(25px); 
        -webkit-backdrop-filter: blur(25px); 
        border: 1px solid rgba(0, 0, 0, 0.1); /* <--- Borde un poco más oscuro para que combine */
        
        padding: 12px 32px; 
        border-radius: 50px; 
        
        opacity: 0.6; 
        transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        font-family: "Georgia", "Times New Roman", serif;
        letter-spacing: 1.5px; 
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05); 
    }

    .btn-flotante-regresar:hover {
        opacity: 1; 
        background: rgba(255, 255, 255, 0.5); /* <--- Cristal claro para que las letras negras destaquen */
        border-color: rgba(0, 0, 0, 0.3);
        transform: translateX(-5px) translateY(-2px); 
        color: #111111 !important; /* <--- Letras en negro oscuro en hover */
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15); 
    }

    .iti { width: 100%; }

    .site-footer-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.95rem;
        color: #8c8c8c;
        padding-top: 40px;
        margin-top: 40px;
        border-top: 1px solid #eee;
    }
    .site-footer-info a {
        color: #8c8c8c;
        text-decoration: none;
        transition: color 0.3s ease;
    }
    .site-footer-info a:hover {
        color: #111;
    }
</style>

{{-- AQUI ESTÁN TUS COLUMNAS DE HTML --}}
{{-- COLUMNA MULTIMEDIA IZQUIERDA --}}
<div class="side-media side-left {{ !$isVideo ? 'hero-image-bg' : '' }}" @if(!$isVideo) style="background-image: url('{{ $mediaUrl }}');" @endif>
    @if($isVideo)
        <video autoplay loop muted playsinline class="hero-video-bg"><source src="{{ $mediaUrl }}" type="video/mp4"></video>
    @endif
</div>

{{-- COLUMNA MULTIMEDIA DERECHA --}}
<div class="side-media side-right {{ !$isVideo ? 'hero-image-bg' : '' }}" @if(!$isVideo) style="background-image: url('{{ $mediaUrl }}'); transform: scaleX(-1);" @endif>
    @if($isVideo)
        <video autoplay loop muted playsinline class="hero-video-bg" style="transform: translateX(-50%) translateY(-50%) scaleX(-1);"><source src="{{ $mediaUrl }}" type="video/mp4"></video>
    @endif
</div>
{{-- FIN DE LAS COLUMNAS MULTIMEDIA --}}

<a href="{{ route('landing') }}" class="btn-flotante-regresar">← regresar</a>

<div class="akira-container">
    <header class="site-header-main">
        <a href="{{ route('project.detail') ?? '#' }}" class="nav-link-akira {{ request()->routeIs('project.detail') ? 'active-link' : '' }}">Estudio Akiraka ,</a>
        <a href="{{ route('info') ?? '#' }}" class="nav-link-akira {{ request()->routeIs('info') ? 'active-link' : '' }}">Info ,</a>
        
        {{-- EL NUEVO ENLACE A RESEÑAS --}}
        <a href="{{ route('resenas.index') ?? '#' }}" class="nav-link-akira {{ request()->routeIs('resenas.index') ? 'active-link' : '' }}">Reseñas ,</a>
        
        <a href="{{ route('contacto') ?? '#' }}" class="nav-link-akira {{ request()->routeIs('contacto') ? 'active-link' : '' }}">Contacto</a>
    </header>
    @if(session('success'))
        <div style="background: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9; padding: 15px; text-align: center; margin-bottom: 30px; font-family: Arial; font-size: 0.9rem; letter-spacing: 1px;">
            {{ session('success') }}
        </div>
    @endif

    <p class="contact-instruction">Si deseas contactarnos por consultas generales o prensa, selecciona una opción.</p>

    <div class="row contact-group-section mb-5">
        <div class="col-md-4 mb-4">
            <span class="contact-label">Proyectos y Eventos</span>
            <div class="contact-value-reset notranslate" onclick="abrirModalContacto('{{ $config->correo_contacto ?? 'akiraka.estudio@gmail.com' }}')">
                {{ $config->correo_contacto ?? 'akiraka.estudio@gmail.com' }}
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <span class="contact-label">Prensa</span>
            <div class="contact-value-reset notranslate" onclick="abrirModalContacto('{{ $config->correo_prensa ?? 'proyectos@akirakastudio.com' }}')">
                {{ $config->correo_prensa ?? 'proyectos@akirakastudio.com' }}
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <span class="contact-label">Oportunidades laborales</span>
            <div class="contact-value-reset notranslate" onclick="abrirModalContacto('{{ $config->correo_laboral_1 ?? 'dirección@akirakastudio.com' }}')">
                {{ $config->correo_laboral_1 ?? 'dirección@akirakastudio.com' }}<br>
            </div>
            <div class="contact-value-reset notranslate" onclick="abrirModalContacto('{{ $config->correo_laboral_2 ?? 'studio@akirakastudio.com' }}')">
                {{ $config->correo_laboral_2 ?? 'studio@akirakastudio.com' }}<br>
            </div>
        </div>
    </div>

    <div class="location-group border-top pt-4 mb-5 d-flex flex-column align-items-center text-center">
        <span class="location-year-label" style="margin-right: 0; width: auto; margin-bottom: 5px;">Teléfono</span>
        <span class="contact-value-reset notranslate">{{ $config->telefono ?? '+52 722 165 5901' }}</span>
    </div>

    <div class="social-group-section">
        <span style="display: block; margin-bottom: 20px; font-family: Arial; font-size: 0.8rem; letter-spacing: 1px; color: #888; text-transform: uppercase;">Síguenos</span>
        <a href="{{ $config->instagram ?? 'https://www.instagram.com/' }}" target="_blank" class="social-btn-circle" title="Instagram"><i class="bi bi-instagram"></i></a>
        <a href="{{ $config->facebook ?? 'https://www.facebook.com/' }}" target="_blank" class="social-btn-circle" title="Facebook"><i class="bi bi-facebook"></i></a>
        <a href="https://wa.me/{{ $whatsappPhone }}" target="_blank" class="social-btn-circle" title="WhatsApp"><i class="bi bi-whatsapp"></i></a>
    </div>

    <div style="text-align: center; margin-top: 60px; padding-top: 50px; border-top: 1px solid #eee;">
        <h2 style="font-family: 'Garamond', serif; font-size: 2rem; margin-bottom: 15px;">¿Tienes un proyecto en mente?</h2>
        <p class="contact-instruction" style="margin-bottom: 30px;">Nos encantaría escucharte y hacerlo realidad.</p>
        <button class="btn-agendar-cita" onclick="abrirModalCita()">Iniciar Proyecto / Agendar Cita</button>
    </div>

    <footer class="site-footer-info">
        <div>2026</div>
        <div>
            <a href="#" id="btn-traducir" onclick="cambiarIdioma('en', event)">Read in English</a>
            <a href="#" id="btn-espanol" onclick="cambiarIdioma('es', event)" style="display:none;">Leer en Español</a>
        </div>
    </footer>
</div>

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

        <form id="formMensajeDirecto" action="{{ route('contacto.mensaje.store') }}" method="POST" style="display: none;">
            @csrf
            <input type="hidden" name="departamento_email" id="inputDepartamento" value="" class="notranslate">
            
            <input type="text" name="nombre" class="form-input-contact" placeholder="Tu Nombre" pattern="^[A-ZÁÉÍÓÚÑ][a-zA-ZáéíóúÁÉÍÓÚñÑ\s]*[aeiouáéíóúAEIOUÁÉÍÓÚ][a-zA-ZáéíóúÁÉÍÓÚñÑ\s]*$" title="El nombre debe iniciar con Mayúscula y contener al menos una vocal." required>
            <input type="email" name="correo" class="form-input-contact" placeholder="Tu Correo" required>
            <input type="text" name="asunto" class="form-input-contact" placeholder="Asunto" required>
            <textarea name="mensaje" class="form-input-contact" rows="4" placeholder="Escribe tu mensaje aquí..." required></textarea>
            
            <button type="submit" class="btn-submit-contact">Enviar al Estudio</button>
            <div style="text-align: center; margin-top: 15px;">
                <a href="javascript:void(0)" onclick="volverOpcionesContacto()" style="color: #888; text-decoration: underline; font-size: 0.8rem; font-family: Arial;">← Volver a opciones</a>
            </div>
        </form>
    </div>
</div>

<div id="modalAgendarCita" class="modal-overlay-contact">
    <div class="modal-contact-box modal-cita-box">
        <button class="btn-close-modal" onclick="cerrarModalCita()">✕</button>
        
        <h3 class="modal-contact-title">Inicia tu Proyecto</h3>
        <p class="modal-contact-subtitle" style="margin-bottom: 20px;">Llena este formulario y nos pondremos en contacto para confirmar tu cita.</p>

        <form action="{{ route('api.citas.store') }}" method="POST" id="form-cita">
            @csrf
            <div style="margin-bottom: 20px;">
               <label class="form-label-small">NOMBRE COMPLETO</label>
                <input type="text" 
                       name="nombre" 
                       class="form-input-contact" 
                       style="margin-bottom: 0;" 
                       pattern="^[A-ZÁÉÍÓÚÑ][a-zA-ZáéíóúÁÉÍÓÚñÑ\s]*[aeiouáéíóúAEIOUÁÉÍÓÚ][a-zA-ZáéíóúÁÉÍÓÚñÑ\s]*$" 
                       title="El nombre debe iniciar con Mayúscula y contener al menos una vocal." 
                       required>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; margin-top: 20px;">
                <div>
                    <label class="form-label-small">CORREO ELECTRÓNICO</label>
                    <input type="email" name="correo" class="form-input-contact" style="margin-bottom: 0;" required>
                </div>
                <div>
                    <label class="form-label-small">TELÉFONO</label>
                    <input type="tel" name="telefono" id="telefonoCita" class="form-input-contact" style="margin-bottom: 0;" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label class="form-label-small">ASUNTO / SERVICIO</label>
                    <select name="id_servicio" class="form-input-contact" style="margin-bottom: 0; padding: 10px 0;" required>
                        <option value="" disabled selected>Selecciona una opción...</option>
                        
                        @foreach($servicios as $servicio)
                        <option value="{{ $servicio->id_servicio }}">{{ $servicio->nombre }}</option>
                        @endforeach 
                        
                    </select>
                </div>
                <div>
                    <label class="form-label-small">FECHA Y HORA DESEADA</label>
                    <input type="datetime-local" name="fecha_hora" class="form-input-contact" style="margin-bottom: 0;" required>
                </div>
            </div>

            <div style="margin-bottom: 30px;">
                <label class="form-label-small">CUÉNTANOS SOBRE TU PROYECTO</label>
                <textarea name="descripcion" class="form-input-contact" rows="3" style="margin-bottom: 0;" required></textarea>
            </div>

        <button type="submit" id="btn-enviar-cita" class="btn-submit-contact">ENVIAR SOLICITUD</button>
        </form>
    </div>
</div>

<div id="google_translate_element" style="display:none;"></div>
<script type="text/javascript">
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({pageLanguage: 'es', autoDisplay: false}, 'google_translate_element');
    }
</script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

<script>
    function cambiarIdioma(idioma, event) {
        event.preventDefault();
        
        document.cookie = `googtrans=/es/${idioma}; path=/;`;
        document.cookie = `googtrans=/es/${idioma}; domain=${window.location.hostname}; path=/;`;

        window.location.reload();
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (document.cookie.includes('googtrans=/es/en')) {
            document.getElementById('btn-traducir').style.display = 'none';
            document.getElementById('btn-espanol').style.display = 'inline-block';
        }
    });

    const modalContacto = document.getElementById('modalContactoElegir');
    const btnMailto = document.getElementById('btnMailto');
    const gridOpciones = document.getElementById('opcionesContactoGrid');
    const formDirecto = document.getElementById('formMensajeDirecto');
    const inputDept = document.getElementById('inputDepartamento');

    const modalCita = document.getElementById('modalAgendarCita');

    function abrirModalContacto(correoDestino) {
        modalContacto.classList.add('active');
        btnMailto.href = "mailto:" + correoDestino;
        inputDept.value = correoDestino; 
    }

    function cerrarModalContacto() {
        modalContacto.classList.remove('active');
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

    function abrirModalCita() {
        modalCita.classList.add('active');
    }

    function cerrarModalCita() {
        modalCita.classList.remove('active');
    }

    window.onclick = function(event) {
        if (event.target == modalContacto) cerrarModalContacto();
        if (event.target == modalCita) cerrarModalCita();
    }
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const phoneInputField = document.querySelector("#telefonoCita");

        if (phoneInputField) {
            const phoneInput = window.intlTelInput(phoneInputField, {
                preferredCountries: ["mx", "us", "es"],
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
            });

            phoneInputField.addEventListener('keypress', function (e) {
                if (!/[0-9]/.test(e.key) && e.key !== 'Enter') {
                    e.preventDefault(); 
                    return; 
                }

                const cantidadNumeros = this.value.replace(/[^0-9]/g, '').length;

                if (cantidadNumeros >= 10 && /[0-9]/.test(e.key)) {
                    e.preventDefault();
                }
            });

            const formCita = phoneInputField.closest('form');
            formCita.addEventListener("submit", function(event) {
                if (this.checkValidity()) {
                    var boton = document.getElementById('btn-enviar-cita');

                    boton.disabled = true;
                    boton.innerText = 'ENVIANDO SOLICITUD...';

                    const numeroCompleto = phoneInput.getNumber();

                    if (numeroCompleto) {
                        phoneInputField.value = numeroCompleto;
                    }
                } else {
                    return false;
                }
            });
        }
    });
</script>
@include('Principal.cita')
@endsection