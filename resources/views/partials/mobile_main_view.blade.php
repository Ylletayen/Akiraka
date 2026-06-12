<style>
    /* ================= ESTILOS VISTA MÓVIL ================= */

    /* TOP BAR */
    .mobile-top-bar {
        display: flex;
        justify-content: space-between;
        padding: 20px;
        font-size: 0.85rem;
        font-weight: bold;
        border-bottom: 1px solid transparent; 
        /* MAGIA AQUÍ: Aseguramos que nada bloquee los toques en la pantalla */
        position: relative;
        z-index: 50; 
    }
    .mobile-top-bar a {
        color: #111;
        text-decoration: none;
        text-transform: uppercase;
    }

    /* SLIDER HORIZONTAL (LA MAGIA DEL SWIPE) */
    .mobile-slider-container {
        display: flex;
        overflow-x: auto;
        scroll-snap-type: x mandatory; /* Frena exacto en cada columna */
        -webkit-overflow-scrolling: touch; /* Fluidez nativa en iOS */
        width: 100vw;
        padding: 40px 0;
        gap: 15px;
        position: relative;
        z-index: 10;
    }

    /* Ocultar la barra de scroll para mantener el minimalismo */
    .mobile-slider-container::-webkit-scrollbar {
        display: none;
    }

    /* CADA COLUMNA */
    .slider-section {
        flex: 0 0 85%; /* Ocupa el 85% para dejar ver la siguiente opción */
        scroll-snap-align: center; /* Centra la columna al soltar el dedo */
        padding: 0 20px;
    }

    /* ESTILOS DE TEXTO INTERNOS (Adaptados a tu diseño) */
    .section-title {
        font-size: 0.9rem;
        font-weight: bold;
        text-transform: uppercase;
        margin-bottom: 20px;
        line-height: 1.2;
    }
    .section-title small {
        font-size: 0.8rem;
        font-weight: normal;
        padding-left: 15px;
    }
    .item-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .item-list li {
        font-size: 0.8rem;
        margin-bottom: 12px;
        display: flex;
        gap: 15px;
    }
    .item-list .year {
        color: #888;
        min-width: 35px;
    }
    .item-list a {
        color: #111;
        text-decoration: none;
        text-transform: uppercase;
        display: block;
        width: 100%;
    }
</style>

<div class="mobile-layout d-block d-md-none">
    
    <nav class="mobile-top-bar">
        <div class="brand">
            {{-- Rutas corregidas --}}
            <a href="{{ route('project.detail') ?? '#' }}">Estudio Akiraka ,</a>
        </div>
        <div class="links">
            <a href="{{ route('info') ?? '#' }}">Info</a>
            <span>,</span>
            <a href="{{ route('contacto') ?? '#' }}">Contacto</a>
        </div>
    </nav>

    <div class="mobile-slider-container">
        
        <div class="slider-section">
            <h2 class="section-title">Obras<br><small>Proyectos</small></h2>
            <ul class="item-list">
                {{-- Ciclo Dinámico de Obras en Proceso --}}
                @forelse($proyectosEnProceso ?? [] as $proyecto)
                    <li><span class="year">PROC</span> <a href="{{ route('project.main', $proyecto->id_proyecto) }}">{{ $proyecto->titulo }}</a></li>
                @empty
                @endforelse
                
                {{-- Ciclo Dinámico de Obras Construidas --}}
                @forelse($proyectosConstruidos ?? [] as $proyecto)
                    <li><span class="year">{{ $proyecto->anio ?? 'S/A' }}</span> <a href="{{ route('project.main', $proyecto->id_proyecto) }}">{{ $proyecto->titulo }}</a></li>
                @empty
                    <li><span style="color: #ccc; font-style: italic;">Ningún proyecto disponible.</span></li>
                @endforelse
            </ul>
        </div>

        <div class="slider-section">
            <h2 class="section-title">Objetos<br>&nbsp;</h2>
            <ul class="item-list">
                {{-- Ciclo Dinámico de Objetos --}}
                @forelse($objetos ?? [] as $objeto)
                    <li><span class="year">{{ $objeto->anio ?? 'S/A' }}</span> <a href="{{ route('objeto.main', $objeto->id_objeto) }}">{{ $objeto->titulo }}</a></li>
                @empty
                    <li><span style="color: #ccc; font-style: italic;">Ningún objeto en exhibición.</span></li>
                @endforelse
            </ul>
        </div>

        <div class="slider-section">
            <h2 class="section-title">Publicaciones<br>&nbsp;</h2>
            <ul class="item-list">
                {{-- Ciclo Dinámico de Publicaciones --}}
                @forelse($publicaciones ?? [] as $publicacion)
                    <li><span class="year">{{ \Carbon\Carbon::parse($publicacion->fecha)->format('Y') }}</span> <a href="{{ route('publicaciones.show', $publicacion->id_publicacion) }}">{{ $publicacion->titulo }}</a></li>
                @empty
                    <li><span style="color: #ccc; font-style: italic;">Ninguna publicación disponible.</span></li>
                @endforelse
            </ul>
        </div>

    </div>
</div>