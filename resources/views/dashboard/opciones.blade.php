@extends('layouts.app')

@section('content')
<div class="dash-admin-view">
    <style>
        /* ================= ESTILOS BASE DEL DASHBOARD ================= */
        .dash-admin-view {
            min-height: 100vh;
            background-color: #f8f8f8;
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

        /* ================= MAIN CONTENT (FONDO DEGRADADO) ================= */
        .main-content {
            flex-grow: 1;
            /* Degradado arquitectónico, elegante y sutil en tonos grises/plata */
            background: linear-gradient(135deg, #e2e6eb 0%, #ffffff 50%, #d5d9e0 100%);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            position: relative;
            overflow: hidden;
        }

        /* Elemento decorativo de fondo para mejorar el efecto cristal */
        .main-content::after {
            content: '';
            position: absolute;
            top: -20%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(255,255,255,0.8) 0%, rgba(255,255,255,0) 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .header-section {
            border-bottom: 1px solid rgba(0,0,0,0.1);
            padding-bottom: 20px;
            margin-bottom: 40px;
            position: relative;
            z-index: 2;
        }

        .header-section h1 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 5px;
            color: #222;
        }

        .header-section p {
            color: #555;
            font-style: italic;
        }

        /* ================= EFECTO GLASSMORPHISM (CRISTAL) EN LAS TARJETAS ================= */
        .options-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            position: relative;
            z-index: 2;
        }

        @media (max-width: 992px) {
            .options-grid {
                grid-template-columns: 1fr;
            }
        }

        .options-card {
            /* Fondo semitransparente blanco */
            background: rgba(255, 255, 255, 0.45);
            /* El truco de magia: difumina lo que está detrás */
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            /* Borde ligero y sombra suave para dar volumen */
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 12px;
            padding: 30px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .options-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.08);
        }

        .options-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background-color: #4b4b4b; /* Línea superior oscura y elegante */
        }

        .options-card h3 {
            font-size: 1.4rem;
            margin-bottom: 5px;
            font-weight: bold;
            color: #111;
        }

        .options-card p.subtitle {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 25px;
            font-family: "Helvetica Neue", Arial, sans-serif;
        }

        /* ================= INPUTS ADAPTADOS AL CRISTAL ================= */
        .form-group {
            margin-bottom: 20px;
            font-family: "Helvetica Neue", Arial, sans-serif;
            position: relative;
        }

        .form-group label {
            display: block;
            font-size: 0.75rem;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #444;
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            /* Inputs también semitransparentes */
            background-color: rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 6px;
            font-size: 0.95rem;
            transition: all 0.3s;
            color: #222;
        }

        .form-control:focus {
            outline: none;
            border-color: #111;
            background-color: rgba(255, 255, 255, 0.95);
            box-shadow: 0 0 0 3px rgba(17, 17, 17, 0.05);
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 38px;
            cursor: pointer;
            color: #666;
            font-size: 1.1rem;
            transition: color 0.3s;
        }

        .toggle-password:hover {
            color: #111;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 80px;
        }

        .divider {
            height: 1px;
            background-color: rgba(0, 0, 0, 0.08);
            margin: 25px 0;
        }

        .btn-save {
            display: block;
            width: 100%;
            padding: 15px;
            background-color: #111;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.1s;
            font-family: "Helvetica Neue", Arial, sans-serif;
        }

        .btn-save:hover {
            background-color: #333;
        }
        
        .btn-save:active {
            transform: scale(0.98);
        }

        .btn-outline {
            background-color: transparent;
            color: #111;
            border: 2px solid #111;
        }

        .btn-outline:hover {
            background-color: #111;
            color: #fff;
        }

        /* Alerta de éxito integrada */
        .alert-success {
            background: rgba(209, 250, 229, 0.8);
            backdrop-filter: blur(10px);
            color: #065f46;
            border: 1px solid rgba(52, 211, 153, 0.5);
            padding: 12px 20px;
            border-radius: 8px;
            margin-top: 15px;
            font-family: "Helvetica Neue", Arial, sans-serif;
            font-weight: 500;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        }

        /* ================= VENTANA MODAL PERSONALIZADA (GLASSMORPHISM) ================= */
        .custom-modal-overlay {
            display: none; /* Oculta por defecto */
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .custom-modal-overlay.active {
            display: flex;
            opacity: 1;
        }

        .custom-modal-box {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 12px;
            padding: 40px 30px;
            width: 90%;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
            transform: translateY(-20px) scale(0.95);
            transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            font-family: "Helvetica Neue", Arial, sans-serif;
        }

        .custom-modal-overlay.active .custom-modal-box {
            transform: translateY(0) scale(1);
        }

        .custom-modal-icon {
            font-size: 3rem;
            color: #111;
            margin-bottom: 15px;
        }

        .custom-modal-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #111;
            margin-bottom: 10px;
            font-family: "Garamond", "Baskerville", serif;
        }

        .custom-modal-text {
            font-size: 0.95rem;
            color: #555;
            margin-bottom: 30px;
            line-height: 1.5;
        }

        .custom-modal-actions {
            display: flex;
            gap: 15px;
        }

        .btn-modal {
            flex: 1;
            padding: 12px;
            border-radius: 6px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-modal-cancel {
            background: #eaeaea;
            color: #555;
            border: none;
        }

        .btn-modal-cancel:hover {
            background: #d4d4d4;
            color: #111;
        }

        .btn-modal-confirm {
            background: #111;
            color: #fff;
            border: none;
        }

        .btn-modal-confirm:hover {
            background: #333;
        }
    </style>

    <div class="dashboard-container">
        <!-- ================= INCLUIR SIDEBAR ================= -->
        <!-- Aquí llamamos a la vista sidebar.blade.php que crearon -->
        @include('partials.sidebar')

        <!-- ================= MAIN CONTENT ================= -->
        <main class="main-content">
            
            <div class="header-section">
                <h1>Opciones del Sistema</h1>
                <p>Configuración de cuenta administrativa e información pública del sitio.</p>

                <!-- Mensaje de éxito que aparece después de guardar -->
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
                    <p class="subtitle">Actualiza tus credenciales de acceso</p>

                    <!-- Cambio: Agregamos onsubmit llamando a nuestra función JS -->
                    <form id="form-perfil" action="{{ route('opciones.perfil.update') }}" method="POST" onsubmit="triggerCustomModal(event, this, '¿Estás seguro de guardar los cambios en tu perfil? Las nuevas credenciales aplicarán en tu próximo inicio de sesión.');">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label>Nombre Mostrado</label>
                            <input type="text" name="nombre" class="form-control" value="{{ Auth::user()->nombre ?? 'Akira Kameta Miyamoto' }}" required>
                        </div>

                        <div class="form-group">
                            <label>Correo de Acceso (Login)</label>
                            <input type="email" name="correo" class="form-control" value="{{ Auth::user()->correo ?? 'administracion@akirakastudio.com' }}" required>
                        </div>

                        <div class="divider"></div>
                        <p class="subtitle mb-3" style="font-weight: bold; color: #111;">Cambio de Contraseña</p>

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
                    <h3 style="color: #444;">Datos Públicos</h3>
                    <p class="subtitle">Información visible para los clientes en la web</p>

                    <!-- Cambio: Agregamos onsubmit llamando a nuestra función JS -->
                    <form id="form-publicos" action="{{ route('opciones.publicos.update') }}" method="POST" onsubmit="triggerCustomModal(event, this, '¿Estás seguro de actualizar estos datos? Los cambios serán visibles inmediatamente para el público en la página principal.');">
                        @csrf
                        @method('PUT')
                        
                        <div style="display: flex; gap: 15px;">
                            <div class="form-group" style="flex: 1;">
                                <label>Teléfono Público</label>
                                <input type="text" name="telefono" class="form-control" value="722 165 5901">
                            </div>

                            <div class="form-group" style="flex: 1;">
                                <label>Correo de Contacto</label>
                                <input type="email" name="correo_contacto" class="form-control" value="akiraka.estudio@gmail.com">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Dirección del Estudio</label>
                            <textarea name="direccion" class="form-control" rows="2">Parque Santa María 10, Santa María Ahuacatlán, 51200 Valle de Bravo, Estado de México</textarea>
                        </div>

                        <div class="form-group">
                            <label>Enlace de Instagram</label>
                            <input type="url" name="instagram" class="form-control" value="https://www.instagram.com/">
                        </div>

                        <button type="submit" class="btn-save btn-outline mt-4" style="margin-top: 55px;">Actualizar Datos Públicos</button>
                    </form>
                </div>

            </div>
        </main>
    </div>
</div>

<!-- ================= ESTRUCTURA HTML DEL MODAL PERSONALIZADO ================= -->
<div id="custom-confirm-modal" class="custom-modal-overlay">
    <div class="custom-modal-box">
        <div class="custom-modal-icon">
            <i class="fas fa-exclamation-circle"></i>
        </div>
        <h3 class="custom-modal-title">Confirmar Actualización</h3>
        <p id="custom-modal-message" class="custom-modal-text">¿Estás seguro de que deseas guardar los cambios?</p>
        
        <div class="custom-modal-actions">
            <button type="button" class="btn-modal btn-modal-cancel" onclick="closeCustomModal()">Cancelar</button>
            <button type="button" class="btn-modal btn-modal-confirm" id="btn-modal-accept">Aceptar</button>
        </div>
    </div>
</div>

<!-- ================= SCRIPTS: MODAL Y OJITO ================= -->
<script>
    // 1. Lógica del Ojito para Mostrar/Ocultar Contraseña
    function togglePasswordVisibility(inputId, icon) {
        var input = document.getElementById(inputId);
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = "password";
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // 2. Lógica del Modal Elegante
    let formToSubmit = null;
    const modalOverlay = document.getElementById('custom-confirm-modal');
    const modalMessage = document.getElementById('custom-modal-message');
    const btnAccept = document.getElementById('btn-modal-accept');

    function triggerCustomModal(event, formElement, messageText) {
        event.preventDefault(); // Detiene el envío automático
        formToSubmit = formElement; // Guarda el formulario que disparó el evento
        modalMessage.innerText = messageText; // Cambia el texto dinámicamente
        
        // Muestra el modal
        modalOverlay.classList.add('active');
    }

    function closeCustomModal() {
        modalOverlay.classList.remove('active');
        formToSubmit = null; // Limpia la variable
    }

    // Cuando dan clic en "Aceptar" dentro del modal
    btnAccept.addEventListener('click', function() {
        if (formToSubmit) {
            formToSubmit.submit(); // Fuerza el envío real a Laravel
        }
    });
</script>
@endsection