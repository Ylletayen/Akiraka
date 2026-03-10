<style>
    /* =========================================
       TOPBAR (Minimalista, Seamless)
       ========================================= */
    .topbar {
        background-color: #ffffff; /* Blanco puro para fusionarse con el fondo */
        height: 70px;
        display: flex;
        justify-content: flex-end; /* Empuja todo a la derecha ya que quitamos el logo izquierdo */
        align-items: center;
        padding: 0 40px;
        border-bottom: 1px solid #f5f5f5; /* Borde casi invisible solo para dar estructura */
        position: sticky;
        top: 0;
        z-index: 100;
    }

    .topbar-right {
        display: flex;
        align-items: center;
        gap: 30px; /* Separación entre la campana y el perfil */
    }

    /* --- Notificaciones (Campanita) --- */
    .topbar-notifications {
        position: relative;
        cursor: pointer;
        color: #888;
        font-size: 1.2rem;
        transition: color 0.3s ease;
    }

    .topbar-notifications:hover {
        color: #111;
    }

    .notification-badge {
        position: absolute;
        top: -6px;
        right: -8px;
        background-color: #111; /* Color oscuro Akiraka */
        color: #fff;
        font-size: 0.6rem;
        font-family: "Helvetica Neue", Arial, sans-serif;
        font-weight: bold;
        padding: 2px 5px;
        border-radius: 10px;
        border: 2px solid #fff; /* Borde blanco para separarlo de la campana */
    }

    /* --- Info del Usuario --- */
    .topbar-user {
        display: flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
    }

    .topbar-user-info {
        display: flex;
        flex-direction: column;
        text-align: right;
    }

    .topbar-user-name {
        font-size: 0.85rem;
        font-weight: bold;
        color: #111;
        font-family: "Helvetica Neue", Arial, sans-serif;
    }

    .topbar-user-role {
        font-size: 0.65rem;
        color: #888;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-family: "Helvetica Neue", Arial, sans-serif;
        margin-top: 2px;
    }

    .topbar-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        background-color: #f4f4f4;
        border: 1px solid #eaeaea;
        transition: transform 0.3s ease;
    }

    .topbar-user:hover .topbar-avatar {
        transform: scale(1.05);
        border-color: #111;
    }

    /* Ocultar el texto en celulares para que no se amontone */
    @media (max-width: 768px) {
        .topbar-user-info {
            display: none;
        }
        .topbar {
            padding: 0 20px;
        }
    }
</style>

<header class="topbar">
    
    <!-- Parte Derecha: Campana y Perfil (La parte izquierda fue eliminada) -->
    <div class="topbar-right">
        
        

        <!-- Perfil del Administrador (Dinámico) -->
        <div class="topbar-user">
            <div class="topbar-user-info">
                <!-- Obtiene el nombre del usuario logueado -->
                <span class="topbar-user-name">{{ Auth::user()->nombre ?? 'Usuario' }}</span>
                
                <!-- Traduce el ID del rol a texto -->
                <span class="topbar-user-role">
                    @if(Auth::check())
                        @if(Auth::user()->id_rol == 1) Superadmin
                        @elseif(Auth::user()->id_rol == 2) Administrador
                        @else Colaborador @endif
                    @else
                        Staff
                    @endif
                </span>
            </div>
            
            <!-- Foto de perfil dinámica -->
            <img src="{{ Auth::check() && Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : asset('images/default-avatar.png') }}" alt="Avatar" class="topbar-avatar">
        </div>

    </div>

</header>