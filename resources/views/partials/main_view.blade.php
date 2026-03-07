<style>
    /* ================= ESTILOS DEL CARRUSEL DE HISTORIA ================= */
    .historia-section {
        padding: clamp(40px, 8vw, 100px);
        background-color: #fdfdfd;
        font-family: "Garamond", "Baskerville", serif;
        color: #111;
    }

    .historia-header {
        margin-bottom: 40px;
        border-bottom: 1px solid #eaeaea;
        padding-bottom: 15px;
    }

    .historia-header h2 {
        font-size: 2rem;
        font-weight: normal;
        margin: 0;
        letter-spacing: 0.05em;
    }

    /* Contenedor principal con Scroll Horizontal Automático */
    .carousel-container {
        display: flex;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        gap: 40px;
        padding-bottom: 20px;
        /* Ocultar barra de scroll para mayor elegancia (opcional) */
        scrollbar-width: none; 
    }
    .carousel-container::-webkit-scrollbar { display: none; }

    /* Cada "Slide" del carrusel */
    .carousel-slide {
        flex: 0 0 100%; /* Ocupa el 100% del ancho visible */
        scroll-snap-align: start;
        display: grid;
        grid-template-columns: 1fr 1fr; /* Mitad imagen, mitad texto */
        gap: 40px;
        align-items: center;
    }

    /* Imagen de la historia */
    .slide-image-wrapper {
        width: 100%;
        height: 60vh; /* Altura controlada */
        overflow: hidden;
        border-radius: 4px;
    }

    .slide-image-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .slide-image-wrapper:hover img {
        transform: scale(1.03); /* Ligero zoom al pasar el mouse */
    }

    /* Texto de la historia */
    .slide-text-wrapper {
        padding: 20px;
    }

    .slide-text-wrapper p {
        font-size: 1.15rem;
        line-height: 1.8;
        color: #444;
        margin-bottom: 20px;
    }

    .slide-counter {
        font-family: Arial, sans-serif;
        font-size: 0.8rem;
        color: #888;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    /* Responsivo para móviles (se apilan uno sobre otro) */
    @media (max-width: 768px) {
        .carousel-slide {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        .slide-image-wrapper { height: 40vh; }
    }
</style>

<section class="historia-section">
    <div class="historia-header">
        <h2>Historia del Proyecto</h2>
    </div>

    <div class="carousel-container">
        
        {{-- Aquí iteraremos las imágenes cuando las mandemos desde el controlador --}}
        {{-- @foreach($imagenes as $index => $imagen) --}}
        
        <div class="carousel-slide">
            <div class="slide-image-wrapper">
                <img src="https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Fase 1">
            </div>
            <div class="slide-text-wrapper">
                <div class="slide-counter">Fase 01</div>
                <p>El terreno presentaba un desnivel pronunciado que dictó la volumetría inicial. Decidimos aprovechar esta pendiente para crear terrazas escalonadas que respetaran la topografía original del lugar, minimizando el impacto ambiental.</p>
            </div>
        </div>

        <div class="carousel-slide">
            <div class="slide-image-wrapper">
                <img src="https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Fase 2">
            </div>
            <div class="slide-text-wrapper">
                <div class="slide-counter">Fase 02</div>
                <p>La selección de materiales se centró en concreto aparente y maderas locales. Esta combinación no solo garantiza la durabilidad estructural, sino que permite que la obra envejezca dignamente, mimetizándose con el entorno boscoso.</p>
            </div>
        </div>

        {{-- @endforeach --}}
    </div>
</section>