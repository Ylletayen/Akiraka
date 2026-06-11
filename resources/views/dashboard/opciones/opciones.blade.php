@extends('layouts.app')

@section('content')
@php
    $defaultQuienesSomos = 'Somos AKIRAKA, un estudio de arquitectura que encuentra su nombre y filosofía en el concepto japonés de 明か (akiraka), que significa claro, evidente y brillante. Creado por el arq. Akira Kameta, mexicano - japonés, que lleva su percepción de ambos mundos a una interpretación de solución de los proyectos.';
    $defaultValores = "- Colaboración y Empatía: Se establece una relación con el cliente y la comunidad, diseñando desde un entendimiento profundo de sus necesidades para lograr un éxito compartido.\n- Impacto Regenerativo: El enfoque supera la sostenibilidad convencional buscando la regeneración activa de los ecosistemas y el fortalecimiento del tejido social.\n- Materialidad Sostenible: La madera de origen responsable es la protagonista (\"materia viva\"), valorada por su estética, capacidad de secuestro de carbono y beneficios biológicos.\n- Simplicidad y Honestidad: Se apuesta por la claridad conceptual para transformar ideas complejas en soluciones ejecutables (ideales para la autoconstrucción) y una transparencia radical en cuanto a costos, plazos y origen de los materiales.";
@endphp

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>

<div class="dash-admin-view">
    <style>
        .dash-admin-view { min-height: 100vh; background-color: #ffffff; font-family: "Helvetica Neue", Arial, sans-serif; color: #111; padding: 20px; display: flex; justify-content: center; overflow-x: hidden; }
        .dashboard-container { display: flex; width: 100%; max-width: 1400px; gap: 20px; align-items: stretch; }
        .main-content { flex-grow: 1; background: #ffffff; padding: 40px 50px; border-radius: 12px; position: relative; }
        
        .options-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; }
        
        /* Animaciones base de Anime.js */
        .options-card { opacity: 0; background: #ffffff; border: 1px solid #eaeaea; border-radius: 8px; padding: 40px 30px; position: relative; box-shadow: 0 10px 40px rgba(0,0,0,0.03); transition: all 0.3s ease; margin-bottom: 30px; }
        .options-card::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 3px; background-color: #111; }
        .header-section { opacity: 0; } 
        
        .section-title-card { font-family: 'Garamond', serif; font-size: 1.6rem; margin-bottom: 25px; border-bottom: 1px solid #eee; padding-bottom: 10px; color: #111; }
        .subsection-title { font-family: 'Garamond', serif; font-size: 1.3rem; margin-bottom: 20px; color: #111; }
        .form-group { margin-bottom: 20px; position: relative; }
        .form-group label { display: block; font-size: 0.7rem; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px; color: #555; margin-bottom: 8px; }
        .form-control { width: 100%; padding: 12px 15px; background-color: #fafafa; border: 1px solid #eaeaea; border-radius: 4px; font-size: 0.95rem; font-family: inherit; }
        .form-control:focus { outline: none; border-color: #111; background-color: #fff; }
        .form-control::placeholder { color: #bbb; font-style: italic; }
        .btn-save { display: block; width: 100%; padding: 16px; background-color: #111; color: #fff; border: none; border-radius: 4px; font-weight: bold; text-transform: uppercase; letter-spacing: 2px; cursor: pointer; transition: all 0.3s; }
        .btn-save:hover { background-color: #333; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .profile-pic-wrapper { width: 120px; height: 120px; border-radius: 50%; margin-bottom: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); background-color: #fafafa; overflow: hidden; border: 1px solid #eaeaea; margin-left: auto; margin-right: auto; }
        .profile-pic { width: 100%; height: 100%; object-fit: cover; }
        .media-preview-box { width: 100%; height: 180px; border-radius: 4px; margin-bottom: 10px; border: 1px solid #eee; overflow: hidden; display: flex; align-items: center; justify-content: center; background: #f9f9f9; }
        .media-preview-box img, .media-preview-box video { width: 100%; height: 100%; object-fit: cover; }
        
        .roles-list-item { padding: 15px; border: 1px solid #f0f0f0; border-radius: 6px; margin-bottom: 15px; background: #fff; display: flex; gap: 20px; align-items: center; }
        .member-info { flex: 1; }
        .member-info strong { font-size: 0.9rem; color: #111; display: block; margin-bottom: 5px; }
        .role-input-group { flex: 2; display: flex; gap: 10px; }

        /* ESTILOS DEL MODAL */
        .custom-modal-overlay { display: none !important; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(5px); z-index: 9999; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.3s ease; }
        .custom-modal-overlay.active { display: flex !important; opacity: 1; }
        .custom-modal-box { background: #ffffff; border: 1px solid #eaeaea; border-radius: 8px; padding: 40px; width: 90%; max-width: 420px; text-align: center; box-shadow: 0 30px 60px rgba(0,0,0,0.2); }
        .custom-modal-icon { font-size: 2.5rem; color: #111; margin-bottom: 20px; }
        .custom-modal-title { font-size: 1.4rem; font-family: "Garamond", serif; margin-bottom: 15px; }
        .custom-modal-text { font-size: 0.95rem; color: #555; margin-bottom: 30px; }
        .custom-modal-actions { display: flex; gap: 15px; }
        .btn-modal { flex: 1; padding: 12px; border-radius: 4px; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; cursor: pointer; border: 1px solid transparent; }
        .btn-modal-cancel { background: #fafafa; color: #555; border-color: #ddd; }
        .btn-modal-confirm { background: #111; color: #fff; }

        /* BANDERITAS */
        .iti { width: 100%; display: block; }
        .iti__selected-flag { background-color: #fafafa; border-radius: 4px 0 0 4px; border-right: 1px solid #eaeaea; transition: background-color 0.3s; padding: 0 12px; }
        .iti__selected-flag:hover { background-color: #f0f0f0; }
        .iti input[type="tel"] { padding-left: 90px !important; }
        .iti__country-list { border-radius: 4px; border: 1px solid #eaeaea; box-shadow: 0 10px 30px rgba(0,0,0,0.08); font-family: "Helvetica Neue", Arial, sans-serif; font-size: 0.85rem; }
        .iti__flag { background-image: url("https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/img/flags.png"); }
        @media (min-resolution: 2x) { .iti__flag { background-image: url("https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/img/flags@2x.png"); } }

        /* =========================================================
           CLASES ESTRUCTURALES RESPONSIVAS (MAGIA PARA MÓVILES)
           ========================================================= */
        .profile-layout { display: flex; flex-wrap: wrap; gap: 40px; align-items: center; }
        .profile-sidebar { flex: 0 0 150px; text-align: center; }
        .profile-form-grid { flex: 1; display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .contact-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }

        @media (max-width: 992px) { 
            .options-grid { grid-template-columns: 1fr; } 
            /* Se empuja el contenido hacia abajo para que el botón hamburguesa del sidebar no estorbe */
            .main-content { padding: 80px 20px 40px 20px !important; }
        }

        @media (max-width: 768px) {
            .options-card { padding: 25px 20px; } /* Menos relleno lateral en tarjetas */
            .header-section h1 { font-size: 1.8rem !important; }
            
            /* Reordenar Perfil */
            .profile-layout { flex-direction: column; text-align: center; gap: 25px; }
            .profile-form-grid { grid-template-columns: 1fr; width: 100%; }
            .profile-sidebar { margin: 0 auto; }
            
            /* Reordenar Contacto */
            .contact-grid { grid-template-columns: 1fr; }
            .contact-grid .form-group { grid-column: 1 / -1 !important; grid-row: auto !important; }
            
            /* Reordenar Roles de Equipo */
            .roles-list-item { flex-direction: column; align-items: stretch; gap: 10px; }
            .member-info strong { margin-bottom: 0; }
        }
    </style>

    <div class="dashboard-container">
        @include('partials.sidebar')
        <main class="main-content">
            @include('partials.topbar')
            
            <div class="header-section" style="margin-top: 30px; border-bottom: 1px solid #f0f0f0; padding-bottom: 20px; margin-bottom: 40px;">
                <h1 style="font-size: 2.2rem; font-family: 'Garamond', serif;">Opciones del Sistema</h1>
                <p style="color: #888; font-style: italic;">Gestión de cuenta personal, contenido web y roles del equipo.</p>
                
                @if(session('success'))
                    <div style="background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; padding: 15px; border-radius: 6px; margin-top: 15px; font-size: 0.9rem;">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    </div>
                @endif
            </div>

            <div class="options-card">
                <h3 class="section-title-card">Mi Perfil ({{ Auth::user()->id_rol == 1 ? 'Superadmin' : (Auth::user()->id_rol == 2 ? 'Administrador' : 'Colaborador') }})</h3>
                <form id="form-perfil" action="{{ route('opciones.perfil.update') }}" method="POST" enctype="multipart/form-data" onsubmit="triggerCustomModal(event, this, '¿Guardar cambios en tu perfil?');">
                    @csrf @method('PUT')
                    
                    <!-- ESTRUCTURA RESPONSIVA DE PERFIL -->
                    <div class="profile-layout">
                        <div class="profile-sidebar">
                            <div class="profile-pic-wrapper"><img src="{{ Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : asset('images/default-avatar.png') }}" id="preview-foto" class="profile-pic"></div>
                            <label for="foto-upload" style="font-size: 0.7rem; cursor: pointer; color: #555; text-decoration: underline;">Cambiar foto</label>
                            <input type="file" id="foto-upload" name="foto" accept="image/*" style="display: none;" onchange="previewImage(event)">
                        </div>
                        
                        <div class="profile-form-grid">
                            <div class="form-group"><label>Nombre</label><input type="text" name="nombre" class="form-control" value="{{ Auth::user()->nombre }}" placeholder="Ej: Eduardo Pérez" required></div>
                            <div class="form-group"><label>Correo</label><input type="email" name="correo" class="form-control" value="{{ Auth::user()->correo }}" placeholder="ejemplo@estudioakiraka.com" required></div>
                            
                            <div class="form-group">
                                <label>Contraseña Actual</label>
                                <input type="password" class="form-control" value="••••••••" disabled title="Por seguridad del sistema, tu contraseña está encriptada.">
                            </div>
                            
                            <div class="form-group" style="position: relative;">
                                <label>Nueva Contraseña</label>
                                <input type="password" id="password_nueva" name="password_nueva" class="form-control" placeholder="Dejar vacío para no cambiar">
                                <i class="fas fa-eye" id="togglePasswordBtn" onclick="togglePassword()" style="position: absolute; right: 15px; top: 35px; cursor: pointer; color: #888; font-size: 1.1rem; transition: color 0.3s;"></i>
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; justify-content: flex-end; margin-top: 25px;">
                        <button type="submit" class="btn-save" style="max-width: 200px; padding: 12px;">Guardar Perfil</button>
                    </div>
                </form>
            </div>

            @if(in_array(Auth::user()->id_rol, [1, 2]))
            <form id="form-publicos" action="{{ route('opciones.publicos.update') }}" method="POST" enctype="multipart/form-data" onsubmit="triggerCustomModal(event, this, '¿Actualizar contenido y roles del equipo?');">
                @csrf @method('PUT')

                <div class="options-card">
    <h3 class="section-title-card">Información del Equipo</h3>
    <p style="font-size: 0.8rem; color: #888; margin-bottom: 20px;">
        Agrega, edita o elimina integrantes que aparecerán públicamente en la página de Información.
    </p>

    <div id="equipo-lista">
        @isset($equipo)
            @foreach($equipo as $index => $miembro)
                <div class="equipo-item" data-index="{{ $index }}" style="border: 1px solid #eee; border-radius: 8px; padding: 20px; margin-bottom: 20px; background: #fff;">
                    <input type="hidden" name="equipo_items[{{ $index }}][id_miembro]" value="{{ $miembro->id_miembro }}">
                    <input type="hidden" name="equipo_items[{{ $index }}][eliminar]" value="0" class="input-eliminar">

                    <div style="display: flex; justify-content: space-between; align-items: center; gap: 15px; margin-bottom: 15px;">
                        <h4 style="font-family: 'Garamond', serif; font-size: 1.2rem; margin: 0;">
                            Integrante {{ $index + 1 }}
                        </h4>

                        <button type="button" onclick="eliminarIntegrante(this)" style="background: #fff; color: #991b1b; border: 1px solid #fecaca; padding: 8px 12px; border-radius: 4px; cursor: pointer; font-size: 0.75rem; font-weight: bold; text-transform: uppercase;">
                            Eliminar
                        </button>
                    </div>

                    <div class="options-grid">
                        <div class="form-group">
                            <label>Nombre público</label>
                            <input 
                                type="text" 
                                name="equipo_items[{{ $index }}][nombre]" 
                                class="form-control" 
                                value="{{ $miembro->usuario->nombre ?? '' }}"
                                placeholder="Ej: Arq. Alberto Akira Kameta Miyamoto"
                            >
                        </div>

                        <div class="form-group">
                            <label>Rol / Puesto</label>
                            <input 
                                type="text" 
                                name="equipo_items[{{ $index }}][puesto]" 
                                class="form-control" 
                                value="{{ $miembro->puesto ?? '' }}"
                                placeholder="Ej: Dirección general"
                            >
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Biografía / Descripción</label>
                        <textarea 
                            name="equipo_items[{{ $index }}][biografia]" 
                            class="form-control" 
                            rows="4"
                            placeholder="Ej: Arquitecto mexicano japonés, egresado de..."
                        >{{ $miembro->biografia ?? '' }}</textarea>
                    </div>
                </div>
            @endforeach
        @endisset
    </div>

    <button type="button" onclick="agregarIntegrante()" style="width: 100%; padding: 14px; background: #fff; color: #111; border: 1px dashed #999; border-radius: 6px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; cursor: pointer;">
        + Añadir integrante
    </button>
</div>

                <div class="options-grid">
                    <div class="options-card">
                        <h3 class="subsection-title">Textos del Estudio</h3>
                        <div class="form-group">
                            <label>¿Quiénes Somos?</label>
                            <textarea name="quienes_somos_texto" class="form-control" rows="6" placeholder="Escribe una breve introducción sobre la historia y visión de Estudio Akiraka...">{{ $configuracion->quienes_somos_texto ?? $defaultQuienesSomos }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Valores</label>
                            <textarea name="valores_texto" class="form-control" rows="10" placeholder="Ej:&#10;- Colaboración y Empatía...&#10;- Sostenibilidad y Regeneración...">{{ $configuracion->valores_texto ?? $defaultValores }}</textarea>
                        </div>
                    </div>

                    <div class="options-card">
                        <h3 class="subsection-title">Multimedia & Redes</h3>
                        <div class="form-group">
                            <label>Portada (Imagen o Video)</label>
                            <div class="media-preview-box" id="media-preview-container">
                                @php $esVideo = $configuracion->landing_hero_image && preg_match('/\.(mp4|webm)$/i', $configuracion->landing_hero_image); @endphp
                                @if($esVideo)<video src="{{ asset('storage/'.$configuracion->landing_hero_image) }}" autoplay loop muted playsinline></video>
                                @else<img src="{{ $configuracion->landing_hero_image ? asset('storage/'.$configuracion->landing_hero_image) : 'https://via.placeholder.com/1920x1080' }}">@endif
                            </div>
                            <input type="file" name="landing_hero_image" class="form-control" accept="image/*,video/mp4" onchange="previewMedia(this)">
                        </div>

                        <div class="form-group">
                            <label>Instagram</label>
                            <input type="url" name="instagram" class="form-control" value="{{ $configuracion->instagram }}" pattern="https://.*" title="El enlace debe comenzar con https://" placeholder="https://www.instagram.com/estudioakiraka">
                        </div>
                        <div class="form-group">
                            <label>Facebook</label>
                            <input type="url" name="facebook" class="form-control" value="{{ $configuracion->facebook }}" pattern="https://.*" title="El enlace debe comenzar con https://" placeholder="https://www.facebook.com/estudioakiraka">
                        </div>
                    </div>
                </div>

                <div class="options-card">
                    <h3 class="subsection-title">Contacto & Ubicación</h3>
                    
                    <!-- ESTRUCTURA RESPONSIVA DE CONTACTO -->
                    <div class="contact-grid">
                        <div class="form-group">
                            <label>Teléfono (WhatsApp)</label>
                            <input type="tel" id="telefono_visible" class="form-control" placeholder="722 123 4567">
                            <input type="hidden" name="telefono" id="telefono_hidden" value="{{ $configuracion->telefono }}">
                        </div>
                        
                        <div class="form-group" style="grid-row: span 2;">
                            <label>Dirección</label>
                            <textarea name="direccion" class="form-control" rows="5" placeholder="Ej: Calle Principal 123, Colonia Centro, CP 51200, Valle de Bravo, Méx.">{{ $configuracion->direccion }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Correo Principal</label>
                            <input type="email" name="correo_contacto" class="form-control" value="{{ $configuracion->correo_contacto }}" placeholder="contacto@estudioakiraka.com">
                        </div>

                        <div class="form-group">
                            <label>Correo Prensa</label>
                            <input type="email" name="correo_prensa" class="form-control" value="{{ $configuracion->correo_prensa }}" placeholder="prensa@estudioakiraka.com">
                        </div>

                        <div class="form-group">
                            <label>Correo Laboral 1</label>
                            <input type="email" name="correo_laboral_1" class="form-control" value="{{ $configuracion->correo_laboral_1 }}" placeholder="rh@estudioakiraka.com">
                        </div>

                        <div class="form-group" style="grid-column: 1 / -1;">
                            <label>Correo Laboral 2</label>
                            <input type="email" name="correo_laboral_2" class="form-control" value="{{ $configuracion->correo_laboral_2 }}" placeholder="talento@estudioakiraka.com">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-save">Guardar Todos los Cambios de la Web</button>
            </form>
            @endif
        </main>
    </div>

    <div id="custom-confirm-modal" class="custom-modal-overlay">
        <div class="custom-modal-box">
            <div class="custom-modal-icon"><i class="fas fa-exclamation-circle"></i></div>
            <h3 class="custom-modal-title">Confirmar</h3>
            <p id="custom-modal-message" class="custom-modal-text"></p>
            <div class="custom-modal-actions">
                <button type="button" class="btn-modal btn-modal-cancel" onclick="closeCustomModal()">Cancelar</button>
                <button type="button" class="btn-modal btn-modal-confirm" id="btn-modal-accept">Aceptar</button>
            </div>
        </div>
    </div>
</div>

<script>



    // =================================================================
    // ANIMACIONES CON ANIME.JS
    // =================================================================
    document.addEventListener("DOMContentLoaded", function() {
        anime({
            targets: '.header-section',
            translateY: [30, 0],
            opacity: [0, 1],
            easing: 'easeOutCubic',
            duration: 800,
            delay: 100
        });

        anime({
            targets: '.options-card',
            translateY: [40, 0],
            opacity: [0, 1],
            easing: 'easeOutExpo',
            duration: 800,
            delay: anime.stagger(150, {start: 200}) 
        });
    });

    // =================================================================
    // FUNCIONES ORIGINALES DEL FORMULARIO
    // =================================================================
    function previewImage(e) { var r = new FileReader(); r.onload = function(){ document.getElementById('preview-foto').src = r.result; }; if(e.target.files[0]) r.readAsDataURL(e.target.files[0]); }
    function previewMedia(i) { var c = document.getElementById('media-preview-container'); var f = i.files[0]; if(f) { var u = URL.createObjectURL(f); if(f.type.startsWith('video/')) { c.innerHTML = `<video src="${u}" autoplay loop muted playsinline style="width:100%; height:100%; object-fit:cover;"></video>`; } else { c.innerHTML = `<img src="${u}" style="width:100%; height:100%; object-fit:cover;">`; } } }
    
    function togglePassword() {
        const input = document.getElementById("password_nueva");
        const icon = document.getElementById("togglePasswordBtn");
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            input.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    }

    let fTS = null;
    function triggerCustomModal(e, f, m) { e.preventDefault(); fTS = f; document.getElementById('custom-modal-message').innerText = m; document.getElementById('custom-confirm-modal').classList.add('active'); }
    function closeCustomModal() { document.getElementById('custom-confirm-modal').classList.remove('active'); }
    
    // =================================================================
    // LÓGICA DE LAS BANDERITAS (Intl-Tel-Input)
    // =================================================================
    const inputTelVisible = document.querySelector("#telefono_visible");
    const inputTelHidden = document.querySelector("#telefono_hidden");
    let iti = null;

    if (inputTelVisible) {
        iti = window.intlTelInput(inputTelVisible, {
            initialCountry: "mx",
            preferredCountries: ["mx", "us", "es", "co", "ar"],
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js",
            separateDialCode: true, 
        });

        if (inputTelHidden.value) {
            iti.setNumber(inputTelHidden.value);
        }
    }
    

    let contadorIntegrantes = document.querySelectorAll('.equipo-item').length;

function agregarIntegrante() {
    const lista = document.getElementById('equipo-lista');
    const index = contadorIntegrantes++;

    const div = document.createElement('div');
    div.className = 'equipo-item';
    div.dataset.index = index;
    div.style.border = '1px solid #eee';
    div.style.borderRadius = '8px';
    div.style.padding = '20px';
    div.style.marginBottom = '20px';
    div.style.background = '#fff';

    div.innerHTML = `
        <input type="hidden" name="equipo_items[${index}][id_miembro]" value="">
        <input type="hidden" name="equipo_items[${index}][eliminar]" value="0" class="input-eliminar">

        <div style="display: flex; justify-content: space-between; align-items: center; gap: 15px; margin-bottom: 15px;">
            <h4 style="font-family: 'Garamond', serif; font-size: 1.2rem; margin: 0;">
                Nuevo integrante
            </h4>

            <button type="button" onclick="eliminarIntegrante(this)" style="background: #fff; color: #991b1b; border: 1px solid #fecaca; padding: 8px 12px; border-radius: 4px; cursor: pointer; font-size: 0.75rem; font-weight: bold; text-transform: uppercase;">
                Eliminar
            </button>
        </div>

        <div class="options-grid">
            <div class="form-group">
                <label>Nombre público</label>
                <input 
                    type="text" 
                    name="equipo_items[${index}][nombre]" 
                    class="form-control" 
                    placeholder="Ej: Arq. Alberto Akira Kameta Miyamoto"
                >
            </div>

            <div class="form-group">
                <label>Rol / Puesto</label>
                <input 
                    type="text" 
                    name="equipo_items[${index}][puesto]" 
                    class="form-control" 
                    placeholder="Ej: Dirección general"
                >
            </div>
        </div>

        <div class="form-group">
            <label>Biografía / Descripción</label>
            <textarea 
                name="equipo_items[${index}][biografia]" 
                class="form-control" 
                rows="4"
                placeholder="Ej: Arquitecto mexicano japonés, egresado de..."
            ></textarea>
        </div>
    `;

    lista.appendChild(div);

    if (typeof anime !== 'undefined') {
        anime({
            targets: div,
            opacity: [0, 1],
            translateY: [20, 0],
            easing: 'easeOutCubic',
            duration: 500
        });
    }
}

function eliminarIntegrante(button) {
    const item = button.closest('.equipo-item');
    const inputEliminar = item.querySelector('.input-eliminar');
    const idInput = item.querySelector('input[name*="[id_miembro]"]');

    if (idInput && idInput.value) {
        inputEliminar.value = '1';
        item.style.display = 'none';
    } else {
        item.remove();
    }
}
    document.getElementById('btn-modal-accept').onclick = function() 
    { 
        if(fTS) {
            if(fTS.id === 'form-publicos' && iti) {
                inputTelHidden.value = iti.getNumber();
            }
            fTS.submit(); 
        }
    };
</script>
@endsection