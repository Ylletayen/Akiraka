<style>
    /* ... TUS ESTILOS EXISTENTES ... */
    .login-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(10px);
        z-index: 10000;
        display: none; 
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.4s ease;
    }

    .login-overlay.active {
        display: flex;
        opacity: 1;
    }

    .login-card {
        width: 100%;
        max-width: 380px;
        padding: 40px;
        font-family: "Garamond", serif;
    }

    .login-input {
        border: none;
        border-bottom: 1px solid #111;
        border-radius: 0;
        padding: 10px 0;
        width: 100%;
        margin-bottom: 30px;
        background: transparent;
        outline: none;
    }

    .btn-login-submit {
        background: #111;
        color: #fff;
        border: none;
        padding: 12px;
        width: 100%;
        letter-spacing: 2px;
        text-transform: uppercase;
        font-size: 0.8rem;
        transition: opacity 0.3s;
    }

    .btn-login-submit:hover { opacity: 0.8; }

    /* --- NUEVO ESTILO PARA EL LINK DE REGISTRO --- */
    .register-link-container {
        margin-top: 25px;
        text-align: center;
        font-size: 0.8rem;
        letter-spacing: 1px;
    }

    .link-reg {
        color: #111;
        text-decoration: underline;
        font-weight: 600;
        transition: color 0.3s;
    }

    .link-reg:hover {
        color: #8c8c8c;
    }
</style>

<div id="login-modal" class="login-overlay">
    <div class="login-card">
        <button onclick="cerrarLogin()" class="btn-close-fixed" style="position: absolute; top: 30px; right: 50px; background: none; border: none; cursor: pointer;">
            Cerrar [×]
        </button>

        <h2 style="font-weight: normal; text-align: center; margin-bottom: 50px; letter-spacing: 3px;">STAFF LOGIN</h2>

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <input type="email" name="email" placeholder="CORREO ELECTRÓNICO" class="login-input" required>
            <input type="password" name="password" placeholder="CONTRASEÑA" class="login-input" required>
            
            <button type="submit" class="btn-login-submit">Entrar</button>
        </form>

        <div class="register-link-container">
            <span style="color: #8c8c8c;">¿NO TIENES CUENTA?</span> 
            <a href="javascript:void(0)" onclick="cerrarLogin(); abrirRegistro();" class="link-reg">
                REGÍSTRATE
            </a>
        </div>
    </div>
</div>

<script>
    function abrirLogin() {
        const modal = document.getElementById('login-modal');
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function cerrarLogin() {
        const modal = document.getElementById('login-modal');
        modal.classList.remove('active');
        document.body.style.overflow = 'auto';
    }
</script>