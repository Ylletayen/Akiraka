<style>
    /* ================= SIDEBAR (Estilo Original Oscuro Restaurado) ================= */
    .sidebar {
        width: 260px;
        background-color: #1c1c1c; 
        color: #fff; 
        padding: 30px 25px;
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        z-index: 10;
        box-shadow: 4px 0 15px rgba(0,0,0,0.2); 
        height: 100vh; /* Aseguramos que ocupe todo el alto para hacer scroll si es necesario */
        overflow-y: auto; /* Scroll interno si hay muchas opciones */
    }

    /* Ocultar barra de scroll para estética limpia */
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

    .sidebar p.small {
        color: #ccc; 
        font-family: "Helvetica Neue", Arial, sans-serif;
    }

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
        color: #888; 
        width: 20px;
        text-align: center;
        transition: color 0.3s ease;
    }

    /* Títulos de Agrupación */
    .sidebar .nav-group-title {
        background-color: #111; /* Un poco más oscuro para diferenciar */
        margin-top: 10px;
        border-left: 3px solid transparent;
    }

    .sidebar .nav-group-title:hover {
        background-color: #222;
        border-left: 3px solid #888;
    }

    .sidebar .nav-arrow {
        font-size: 0.7rem;
        transition: transform 0.3s ease;
    }

    .sidebar .nav-group.open .nav-arrow {
        transform: rotate(180deg);
    }

    /* Contenedor de sub-enlaces (El Acordeón) */
    .sidebar .nav-sub-menu {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease-out;
        padding-left: 15px; /* Indentación para jerarquía */
        border-left: 1px solid #333;
        margin-left: 15px;
        margin-bottom: 5px;
    }

    /* Sub-enlaces más pequeños */
    .sidebar .nav-sub-menu .nav-link {
        padding: 8px 15px;
        font-size: 0.85rem;
        margin-bottom: 2px;
    }

    /* Estado Hover y Activo */
    .sidebar .nav-link:hover, 
    .sidebar .nav-link.active { 
        background-color: #2c2c2c; 
        color: #fff; 
    }

    .sidebar .nav-link:hover i,
    .sidebar .nav-link.active i {
        color: #fff; 
    }

    /* Estilo del botón de salir */
    .sidebar form .nav-link { color: #e0e0e0; }
    .sidebar form .nav-link:hover {
        background-color: #3a1c1c; 
        color: #ff6b6b;
    }
    .sidebar form .nav-link:hover i { color: #ff6b6b; }

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
    function toggleSubmenu(button) {
        // Encontramos el contenedor padre (.nav-group) y el submenú (.nav-sub-menu)
        const navGroup = button.parentElement;
        const subMenu = navGroup.querySelector('.nav-sub-menu');

        // Alternamos la clase 'open' para rotar la flecha
        navGroup.classList.toggle('open');

        // Animamos el max-height para abrir/cerrar suavemente
        if (subMenu.style.maxHeight && subMenu.style.maxHeight !== '0px') {
            subMenu.style.maxHeight = '0px';
        } else {
            subMenu.style.maxHeight = subMenu.scrollHeight + 'px';
        }
    }
</script>