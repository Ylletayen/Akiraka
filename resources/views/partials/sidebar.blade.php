<aside class="sidebar">
    <div>
        <div class="text-center">
            <img src="{{ asset('logo.png') }}" alt="Logo" class="logo-sidebar">
            <p class="small mb-4 text-uppercase" style="letter-spacing: 2px;">Akiraka Estudio</p>
        </div>
        
        <ul class="nav flex-column" style="list-style: none; padding: 0;">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard.main') ? 'active' : '' }}" 
                   href="{{ route('dashboard.main') }}">
                    <i class="fas fa-home me-2"></i> Inicio
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="fas fa-pencil-alt me-2"></i> Proyectos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard.quienes_somos') ? 'active' : '' }}" 
                   href="{{ route('dashboard.quienes_somos') }}">
                    <i class="fas fa-users me-2"></i> Quienes somos
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('mensajes') ? 'active' : '' }}" 
                   href="{{ route('mensajes') }}">
                    <i class="fas fa-envelope me-2"></i> Mensajes
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard.opciones') ? 'active' : '' }}" 
                   href="{{ route('dashboard.opciones') }}">
                    <i class="fas fa-cog me-2"></i> Opciones
                </a>
            </li>
        </ul>
    </div>
    
    <div>
        <form action="{{ route('logout') }}" method="POST" style="margin: 0;" 
              onsubmit="return confirm('¿Estás seguro de que deseas cerrar sesión?');">
            @csrf
            <button type="submit" class="nav-link border-top pt-3 w-100 text-start" 
                    style="background:none; border:none; cursor:pointer;">
                <i class="fas fa-sign-out-alt me-2"></i> Salir
            </button>
        </form>
        <div class="mt-4 text-center" style="font-size: 0.75rem; color: #888;">
            <p class="mb-1 text-uppercase">Akiraka Estudio</p>
            <p class="mb-0">© {{ date('Y') }} Derechos reservados</p>
        </div>
    </div>
</aside>