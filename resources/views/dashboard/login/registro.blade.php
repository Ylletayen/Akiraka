<style>
    /* =========================================
       FONDO DESENFOCADO (AKIRAKA STYLE)
       ========================================= */
    .register-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background-color: rgba(28, 28, 28, 0.85); /* Tono oscuro Akiraka (#1c1c1c) con transparencia */
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        z-index: 11000;
        display: none; 
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.4s ease;
    }

    .register-overlay.active {
        display: flex;
        opacity: 1;
    }

    /* =========================================
       TARJETA BLANCA (ESTILO MINIMALISTA PREMIUM)
       ========================================= */
    .register-card {
        background-color: #ffffff;
        width: 100%;
        max-width: 440px;
        padding: 50px 40px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        position: relative;
        transform: translateY(20px);
        transition: transform 0.4s ease;
        border-radius: 8px;
        border-top: 5px solid #1c1c1c;
    }

    .register-overlay.active .register-card {
        transform: translateY(0);
    }

    /* Botón cerrar */
    .btn-close-fixed {
        position: absolute;
        top: 20px;
        right: 20px;
        background: none;
        border: none;
        cursor: pointer;
        font-size: 0.65rem;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        color: #888;
        font-family: "Helvetica Neue", Arial, sans-serif;
        font-weight: bold;
        transition: color 0.3s ease;
    }

    .btn-close-fixed:hover {
        color: #111;
    }

    /* Títulos */
    .register-header {
        text-align: center;
        margin-bottom: 35px;
    }

    .register-title {
        font-family: "Garamond", "Baskerville", serif;
        font-weight: 600;
        margin: 0 0 5px 0;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        font-size: 1.5rem;
        color: #1c1c1c;
    }

    .register-subtitle {
        font-family: "Helvetica Neue", Arial, sans-serif;
        font-size: 0.65rem;
        letter-spacing: 0.25em;
        color: #888;
        text-transform: uppercase;
    }

    /* =========================================
       INPUTS (Cajas limpias y modernas)
       ========================================= */
    .register-label {
        font-size: 0.65rem;
        font-weight: bold;
        letter-spacing: 0.15em;
        color: #4b4b4b;
        display: block;
        margin-bottom: 8px;
        text-transform: uppercase;
        font-family: "Helvetica Neue", Arial, sans-serif;
    }

    .register-input {
        width: 100%;
        background-color: #fafafa;
        border: 1px solid #e5e5e5;
        border-radius: 6px;
        padding: 12px 16px;
        margin-bottom: 25px;
        outline: none;
        font-size: 0.95rem;
        color: #111;
        font-family: "Helvetica Neue", Arial, sans-serif;
        transition: all 0.3s ease;
    }

    .register-input::placeholder {
        color: #bbb;
        font-weight: 300;
    }

    .register-input:focus {
        background-color: #ffffff;
        border-color: #1c1c1c;
        box-shadow: 0 0 0 3px rgba(28, 28, 28, 0.08);
    }

    /* Estado de error para el input */
    .register-input.input-error {
        border-color: #d9534f;
        background-color: #fffcfc;
        box-shadow: 0 0 0 3px rgba(217, 83, 79, 0.1);
    }

    /* =========================================
       MENSAJE DE ERROR PERSONALIZADO (TOOLTIP)
       ========================================= */
    .custom-error-msg {
        display: none; /* Oculto por defecto */
        background-color: #1c1c1c;
        color: #ffffff;
        padding: 12px 16px;
        font-size: 0.75rem;
        border-radius: 6px;
        margin-top: -15px; /* Sube para pegarse al input */
        margin-bottom: 25px;
        position: relative;
        font-family: "Helvetica Neue", Arial, sans-serif;
        letter-spacing: 0.05em;
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        animation: slideDownFade 0.3s ease;
    }

    /* El piquito del globo de diálogo apuntando hacia arriba */
    .custom-error-msg::before {
        content: '';
        position: absolute;
        top: -4px;
        left: 20px;
        width: 10px;
        height: 10px;
        background-color: #1c1c1c;
        transform: rotate(45deg);
    }

    .custom-error-msg.show {
        display: block;
    }

    @keyframes slideDownFade {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* =========================================
       BOTÓN DE ENVÍO
       ========================================= */
    .btn-register-submit {
        background: #1c1c1c;
        color: #ffffff;
        border: none;
        border-radius: 6px;
        padding: 16px;
        width: 100%;
        letter-spacing: 0.25em;
        text-transform: uppercase;
        font-size: 0.75rem;
        font-weight: bold;
        margin-top: 5px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-family: "Helvetica Neue", Arial, sans-serif;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .btn-register-submit:hover {
        background: #3a3a3a;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .btn-register-submit:active {
        transform: translateY(0);
    }
</style>

<!-- MODAL HTML -->
<div id="register-modal" class="register-overlay">
    <div class="register-card">
        
        <button onclick="cerrarRegistro()" class="btn-close-fixed">
            Cerrar [×]
        </button>

        <div class="register-header">
            <h2 class="register-title">Nuevo Miembro</h2>
            <p class="register-subtitle">Acceso Administrativo</p>
        </div>

        <form action="{{ route('registro.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <label class="register-label">Nombre Completo</label>
            <input type="text" name="nombre" class="register-input" placeholder="Ej. Arquitecto..." required>

            <label class="register-label">Correo Electrónico</label>
            <input type="email" name="correo" class="register-input" placeholder="correo@akiraka.com" required>

            <label class="register-label">Contraseña de Acceso <span style="text-transform:none; letter-spacing:normal; color:#999; font-weight:normal;">(Mín. 12 caracteres)</span></label>
            
            <!-- Quitamos los atributos oninvalid/oninput nativos para usar los nuestros -->
            <input type="password" 
                   id="reg-password"
                   name="password" 
                   class="register-input" 
                   placeholder="••••••••••••" 
                   required 
                   minlength="12">

            <!-- NUESTRO GLOBITO DE ERROR PERSONALIZADO -->
            <div id="custom-pass-error" class="custom-error-msg">
                <i class="fas fa-shield-alt" style="margin-right: 6px; color: #d9534f;"></i>
                Por seguridad, la contraseña debe tener al menos 12 caracteres.
            </div>

            <input type="hidden" name="id_rol" value="3">

            <button type="submit" class="btn-register-submit">Crear Cuenta</button>
        </form>

    </div>
</div>

<!-- SCRIPTS -->
<script>
    function abrirRegistro() {
        const modalReg = document.getElementById('register-modal');
        modalReg.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function cerrarRegistro() {
        const modalReg = document.getElementById('register-modal');
        modalReg.classList.remove('active');
        document.body.style.overflow = 'auto';
        
        // Limpiar el error visual si cierran la ventana
        document.getElementById('custom-pass-error').classList.remove('show');
        document.getElementById('reg-password').classList.remove('input-error');
    }

    // LÓGICA PARA REEMPLAZAR LA ALERTA NATIVA POR LA NUESTRA ESTILIZADA
    document.addEventListener("DOMContentLoaded", function() {
        const passInput = document.getElementById('reg-password');
        const passError = document.getElementById('custom-pass-error');

        if(passInput) {
            // Cuando el navegador detecta que es inválido (ej: le dan click a Enviar y no tiene 12 letras)
            passInput.addEventListener('invalid', function(e) {
                e.preventDefault(); // ¡Magia! Esto cancela el globito feo del navegador
                passError.classList.add('show'); // Mostramos nuestro tooltip oscuro
                passInput.classList.add('input-error'); // Pintamos el borde de rojo
            });

            // Mientras el usuario escribe, vamos escondiendo el error para no molestarlo
            passInput.addEventListener('input', function(e) {
                passError.classList.remove('show');
                passInput.classList.remove('input-error');
            });
        }
    });
</script>