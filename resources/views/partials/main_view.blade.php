<div class="historia-wrapper-vertical modal-portada-full" id="scroll-container-proyecto">
    <button class="modal-close-btn" onclick="cerrarModalHistoria()">✕</button>
    
    <style>
        /* ================= RESET GENERAL ================= */
        html, body {
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        body {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        /* ================= MODAL FULLSCREEN ================= */
        .historia-wrapper-vertical {
            position: fixed;
            inset: 0;
            width: 100vw;
            height: 100vh;
            margin: 0;
            padding: 0;
            overflow-y: auto;
            overflow-x: hidden;
            scroll-snap-type: y mandatory;
            scroll-behavior: smooth;
            background-color: #fdfdfd;
            font-family: "Garamond", "Baskerville", serif;
            color: #111;
            z-index: 9998;
        }

        .historia-wrapper-vertical::-webkit-scrollbar {
            display: none;
        }

        .historia-wrapper-vertical {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* ================= SLIDES GENERALES ================= */
        .fase-slide {
            width: 100vw;
            min-height: 100vh;
            scroll-snap-align: start;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px 5vw;
            box-sizing: border-box;
            position: relative;
        }

        /* ================= PORTADA ================= */
        .portada-slide {
            position: relative;
            width: 100vw;
            height: 100vh;
            min-height: 100vh;
            margin: 0 !important;
            padding: 0 !important;
            overflow: hidden;
            background: #fff;
        }

        .portada-fondo {
            position: absolute;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            min-width: 100vw;
            min-height: 100vh;
            object-fit: cover;
            object-position: center center;
            z-index: 0;
            pointer-events: none;
            display: block;
        }

        /* Capa blanca general */
        .portada-overlay-base {
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, 0.52);
            z-index: 1;
            pointer-events: none;
        }

        /* Difuminado elegante hacia los bordes */
        .portada-overlay-vignette {
            position: absolute;
            inset: 0;
            z-index: 2;
            pointer-events: none;
            background:
                radial-gradient(
                    ellipse at center,
                    rgba(255,255,255,0.00) 30%,
                    rgba(255,255,255,0.14) 55%,
                    rgba(255,255,255,0.35) 75%,
                    rgba(255,255,255,0.62) 100%
                );
        }

        .historia-header-title {
            text-align: center;
            max-width: 800px;
            position: relative;
            z-index: 3;
            padding: 20px;
            box-sizing: border-box;
        }

        .historia-header-title h2 {
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: normal;
            margin: 0 0 20px 0;
            letter-spacing: 0.02em;
        }

        .historia-header-title p {
            color: #555;
            font-family: Arial, sans-serif;
            font-size: 1rem;
            line-height: 1.8;
            max-width: 600px;
            margin: 0 auto;
        }

        /* ================= CONTENIDO DE FASES ================= */
        .fase-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
            max-width: 1200px;
            width: 100%;
        }

        .fase-imagen-wrapper {
            width: 100%;
            height: 70vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            background: transparent;
        }

        .fase-imagen-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center center;
            display: block;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }

        .fase-texto {
            text-align: left;
            padding: 20px;
        }

        .fase-num {
            font-family: Arial, sans-serif;
            font-size: 0.8rem;
            letter-spacing: 2px;
            color: #888;
            text-transform: uppercase;
            display: inline-block;
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }

        .fase-texto p {
            font-size: 1.15rem;
            line-height: 1.8;
            color: #444;
            margin: 0;
            word-break: break-word;
        }

        /* ================= BOTÓN DE CERRAR ================= */
        .modal-close-btn {
            position: fixed;
            top: 20px;
            right: 25px;
            z-index: 10000;
            background: transparent;
            border: none;
            color: #111;
            font-size: 2rem;
            cursor: pointer;
            line-height: 1;
        }

        /* ================= ANIMACIONES ================= */
        @supports (animation-timeline: view()) {
            .fase-imagen-wrapper img {
                animation: revelarImagen linear both;
                animation-timeline: view();
                animation-range: entry 10% cover 30%;
            }

            .fase-texto {
                animation: deslizarTexto linear both;
                animation-timeline: view();
                animation-range: entry 15% cover 35%;
            }
        }

        @keyframes revelarImagen {
            from {
                opacity: 0;
                transform: scale(0.95);
                filter: blur(3px);
            }

            to {
                opacity: 1;
                transform: scale(1);
                filter: blur(0);
            }
        }

        @keyframes deslizarTexto {
            from {
                opacity: 0;
                transform: translateX(30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* ================= TABLET ================= */
        @media (max-width: 1024px) {
            .fase-content {
                gap: 40px;
            }

            .fase-imagen-wrapper {
                height: 60vh;
            }

            .fase-texto p {
                font-size: 1.05rem;
            }
        }

        /* ================= MÓVIL ================= */
        @media (max-width: 900px) {
            .fase-slide {
                padding: 30px 20px;
            }

            .portada-slide {
                padding: 0 !important;
            }

            .historia-header-title {
                padding: 20px 24px;
                max-width: 100%;
            }

            .historia-header-title h2 {
                font-size: clamp(2rem, 8vw, 3rem);
                margin-bottom: 16px;
            }

            .historia-header-title p {
                font-size: 0.95rem;
                line-height: 1.7;
                max-width: 100%;
            }

            .fase-content {
                grid-template-columns: 1fr;
                gap: 24px;
                width: 100%;
            }

            .fase-imagen-wrapper {
                width: 100%;
                height: 42vh;
                min-height: 280px;
                max-height: 460px;
            }

            .fase-imagen-wrapper img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .fase-texto {
                text-align: center;
                padding: 10px 0;
            }

            .fase-num {
                font-size: 0.75rem;
                letter-spacing: 1.8px;
                margin-bottom: 16px;
            }

            .fase-texto p {
                font-size: 1rem;
                line-height: 1.7;
            }

            .modal-close-btn {
                top: 14px;
                right: 18px;
                font-size: 1.9rem;
            }
        }

        /* ================= MÓVIL PEQUEÑO ================= */
        @media (max-width: 480px) {
            .historia-header-title {
                padding: 20px 18px;
            }

            .historia-header-title h2 {
                font-size: clamp(1.8rem, 9vw, 2.6rem);
            }

            .historia-header-title p {
                font-size: 0.9rem;
            }

            .fase-slide {
                padding: 24px 16px;
            }

            .fase-imagen-wrapper {
                height: 36vh;
                min-height: 240px;
            }

            .fase-texto p {
                font-size: 0.95rem;
            }
        }
    </style>

    {{-- Separamos la portada del resto de la galería --}}
    @php
        $portada = $imagenes->firstWhere('descripcion', 'Portada principal');

        $galeria = $imagenes->filter(function($img) use ($portada) {
            return $portada ? $img->id_imagen != $portada->id_imagen : true;
        })->values();
    @endphp

    {{-- PORTADA --}}
    <section class="fase-slide portada-slide">
        @if($portada)
            <img src="{{ asset('storage/' . $portada->url_imagen) }}"
                 alt="Fondo Portada"
                 class="portada-fondo">
        @endif

        <div class="portada-overlay-base"></div>
        <div class="portada-overlay-vignette"></div>

        <div class="historia-header-title">
            <h2>{{ $proyecto->titulo }}</h2>
            
            @if($proyecto->anio)
                <p style="color: #666; font-style: italic; font-size: 1.1rem; margin-bottom: 15px;">
                    Año de creación: {{ $proyecto->anio }}
                </p>
            @endif

            @if($proyecto->descripcion)
                <p>{{ $proyecto->descripcion }}</p>
            @endif
            
            <div style="margin-top: 40px; font-family: Arial, sans-serif; font-size: 0.75rem; letter-spacing: 2px; color: #aaa; text-transform: uppercase;">
                Haz scroll hacia abajo ↓
            </div>
        </div>
    </section>

    {{-- GALERÍA --}}
    @forelse($galeria as $index => $imagen)
        <section class="fase-slide">
            <div class="fase-content">
                <div class="fase-imagen-wrapper">
                    <img src="{{ asset('storage/' . $imagen->url_imagen) }}" alt="Fase {{ $index + 1 }}">
                </div>

                <div class="fase-texto">
                    <span class="fase-num">
                        FASE {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                        @if($imagen->anio) | {{ $imagen->anio }} @endif
                    </span>

                    <p>{{ $imagen->descripcion }}</p>
                </div>
            </div>
        </section>
    @empty
        <section class="fase-slide">
            <div style="text-align: center; font-style: italic; color: #888; font-size: 1.2rem; position: relative; z-index: 1;">
                La historia visual de este proyecto está siendo documentada.<br>
                Estará disponible pronto.
            </div>
        </section>
    @endforelse
</div>