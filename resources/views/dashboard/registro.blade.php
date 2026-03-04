<style>
    /* Usamos la misma clase que el login para consistencia */
    .register-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(10px);
        z-index: 11000; /* Un poco más alto que el login por si se solapan */
        display: none; 
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.4s ease;
        font-family: "Garamond", serif;
    }

    .register-overlay.active {
        display: flex;
        opacity: 1;
    }

    .register-card {
        width: 100%;
        max-width: 450px;
        padding: 40px;
    }

    .register-input {
        border: none;
        border-bottom: 1px solid #111;
        border-radius: 0;
        padding: 10px 0;
        width: 100%;
        margin-bottom: 25px;
        background: transparent;
        outline: none;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .register-label {
        font-size: 0.7rem;
        letter-spacing: 2px;
        color: #8c8c8c;
        display: block;
        margin-bottom: 5px;
    }

    .btn-register-submit {
        background: #111;
        color: #fff;
        border: none;
        padding: 15px;
        width: 100%;
        letter-spacing: 3px;
        text-transform: uppercase;
        font-size: 0.8rem;
        margin-top: 20px;
        transition: all 0.3s;
    }

    .btn-register-submit:hover {
        background: #333;
        transform: translateY(-2px);
    }
</style>

<div id="register-modal" class="register-overlay">
    <div class="register-card">
        <button onclick="cerrarRegistro()" class="btn-close-fixed" style="position: absolute; top: 30px; right: 50px; background: none; border: none; cursor: pointer; font-size: 1.2rem;">
            Cerrar [×]
        </button>

        <h2 style="font-weight: normal; text-align: center; margin-bottom: 40px; letter-spacing: 5px; text-transform: uppercase;">
            Nuevo Miembro
        </h2>

        <form action="{{ route('registro.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <span class="register-label">Nombre Completo</span>
            <input type="text" name="nombre" class="register-input" required>

            <span class="register-label">Correo</span>
            <input type="email" name="correo" class="register-input" required>

            <span class="register-label">Contraseña de Acceso</span>
            <input type="password" name="password" class="register-input" required>

            {{-- Input oculto para el rol: 3 es Colaborador según tu tabla Roles --}}
            <input type="hidden" name="id_rol" value="3">

            <button type="submit" class="btn-register-submit">Crear Cuenta</button>
        </form>
    </div>
</div>

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