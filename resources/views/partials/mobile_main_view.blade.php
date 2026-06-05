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
    }
    .item-list a {
        color: #111;
        text-decoration: none;
        text-transform: uppercase;
    }
</style>

<div class="mobile-layout d-block d-md-none">
    
    <nav class="mobile-top-bar">
        <div class="brand">
            <a href="#">Estudio Akiraka</a>
        </div>
        <div class="links">
            <a href="#">Info</a>
            <span>,</span>
            <a href="#">Contacto</a>
        </div>
    </nav>

    <div class="mobile-slider-container">
        
        <div class="slider-section">
            <h2 class="section-title">Obras<br><small>Proyectos</small></h2>
            <ul class="item-list">
                <li><span class="year">2026</span> <a href="#">ASAI</a></li>
                <li><span class="year">2026</span> <a href="#">MARKETING</a></li>
                <li><span class="year">2026</span> <a href="#">MEETING ROOM</a></li>
                <li><span class="year">2026</span> <a href="#">OJON DE CITA</a></li>
                <li><span class="year">2026</span> <a href="#">REVILLAGIGEDO</a></li>
                <li><span class="year">2026</span> <a href="#">SUKIYA GENOVA</a></li>
            </ul>
        </div>

        <div class="slider-section">
            <h2 class="section-title">Objetos<br>&nbsp;</h2>
            <ul class="item-list">
                <li><span class="year">2026</span> <a href="#">Mesa Akiraka</a></li>
                <li><span class="year">2025</span> <a href="#">Silla Akika</a></li>
            </ul>
        </div>

        <div class="slider-section">
            <h2 class="section-title">Publicaciones<br>&nbsp;</h2>
            <ul class="item-list">
                <li><span class="year">2026</span> <a href="#">Story time house mountain</a></li>
            </ul>
        </div>

    </div>
</div>