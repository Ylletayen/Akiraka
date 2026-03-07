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
    </style>

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
                            <a href="{{ route('project.main', $proyecto->id_proyecto) }}" 
                               class="project-link" 
                               data-img="{{ $proyecto->portada ? asset('storage/' . $proyecto->portada) : 'https://via.placeholder.com/320x220?text=Sin+Imagen' }}">
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
                            <span class="year-label">{{ date('Y') }}</span> 
                            <a href="{{ route('project.main', $proyecto->id_proyecto) }}" 
                               class="project-link" 
                               data-img="{{ $proyecto->portada ? asset('storage/' . $proyecto->portada) : 'https://via.placeholder.com/320x220?text=Sin+Imagen' }}">
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
                <li><span class="year-label">2025</span> <a href="#" class="project-link" data-img="{{ asset('img/silla.jpg') }}">Silla Akira 01</a></li>
                <li><span class="year-label">2024</span> <a href="#" class="project-link" data-img="{{ asset('img/mesa.jpg') }}">Mesa de Concreto Pulido</a></li>
                <li><span class="year-label">2022</span> <a href="#" class="project-link" data-img="{{ asset('img/escultura.jpg') }}">Escultura de Luz</a></li>
            </ul>
        </section>

        <section>
            <h2 class="column-title">Publicaciones</h2>
            <ul class="project-list">
                <li><span class="year-label">2026</span> <a href="#" class="project-link" data-img="{{ asset('img/revista.jpg') }}">Arquitectura Viva: El minimalismo de Akiraka</a></li>
                <li><span class="year-label">2025</span> <a href="#" class="project-link" data-img="{{ asset('img/premios.jpg') }}">Instagram Design Awards</a></li>
                <li><span class="year-label">2023</span> <a href="#" class="project-link" data-img="{{ asset('img/libro.jpg') }}">Libro: Espacios Silenciosos</a></li>
            </ul>
        </section>
    </main>

    <footer class="site-footer-main">
        <div class="footer-left">
            <a href="javascript:void(0)" onclick="regresarAlLanding()">regresar</a>
            <span>2026</span>
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
        // 1. Lógica del Hover Preview (Ya la tenías)
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

        // 2. NUEVO: Lógica del Click para el Modal AJAX
        link.addEventListener('click', function(e) {
            const url = this.getAttribute('href');
            
            // Si el enlace es estático (#), dejamos que actúe normal (no abre modal)
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
</script>