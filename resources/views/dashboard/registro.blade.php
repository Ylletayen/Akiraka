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
        /* Negro transparente con efecto de cristal esmerilado */
        background-color: rgba(10, 10, 10, 0.85);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        z-index: 11000;
        display: none; 
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.4s ease;
        /* Tipografía clásica/aburrida solicitada */
        font-family: "Garamond", "Times New Roman", serif;
    }

    .register-overlay.active {
        display: flex;
        opacity: 1;
    }

    /* =========================================
       TARJETA BLANCA (VENTANA DE ENFOQUE)
       ========================================= */
    .register-card {
        background-color: #ffffff;
        width: 100%;
        max-width: 480px;
        padding: 60px 50px;
        box-shadow: 0 40px 80px rgba(0, 0, 0, 0.6);
        position: relative;
        transform: translateY(20px);
        transition: transform 0.4s ease;
        border: 1px solid #222;
    }

    .register-overlay.active .register-card {
        transform: translateY(0);
    }

    /* Botón cerrar minimalista */
    .btn-close-fixed {
        position: absolute;
        top: 25px;
        right: 25px;
        background: none;
        border: none;
        cursor: pointer;
        font-size: 0.65rem;
        letter-spacing: 0.3em;
        text-transform: uppercase;
        color: #888;
        font-family: "Helvetica Neue", Arial, sans-serif; /* Sans-serif para contraste técnico */
        transition: color 0.3s ease;
    }

    .btn-close-fixed:hover {
        color: #000;
    }

    .register-title {
        font-weight: 400;
        text-align: center;
        margin-bottom: 40px;
        letter-spacing: 0.4em;
        text-transform: uppercase;
        font-size: 1.2rem;
        color: #111;
        border-bottom: 1px solid #eaeaea;
        padding-bottom: 20px;
    }

    /* =========================================
       INPUTS ESTILO ARQUITECTÓNICO
       ========================================= */
    .register-label {
        font-size: 0.65rem;
        letter-spacing: 0.25em;
        color: #888;
        display: block;
        margin-bottom: 5px;
        text-transform: uppercase;
        font-family: "Helvetica Neue", Arial, sans-serif;
    }

    .register-input {
        border: none;
        border-bottom: 1px solid #ddd;
        border-radius: 0;
        padding: 10px 0;
        width: 100%;
        margin-bottom: 35px;
        background: transparent;
        outline: none;
        font-size: 1.2rem;
        color: #111;
        font-family: "Garamond", "Times New Roman", serif;
        transition: border-color 0.3s ease;
    }

    /* Efecto al hacer click en el input */
    .register-input:focus {
        border-bottom: 1px solid #111;
    }

    /* =========================================
       BOTÓN DE ENVÍO
       ========================================= */
    .btn-register-submit {
        background: #111;
        color: #fff;
        border: 1px solid #111;
        padding: 20px;
        width: 100%;
        letter-spacing: 0.4em;
        text-transform: uppercase;
        font-size: 0.7rem;
        margin-top: 10px;
        cursor: pointer;
        transition: all 0.4s ease;
        font-family: "Helvetica Neue", Arial, sans-serif;
    }

    /* Inversión de colores al pasar el mouse */
    .btn-register-submit:hover {
        background: #fff;
        color: #111;
    }
</style>

<!-- MODAL HTML -->
<div id="register-modal" class="register-overlay">
    <div class="register-card">
        
        <button onclick="cerrarRegistro()" class="btn-close-fixed">
            Cerrar [×]
        </button>

        <h2 class="register-title">
            Nuevo Miembro
        </h2>

        <!-- EL FORMULARIO ORIGINAL INTACTO PARA QUE EL BACKEND FUNCIONE -->
        <form action="{{ route('registro.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <label class="register-label">Nombre Completo</label>
            <input type="text" name="nombre" class="register-input" placeholder="Ej. Arquitecto..." required>

            <label class="register-label">Correo Electrónico</label>
            <input type="email" name="correo" class="register-input" placeholder="correo@akiraka.com" required>

            <label class="register-label">Contraseña de Acceso</label>
            <input type="password" name="password" class="register-input" placeholder="••••••••" required>

            {{-- Input oculto para el rol: 3 es Colaborador según tu tabla Roles --}}
            <input type="hidden" name="id_rol" value="3">

            <button type="submit" class="btn-register-submit">Crear Cuenta</button>
        </form>

    </div>
</div>

<!-- SCRIPTS INTACTOS -->
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
    }
</script>