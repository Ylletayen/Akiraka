<div class="historia-wrapper-vertical" id="scroll-container-publicacion">
    
    <a href="{{ route('project.detail') }}" class="modal-close-btn" style="text-decoration: none;">✕</a>
    
    <style>
        /* ================= ESTILOS DEL BOTÓN CERRAR ================= */
        .modal-close-btn {
            position: fixed;
            top: 30px; 
            right: 40px;
            font-size: 1.8rem;
            cursor: pointer;
            z-index: 100001; /* Por encima de todo */
            background: rgba(253, 253, 253, 0.8);
            backdrop-filter: blur(5px);
            border: none;
            border-radius: 50%;
            width: 50px; height: 50px;
            display: flex; justify-content: center; align-items: center;
            font-family: Arial, sans-serif;
            color: #111;
            transition: transform 0.3s ease, background 0.3s;
        }

        .modal-close-btn:hover {
            transform: scale(1.1);
            background: #eee;
        }

        /* ================= ESTILOS VERTICALES DEL MODAL ================= */
        .historia-wrapper-vertical { width: 100%; height: 100vh; overflow-y: auto; scroll-snap-type: y mandatory; scroll-behavior: smooth; background-color: #fdfdfd; font-family: "Garamond", "Baskerville", serif; color: #111; }
        .historia-wrapper-vertical::-webkit-scrollbar { display: none; }
        .historia-wrapper-vertical { -ms-overflow-style: none; scrollbar-width: none; }

        .fase-slide { width: 100%; min-height: 100vh; scroll-snap-align: center; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 20px 5vw; box-sizing: border-box; position: relative; }
        
        .historia-header-title { text-align: center; max-width: 800px; }
        .historia-header-title h2 { font-size: clamp(2.5rem, 5vw, 4rem); font-weight: normal; margin-bottom: 20px; letter-spacing: 0.02em; }
        .historia-header-title p { color: #555; font-family: Arial, sans-serif; font-size: 1rem; line-height: 1.8; max-width: 600px; margin: 0 auto; }

        /* ================= TEXTO DE LA PUBLICACIÓN ================= */
        .publicacion-content-wrapper { display: flex; flex-direction: column; align-items: center; max-width: 800px; width: 100%; padding: 40px 0; }
        .fase-num { font-family: Arial, sans-serif; font-size: 0.8rem; letter-spacing: 2px; color: #888; text-transform: uppercase; display: inline-block; margin-bottom: 30px; border-bottom: 1px solid #eee; padding-bottom: 5px; }
        
        .publicacion-texto { font-size: 1.2rem; line-height: 1.8; color: #333; text-align: justify; margin-bottom: 40px; }
        
        .pub-link { display: inline-block; border-bottom: 1px solid #111; padding-bottom: 5px; font-style: italic; font-size: 1.1rem; color: #111; text-decoration: none; transition: all 0.3s ease; margin-bottom: 40px; }
        .pub-link:hover { border-color: #8c8c8c; padding-right: 10px; color: #888; }

        /* Animaciones */
        @supports (animation-timeline: view()) {
            .publicacion-content-wrapper { animation: deslizarTextoPub linear both; animation-timeline: view(); animation-range: entry 15% cover 35%; }
        }
        @keyframes deslizarTextoPub { from { opacity: 0; transform: translateY(40px); } to { opacity: 1; transform: translateY(0); } }

        /* Responsivo */
        @media (max-width: 900px) {
            .publicacion-texto { font-size: 1.1rem; text-align: left; }
            .modal-close-btn { top: 15px; right: 15px; width: 40px; height: 40px; font-size: 1.2rem; }
        }
    </style>

    <section class="fase-slide" style="position: relative; overflow: hidden;">
        <div class="historia-header-title" style="position: relative; z-index: 1;">
            <h2>{{ $publicacion->titulo }}</h2>
            
            <p style="color: #666; font-style: italic; font-size: 1.1rem; margin-bottom: 15px;">
                {{ \Carbon\Carbon::parse($publicacion->fecha)->format('d . m . Y') }}
            </p>

            
            <div style="margin-top: 40px; font-family: Arial, sans-serif; font-size: 0.75rem; letter-spacing: 2px; color: #aaa; text-transform: uppercase;">
                Haz scroll hacia abajo ↓
            </div>
        </div>
    </section>


    <section class="fase-slide" style="justify-content: flex-start;">
        <div class="publicacion-content-wrapper">
            <span class="fase-num">
                Detalles de la Publicación
            </span>
            
            <div class="publicacion-texto">
                {{ $publicacion->descripcion }}
            </div>

            @if($publicacion->url)
                <a href="{{ $publicacion->url }}" target="_blank" class="pub-link">
                    Explorar Proyecto Completo &rarr;
                </a>
            @endif
        </div>
    </section>
</div>