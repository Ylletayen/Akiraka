<div class="historia-wrapper-vertical">
    <style>
        /* ================= ESTILOS VERTICALES DEL MODAL ================= */
        .historia-wrapper-vertical {
            width: 100%;
            height: 100vh;
            overflow-y: auto;
            scroll-snap-type: y mandatory;
            scroll-behavior: smooth;
            background-color: #fdfdfd;
            font-family: "Garamond", "Baskerville", serif;
            color: #111;
        }

        .historia-wrapper-vertical::-webkit-scrollbar { display: none; }
        .historia-wrapper-vertical { -ms-overflow-style: none; scrollbar-width: none; }

        /* Botón de Volver Corregido */
        .btn-volver-obras {
            position: fixed;
            top: 40px;
            left: 40px;
            background: rgba(253, 253, 253, 0.9);
            border: 1px solid #eee;
            border-radius: 30px;
            padding: 10px 20px;
            font-family: Arial, sans-serif;
            font-size: 0.8rem;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #111;
            cursor: pointer;
            z-index: 100002;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        
        .btn-volver-obras:hover {
            background: #111;
            color: #fff;
            transform: translateY(-2px);
        }

        .fase-slide {
            width: 100%;
            height: 100vh;
            scroll-snap-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px 5vw;
            box-sizing: border-box;
            position: relative;
        }

        .historia-header-title {
            text-align: center;
            max-width: 800px;
        }
        
        .historia-header-title h2 {
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: normal;
            margin-bottom: 20px;
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

        /* ================= TEXTO AL LADO DE LA IMAGEN ================= */
        .fase-content {
            display: grid;
            grid-template-columns: 1fr 1fr; /* Divide el espacio: 50% imagen, 50% texto */
            gap: 60px;
            align-items: center;
            max-width: 1200px;
            width: 100%;
        }

        .fase-imagen-wrapper {
            width: 100%;
            height: 70vh; /* Altura ideal para la foto */
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .fase-imagen-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: contain; /* Evita que se recorte, preserva el formato */
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
        }

        /* OPCIONAL: Si quieres que las fotos se alternen (Izquierda/Derecha) descomenta esto: */
        /* .fase-slide:nth-child(even) .fase-content {
            direction: rtl;
        }
        .fase-slide:nth-child(even) .fase-texto {
            direction: ltr; 
        } */

        /* Animaciones */
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
            from { opacity: 0; transform: scale(0.95); filter: blur(3px); }
            to { opacity: 1; transform: scale(1); filter: blur(0); }
        }

        @keyframes deslizarTexto {
            from { opacity: 0; transform: translateX(30px); }
            to { opacity: 1; transform: translateX(0); }
        }

        /* Responsivo para móviles: se apilan imagen arriba y texto abajo */
        @media (max-width: 900px) {
            .fase-content { grid-template-columns: 1fr; gap: 30px; }
            .fase-imagen-wrapper { height: 45vh; }
            .fase-texto { text-align: center; }
            .btn-volver-obras { top: 20px; left: 20px; padding: 8px 15px; font-size: 0.7rem; }
        }
    </style>

    <a href="{{ route('project.detail') }}" class="btn-volver-obras" style="text-decoration: none; display: inline-flex; align-items: center;">
        &larr; Volver a Projectos
    </a>

    <section class="fase-slide">
        <div class="historia-header-title">
            <h2>{{ $proyecto->titulo }}</h2>
            @if($proyecto->descripcion)
                <p>{{ $proyecto->descripcion }}</p>
            @endif
            <div style="margin-top: 40px; font-family: Arial; font-size: 0.75rem; letter-spacing: 2px; color: #aaa; text-transform: uppercase;">
                Haz scroll hacia abajo ↓
            </div>
        </div>
    </section>

    @forelse($imagenes as $index => $imagen)
        <section class="fase-slide">
            <div class="fase-content">
                <div class="fase-imagen-wrapper">
                    <img src="{{ asset('storage/' . $imagen->url_imagen) }}" alt="Fase {{ $index + 1 }}">
                </div>
                <div class="fase-texto">
                    <span class="fase-num">FASE {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    <p>{{ $imagen->descripcion }}</p>
                </div>
            </div>
        </section>
    @empty
        <section class="fase-slide">
            <div style="text-align: center; font-style: italic; color: #888; font-size: 1.2rem;">
                La historia visual de este proyecto está siendo documentada.<br>
                Estará disponible pronto.
            </div>
        </section>
    @endforelse

</div>