@extends('layouts.app')

@section('content')
@php
    $defaultQuienesSomos = 'Somos AKIRAKA, un estudio de arquitectura que encuentra su nombre y filosofía en el concepto japonés de 明か (akiraka), que significa claro, evidente y brillante. Creado por el arq. Akira Kameta, mexicano - japonés, que lleva su percepción de ambos mundos a una interpretación de solución de los proyectos.';
    $defaultValores = "- Colaboración y Empatía: Se establece una relación con el cliente y la comunidad, diseñando desde un entendimiento profundo de sus necesidades para lograr un éxito compartido.\n- Impacto Regenerativo: El enfoque supera la sostenibilidad convencional buscando la regeneración activa de los ecosistemas y el fortalecimiento del tejido social.\n- Materialidad Sostenible: La madera de origen responsable es la protagonista (\"materia viva\"), valorada por su estética, capacidad de secuestro de carbono y beneficios biológicos.\n- Simplicidad y Honestidad: Se apuesta por la claridad conceptual para transformar ideas complejas en soluciones ejecutables (ideales para la autoconstrucción) y una transparencia radical en cuanto a costos, plazos y origen de los materiales.";
@endphp

<div class="dash-admin-view">
    <style>
        .dash-admin-view { min-height: 100vh; background-color: #ffffff; font-family: "Helvetica Neue", Arial, sans-serif; color: #111; padding: 20px; display: flex; justify-content: center; }
        .dashboard-container { display: flex; width: 100%; max-width: 1400px; gap: 20px; align-items: stretch; }
        .main-content { flex-grow: 1; background: #ffffff; padding: 40px 50px; border-radius: 12px; position: relative; }
        .options-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; }
        @media (max-width: 992px) { .options-grid { grid-template-columns: 1fr; } }
        .options-card { background: #ffffff; border: 1px solid #eaeaea; border-radius: 8px; padding: 40px 30px; position: relative; box-shadow: 0 10px 40px rgba(0,0,0,0.03); transition: all 0.3s ease; margin-bottom: 30px; }
        .options-card::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 3px; background-color: #111; }
        .section-title-card { font-family: 'Garamond', serif; font-size: 1.6rem; margin-bottom: 25px; border-bottom: 1px solid #eee; padding-bottom: 10px; color: #111; }
        .subsection-title { font-family: 'Garamond', serif; font-size: 1.3rem; margin-bottom: 20px; color: #111; }
        .form-group { margin-bottom: 20px; position: relative; }
        .form-group label { display: block; font-size: 0.7rem; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px; color: #555; margin-bottom: 8px; }
        .form-control { width: 100%; padding: 12px 15px; background-color: #fafafa; border: 1px solid #eaeaea; border-radius: 4px; font-size: 0.95rem; font-family: inherit; }
        .form-control:focus { outline: none; border-color: #111; background-color: #fff; }
        .btn-save { display: block; width: 100%; padding: 16px; background-color: #111; color: #fff; border: none; border-radius: 4px; font-weight: bold; text-transform: uppercase; letter-spacing: 2px; cursor: pointer; transition: all 0.3s; }
        .btn-save:hover { background-color: #333; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .profile-pic-wrapper { width: 120px; height: 120px; border-radius: 50%; margin-bottom: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); background-color: #fafafa; overflow: hidden; border: 1px solid #eaeaea; }
        .profile-pic { width: 100%; height: 100%; object-fit: cover; }
        .media-preview-box { width: 100%; height: 180px; border-radius: 4px; margin-bottom: 10px; border: 1px solid #eee; overflow: hidden; display: flex; align-items: center; justify-content: center; background: #f9f9f9; }
        .media-preview-box img, .media-preview-box video { width: 100%; height: 100%; object-fit: cover; }
        
        .roles-list-item { padding: 15px; border: 1px solid #f0f0f0; border-radius: 6px; margin-bottom: 15px; background: #fff; display: flex; gap: 20px; align-items: center; }
        .member-info { flex: 1; }
        .member-info strong { font-size: 0.9rem; color: #111; display: block; margin-bottom: 5px; }
        .role-input-group { flex: 2; display: flex; gap: 10px; }

        /* ESTILOS DEL MODAL (CORREGIDOS PARA OCULTARLO) */
        .custom-modal-overlay { 
            display: none !important; /* Forzamos el ocultamiento por defecto */
            position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
            background: rgba(0, 0, 0, 0.5); /* Fondo oscuro semitransparente */
            backdrop-filter: blur(5px); z-index: 9999; align-items: center; justify-content: center; 
            opacity: 0; transition: opacity 0.3s ease; 
        }
        .custom-modal-overlay.active { display: flex !important; opacity: 1; }
        .custom-modal-box { background: #ffffff; border: 1px solid #eaeaea; border-radius: 8px; padding: 40px; width: 90%; max-width: 420px; text-align: center; box-shadow: 0 30px 60px rgba(0,0,0,0.2); }
        .custom-modal-icon { font-size: 2.5rem; color: #111; margin-bottom: 20px; }
        .custom-modal-title { font-size: 1.4rem; font-family: "Garamond", serif; margin-bottom: 15px; }
        .custom-modal-text { font-size: 0.95rem; color: #555; margin-bottom: 30px; }
        .custom-modal-actions { display: flex; gap: 15px; }
        .btn-modal { flex: 1; padding: 12px; border-radius: 4px; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; cursor: pointer; border: 1px solid transparent; }
        .btn-modal-cancel { background: #fafafa; color: #555; border-color: #ddd; }
        .btn-modal-confirm { background: #111; color: #fff; }
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
                {{-- Título dinámico para que no le diga "Administrador" a un colaborador --}}
                <h3 class="section-title-card">Mi Perfil ({{ Auth::user()->id_rol == 1 ? 'Superadmin' : (Auth::user()->id_rol == 2 ? 'Administrador' : 'Colaborador') }})</h3>
                <form id="form-perfil" action="{{ route('opciones.perfil.update') }}" method="POST" enctype="multipart/form-data" onsubmit="triggerCustomModal(event, this, '¿Guardar cambios en tu perfil?');">
                    @csrf @method('PUT')
                    <div style="display: flex; flex-wrap: wrap; gap: 40px; align-items: center;">
                        <div style="flex: 0 0 150px; text-align: center;">
                            <div class="profile-pic-wrapper"><img src="{{ Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : asset('images/default-avatar.png') }}" id="preview-foto" class="profile-pic"></div>
                            <label for="foto-upload" style="font-size: 0.7rem; cursor: pointer; color: #555; text-decoration: underline;">Cambiar foto</label>
                            <input type="file" id="foto-upload" name="foto" accept="image/*" style="display: none;" onchange="previewImage(event)">
                        </div>
                        <div style="flex: 1; display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div class="form-group"><label>Nombre</label><input type="text" name="nombre" class="form-control" value="{{ Auth::user()->nombre }}" required></div>
                            <div class="form-group"><label>Correo</label><input type="email" name="correo" class="form-control" value="{{ Auth::user()->correo }}" required></div>
                            <div class="form-group" style="grid-column: 1 / -1;"><label>Nueva Contraseña</label><input type="password" name="password_nueva" class="form-control" placeholder="Dejar vacío para no cambiar"></div>
                        </div>
                    </div>
                    <div style="display: flex; justify-content: flex-end; margin-top: 10px;"><button type="submit" class="btn-save" style="max-width: 200px; padding: 12px;">Guardar Perfil</button></div>
                </form>
            </div>

            @if(in_array(Auth::user()->id_rol, [1, 2]))
            <form action="{{ route('opciones.publicos.update') }}" method="POST" enctype="multipart/form-data" onsubmit="triggerCustomModal(event, this, '¿Actualizar contenido y roles del equipo?');">
                @csrf @method('PUT')

                <div class="options-card">
                    <h3 class="section-title-card">Roles dentro de la Empresa</h3>
                    <p style="font-size: 0.8rem; color: #888; margin-bottom: 20px;">Asigna el cargo público que aparecerá en la página de Información para cada integrante.</p>
                    
                    @isset($equipo)
                        @foreach($equipo as $miembro)
                        <div class="roles-list-item">
                            <div class="member-info">
                                <strong>{{ $miembro->usuario->nombre ?? 'Miembro' }}</strong>
                                <span style="font-size: 0.7rem; color: #aaa; text-transform: uppercase;">ID #{{ $miembro->id_miembro }}</span>
                            </div>
                            <div class="role-input-group">
                                <div style="flex: 1;">
                                    <label style="font-size: 0.6rem; font-weight: bold; color: #999;">PUESTO / ROL</label>
                                    <input type="text" name="puestos[{{ $miembro->id_miembro }}][puesto]" 
                                           class="form-control" 
                                           style="padding: 8px 12px; font-size: 0.85rem;"
                                           value="{{ $miembro->puesto }}" 
                                           placeholder="Ej: Dirección general">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @endisset
                </div>

                <div class="options-grid">
                    <div class="options-card">
                        <h3 class="subsection-title">Textos del Estudio</h3>
                        <div class="form-group"><label>¿Quiénes Somos?</label><textarea name="quienes_somos_texto" class="form-control" rows="6">{{ $configuracion->quienes_somos_texto ?? $defaultQuienesSomos }}</textarea></div>
                        <div class="form-group"><label>Valores</label><textarea name="valores_texto" class="form-control" rows="10">{{ $configuracion->valores_texto ?? $defaultValores }}</textarea></div>
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
                        <div class="form-group"><label>Instagram</label><input type="url" name="instagram" class="form-control" value="{{ $configuracion->instagram }}"></div>
                        <div class="form-group"><label>Facebook</label><input type="url" name="facebook" class="form-control" value="{{ $configuracion->facebook }}"></div>
                    </div>
                </div>

                <div class="options-card">
                    <h3 class="subsection-title">Contacto & Ubicación</h3>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label>Teléfono (WhatsApp)</label>
                            <input type="text" name="telefono" class="form-control" value="{{ $configuracion->telefono }}">
                        </div>
                        
                        <div class="form-group" style="grid-row: span 2;">
                            <label>Dirección</label>
                            <textarea name="direccion" class="form-control" rows="5">{{ $configuracion->direccion }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Correo Principal</label>
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
    function previewImage(e) { var r = new FileReader(); r.onload = function(){ document.getElementById('preview-foto').src = r.result; }; if(e.target.files[0]) r.readAsDataURL(e.target.files[0]); }
    function previewMedia(i) { var c = document.getElementById('media-preview-container'); var f = i.files[0]; if(f) { var u = URL.createObjectURL(f); if(f.type.startsWith('video/')) { c.innerHTML = `<video src="${u}" autoplay loop muted playsinline style="width:100%; height:100%; object-fit:cover;"></video>`; } else { c.innerHTML = `<img src="${u}" style="width:100%; height:100%; object-fit:cover;">`; } } }
    let fTS = null;
    function triggerCustomModal(e, f, m) { e.preventDefault(); fTS = f; document.getElementById('custom-modal-message').innerText = m; document.getElementById('custom-confirm-modal').classList.add('active'); }
    function closeCustomModal() { document.getElementById('custom-confirm-modal').classList.remove('active'); }
    document.getElementById('btn-modal-accept').onclick = function() { if(fTS) fTS.submit(); };
</script>
@endsection