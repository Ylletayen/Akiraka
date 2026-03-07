<style>
    /* ================= SIDEBAR (Estilo Original Oscuro Restaurado) ================= */
    .sidebar {
        width: 260px;
        background-color: #1c1c1c; /* Fondo oscuro original */
        color: #fff; /* Texto blanco */
        padding: 30px 25px;
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        z-index: 10;
        box-shadow: 4px 0 15px rgba(0,0,0,0.2); /* Sombra para despegarlo del fondo principal */
    }

    .sidebar .text-center {
        margin-bottom: 2rem;
    }

    .sidebar img.logo-sidebar {
        width: 80px;
        margin-bottom: 15px;
        /* Si tu logo es oscuro y no se ve en el fondo negro, descomenta la siguiente línea: */
        /* filter: brightness(0) invert(1); */ 
    }

    .sidebar p.small {
        color: #ccc; /* Gris claro para el subtítulo */
        font-family: "Helvetica Neue", Arial, sans-serif;
    }

    .sidebar .nav-link {
        color: #e0e0e0; /* Gris muy claro para los enlaces inactivos */
        margin-bottom: 8px;
        display: flex;
        justify-content: space-between; /* Espacio entre el texto y el icono de la derecha si lo hubiera */
        align-items: center;
        background: transparent; /* Fondo transparente por defecto */
        border-radius: 8px;
        padding: 12px 15px;
        transition: all 0.3s ease;
        font-weight: 500;
        text-decoration: none;
        font-family: "Helvetica Neue", Arial, sans-serif;
        font-size: 0.95rem;
    }

    .sidebar .nav-link i {
        color: #888; /* Iconos en gris medio */
        width: 20px;
        text-align: center;
        transition: color 0.3s ease;
    }

    /* Estado Hover y Activo */
    .sidebar .nav-link:hover, 
    .sidebar .nav-link.active { 
        background-color: #2c2c2c; /* Gris oscuro para el hover/activo */
        color: #fff; /* Texto blanco puro */
    }

    .sidebar .nav-link:hover i,
    .sidebar .nav-link.active i {
        color: #fff; /* Iconos blancos al hacer hover/activo */
    }

    .sidebar .icon-badge {
        background-color: #10b981; /* Verde Akiraka (opcional, lo puedes quitar del HTML si ya no lo usan) */
        color: #fff;
        border-radius: 4px;
        padding: 2px 6px;
        font-size: 0.7rem;
    }

    .sidebar form .nav-link {
        color: #e0e0e0;
    }
    
    .sidebar form .nav-link:hover {
        background-color: #3a1c1c; /* Un tono ligeramente rojizo para el botón de salir (opcional) */
        color: #ff6b6b;
    }
    .sidebar form .nav-link:hover i {
        color: #ff6b6b;
    }

    .sidebar .footer-text {
        font-size: 0.7rem;
        color: #666;
        margin-top: 20px;
        border-top: 1px solid #333;
        padding-top: 15px;
    }
</style>

<aside class="sidebar">
    <div>
        <div class="text-center">
            <!-- Asumiendo que logo.png está en public/ -->
            <img src="{{ asset('logo.png') }}" alt="Logo" class="logo-sidebar">
            <p class="small mb-4 text-uppercase" style="letter-spacing: 2px;">Akiraka Estudio</p>
        </div>
        
        <ul class="nav flex-column" style="list-style: none; padding: 0;">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard.main') ? 'active' : '' }}" 
                   href="{{ route('dashboard.main') }}">
                    <div><i class="fas fa-home me-3"></i> Inicio</div>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <div><i class="fas fa-pencil-alt me-3"></i> Proyectos</div>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard.quienes_somos') ? 'active' : '' }}" 
                   href="{{ route('dashboard.quienes_somos') ?? '#' }}">
                    <div><i class="fas fa-users me-3"></i> Quienes somos</div>
                </a>
            </li>
            <li class="nav-item">
               <a class="nav-link {{ request()->routeIs('dashboard.mensajes') ? 'active' : '' }}" 
                 href="{{ route('dashboard.mensajes') }}"><div><i class="fas fa-envelope me-3"></i> Mensajes</div>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard.opciones') ? 'active' : '' }}" 
                   href="{{ route('dashboard.opciones') }}">
                    <div><i class="fas fa-cog me-3"></i> Opciones</div>
                </a>
            </li>
        </ul>
    </div>
    
    <div>
        <form action="{{ route('logout') }}" method="POST" style="margin: 0;" 
              onsubmit="return confirm('¿Estás seguro de que deseas cerrar sesión?');">
            @csrf
            <button type="submit" class="nav-link w-100 text-start" 
                    style="background:none; border:none; cursor:pointer; padding-left: 15px;">
                <div><i class="fas fa-sign-out-alt me-3"></i> Salir</div>
            </button>
        </form>
        <div class="text-center footer-text">
            <p class="mb-1 text-uppercase" style="letter-spacing: 1px;">Akiraka Estudio</p>
            <p class="mb-0">© {{ date('Y') }} Derechos reservados</p>
        </div>
    </div>
</aside>