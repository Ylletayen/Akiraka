<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Valle de Bravo - Proyecto</title>
    
    <style>
        /* =========================================
           RESET Y TIPOGRAFÍA (Estilo Frida Escobedo)
           ========================================= */
        :root {
            --color-bg: #ffffff;
            --color-text: #000000;
        }

        body {
            /* Fuente clásica Serif, clave para este diseño */
            font-family: "Times New Roman", Times, Baskerville, Georgia, serif;
            background-color: var(--color-bg);
            color: var(--color-text);
            margin: 0;
            padding: 0;
            font-size: 16px;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        a {
            color: var(--color-text);
            text-decoration: none;
        }

        /* =========================================
           ESTRUCTURA DE REJILLA (GRID)
           ========================================= */
        .editorial-grid {
            display: grid;
            grid-template-columns: 8% 8% 54% 30%;
            padding: 2.5rem 3rem;
            min-height: 100vh;
            gap: 1rem;
        }

        /* COLUMNA 1: Navegación Izquierda */
        .col-nav {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: sticky;
            top: 2.5rem;
            height: calc(100vh - 5rem);
        }

        .nav-link {
            display: block;
            transition: opacity 0.3s ease;
        }

        .nav-link:hover {
            opacity: 0.5;
        }

        /* COLUMNA 2: Año */
        .col-year {
            position: sticky;
            top: 2.5rem;
            height: fit-content;
        }

        /* COLUMNA 3: Título e Imágenes */
        .col-main {
            padding-right: 3rem;
        }

        .project-title {
            font-size: 16px;
            font-weight: normal;
            margin: 0 0 3rem 0;
        }

        .image-stack {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        /* Efecto hover sutil para indicar que son clickeables */
        .image-stack img {
            width: 100%;
            height: auto;
            display: block;
            cursor: pointer;
            transition: opacity 0.3s ease;
        }
        
        .image-stack img:hover {
            opacity: 0.9;
        }

        /* COLUMNA 4: Ficha Técnica */
        .col-info {
            position: sticky;
            top: 2.5rem;
            height: fit-content;
        }

        .fact-sheet-list {
            list-style: none;
            padding: 0;
            margin: 0 0 2rem 0;
        }

        .fact-sheet-list li {
            margin-bottom: 0.4rem;
        }

        .italic-text {
            font-style: italic;
        }

        /* =========================================
           CARRUSEL FULLSCREEN (LIGHTBOX)
           ========================================= */
        .carousel-modal {
            display: none; /* Oculto por defecto */
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: var(--color-bg);
            z-index: 9999;
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .carousel-modal.active {
            display: block;
            opacity: 1;
        }

        /* Botón Cerrar en el Carrusel */
        .carousel-close {
            position: absolute;
            top: 2.5rem;
            left: 3rem; /* Alineado a la izquierda como el "back" */
            background: none;
            border: none;
            font-family: inherit;
            font-size: 16px;
            color: var(--color-text);
            cursor: pointer;
            z-index: 10001;
            padding: 0;
        }

        /* Pista de imágenes con scroll magnético */
        .carousel-track {
            display: flex;
            width: 100%;
            height: 100%;
            overflow-x: auto;
            /* Snap para que se sienta como en teléfono */
            scroll-snap-type: x mandatory;
            scroll-behavior: smooth;
            scrollbar-width: none; /* Oculta barra en Firefox */
        }
        
        .carousel-track::-webkit-scrollbar {
            display: none; /* Oculta barra en Chrome/Safari */
        }

        .carousel-slide {
            flex: 0 0 100vw;
            width: 100vw;
            height: 100vh;
            scroll-snap-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            /* Padding para no tapar con las flechas */
            padding: 5rem; 
            box-sizing: border-box;
        }

        .carousel-slide img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        /* Botones de Flechas < y > */
        .carousel-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            font-family: inherit;
            font-size: 24px;
            color: var(--color-text);
            cursor: pointer;
            z-index: 10001;
            padding: 2rem;
            transition: opacity 0.3s ease;
        }

        .carousel-btn:hover {
            opacity: 0.5;
        }

        .carousel-btn.prev {
            left: 1rem;
        }

        .carousel-btn.next {
            right: 1rem;
        }

        /* =========================================
           RESPONSIVE (Móviles)
           ========================================= */
        @media (max-width: 1024px) {
            .editorial-grid {
                grid-template-columns: 10% 15% 75%;
            }
            .col-info {
                display: none;
            }
            .carousel-slide {
                padding: 1rem; /* Menos margen en móvil */
            }
            .carousel-btn {
                padding: 1rem;
            }
            .carousel-close {
                left: 1.5rem;
                top: 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .editorial-grid {
                display: flex;
                flex-direction: column;
                padding: 1.5rem;
            }
            .col-nav {
                position: relative;
                height: auto;
                flex-direction: row;
                top: 0;
                margin-bottom: 2rem;
            }
            .col-year {
                position: relative;
                top: 0;
                margin-bottom: 1rem;
            }
            .col-main {
                padding-right: 0;
            }
            .col-info {
                display: block;
                position: relative;
                top: 0;
                margin-top: 3rem;
            }
        }
    </style>
</head>
<body>

    <div class="editorial-grid">
        
        <!-- COLUMNA 1: Back y Lenguaje -->
        <div class="col-nav">
            <!-- Botón back redirige a la raíz del sitio (Landing Page) -->
            <a href="/" class="nav-link">Regresar</a>
            <a href="#" class="nav-link">Leer en English</a>
        </div>

        <!-- COLUMNA 2: Año -->
        <div class="col-year">
            <span>2025</span>
        </div>

        <!-- COLUMNA 3: Título y Galería -->
        <div class="col-main">
            <h1 class="project-title">Valle de Bravo</h1>
            
            <!-- Apilamiento de imágenes estáticas -->
            <div class="image-stack" id="image-stack">
                <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&q=80&w=1200" alt="Fachada edificio de ladrillo">
                <img src="https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&q=80&w=1200" alt="Detalle interior minimalista">
                <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&q=80&w=1200" alt="Vista del lobby">
                <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&q=80&w=1200" alt="Exterior edificio corporativo">
            </div>
        </div>

        <!-- COLUMNA 4: Ficha Técnica -->
        <div class="col-info">
            <ul class="fact-sheet-list">
                <li>Valle de Bravo, Estado de México, Mexico</li>
                <li>Facade Design: Akira</li>
                <li>Interior Design: Workstead</li>
                <li>Mass Planning: DXA</li>
                <li>Architect of Record: GF55</li>
                <li>Landscape Design: Patrick Cullina</li>
                <li>Design Team: nombre del equipo de diseño, por ejemplo:
                    Amanda Kemeny, Alonso López, Miguel Lucero, 
                    Josue Palma, Adriana Rojas</li>
                <li>Visualizations: Placeholder</li>
                <li>Photos: Placeholder</li>
            </ul>
            
            <p class="italic-text">Fact Sheet</p>
        </div>

    </div>

    <!-- =========================================
         HTML DEL CARRUSEL (Lightbox)
         ========================================= -->
    <div id="carousel-modal" class="carousel-modal">
        <button class="carousel-close" onclick="closeCarousel()">back</button>
        
        <button class="carousel-btn prev" onclick="scrollCarousel(-1)">&#60;</button>
        <button class="carousel-btn next" onclick="scrollCarousel(1)">&#62;</button>
        
        <div class="carousel-track" id="carousel-track">
            <!-- Las imágenes se inyectan dinámicamente aquí vía JS -->
        </div>
    </div>

    <!-- =========================================
         LÓGICA JAVASCRIPT
         ========================================= -->
    <script>
        const modal = document.getElementById('carousel-modal');
        const track = document.getElementById('carousel-track');
        const stackImages = document.querySelectorAll('#image-stack img');

        // 1. Clonar las imágenes del proyecto al carrusel
        stackImages.forEach((img, index) => {
            // Abrir carrusel al hacer click en la imagen normal
            img.addEventListener('click', () => openCarousel(index));
            
            // Crear contenedor de la diapositiva (slide)
            const slide = document.createElement('div');
            slide.className = 'carousel-slide';
            
            // Clonar la imagen
            const clone = img.cloneNode();
            clone.style.cursor = 'default'; // Quitar el puntero de click
            clone.removeAttribute('onclick');
            
            slide.appendChild(clone);
            track.appendChild(slide);
        });

        // 2. Función para abrir el carrusel en una imagen específica
        function openCarousel(index) {
            modal.classList.add('active');
            
            // Deshabilitar scroll del body
            document.body.style.overflow = 'hidden';
            
            // Pequeño timeout para asegurar que el modal es visible antes de scrollear
            setTimeout(() => {
                const slides = document.querySelectorAll('.carousel-slide');
                if(slides[index]) {
                    // Mover el carrusel a la imagen clickeada inmediatamente (sin suavidad para que aparezca directo)
                    slides[index].scrollIntoView({ behavior: 'instant', inline: 'center' });
                }
            }, 10);
        }

        // 3. Función para cerrar el carrusel
        function closeCarousel() {
            modal.classList.remove('active');
            // Restaurar scroll del body
            document.body.style.overflow = 'auto';
        }

        // 4. Función para las flechas < y >
        function scrollCarousel(direction) {
            const slideWidth = window.innerWidth;
            // Scrollear hacia la izquierda o derecha
            track.scrollBy({ 
                left: direction * slideWidth, 
                behavior: 'smooth' 
            });
        }
    </script>

</body>
</html>