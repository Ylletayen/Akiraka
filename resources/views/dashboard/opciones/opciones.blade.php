@extends('layouts.app')

@section('content')
<div class="dash-admin-view">
    <style>
        /* ================= ESTILOS BASE DEL DASHBOARD ================= */
        .dash-admin-view {
            min-height: 100vh;
            background-color: #ffffff; /* Fondo completamente BLANCO */
            font-family: "Garamond", "Baskerville", serif;
            color: #111;
            padding: 20px;
            display: flex;
            justify-content: center;
        }

        .dashboard-container {
            display: flex;
            width: 100%;
            max-width: 1400px;
            gap: 20px;
            align-items: stretch;
        }

        /* ================= MAIN CONTENT (LIMPIO Y BLANCO) ================= */
        .main-content {
            flex-grow: 1;
            background: #ffffff;
            padding: 40px 50px; 
            border-radius: 12px;
            position: relative;
        }

        .header-section {
            border-bottom: 1px solid #f0f0f0;
            padding-bottom: 20px;
            margin-bottom: 40px;
        }

        .header-section h1 {
            font-size: 2.2rem;
            font-weight: normal; 
            margin-bottom: 5px;
            color: #111;
        }

        .header-section p {
            color: #888;
            font-style: italic;
            font-size: 0.95rem;
        }

        /* ================= TARJETAS MINIMALISTAS ================= */
        .options-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
        }

        @media (max-width: 992px) {
            .options-grid {
                grid-template-columns: 1fr;
            }
        }

        .options-card {
            background: #ffffff; 
            border: 1px solid #eaeaea; 
            border-radius: 8px;
            padding: 40px 30px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.03); 
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .options-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.06);
        }

        /* Barra de acento negra arriba de la tarjeta */
        .options-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background-color: #111;
        }

        .options-card h3 {
            font-size: 1.4rem;
            margin-bottom: 5px;
            font-weight: normal;
            color: #111;
        }

        .options-card p.subtitle {
            font-size: 0.8rem;
            color: #888;
            margin-bottom: 35px;
            font-family: "Helvetica Neue", Arial, sans-serif;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* ================= SECCIÓN DE FOTO DE PERFIL ================= */
        .profile-pic-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 30px;
            border-bottom: 1px solid #f0f0f0;
        }

        .profile-pic-wrapper {
            position: relative;
            width: 110px;
            height: 110px;
            border-radius: 50%;
            margin-bottom: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            background-color: #fafafa;
            overflow: hidden;
            border: 1px solid #eaeaea;
        }

        .profile-pic {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .btn-change-photo {
            font-family: "Helvetica Neue", Arial, sans-serif;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #555;
            background: #fafafa;
            border: 1px solid #ddd;
            padding: 8px 20px;
            border-radius: 30px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-change-photo:hover {
            background: #111;
            color: #fff;
            border-color: #111;
        }

        /* ================= INPUTS LIMPIOS ================= */
        .form-group {
            margin-bottom: 25px;
            font-family: "Helvetica Neue", Arial, sans-serif;
            position: relative;
        }

        .form-group label {
            display: block;
            font-size: 0.7rem;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #555;
            margin-bottom: 10px;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            background-color: #fafafa; 
            border: 1px solid #eaeaea;
            border-radius: 4px;
            font-size: 0.95rem;
            transition: all 0.3s;
            color: #111;
        }

        .form-control:focus {
            outline: none;
            border-color: #111;
            background-color: #ffffff;
            box-shadow: 0 0 0 2px rgba(17, 17, 17, 0.05);
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 40px;
            cursor: pointer;
            color: #888;
            font-size: 1.1rem;
            transition: color 0.3s;
        }

        .toggle-password:hover { color: #111; }

        textarea.form-control { resize: vertical; min-height: 90px; }

        .btn-save {
            display: block; width: 100%; padding: 16px; background-color: #111; color: #fff;
            border: none; border-radius: 4px; font-size: 0.8rem; font-weight: bold;
            text-transform: uppercase; letter-spacing: 2px; cursor: pointer;
            transition: background-color 0.3s, transform 0.1s; font-family: "Helvetica Neue", Arial, sans-serif;
        }
        .btn-save:hover { background-color: #333; }
        .btn-save:active { transform: scale(0.98); }

        .btn-outline { background-color: transparent; color: #111; border: 1px solid #111; }
        .btn-outline:hover { background-color: #111; color: #fff; }

        .alert-success {
            background: #f0fdf4; color: #166534;
            border: 1px solid #bbf7d0; padding: 15px 20px; border-radius: 6px;
            margin-top: 15px; font-family: "Helvetica Neue", Arial, sans-serif; font-size: 0.9rem;
        }

        /* ================= VENTANA MODAL ================= */
        .custom-modal-overlay {
            display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(5px); z-index: 9999;
            align-items: center; justify-content: center; opacity: 0; transition: opacity 0.3s ease;
        }
        .custom-modal-overlay.active { display: flex; opacity: 1; }
        .custom-modal-box {
            background: #ffffff; border: 1px solid #eaeaea;
            border-radius: 8px; padding: 50px 40px; width: 90%; max-width: 420px; text-align: center;
            box-shadow: 0 30px 60px rgba(0,0,0,0.08); transform: translateY(-20px) scale(0.95);
            transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); font-family: "Helvetica Neue", Arial, sans-serif;
        }
        .custom-modal-overlay.active .custom-modal-box { transform: translateY(0) scale(1); }
        .custom-modal-icon { font-size: 2.5rem; color: #111; margin-bottom: 20px; }
        .custom-modal-title { font-size: 1.4rem; font-weight: normal; color: #111; margin-bottom: 15px; font-family: "Garamond", "Baskerville", serif; }
        .custom-modal-text { font-size: 0.95rem; color: #555; margin-bottom: 35px; line-height: 1.6; }
        .custom-modal-actions { display: flex; gap: 15px; }
        .btn-modal { flex: 1; padding: 14px; border-radius: 4px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; font-size: 0.75rem; cursor: pointer; transition: all 0.2s; }
        .btn-modal-cancel { background: #fafafa; color: #555; border: 1px solid #ddd; }
        .btn-modal-cancel:hover { background: #eee; color: #111; }
        .btn-modal-confirm { background: #111; color: #fff; border: 1px solid #111; }
        .btn-modal-confirm:hover { background: #333; }
    </style>

    <div class="dashboard-container">
        <!-- ================= INCLUIR SIDEBAR ================= -->
        @include('partials.sidebar')

        <main class="main-content">
            
            @include('partials.topbar')

            <div class="header-section" style="margin-top: 30px;">
                <h1>Opciones del Sistema</h1>
                <p>Configuración de cuenta administrativa e información pública del sitio.</p>

                @if(session('success'))
                    <div class="alert-success">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    </div>
                @endif
            </div>

            <div class="options-grid">
                
                <!-- ================= TARJETA 1: PERFIL ================= -->
                <div class="options-card">
                    <h3>Perfil de Administrador</h3>
                    <p class="subtitle">Actualiza tus credenciales</p>

                    <form id="form-perfil" action="{{ route('opciones.perfil.update') }}" method="POST" enctype="multipart/form-data" onsubmit="triggerCustomModal(event, this, '¿Estás seguro de guardar los cambios en tu perfil? Las nuevas credenciales aplicarán en tu próximo inicio de sesión.');">
                        @csrf
                        @method('PUT')
                        
                        <div class="profile-pic-container">
                            <div class="profile-pic-wrapper">
                                <img src="{{ Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : asset('images/default-avatar.png') }}" 
                                     id="preview-foto" class="profile-pic" alt="Foto de perfil">
                            </div>
                            
                            <label for="foto-upload" class="btn-change-photo">
                                <i class="fas fa-camera me-2"></i> Cambiar Foto
                            </label>
                            
                            <input type="file" id="foto-upload" name="foto" accept="image/*" style="display: none;" onchange="previewImage(event)">
                        </div>

                        <div class="form-group">
                            <label>Nombre Mostrado</label>
                            <input type="text" name="nombre" class="form-control" value="{{ Auth::user()->nombre ?? '' }}" required>
                        </div>

                        <div class="form-group">
                            <label>Correo de Acceso</label>
                            <input type="email" name="correo" class="form-control" value="{{ Auth::user()->correo ?? '' }}" required>
                        </div>

                        <div style="height: 1px; background-color: #f0f0f0; margin: 30px 0;"></div>
                        <p class="subtitle" style="color: #111;">Cambio de Contraseña</p>

                        <div class="form-group">
                            <label>Nueva Contraseña (Opcional)</label>
                            <input type="password" id="password_nueva" name="password_nueva" class="form-control" placeholder="Escribe aquí para cambiarla...">
                            <i class="fas fa-eye toggle-password" onclick="togglePasswordVisibility('password_nueva', this)"></i>
                        </div>

                        <button type="submit" class="btn-save mt-4">Guardar Perfil</button>
                    </form>
                </div>

                <!-- ================= TARJETA 2: PÚBLICOS ================= -->
                <div class="options-card">
                    <h3>Datos Públicos</h3>
                    <p class="subtitle">Visibles para los clientes web</p>

                    <form id="form-publicos" action="{{ route('opciones.publicos.update') }}" method="POST" onsubmit="triggerCustomModal(event, this, '¿Estás seguro de actualizar estos datos? Los cambios serán visibles inmediatamente para el público en la página principal.');">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label>Teléfono Público</label>
                            <input type="text" name="telefono" class="form-control" value="{{ $configuracion->telefono ?? '' }}">
                        </div>

                        <!-- Sección de Correos Agrupados -->
                        <div style="height: 1px; background-color: #f0f0f0; margin: 25px 0 15px 0;"></div>
                        <label style="display: block; font-size: 0.7rem; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px; color: #111; margin-bottom: 15px;">Gestión de Correos</label>

                        <div style="display: flex; gap: 15px;">
                            <div class="form-group" style="flex: 1;">
                                <label>Proyectos y Eventos</label>
                                <input type="email" name="correo_contacto" class="form-control" value="{{ $configuracion->correo_contacto ?? '' }}">
                            </div>

                            <div class="form-group" style="flex: 1;">
                                <label>Prensa</label>
                                <input type="email" name="correo_prensa" class="form-control" value="{{ $configuracion->correo_prensa ?? '' }}">
                            </div>
                        </div>

                        <div style="display: flex; gap: 15px;">
                            <div class="form-group" style="flex: 1;">
                                <label>Oportunidades Laborales 1</label>
                                <input type="email" name="correo_laboral_1" class="form-control" value="{{ $configuracion->correo_laboral_1 ?? '' }}">
                            </div>

                            <div class="form-group" style="flex: 1;">
                                <label>Oportunidades Laborales 2</label>
                                <input type="email" name="correo_laboral_2" class="form-control" value="{{ $configuracion->correo_laboral_2 ?? '' }}">
                            </div>
                        </div>

                        <div style="height: 1px; background-color: #f0f0f0; margin: 15px 0 25px 0;"></div>

                        <div class="form-group">
                            <label>Dirección del Estudio</label>
                            <textarea name="direccion" class="form-control" rows="2">{{ $configuracion->direccion ?? '' }}</textarea>
                        </div>

                        <div style="display: flex; gap: 15px;">
                            <div class="form-group" style="flex: 1;">
                                <label>Enlace Instagram</label>
                                <input type="url" name="instagram" class="form-control" value="{{ $configuracion->instagram ?? '' }}">
                            </div>

                            <div class="form-group" style="flex: 1;">
                                <label>Enlace Facebook</label>
                                <input type="url" name="facebook" class="form-control" value="{{ $configuracion->facebook ?? '' }}">
                            </div>
                        </div>

                        <button type="submit" class="btn-save btn-outline mt-4" style="margin-top: 30px;">Actualizar Datos</button>
                    </form>
                </div>

            </div>
        </main>
    </div>
</div>

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
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('preview-foto');
            output.src = reader.result;
        };
        if(event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }

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