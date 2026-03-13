@extends('layouts.app')

@section('content')
<!-- Precargamos los textos por defecto por si la base de datos está vacía -->
@php
    $defaultQuienesSomos = 'Somos AKIRAKA, un estudio de arquitectura que encuentra su nombre y filosofía en el concepto japonés de 明か (akiraka), que significa claro, evidente y brillante. Creado por el arq. Akira Kameta, mexicano - japonés, que lleva su percepción de ambos mundos a una interpretación de solución de los proyectos.';
    
    $defaultValores = "- Colaboración y Empatía: Se establece una relación con el cliente y la comunidad, diseñando desde un entendimiento profundo de sus necesidades para lograr un éxito compartido.\n- Impacto Regenerativo: El enfoque supera la sostenibilidad convencional buscando la regeneración activa de los ecosistemas y el fortalecimiento del tejido social.\n- Materialidad Sostenible: La madera de origen responsable es la protagonista (\"materia viva\"), valorada por su estética, capacidad de secuestro de carbono y beneficios biológicos.\n- Simplicidad y Honestidad: Se apuesta por la claridad conceptual para transformar ideas complejas en soluciones ejecutables (ideales para la autoconstrucción) y una transparencia radical en cuanto a costos, plazos y origen de los materiales.";
@endphp

<div class="dash-admin-view">
    <style>
        .dash-admin-view { min-height: 100vh; background-color: #ffffff; font-family: "Helvetica Neue", Arial, sans-serif; color: #111; padding: 20px; display: flex; justify-content: center; }
        .dashboard-container { display: flex; width: 100%; max-width: 1400px; gap: 20px; align-items: stretch; }
        .main-content { flex-grow: 1; background: #ffffff; padding: 40px 50px; border-radius: 12px; position: relative; }
        
        /* Sistema de Cuadrículas Moderno */
        .options-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; }
        @media (max-width: 992px) { .options-grid { grid-template-columns: 1fr; } }
        
        .options-card { background: #ffffff; border: 1px solid #eaeaea; border-radius: 8px; padding: 40px 30px; position: relative; box-shadow: 0 10px 40px rgba(0,0,0,0.03); transition: all 0.3s ease; }
        .options-card::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 3px; background-color: #111; }
        
        .section-title-card { font-family: 'Garamond', serif; font-size: 1.6rem; margin-bottom: 25px; border-bottom: 1px solid #eee; padding-bottom: 10px; color: #111; }
        .subsection-title { font-family: 'Garamond', serif; font-size: 1.3rem; margin-bottom: 20px; color: #111; }
        
        .form-group { margin-bottom: 20px; position: relative; }
        .form-group label { display: block; font-size: 0.7rem; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px; color: #555; margin-bottom: 8px; }
        .form-control { width: 100%; padding: 12px 15px; background-color: #fafafa; border: 1px solid #eaeaea; border-radius: 4px; font-size: 0.95rem; font-family: inherit; }
        .form-control:focus { outline: none; border-color: #111; background-color: #fff; }
        
        .btn-save { display: block; width: 100%; padding: 16px; background-color: #111; color: #fff; border: none; border-radius: 4px; font-weight: bold; text-transform: uppercase; letter-spacing: 2px; cursor: pointer; transition: all 0.3s; }
        .btn-save:hover { background-color: #333; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        
        /* Estilos de Foto de Perfil */
        .profile-pic-container { display: flex; flex-direction: column; align-items: center; text-align: center; }
        .profile-pic-wrapper { width: 120px; height: 120px; border-radius: 50%; margin-bottom: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); background-color: #fafafa; overflow: hidden; border: 1px solid #eaeaea; }
        .profile-pic { width: 100%; height: 100%; object-fit: cover; }
        .btn-change-photo { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; color: #555; background: #fafafa; border: 1px solid #ddd; padding: 6px 15px; border-radius: 30px; cursor: pointer; transition: all 0.3s ease; }
        .btn-change-photo:hover { background: #111; color: #fff; border-color: #111; }
        
        /* Estilos de Multimedia (Landing) */
        .media-preview-box { width: 100%; height: 180px; border-radius: 4px; margin-bottom: 10px; border: 1px solid #eee; overflow: hidden; display: flex; align-items: center; justify-content: center; background: #f9f9f9; }
        .media-preview-box img, .media-preview-box video { width: 100%; height: 100%; object-fit: cover; }

        /* Estilos del modal nativo */
        .custom-modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(5px); z-index: 9999; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.3s ease; }
        .custom-modal-overlay.active { display: flex; opacity: 1; }
        .custom-modal-box { background: #ffffff; border: 1px solid #eaeaea; border-radius: 8px; padding: 40px; width: 90%; max-width: 420px; text-align: center; box-shadow: 0 30px 60px rgba(0,0,0,0.08); }
        .custom-modal-icon { font-size: 2.5rem; color: #111; margin-bottom: 20px; }
        .custom-modal-title { font-size: 1.4rem; font-family: "Garamond", serif; margin-bottom: 15px; }
        .custom-modal-text { font-size: 0.95rem; color: #555; margin-bottom: 30px; }
        .custom-modal-actions { display: flex; gap: 15px; }
        .btn-modal { flex: 1; padding: 12px; border-radius: 4px; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; cursor: pointer; }
        .btn-modal-cancel { background: #fafafa; color: #555; border: 1px solid #ddd; }
        .btn-modal-confirm { background: #111; color: #fff; border: 1px solid #111; }
    </style>

    <div class="dashboard-container">
        @include('partials.sidebar')
        <main class="main-content">
            @include('partials.topbar')
            
            <div class="header-section" style="margin-top: 30px; border-bottom: 1px solid #f0f0f0; padding-bottom: 20px; margin-bottom: 40px;">
                <h1 style="font-size: 2.2rem; font-family: 'Garamond', serif;">Opciones del Sistema</h1>
                <p style="color: #888; font-style: italic;">Gestión de cuenta personal y configuración global del sitio web.</p>
                
                @if(session('success'))
                    <div style="background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; padding: 15px; border-radius: 6px; margin-top: 15px; font-size: 0.9rem;">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    </div>
                @endif
            </div>

            <!-- ==========================================
                 SECCIÓN 1: PERFIL DE ADMINISTRADOR (RESTAURADO)
                 ========================================== -->
            <div class="options-card mb-5">
                <h3 class="section-title-card">Mi Perfil (Administrador)</h3>
                <form id="form-perfil" action="{{ route('opciones.perfil.update') }}" method="POST" enctype="multipart/form-data" onsubmit="triggerCustomModal(event, this, '¿Guardar cambios en tu perfil? Las nuevas credenciales aplicarán en tu próximo inicio de sesión.');">
                    @csrf @method('PUT')
                    
                    <div style="display: flex; flex-wrap: wrap; gap: 40px; align-items: center;">
                        <!-- Avatar Izquierda -->
                        <div class="profile-pic-container" style="flex: 0 0 150px;">
                            <div class="profile-pic-wrapper">
                                <img src="{{ Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : asset('images/default-avatar.png') }}" id="preview-foto" class="profile-pic" alt="Foto">
                            </div>
                            <label for="foto-upload" class="btn-change-photo"><i class="fas fa-camera me-1"></i> Cambiar</label>
                            <input type="file" id="foto-upload" name="foto" accept="image/*" style="display: none;" onchange="previewImage(event)">
                        </div>
                        
                        <!-- Campos Derecha -->
                        <div style="flex: 1; display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div class="form-group">
                                <label>Nombre Mostrado</label>
                                <input type="text" name="nombre" class="form-control" value="{{ Auth::user()->nombre ?? '' }}" required>
                            </div>
                            <div class="form-group">
                                <label>Correo de Acceso</label>
                                <input type="email" name="correo" class="form-control" value="{{ Auth::user()->correo ?? '' }}" required>
                            </div>
                            <div class="form-group" style="grid-column: 1 / -1;">
                                <label>Nueva Contraseña (Dejar en blanco para conservar actual)</label>
                                <div style="position: relative;">
                                    <input type="password" id="password_nueva" name="password_nueva" class="form-control" placeholder="Escribe aquí para cambiarla...">
                                    <i class="fas fa-eye" style="position: absolute; right: 15px; top: 15px; cursor: pointer; color: #888;" onclick="togglePasswordVisibility('password_nueva', this)"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div style="display: flex; justify-content: flex-end; margin-top: 20px;">
                        <button type="submit" class="btn-save" style="max-width: 250px;">Guardar Mi Perfil</button>
                    </div>
                </form>
            </div>


            <!-- ==========================================
                 SECCIÓN 2: CONTENIDO PÚBLICO (TODO UNIDO)
                 ========================================== -->
            <form action="{{ route('opciones.publicos.update') }}" method="POST" enctype="multipart/form-data" onsubmit="triggerCustomModal(event, this, '¿Estás seguro de actualizar el contenido de la web? Los cambios serán visibles inmediatamente para los clientes.');">
                @csrf @method('PUT')
                
                <div class="options-grid">
                    <!-- TARJETA A: Textos -->
                    <div class="options-card">
                        <h3 class="subsection-title">Textos de "Quiénes Somos"</h3>
                        <div class="form-group">
                            <label>Historia del Estudio</label>
                            <textarea name="quienes_somos_texto" class="form-control" rows="8">{{ $configuracion->quienes_somos_texto ?? $defaultQuienesSomos }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Valores de la Empresa</label>
                            <textarea name="valores_texto" class="form-control" rows="12">{{ $configuracion->valores_texto ?? $defaultValores }}</textarea>
                        </div>
                    </div>

                    <!-- TARJETA B: Multimedia y Redes -->
                    <div class="options-card">
                        <h3 class="subsection-title">Landing & Redes Sociales</h3>
                        
                        <div class="form-group">
                            <label>Fondo de Portada (Imagen o Video MP4)</label>
                            <div class="media-preview-box" id="media-preview-container">
                                @php
                                    $archivoBd = $configuracion->landing_hero_image;
                                    $esVideoBd = $archivoBd && preg_match('/\.(mp4|webm)$/i', $archivoBd);
                                    $rutaAbsoluta = $archivoBd ? asset('storage/'.$archivoBd) : 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?q=80&w=1920';
                                @endphp
                                @if($esVideoBd)
                                    <video src="{{ $rutaAbsoluta }}" autoplay loop muted playsinline></video>
                                @else
                                    <img src="{{ $rutaAbsoluta }}" id="hero-img-preview">
                                @endif
                            </div>
                            <input type="file" name="landing_hero_image" class="form-control" accept="image/*,video/mp4,video/webm" onchange="previewMedia(this)">
                            <small style="color: #999; font-size: 0.65rem;">Soporta JPG, PNG, GIF y MP4 (Máx 15MB).</small>
                        </div>

                        <div class="form-group">
                            <label>Enlace Instagram</label>
                            <input type="url" name="instagram" class="form-control" value="{{ $configuracion->instagram }}">
                        </div>
                        <div class="form-group">
                            <label>Enlace Facebook</label>
                            <input type="url" name="facebook" class="form-control" value="{{ $configuracion->facebook }}">
                        </div>
                    </div>
                </div>

                <!-- TARJETA C: Toda la información de Contacto -->
                <div class="options-card mt-4 mb-4">
                    <h3 class="subsection-title">Información de Contacto y Ubicación</h3>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label>Teléfono (WhatsApp)</label>
                            <input type="text" name="telefono" class="form-control" value="{{ $configuracion->telefono }}">
                        </div>
                        
                        <div class="form-group" style="grid-row: span 2;">
                            <label>Dirección Física</label>
                            <textarea name="direccion" class="form-control" rows="5">{{ $configuracion->direccion }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Correo Principal (Proyectos y Eventos)</label>
                            <input type="email" name="correo_contacto" class="form-control" value="{{ $configuracion->correo_contacto }}">
                        </div>

                        <div class="form-group">
                            <label>Correo Prensa</label>
                            <input type="email" name="correo_prensa" class="form-control" value="{{ $configuracion->correo_prensa }}">
                        </div>

                        <div class="form-group">
                            <label>Correo Laboral 1</label>
                            <input type="email" name="correo_laboral_1" class="form-control" value="{{ $configuracion->correo_laboral_1 }}">
                        </div>

                        <div class="form-group" style="grid-column: 1 / -1;">
                            <label>Correo Laboral 2</label>
                            <input type="email" name="correo_laboral_2" class="form-control" value="{{ $configuracion->correo_laboral_2 }}">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-save" style="background-color: #000;">Guardar Cambios de la Web</button>
            </form>

        </main>
    </div>
</div>

<!-- ================= ESTRUCTURA HTML DEL MODAL ================= -->
<div id="custom-confirm-modal" class="custom-modal-overlay">
    <div class="custom-modal-box">
        <div class="custom-modal-icon"><i class="fas fa-exclamation-circle"></i></div>
        <h3 class="custom-modal-title">Confirmar</h3>
        <p id="custom-modal-message" class="custom-modal-text">¿Estás seguro de que deseas guardar los cambios?</p>
        <div class="custom-modal-actions">
            <button type="button" class="btn-modal btn-modal-cancel" onclick="closeCustomModal()">Cancelar</button>
            <button type="button" class="btn-modal btn-modal-confirm" id="btn-modal-accept">Aceptar</button>
        </div>
    </div>
</div>

<script>
    // Preview para la foto de Perfil
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function(){ document.getElementById('preview-foto').src = reader.result; };
        if(event.target.files[0]) reader.readAsDataURL(event.target.files[0]);
    }

    // Preview Inteligente para Multimedia (Imagen o Video)
    function previewMedia(input) {
        const container = document.getElementById('media-preview-container');
        const file = input.files[0];
        if(file) {
            const fileURL = URL.createObjectURL(file);
            if(file.type.startsWith('video/')) {
                container.innerHTML = `<video src="${fileURL}" autoplay loop muted playsinline style="width:100%; height:100%; object-fit:cover;"></video>`;
            } else {
                container.innerHTML = `<img src="${fileURL}" style="width:100%; height:100%; object-fit:cover;">`;
            }
        }
    }

    // Mostrar/Ocultar contraseña
    function togglePasswordVisibility(inputId, icon) {
        var input = document.getElementById(inputId);
        if (input.type === "password") {
            input.type = "text";
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = "password";
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }

    // Modal de confirmación
    let formToSubmit = null;
    const modalOverlay = document.getElementById('custom-confirm-modal');
    const modalMessage = document.getElementById('custom-modal-message');
    const btnAccept = document.getElementById('btn-modal-accept');

    function triggerCustomModal(event, formElement, messageText) {
        event.preventDefault(); 
        formToSubmit = formElement; 
        modalMessage.innerText = messageText; 
        modalOverlay.classList.add('active');
    }

    function closeCustomModal() {
        modalOverlay.classList.remove('active');
        formToSubmit = null; 
    }

    btnAccept.addEventListener('click', function() {
        if (formToSubmit) formToSubmit.submit(); 
    });
</script>
@endsection