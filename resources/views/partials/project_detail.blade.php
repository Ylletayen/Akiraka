@extends('layouts.app')

@section('content')
<div id="project-view" class="akira-project-view">

    <style>
        /* ================= ESTILOS GENERALES ================= */
        .akira-project-view {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #fdfdfd !important;
            padding: clamp(30px, 5vw, 60px);
            font-family: "Garamond", "Baskerville", "Times New Roman", serif !important;
            color: #111111 !important;
            /* EVITAR DESBORDAMIENTOS EN MÓVIL */
            box-sizing: border-box;
            width: 100%;
            max-width: 100vw;
            overflow-x: hidden;
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

        /* --- BOTÓN DE REGRESAR FLOTANTE --- */
        .btn-flotante-regresar {
            position: fixed;
            bottom: clamp(20px, 4vh, 40px);
            left: clamp(30px, 5vw, 60px);
            font-weight: bold;
            font-size: 0.95rem;
            color: #111111 !important;
            text-decoration: underline !important;
            z-index: 9999;
            background-color: rgba(253, 253, 253, 0.85);
            backdrop-filter: blur(5px);
            padding: 8px 15px 8px 0;
            border-radius: 4px;
            transition: all 0.3s ease;
        }
        .btn-flotante-regresar:hover { transform: translateX(-5px); }

        /* ================= CONTROL DE VISTAS (MOBILE-FIRST) ================= */
        /* 1. POR DEFECTO: Todos ven el diseño móvil. */
        .vista-escritorio { display: none !important; }
        .vista-movil { display: block !important; width: 100%; }

        /* 2. EXCEPCIÓN: Solo si la pantalla es mayor a 850px (Monitor real), se muestra PC */
        @media screen and (min-width: 851px) {
            .vista-escritorio { display: block !important; }
            .vista-movil { display: none !important; }
        }

        /* ================= ESTILOS VISTA MÓVIL ================= */
        .mobile-top-bar {
            display: flex;
            justify-content: space-between;
            padding-bottom: 40px;
            font-size: 1.1rem;
            font-weight: normal;
        }
        .mobile-top-bar .brand-name { font-weight: bold; }
        
        .mobile-slider-container {
            display: flex !important;
            flex-direction: row !important; 
            flex-wrap: nowrap !important;   
            overflow-x: auto !important;
            scroll-snap-type: x mandatory;
            -webkit-overflow-scrolling: touch;
            width: 100vw;
            margin-left: calc(-1 * clamp(30px, 5vw, 60px));
            padding-bottom: 40px;
            gap: 0;
        }
        .mobile-slider-container::-webkit-scrollbar { display: none; }
        
        .slider-section {
            flex: 0 0 85vw !important; 
            min-width: 85vw !important; /* Fuerza estricta para que no se apachurre */
            scroll-snap-align: center;
            padding: 0 clamp(30px, 5vw, 60px);
            box-sizing: border-box;
        }
        
        .mobile-footer {
            display: flex;
            justify-content: space-between;
            padding-top: 20px;
            font-size: 0.95rem;
            color: #8c8c8c;
            border-top: 1px solid #eaeaea;
        }

        /* ================= ESTILOS VISTA ESCRITORIO ================= */
        .site-header-main { margin-bottom: clamp(60px, 8vh, 120px); font-size: 1.1rem; }
        .main-content-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: clamp(30px, 4vw, 80px);
            flex-grow: 1;
            margin-bottom: clamp(60px, 8vh, 120px);
        }
        .column-title { font-weight: normal; font-size: 1.05rem; margin-bottom: 2rem; letter-spacing: 0.03em; }
        .project-list li { margin-bottom: 0.6rem; font-size: 0.95rem; display: flex; }
        .list-group { margin-bottom: 2.5rem; }
        .indent-1 { padding-left: 2rem; }
        .indent-2 { padding-left: 4rem; }
        .year-label { display: inline-block; min-width: 3.5rem; color: #8c8c8c; font-size: 0.9rem; }
        .site-footer-main { display: flex; justify-content: space-between; align-items: center; font-size: 0.95rem; color: #8c8c8c; padding-bottom: 20px; }
        .footer-left { display: flex; gap: 40px; }

        /* --- HOVER PREVIEW & MODALES --- */
        .hover-preview {
            position: fixed; pointer-events: none; width: 320px; height: 220px;
            overflow: hidden; opacity: 0; z-index: 10000;
            transform: translate(15px, -50%);
            transition: opacity 0.4s ease, transform 0.2s ease-out;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .hover-preview img { width: 100%; height: 100%; object-fit: cover; }
        .hover-preview.active { opacity: 1; }

        .akira-modal-fullscreen {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
            background: #fdfdfd; z-index: 100000; opacity: 0; pointer-events: none;
            transition: opacity 0.4s ease; overflow-y: auto; padding-top: 60px; 
        }
        .akira-modal-fullscreen.active { opacity: 1; pointer-events: auto; }
        
        .modal-close-btn {
            position: fixed; top: 30px; right: 40px; font-size: 1.5rem; cursor: pointer;
            z-index: 100001; background: none; border: none; color: #111;
            transition: transform 0.3s ease;
        }
        .modal-close-btn:hover { transform: scale(1.1); }

        .akira-fade-up {
            opacity: 0; transform: translateY(25px);
            transition: opacity 0.7s ease-out, transform 0.7s ease-out;
        }
        .akira-fade-up.is-visible { opacity: 1; transform: translateY(0); }

        body { -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none; }
        @media (max-width: 900px) { .hover-preview { display: none; } }
    </style>

    <a href="{{ route('landing') }}" class="btn-flotante-regresar">&larr; regresar</a>

    <div class="vista-movil">
        
        <nav class="mobile-top-bar">
            <div><a href="{{ route('project.detail') }}" class="brand-name">Estudio Akiraka ,</a></div>
            <div>
                <a href="{{ route('info') }}">Info ,</a>
                <a href="{{ route('contacto') }}">Contacto</a>
            </div>
        </nav>

        <div class="mobile-slider-container">
            
            <div class="slider-section">
                <h2 class="column-title">Obras</h2>
                <div class="list-group">
                    <ul class="project-list">
                        <li>Proyectos</li>
                        <li class="indent-1">En proceso</li>
                        @forelse($proyectosEnProceso as $proyecto)
                            <li class="indent-2">
                                <a href="{{ route('project.main', $proyecto->id_proyecto) }}" class="project-link">
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
                                <a href="{{ route('project.main', $proyecto->id_proyecto) }}" class="project-link">
                                    {{ $proyecto->titulo }}
                                </a>
                            </li>
                        @empty
                            <li style="color: #ccc; font-style: italic; font-size: 0.85rem;">Ningún proyecto finalizado.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <div class="slider-section">
                <h2 class="column-title">Objetos</h2>
                <ul class="project-list">
                    @forelse($objetos as $objeto)
                        <li>
                            <span class="year-label">{{ $objeto->anio ?? 'S/A' }}</span> 
                            <a href="{{ route('objeto.main', $objeto->id_objeto) }}" class="project-link">
                                {{ $objeto->titulo }}
                            </a>
                        </li>
                    @empty
                        <li style="color: #ccc; font-style: italic; font-size: 0.85rem;">Ningún objeto en exhibición.</li>
                    @endforelse
                </ul>
            </div>

            <div class="slider-section">
                <h2 class="column-title">Publicaciones</h2>
                <ul class="project-list">
                    @forelse($publicaciones as $publicacion)
                        <li>
                            <span class="year-label">{{ \Carbon\Carbon::parse($publicacion->fecha)->format('Y') }}</span> 
                            <a href="{{ route('publicaciones.show', $publicacion->id_publicacion) }}" class="project-link">
                                {{ $publicacion->titulo }}
                            </a>
                        </li>
                    @empty
                        <li style="color: #ccc; font-style: italic; font-size: 0.85rem;">Ninguna publicación disponible.</li>
                    @endforelse
                </ul>
                <div style="margin-top: 40px;">
                    @include('Principal.cita')
                </div>
            </div>

        </div>

        <footer class="mobile-footer">
            <span>2026</span>
            <div>
                <a href="#" id="btn-traducir-mob" onclick="cambiarIdioma('en', event)">Read in English</a>
                <a href="#" id="btn-espanol-mob" onclick="cambiarIdioma('es', event)" style="display:none;">Leer en Español</a>
            </div>
        </footer>
    </div>


    <div class="vista-escritorio">
        <header class="site-header-main">
            <a href="{{ route('project.detail') }}" style="text-decoration: none; color: #1a1a1a; font-weight: bold;">Estudio Akiraka ,</a>
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
                <span style="padding-left: 100px;">2026</span>
            </div>
            <a href="#" id="btn-traducir" onclick="cambiarIdioma('en', event)">Read in English</a>
            <a href="#" id="btn-espanol" onclick="cambiarIdioma('es', event)" style="display:none;">Leer en Español</a>
        </footer>
    </div>

    <div id="hover-preview" class="hover-preview">
        <img src="" alt="Preview" id="preview-img">
    </div>

    <div id="historia-modal" class="akira-modal-fullscreen">
        <button class="modal-close-btn" onclick="cerrarModalHistoria()">✕</button>
        <div id="historia-content"></div>
    </div>
</div>

<div id="google_translate_element" style="display:none;"></div>
<script type="text/javascript">
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({pageLanguage: 'es', autoDisplay: false}, 'google_translate_element');
    }
</script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

<script>
    const previewContainer = document.getElementById('hover-preview');
    const previewImg = document.getElementById('preview-img');
    const projectLinks = document.querySelectorAll('.project-link');
    
    const modalHistoria = document.getElementById('historia-modal');
    const modalContent = document.getElementById('historia-content');

    projectLinks.forEach(link => {
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

        link.addEventListener('click', function(e) {
            const url = this.getAttribute('href');
            if(url === '#' || url === '') return;

            e.preventDefault(); 
            
            modalHistoria.classList.add('active');
            modalContent.innerHTML = '<div style="text-align:center; padding-top: 20vh; font-family: Garamond, serif; font-size: 1.5rem; color: #888;">Cargando historia...</div>';

            fetch(url)
                .then(response => response.text())
                .then(html => {
                    modalContent.innerHTML = html;
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

    function cerrarModalHistoria() {
        modalHistoria.classList.remove('active');
        setTimeout(() => { modalContent.innerHTML = ''; }, 400);
    }

    const scrollElements = document.querySelectorAll('.vista-escritorio .column-title, .vista-escritorio .project-list li');
    scrollElements.forEach(el => el.classList.add('akira-fade-up'));

    const menuObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.classList.add('is-visible');
                }, 50); 
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1, 
        rootMargin: "0px 0px -20px 0px" 
    });

    scrollElements.forEach(el => menuObserver.observe(el));

    function cambiarIdioma(idioma, event) {
        event.preventDefault();
        document.cookie = `googtrans=/es/${idioma}; path=/;`;
        document.cookie = `googtrans=/es/${idioma}; domain=${window.location.hostname}; path=/;`;
        window.location.reload();
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (document.cookie.includes('googtrans=/es/en')) {
            document.getElementById('btn-traducir').style.display = 'none';
            document.getElementById('btn-espanol').style.display = 'inline-block';
            
            if(document.getElementById('btn-traducir-mob')) {
                document.getElementById('btn-traducir-mob').style.display = 'none';
                document.getElementById('btn-espanol-mob').style.display = 'inline-block';
            }
        }
    });
</script>
@endsection