@extends('layouts.app')

@section('content')
@php
    $config = \App\Models\Configuracion::first();
    // Limpiamos el teléfono para el link de WhatsApp
    $whatsappPhone = preg_replace('/[^0-9]/', '', $config->telefono ?? '527221655901');
@endphp

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" />

<style>
    /* ================= ESTILOS BASE DEL ESTUDIO ================= */
    .akira-container {
        max-width: 1100px; 
        margin: 0 auto;
        padding: 50px 20px;
        font-family: "Georgia", "Times New Roman", serif;
        color: #1a1a1a;
    }

    .site-header-main { margin-bottom: 60px; }

    .contact-label { font-weight: bold; display: block; margin-bottom: 5px; font-size: 1rem; color: #1a1a1a; }
    .contact-value-reset { text-decoration: none; color: #666; font-size: 0.95rem; transition: color 0.3s; line-height: 1.6; cursor: pointer; display: block; }
    .contact-value-reset:hover { color: #000; text-decoration: underline; }
    .contact-instruction { font-style: italic; font-size: 0.95rem; margin-bottom: 40px; color: #666; text-align: center; }
    .location-year-label { color: #ccc; font-size: 0.85rem; margin-right: 15px; display: inline-block; width: 60px; }

    /* ================= BOTÓN PRINCIPAL DE CITAS ================= */
    .btn-agendar-cita {
        display: inline-block;
        background: #111; color: #fff; border: none;
        padding: 15px 40px; font-family: Arial, sans-serif; font-size: 0.85rem;
        letter-spacing: 2px; text-transform: uppercase; cursor: pointer;
        transition: background 0.3s ease, transform 0.2s ease;
    }
    .btn-agendar-cita:hover { background: #333; transform: translateY(-2px); }

    /* ================= SECCIÓN DEL MAPA ================= */
    .map-section { margin-top: 60px; border-top: 1px solid #eee; padding-top: 40px; }
    .map-wrapper { width: 100%; height: 500px; filter: grayscale(100%); border: 1px solid #111; transition: filter 0.5s ease; margin-bottom: 20px; }
    .map-wrapper:hover { filter: grayscale(0%); }

    /* ================= REDES SOCIALES ================= */
    .social-group-section { margin-top: 50px; border-top: 1px solid #eee; padding-top: 30px; text-align: center; }
    .social-btn-circle { display: inline-flex; align-items: center; justify-content: center; width: 45px; height: 45px; border-radius: 50%; border: 1px solid #1a1a1a; color: #1a1a1a; text-decoration: none; font-size: 1.2rem; margin: 0 8px; transition: all 0.3s ease; }
    .social-btn-circle:hover { background-color: #1a1a1a; color: #fff; }

    /* ================= ESTILOS DE LOS MODALES ================= */
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

    /* --- ESTILOS PARA EL BOTÓN DE REGRESAR FLOTANTE --- */
    .btn-flotante-regresar {
        position: fixed;
        bottom: clamp(20px, 4vh, 40px);
        left: clamp(30px, 5vw, 60px);
        font-weight: bold;
        font-size: 0.95rem;
        color: #111111 !important;
        text-decoration: underline !important;
        z-index: 9999;
        background-color: rgba(253, 253, 253, 0.85);
        backdrop-filter: blur(5px);
        padding: 8px 15px 8px 0;
        border-radius: 4px;
        transition: all 0.3s ease;
        font-family: "Garamond", "Baskerville", "Times New Roman", serif;
    }

    .btn-flotante-regresar:hover {
        color: #8c8c8c !important;
        transform: translateX(-5px);
    }
    
    /* Regla para que la librería respete el ancho de tu diseño */
    .iti { width: 100%; }

    /* FOOTER ESTÉTICO PARA LOS BOTONES DE IDIOMA */
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

<a href="{{ route('landing') }}" class="btn-flotante-regresar">← regresar</a>

<div class="akira-container">
    <header class="site-header-main">
        <a href="{{ route('project.detail') }}" class="notranslate" style="text-decoration: none; color: inherit;">Estudio Akiraka, </a>
        <a href="{{ route('info') }}" style="text-decoration: none; color: inherit;">Info</a>, 
        <a href="{{ route('contacto') }}" style="text-decoration: none; color: #1a1a1a; font-weight: bold;">Contacto</a>.
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

    <div class="location-group border-top pt-4 mb-5">
        <div class="row">
            <div class="col-md-6 mb-3">
                <span class="location-year-label">Teléfono</span>
                <span class="contact-value-reset notranslate"><br>{{ $config->telefono ?? '+52 722 165 5901' }}</span>
            </div>
            <div class="col-md-6 mb-3">
                <span class="location-year-label">Dirección</span>
                <span class="contact-value-reset"><br>{!! nl2br(e($config->direccion ?? "Parque Santa María 10,\nValle de Bravo, Méx.")) !!}</span>
            </div>
        </div>
    </div>

    <div class="map-section">
        <span class="contact-label mb-3" style="text-transform: uppercase; font-size: 0.75rem; letter-spacing: 2px;">Ubicación / Espacio Odisea</span>
        <div class="map-wrapper">
            <iframe width="100%" height="100%" frameborder="0" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d467.4589255627712!2d-100.1363651761623!3d19.189445173707252!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x85cd647e9a15bc7b%3A0x59426b73189edf87!2sEspacio%20Odisea!5e0!3m2!1ses-419!2smx!4v1709673000000!5m2!1ses-419!2smx" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
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

        <form action="{{ route('api.citas.store') }}" method="POST">
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
                            <option value="{{ $servicio->id }}">{{ $servicio->nombre }}</option>
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

            <button type="submit" class="btn-submit-contact">ENVIAR SOLICITUD</button>
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
    // =========================================================
    // SISTEMA DE TRADUCCIÓN (GOOGLE TRANSLATE HACK)
    // =========================================================
    function cambiarIdioma(idioma, event) {
        event.preventDefault();
        
        // Forzamos la cookie de traducción de Google
        document.cookie = `googtrans=/es/${idioma}; path=/;`;
        document.cookie = `googtrans=/es/${idioma}; domain=${window.location.hostname}; path=/;`;
        
        // Recargamos la página para aplicar
        window.location.reload();
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Alternamos botones si ya está en inglés
        if (document.cookie.includes('googtrans=/es/en')) {
            document.getElementById('btn-traducir').style.display = 'none';
            document.getElementById('btn-espanol').style.display = 'inline-block';
        }
    });

    // Variables Modal 1 (Contacto Correos)
    const modalContacto = document.getElementById('modalContactoElegir');
    const btnMailto = document.getElementById('btnMailto');
    const gridOpciones = document.getElementById('opcionesContactoGrid');
    const formDirecto = document.getElementById('formMensajeDirecto');
    const inputDept = document.getElementById('inputDepartamento');

    // Variables Modal 2 (Citas)
    const modalCita = document.getElementById('modalAgendarCita');

    // Funciones Modal Contacto
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

    // Funciones Modal Citas
    function abrirModalCita() {
        modalCita.classList.add('active');
    }

    function cerrarModalCita() {
        modalCita.classList.remove('active');
    }

    // Cerrar modales si se hace clic afuera del cuadro blanco
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
            // 1. Inicializar la librería con la bandera de México por defecto
            const phoneInput = window.intlTelInput(phoneInputField, {
                preferredCountries: ["mx", "us", "es"],
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
            });

            // 2. Interceptar la tecla antes de que se escriba en el input (Bloqueo y Límite)
            phoneInputField.addEventListener('keypress', function (e) {
                // Si intenta teclear algo que NO sea número (y no es Enter), lo bloqueamos
                if (!/[0-9]/.test(e.key) && e.key !== 'Enter') {
                    e.preventDefault(); 
                    return; 
                }

                // Contamos cuántos NÚMEROS hay actualmente (ignorando los espacios de la bandera)
                const cantidadNumeros = this.value.replace(/[^0-9]/g, '').length;

                // Si ya hay 10 números, bloqueamos cualquier intento de escribir el 11vo
                if (cantidadNumeros >= 10 && /[0-9]/.test(e.key)) {
                    e.preventDefault();
                }
            });

            // 3. Interceptar el envío del formulario para mandar el número limpio a Laravel
            const formCita = phoneInputField.closest('form');
            formCita.addEventListener("submit", function(event) {
                // Obtenemos el número completo (ej. +527221234567)
                const numeroCompleto = phoneInput.getNumber();
                
                // Actualizamos el valor del input justo antes de que se envíe a la base de datos
                if (numeroCompleto) {
                    phoneInputField.value = numeroCompleto;
                }
            });
        }
    });
</script>
@include('Principal.cita')
@endsection