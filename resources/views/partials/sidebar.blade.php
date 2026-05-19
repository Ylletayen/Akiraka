<style>
    /* ================= SIDEBAR FIJO (Escritorio) ================= */
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        width: 260px;
        background-color: #1c1c1c; 
        color: #fff; 
        padding: 30px 25px;
        border-radius: 0; 
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        z-index: 1000; 
        box-shadow: 4px 0 15px rgba(0,0,0,0.2); 
        height: 100vh;
        overflow-y: auto;
        transition: transform 0.3s ease; /* Transición suave para el móvil */
    }

    .sidebar::-webkit-scrollbar { width: 4px; }
    .sidebar::-webkit-scrollbar-thumb { background-color: #333; border-radius: 4px; }

    .sidebar .text-center {
        margin-bottom: 2rem;
        display: flex;
        flex-direction: column;
        align-items: center; 
    }

    .sidebar img.logo-sidebar {
        width: 100px; 
        height: 100px;
        object-fit: contain;
        background-color: #ffffff; 
        border-radius: 50%; 
        padding: 22px 15px; 
        margin-bottom: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25); 
    }

    .sidebar p.small { color: #ccc; font-family: "Helvetica Neue", Arial, sans-serif; }

    /* Enlaces base */
    .sidebar .nav-link, .sidebar .nav-group-title {
        color: #e0e0e0; 
        margin-bottom: 5px;
        display: flex;
        justify-content: space-between; 
        align-items: center;
        background: transparent; 
        border-radius: 8px;
        padding: 12px 15px;
        transition: all 0.3s ease;
        font-weight: 500;
        text-decoration: none;
        font-family: "Helvetica Neue", Arial, sans-serif;
        font-size: 0.95rem;
        cursor: pointer;
        border: none;
        width: 100%;
        text-align: left;
    }

    .sidebar .nav-link i, .sidebar .nav-group-title i {
        color: #888; width: 20px; text-align: center; transition: color 0.3s ease;
    }

    /* Títulos de Agrupación */
    .sidebar .nav-group-title { background-color: #111; margin-top: 10px; border-left: 3px solid transparent; }
    .sidebar .nav-group-title:hover { background-color: #222; border-left: 3px solid #888; }
    .sidebar .nav-arrow { font-size: 0.7rem; transition: transform 0.3s ease; }
    .sidebar .nav-group.open .nav-arrow { transform: rotate(180deg); }

    /* Sub-enlaces */
    .sidebar .nav-sub-menu {
        max-height: 0; overflow: hidden; transition: max-height 0.3s ease-out;
        padding-left: 15px; border-left: 1px solid #333; margin-left: 15px; margin-bottom: 5px;
    }
    .sidebar .nav-sub-menu .nav-link { padding: 8px 15px; font-size: 0.85rem; margin-bottom: 2px; }
    .sidebar .nav-link:hover, .sidebar .nav-link.active { background-color: #2c2c2c; color: #fff; }
    .sidebar .nav-link:hover i, .sidebar .nav-link.active i { color: #fff; }

    /* Botón salir y Footer */
    .sidebar form .nav-link { color: #e0e0e0; }
    .sidebar form .nav-link:hover { background-color: #3a1c1c; color: #ff6b6b; }
    .sidebar form .nav-link:hover i { color: #ff6b6b; }
    .sidebar .footer-text { font-size: 0.7rem; color: #666; margin-top: 20px; border-top: 1px solid #333; padding-top: 15px; }

    /* =====================================================================
       MAGIA: COMPENSACIÓN GLOBAL Y VERSIÓN MÓVIL
       ===================================================================== */
    .dash-admin-view { padding: 0 !important; display: block !important; }
    .dashboard-container { display: block !important; width: 100% !important; max-width: 100% !important; }

    .main-content {
        margin-left: 260px !important; 
        width: calc(100% - 260px) !important; 
        min-height: 100vh;
        border-radius: 0 !important; 
        box-sizing: border-box;
    }

    /* ELEMENTOS EXCLUSIVOS DE MÓVIL (Ocultos en PC) */
    .mobile-menu-btn {
        display: none; position: fixed; top: 15px; left: 15px; z-index: 998;
        background: #111; color: #fff; border: none; padding: 10px 15px;
        border-radius: 8px; cursor: pointer; font-size: 1.2rem;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }
    
    .sidebar-overlay {
        display: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
        background: rgba(0,0,0,0.5); backdrop-filter: blur(3px); z-index: 999;
        opacity: 0; transition: opacity 0.3s ease;
    }

    /* MEDIA QUERY PARA CELULARES Y TABLETS (< 992px) */
    @media (max-width: 992px) {
        .mobile-menu-btn { display: block; } /* Mostramos el botón de hamburguesa */
        
        .main-content {
            margin-left: 0 !important; /* El contenido ocupa todo el ancho */
            width: 100% !important;
            padding-top: 70px !important; /* Espacio para que el contenido no quede debajo del botón */
        }

        .sidebar {
            transform: translateX(-100%); /* Escondemos el menú hacia la izquierda */
            width: 280px; /* Un poco más ancho para que sea fácil tocar con el dedo */
        }

        /* Clases que se activan con JavaScript al tocar el botón */
        .sidebar.active-mobile {
            transform: translateX(0); /* El menú entra a la pantalla */
        }

        .sidebar-overlay.active-mobile {
            display: block; opacity: 1; /* Aparece la capa oscura de fondo */
        }
    }
</style>

<button class="mobile-menu-btn" onclick="toggleMobileMenu()">
    <i class="fas fa-bars"></i>
</button>

<div class="sidebar-overlay" onclick="toggleMobileMenu()"></div>

<aside class="sidebar">
    <div>
        <div class="text-center">
            <img src="{{ asset('images/logo_akiraka.png') }}" alt="Logo Akiraka" class="logo-sidebar">
            <p class="small mb-4 text-uppercase" style="letter-spacing: 2px;">Akiraka Estudio</p>
        </div>
        
        <ul class="nav flex-column" style="list-style: none; padding: 0;">
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard.main') ? 'active' : '' }}" href="{{ route('dashboard.main') }}">
                    <div><i class="fas fa-home me-3"></i> Inicio</div>
                </a>
            </li>
            
            @php
                $isContentActive = request()->routeIs('dashboard.servicios') || request()->routeIs('dashboard.proyectos') || request()->routeIs('dashboard.publicaciones') || request()->routeIs('dashboard.objetos');
            @endphp
            <li class="nav-item nav-group {{ $isContentActive ? 'open' : '' }}">
                <button class="nav-group-title" onclick="toggleSubmenu(this)">
                    <div><i class="fas fa-layer-group me-3"></i> Contenido</div>
                    <i class="fas fa-chevron-down nav-arrow"></i>
                </button>
                <div class="nav-sub-menu" style="{{ $isContentActive ? 'max-height: 500px;' : '' }}">
                    <a class="nav-link {{ request()->routeIs('dashboard.proyectos') ? 'active' : '' }}" href="{{ route('dashboard.proyectos') }}">
                        <div><i class="fas fa-building me-3"></i> Proyectos</div>
                    </a>
                    <a class="nav-link {{ request()->routeIs('dashboard.objetos') ? 'active' : '' }}" href="{{ route('dashboard.objetos') }}">
                        <div><i class="fas fa-chair me-3"></i> Objetos</div>
                    </a>
                    <a class="nav-link {{ request()->routeIs('dashboard.publicaciones') ? 'active' : '' }}" href="{{ route('dashboard.publicaciones') }}">
                        <div><i class="fas fa-newspaper me-3"></i> Publicaciones</div>
                    </a>
                
                    @if(in_array(Auth::user()->id_rol, [1, 2]))
                    <a class="nav-link {{ request()->routeIs('dashboard.servicios') ? 'active' : '' }}" href="{{ route('dashboard.servicios') }}">
                        <div><i class="fas fa-drafting-compass me-3"></i> Servicios</div>
                    </a>
                    @endif
                </div>
            </li>

            @php
                $isClientActive = request()->routeIs('mensajes') || request()->routeIs('dashboard.citas');
            @endphp
            <li class="nav-item nav-group {{ $isClientActive ? 'open' : '' }}">
                <button class="nav-group-title" onclick="toggleSubmenu(this)">
                    <div><i class="fas fa-headset me-3"></i> Atención</div>
                    <i class="fas fa-chevron-down nav-arrow"></i>
                </button>
                <div class="nav-sub-menu" style="{{ $isClientActive ? 'max-height: 500px;' : '' }}">
                    <a class="nav-link {{ request()->routeIs('dashboard.citas') ? 'active' : '' }}" href="{{ route('dashboard.citas') }}">
                        <div><i class="fas fa-calendar-alt me-3"></i> Prospectos / Citas</div>
                    </a>
                    
                </div>
            </li>
            @php
                $isAdminActive = request()->routeIs('dashboard.usuarios') || request()->routeIs('dashboard.equipo.quienes_somos') || request()->routeIs('dashboard.opciones');
            @endphp
            <li class="nav-item nav-group {{ $isAdminActive ? 'open' : '' }}">
                <button class="nav-group-title" onclick="toggleSubmenu(this)">
                    <div><i class="fas fa-tools me-3"></i> Administración</div>
                    <i class="fas fa-chevron-down nav-arrow"></i>
                </button>
                <div class="nav-sub-menu" style="{{ $isAdminActive ? 'max-height: 500px;' : '' }}">
                    
                    @if(in_array(Auth::user()->id_rol, [1, 2]))
                    <a class="nav-link {{ request()->routeIs('dashboard.usuarios') ? 'active' : '' }}" href="{{ route('dashboard.usuarios') }}">
                        <div><i class="fas fa-user-shield me-3"></i> Usuarios</div>
                    </a>
                    @endif

                    @if(in_array(Auth::user()->id_rol, [1, 2]))
                    <a class="nav-link {{ request()->routeIs('dashboard.equipo.quienes_somos') ? 'active' : '' }}" href="{{ route('dashboard.equipo.quienes_somos') ?? '#' }}">
                        <div><i class="fas fa-users me-3"></i> Equipo</div>
                    </a>
                    @endif

                    <a class="nav-link {{ request()->routeIs('dashboard.opciones') ? 'active' : '' }}" href="{{ route('dashboard.opciones') }}">
                        <div><i class="fas fa-cog me-3"></i> Conf. General</div>
                    </a>
                </div>
            </li>

        </ul>
    </div>
    
    <div>
        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
            @csrf
            <button type="submit" class="nav-link w-100 text-start" style="background:none; border:none; cursor:pointer; padding-left: 15px;">
                <div><i class="fas fa-sign-out-alt me-3"></i> Salir</div>
            </button>
        </form>
        <div class="text-center footer-text">
            <p class="mb-1 text-uppercase" style="letter-spacing: 1px;">Akiraka Estudio</p>
            <p class="mb-0">© {{ date('Y') }} Derechos reservados</p>
        </div>
    </div>
</aside>

<script>
    // Función para manejar los acordeones del menú
    function toggleSubmenu(button) {
        const navGroup = button.parentElement;
        const subMenu = navGroup.querySelector('.nav-sub-menu');

        navGroup.classList.toggle('open');

        if (subMenu.style.maxHeight && subMenu.style.maxHeight !== '0px') {
            subMenu.style.maxHeight = '0px';
        } else {
            subMenu.style.maxHeight = subMenu.scrollHeight + 'px';
        }
    }

    // Nueva función para abrir/cerrar el menú en celulares
    function toggleMobileMenu() {
        const sidebar = document.querySelector('.sidebar');
        const overlay = document.querySelector('.sidebar-overlay');
        
        sidebar.classList.toggle('active-mobile');
        overlay.classList.toggle('active-mobile');
    }
</script>