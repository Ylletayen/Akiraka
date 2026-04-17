<div id="project-view" class="akira-project-view">

    <style>
        /* --- TUS ESTILOS EXISTENTES --- */
        .akira-project-view {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #fdfdfd !important;
            padding: clamp(30px, 5vw, 60px);
            font-family: "Garamond", "Baskerville", "Times New Roman", serif !important;
            color: #111111 !important;
        }

        .akira-project-view a {
            text-decoration: none !important;
            color: #111111 !important;
            transition: color 0.3s ease;
        }

        .akira-project-view a:hover {
            color: #8c8c8c !important;
        }

        .akira-project-view ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .site-header-main {
            margin-bottom: clamp(60px, 8vh, 120px);
            font-size: 1.1rem;
        }

        .brand-name { font-weight: 600; }
        .nav-links-main { color: #8c8c8c; }

        .main-content-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: clamp(30px, 4vw, 80px);
            flex-grow: 1;
            margin-bottom: clamp(60px, 8vh, 120px);
        }

        .column-title {
            font-weight: normal;
            font-size: 1.05rem;
            margin-bottom: 2rem;
            letter-spacing: 0.03em;
        }

        .project-list li {
            margin-bottom: 0.6rem;
            font-size: 0.95rem;
            display: flex;
        }

        .list-group { margin-bottom: 2.5rem; }
        .indent-1 { padding-left: 2rem; }
        .indent-2 { padding-left: 4rem; }

        .year-label {
            display: inline-block;
            min-width: 3.5rem;
            color: #8c8c8c;
            font-size: 0.9rem;
        }

        .site-footer-main {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.95rem;
            color: #8c8c8c;
            padding-bottom: 20px;
        }

        .footer-left {
            display: flex;
            gap: 40px;
        }

        /* --- NUEVO: ESTILOS PARA EL BOTÓN DE REGRESAR FLOTANTE --- */
        .btn-flotante-regresar {
            position: fixed;
            bottom: clamp(20px, 4vh, 40px); /* Fijo en la parte inferior */
            left: clamp(30px, 5vw, 60px);   /* Alineado con tu margen izquierdo general */
            font-weight: bold;
            font-size: 0.95rem;
            color: #111111 !important;
            text-decoration: underline !important;
            z-index: 9999; /* Asegura que esté por encima de todo */
            background-color: rgba(253, 253, 253, 0.85); /* Fondo difuminado para legibilidad */
            backdrop-filter: blur(5px);
            padding: 8px 15px 8px 0;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .btn-flotante-regresar:hover {
            color: #8c8c8c !important;
            transform: translateX(-5px); /* Efecto sutil al pasar el mouse */
        }

        /* --- ESTILOS PARA EL HOVER PREVIEW --- */
        .hover-preview {
            position: fixed;
            pointer-events: none;
            width: 320px;
            height: 220px;
            overflow: hidden;
            opacity: 0;
            z-index: 10000;
            transform: translate(15px, -50%);
            transition: opacity 0.4s ease, transform 0.2s ease-out;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .hover-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .hover-preview.active {
            opacity: 1;
        }

        /* --- ESTILOS DEL MODAL A PANTALLA COMPLETA --- */
        .akira-modal-fullscreen {
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            background: #fdfdfd; 
            z-index: 100000;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.4s ease;
            overflow-y: auto;
            padding-top: 60px; 
        }

        .akira-modal-fullscreen.active {
            opacity: 1;
            pointer-events: auto;
        }

        .modal-close-btn {
            position: fixed;
            top: 30px; right: 40px;
            font-size: 1.5rem;
            cursor: pointer;
            z-index: 100001;
            background: none; border: none;
            font-family: Arial, sans-serif;
            color: #111;
            transition: transform 0.3s ease;
        }

        .modal-close-btn:hover {
            transform: scale(1.1);
        }

        /* Responsivo básico */
        @media (max-width: 900px) {
            .main-content-grid { grid-template-columns: repeat(2, 1fr); }
            .hover-preview { display: none; }
        }

        @media (max-width: 600px) {
            .main-content-grid { grid-template-columns: 1fr; gap: 50px; }
            .indent-2 { padding-left: 2rem; }
            .site-footer-main { flex-direction: column; align-items: flex-start; gap: 15px; }
        }

        /* --- ANIMACIONES AL SCROLLEAR (FADE UP) --- */
        .akira-fade-up {
            opacity: 0;
            transform: translateY(25px);
            transition: opacity 0.7s ease-out, transform 0.7s ease-out;
        }

        .akira-fade-up.is-visible {
            opacity: 1;
            transform: translateY(0); 
        }
    </style>

    <!-- ¡NUEVO BOTÓN FLOTANTE QUE SIGUE AL USUARIO! -->
    <a href="{{ route('landing') }}" class="btn-flotante-regresar">&larr; regresar</a>

    <header class="site-header-main">
        <a href="{{ route('project.detail') }}" style="text-decoration: none; color: #1a1a1a; font-weight: bold;">
        Estudio Akiraka ,</a>
        <a href="{{ route('info') }}" class="nav-link-akira">Info ,</a>
        <a href="{{ route('contacto') }}" class="nav-link-akira">Contacto</a>
    </header>

    <main class="main-content-grid">
        <section>
            <h2 class="column-title">Obras</h2>
            
            <div class="list-group">
                <ul class="project-list">
                    <li>Proyectos</li>
                    <li class="indent-1">En proceso</li>
                    @forelse($proyectosEnProceso as $proyecto)
                        <li class="indent-2">
                            <a href="{{ route('project.main', $proyecto->id_proyecto) }}" class="project-link" data-img="{{ $proyecto->portada ? asset('storage/' . $proyecto->portada) : 'https://via.placeholder.com/320x220?text=Sin+Imagen' }}">
                                {{ $proyecto->titulo }}
                            </a>
                        </li>
                    @empty
                        <li class="indent-2" style="color: #ccc; font-style: italic; font-size: 0.85rem;">Ningún proyecto en proceso.</li>
                    @endforelse
                </ul>
            </div>

            <div class="list-group">
                <ul class="project-list">
                    <li>Construidos</li>
                    @forelse($proyectosConstruidos as $proyecto)
                        <li>
                            <span class="year-label">{{ $proyecto->anio ?? 'S/A' }}</span> 
                            <a href="{{ route('project.main', $proyecto->id_proyecto) }}" class="project-link" data-img="{{ $proyecto->portada ? asset('storage/' . $proyecto->portada) : 'https://via.placeholder.com/320x220?text=Sin+Imagen' }}">
                                {{ $proyecto->titulo }}
                            </a>
                        </li>
                    @empty
                        <li style="color: #ccc; font-style: italic; font-size: 0.85rem;">Ningún proyecto finalizado.</li>
                    @endforelse
                </ul>
            </div>
        </section>

        <section>
            <h2 class="column-title">Objetos</h2>
            <ul class="project-list">
                @forelse($objetos as $objeto)
                    <li>
                        <span class="year-label">{{ $objeto->anio ?? 'S/A' }}</span> 
                        <a href="{{ route('objeto.main', $objeto->id_objeto) }}" class="project-link" data-img="{{ $objeto->portada ? asset('storage/' . $objeto->portada) : 'https://via.placeholder.com/320x220?text=Sin+Imagen' }}">
                            {{ $objeto->titulo }}
                        </a>
                    </li>
                @empty
                    <li style="color: #ccc; font-style: italic; font-size: 0.85rem;">Ningún objeto en exhibición.</li>
                @endforelse
            </ul>
        </section>

        <section>
            <h2 class="column-title">Publicaciones</h2>
            
            <ul class="project-list">
                @forelse($publicaciones as $publicacion)
                    <li>
                        <span class="year-label">{{ \Carbon\Carbon::parse($publicacion->fecha)->format('Y') }}</span> 
                        
                        <a href="{{ route('publicaciones.show', $publicacion->id_publicacion) }}" class="project-link" data-img="{{ $publicacion->portada ? asset('storage/' . $publicacion->portada) : 'https://via.placeholder.com/320x220?text=Publicación' }}">
                            {{ $publicacion->titulo }}
                        </a>
                    </li>
                @empty
                    <li style="color: #ccc; font-style: italic; font-size: 0.85rem;">Ninguna publicación disponible.</li>
                @endforelse
            </ul>

            @include('Principal.cita')
        </section>

    </main>

    <footer class="site-footer-main">
        <div class="footer-left">
            <!-- Desplazamos el año ligeramente para que no choque con el botón flotante al llegar al fondo -->
            <span style="padding-left: 100px;">2026</span>
        </div>
        <a href="#">Read in English</a>
    </footer>

    <div id="hover-preview" class="hover-preview">
        <img src="" alt="Preview" id="preview-img">
    </div>

    <div id="historia-modal" class="akira-modal-fullscreen">
        <button class="modal-close-btn" onclick="cerrarModalHistoria()">✕</button>
        <div id="historia-content"></div>
    </div>

</div>

<script>
    const previewContainer = document.getElementById('hover-preview');
    const previewImg = document.getElementById('preview-img');
    const projectLinks = document.querySelectorAll('.project-link');
    
    // Variables para el Modal
    const modalHistoria = document.getElementById('historia-modal');
    const modalContent = document.getElementById('historia-content');

    projectLinks.forEach(link => {
        // 1. Lógica del Hover Preview
        link.addEventListener('mouseenter', () => {
            const imageSrc = link.getAttribute('data-img');
            if(imageSrc) {
                previewImg.src = imageSrc;
                previewContainer.classList.add('active');
            }
        });

        link.addEventListener('mousemove', (e) => {
            previewContainer.style.left = e.clientX + 'px';
            previewContainer.style.top = e.clientY + 'px';
        });

        link.addEventListener('mouseleave', () => {
            previewContainer.classList.remove('active');
        });

        // 2. Lógica del Click para el Modal AJAX
        link.addEventListener('click', function(e) {
            const url = this.getAttribute('href');
            
            // Si el enlace es estático (#), dejamos que actúe normal
            if(url === '#' || url === '') return;

            // Si es una ruta válida, detenemos la redirección y abrimos el modal
            e.preventDefault(); 
            
            modalHistoria.classList.add('active');
            modalContent.innerHTML = '<div style="text-align:center; padding-top: 20vh; font-family: Garamond, serif; font-size: 1.5rem; color: #888;">Cargando historia...</div>';

            // Petición al servidor
            fetch(url)
                .then(response => response.text())
                .then(html => {
                    modalContent.innerHTML = html;

                    // MAGIA: Ejecutar la animación de las fotos desde aquí
                    const slides = modalContent.querySelectorAll('.akira-slide');
                    if(slides.length > 0) {
                        const observerAnim = new IntersectionObserver((entries) => {
                            entries.forEach(entry => {
                                if (entry.isIntersecting) {
                                    entry.target.classList.add('is-visible');
                                } else {
                                    entry.target.classList.remove('is-visible');
                                }
                            });
                        }, { threshold: 0.5 });
                        
                        slides.forEach(slide => observerAnim.observe(slide));
                    }
                })
                .catch(error => {
                    modalContent.innerHTML = '<div style="text-align:center; padding-top: 20vh; color: #d9534f;">Hubo un error al cargar la historia.</div>';
                    console.error('Error:', error);
                });
        }); 
    }); 

    // Función para cerrar el modal AJAX
    function cerrarModalHistoria() {
        modalHistoria.classList.remove('active');
        // Limpiamos el contenido para que el próximo proyecto empiece en blanco
        setTimeout(() => { modalContent.innerHTML = ''; }, 400);
    }

    function regresarAlLanding() {
        window.location.href = "{{ url('/') }}"; 
    }

    // =========================================================
    // ANIMACIÓN DE SCROLL PARA EL MENÚ (FADE UP)
    // =========================================================
    // Seleccionamos todos los elementos que queremos animar
    const scrollElements = document.querySelectorAll('.column-title, .project-list li');
    
    // A todos les ponemos la clase inicial de la animación
    scrollElements.forEach(el => {
        el.classList.add('akira-fade-up');
    });

    // Creamos el observador
    const menuObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Le damos un ligerísimo retraso basado en su posición para que no aparezcan de golpe
                setTimeout(() => {
                    entry.target.classList.add('is-visible');
                }, 50); 
                
                // Opcional: dejamos de observarlo para que la animación solo ocurra la primera vez que bajas
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1, // Se activa cuando al menos el 10% del texto ya entró a la pantalla
        rootMargin: "0px 0px -20px 0px" // Un pequeño margen inferior para que el efecto se note mejor
    });

    // Ponemos al observador a vigilar cada elemento
    scrollElements.forEach(el => {
        menuObserver.observe(el);
    });
</script>