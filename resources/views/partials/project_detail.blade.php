@extends('layouts.app')

@section('content')
<div id="project-view" class="akira-project-view">

    <style>
        .akira-project-view {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #fdfdfd !important;
            padding: clamp(30px, 5vw, 60px);
            font-family: "Garamond", "Baskerville", "Times New Roman", serif !important;
            color: #111111 !important;
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

        /* --- INDICADOR DE PÁGINA ACTUAL (LÍNEA INFERIOR ANIMADA) --- */
        .nav-link-akira, .mobile-top-bar a {
            position: relative;
            display: inline-block;
            padding-bottom: 2px; /* Un ligero espacio para que la línea respire */
        }
        
        .nav-link-akira::after, .mobile-top-bar a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 1px;
            bottom: 0;
            left: 0;
            background-color: currentColor; /* Toma el color gris o negro del texto automáticamente */
            transition: width 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        
        /* Efecto al pasar el cursor */
        .nav-link-akira:hover::after, .mobile-top-bar a:hover::after {
            width: 100%;
        }
        
        /* Estilo cuando es la página activa */
        .active-link {
            font-weight: bold !important;
            color: #111111 !important;
        }
        .active-link::after {
            width: 100% !important; /* La línea se queda dibujada al 100% */
        }
        /* ------------------------------------------------------------- */

        .btn-flotante-regresar {
        position: fixed;
        bottom: clamp(25px, 5vh, 45px); 
        left: clamp(30px, 5vw, 60px);
        font-size: 0.90rem;
        color: #ffffff !important; 
        text-decoration: none !important; 
        z-index: 9999;
        
        background: rgba(255, 255, 255, 0.03); 
        backdrop-filter: blur(25px); 
        -webkit-backdrop-filter: blur(25px); 
        border: 1px solid rgba(255, 255, 255, 0.1); 
        
        padding: 12px 32px; 
        border-radius: 50px; 
        
        opacity: 0.6; 
        transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        font-family: "Georgia", "Times New Roman", serif;
        letter-spacing: 1.5px; 
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05); 
    }

    .btn-flotante-regresar:hover {
        opacity: 1; 
        background: rgba(0, 0, 0, 0.25); 
        border-color: rgba(255, 255, 255, 0.3);
        transform: translateX(-5px) translateY(-2px); 
        color: #ffffff !important;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15); 
    }

        .vista-escritorio { display: none !important; }
        .vista-movil { display: block !important; width: 100%; }

        @media screen and (min-width: 851px) {
            .vista-escritorio { display: block !important; }
            .vista-movil { display: none !important; }
        }

        .mobile-top-bar {
            display: flex;
            justify-content: space-between;
            padding-bottom: 20px;
            font-size: 1.1rem;
            font-weight: normal;
        }
        .mobile-top-bar .brand-name { font-weight: bold; }

        .mobile-nav-tabs {
            display: flex;
            flex-wrap: nowrap;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border-bottom: 1px solid #eaeaea;
            margin-left: calc(-1 * clamp(30px, 5vw, 60px));
            margin-right: calc(-1 * clamp(30px, 5vw, 60px));
            padding: 0 clamp(30px, 5vw, 60px);
            margin-bottom: 30px;
        }
        .mobile-nav-tabs::-webkit-scrollbar { display: none; }
        
        .mobile-nav-tabs .nav-link {
            color: #888;
            font-family: Arial, sans-serif;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-radius: 0;
            white-space: nowrap;
            padding: 15px 20px 15px 0;
            background: transparent;
            border: none;
        }
        
        .mobile-nav-tabs .nav-link.active {
            color: #111;
            font-weight: bold;
            box-shadow: inset 0 -2px 0 #111;
        }

        .mobile-footer {
            display: flex;
            justify-content: space-between;
            padding-top: 20px;
            margin-top: 40px;
            font-size: 0.95rem;
            color: #8c8c8c;
            border-top: 1px solid #eaeaea;
        }

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

        @media (max-width: 900px) { .hover-preview { display: none; } }
    </style>

    <a href="{{ route('landing') ?? '#' }}" class="btn-flotante-regresar">&larr; regresar</a>

    {{-- MENU MÓVIL --}}
    <div class="vista-movil">
        <nav class="mobile-top-bar">
            <div>
                <a href="{{ route('project.detail') ?? '#' }}" class="brand-name {{ request()->routeIs('project.detail') ? 'active-link' : '' }}">Estudio Akiraka ,</a>
            </div>
            <div>
                <a href="{{ route('info') ?? '#' }}" class="nav-link-akira {{ request()->routeIs('info') ? 'active-link' : '' }}">Info ,</a>
                <a href="{{ route('contacto') ?? '#' }}" class="nav-link-akira {{ request()->routeIs('contacto') ? 'active-link' : '' }}">Contacto</a>
            </div>
        </nav>

        <ul class="nav mobile-nav-tabs" id="mobileTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="obras-tab" data-bs-toggle="tab" data-bs-target="#obras" type="button" role="tab">Obras</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="objetos-tab" data-bs-toggle="tab" data-bs-target="#objetos" type="button" role="tab">Objetos</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="publicaciones-tab" data-bs-toggle="tab" data-bs-target="#publicaciones" type="button" role="tab">Publicaciones</button>
            </li>
        </ul>

        <div class="tab-content" id="mobileTabContent">
            
            <div class="tab-pane fade show active" id="obras" role="tabpanel">
                <div class="list-group">
                    <ul class="project-list">
                        <li>Proyectos</li>
                        <li class="indent-1">En proceso</li>
                        @forelse($proyectosEnProceso ?? [] as $proyecto)
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
                        @forelse($proyectosConstruidos ?? [] as $proyecto)
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

            <div class="tab-pane fade" id="objetos" role="tabpanel">
                <ul class="project-list">
                    @forelse($objetos ?? [] as $objeto)
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

            <div class="tab-pane fade" id="publicaciones" role="tabpanel">
                <ul class="project-list">
                    @forelse($publicaciones ?? [] as $publicacion)
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
                    @includeIf('Principal.cita')
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

    {{-- MENU ESCRITORIO --}}
    <div class="vista-escritorio">
        <header class="site-header-main">
            <a href="{{ route('project.detail') ?? '#' }}" class="nav-link-akira {{ request()->routeIs('project.detail') ? 'active-link' : '' }}">Estudio Akiraka ,</a>
            <a href="{{ route('info') ?? '#' }}" class="nav-link-akira {{ request()->routeIs('info') ? 'active-link' : '' }}">Info ,</a>
            <a href="{{ route('contacto') ?? '#' }}" class="nav-link-akira {{ request()->routeIs('contacto') ? 'active-link' : '' }}">Contacto</a>
        </header>

        <main class="main-content-grid">
            <section>
                <h2 class="column-title">Obras</h2>
                <div class="list-group">
                    <ul class="project-list">
                        <li>Proyectos</li>
                        <li class="indent-1">En proceso</li>
                        @forelse($proyectosEnProceso ?? [] as $proyecto)
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
                        @forelse($proyectosConstruidos ?? [] as $proyecto)
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
                    @forelse($objetos ?? [] as $objeto)
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
                    @forelse($publicaciones ?? [] as $publicacion)
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

                @includeIf('Principal.cita')
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
    document.addEventListener('DOMContentLoaded', function() {
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

        window.cerrarModalHistoria = function() {
            modalHistoria.classList.remove('active');
            setTimeout(() => { modalContent.innerHTML = ''; }, 400);
        };

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

        window.cambiarIdioma = function(idioma, event) {
            event.preventDefault();
            document.cookie = `googtrans=/es/${idioma}; path=/;`;
            document.cookie = `googtrans=/es/${idioma}; domain=${window.location.hostname}; path=/;`;
            window.location.reload();
        };

        if (document.cookie.includes('googtrans=/es/en')) {
            const btnTraducir = document.getElementById('btn-traducir');
            const btnEspanol = document.getElementById('btn-espanol');
            if (btnTraducir) btnTraducir.style.display = 'none';
            if (btnEspanol) btnEspanol.style.display = 'inline-block';
            
            const btnTraducirMob = document.getElementById('btn-traducir-mob');
            const btnEspanolMob = document.getElementById('btn-espanol-mob');
            if (btnTraducirMob) btnTraducirMob.style.display = 'none';
            if (btnEspanolMob) btnEspanolMob.style.display = 'inline-block';
        }
    });
</script>
@endsection