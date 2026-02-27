<div id="project-view" class="d-none akira-project-view">

    <style>
        /* Forzamos layout aunque Bootstrap meta mano */
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

        @media (max-width: 900px) {
            .main-content-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 600px) {
            .main-content-grid {
                grid-template-columns: 1fr;
                gap: 50px;
            }

            .indent-2 { padding-left: 2rem; }

            .site-footer-main {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
        }
    </style>

    <header class="site-header-main">
        <span class="brand-name">Akiraka Studio,</span>
        <span class="nav-links-main"> Noticias, Obras, Info, Contacto.</span>
    </header>

    <main class="main-content-grid">

        <section>
            <h2 class="column-title">Obras</h2>

            <div class="list-group">
                <ul class="project-list">
                    <li>Proyectos</li>
                    <li class="indent-1">En proceso</li>
                    <li class="indent-2"><a href="#">Residencial Valle de Bravo</a></li>
                    <li class="indent-2"><a href="#">Pabellón Efímero CDMX</a></li>
                    <li class="indent-2"><a href="#">Centro Cultural Akiraka 2026</a></li>
                </ul>
            </div>

            <div class="list-group">
                <ul class="project-list">
                    <li>Construidos</li>
                    <li><span class="year-label">2025</span> <a href="#">Casa Bosque</a></li>
                    <li><span class="year-label">2024</span> <a href="#">Estudio de Pintura Nómada</a></li>
                    <li><span class="year-label">2023</span> <a href="#">Terraza Akiraka</a></li>
                </ul>
            </div>
        </section>

        <section>
            <h2 class="column-title">Objetos</h2>
            <ul class="project-list">
                <li><span class="year-label">2025</span> <a href="#">Silla Akira 01</a></li>
                <li><span class="year-label">2024</span> <a href="#">Mesa de Concreto Pulido</a></li>
                <li><span class="year-label">2022</span> <a href="#">Escultura de Luz</a></li>
            </ul>
        </section>

        <section>
            <h2 class="column-title">Publicaciones</h2>
            <ul class="project-list">
                <li><span class="year-label">2026</span> <a href="#">Arquitectura Viva: El minimalismo de Akiraka</a></li>
                <li><span class="year-label">2025</span> <a href="#">Instagram Design Awards</a></li>
                <li><span class="year-label">2023</span> <a href="#">Libro: Espacios Silenciosos</a></li>
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

</div>