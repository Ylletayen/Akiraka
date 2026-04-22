<div class="historia-wrapper-vertical" id="scroll-container-objeto">
    <button class="modal-close-btn" onclick="cerrarModalHistoria()">✕</button>
    
    <style>
        /* ================= ESTILOS VERTICALES DEL MODAL ================= */
        .historia-wrapper-vertical { width: 100%; height: 100vh; overflow-y: auto; scroll-snap-type: y mandatory; scroll-behavior: smooth; background-color: #fdfdfd; font-family: "Garamond", "Baskerville", serif; color: #111; }
        .historia-wrapper-vertical::-webkit-scrollbar { display: none; }
        .historia-wrapper-vertical { -ms-overflow-style: none; scrollbar-width: none; }

        .fase-slide { width: 100%; height: 100vh; scroll-snap-align: center; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 20px 5vw; box-sizing: border-box; position: relative; }
        .historia-header-title { text-align: center; max-width: 800px; }
        .historia-header-title h2 { font-size: clamp(2.5rem, 5vw, 4rem); font-weight: normal; margin-bottom: 20px; letter-spacing: 0.02em; }
        .historia-header-title p { color: #555; font-family: Arial, sans-serif; font-size: 1rem; line-height: 1.8; max-width: 600px; margin: 0 auto; }

        /* ================= TEXTO AL LADO DE LA IMAGEN ================= */
        .fase-content { display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center; max-width: 1200px; width: 100%; }
        .fase-imagen-wrapper { width: 100%; height: 70vh; display: flex; justify-content: center; align-items: center; }
        .fase-imagen-wrapper img { width: 100%; height: 100%; object-fit: contain; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .fase-texto { text-align: left; padding: 20px; }
        .fase-num { font-family: Arial, sans-serif; font-size: 0.8rem; letter-spacing: 2px; color: #888; text-transform: uppercase; display: inline-block; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 5px; }
        .fase-texto p { font-size: 1.15rem; line-height: 1.8; color: #444; margin: 0; }

        /* Animaciones */
        @supports (animation-timeline: view()) {
            .fase-imagen-wrapper img { animation: revelarImagen linear both; animation-timeline: view(); animation-range: entry 10% cover 30%; }
            .fase-texto { animation: deslizarTexto linear both; animation-timeline: view(); animation-range: entry 15% cover 35%; }
        }
        @keyframes revelarImagen { from { opacity: 0; transform: scale(0.95); filter: blur(3px); } to { opacity: 1; transform: scale(1); filter: blur(0); } }
        @keyframes deslizarTexto { from { opacity: 0; transform: translateX(30px); } to { opacity: 1; transform: translateX(0); } }

        /* Responsivo */
        @media (max-width: 900px) {
            .fase-content { grid-template-columns: 1fr; gap: 30px; }
            .fase-imagen-wrapper { height: 45vh; }
            .fase-texto { text-align: center; }
        }
    </style>

    @php
        $portada = $imagenes->firstWhere('descripcion', 'Portada principal');
        $galeria = $imagenes->filter(function($img) use ($portada) {
            return $portada ? $img->id_imagen != $portada->id_imagen : true;
        })->values();
    @endphp

    <section class="fase-slide" style="position: relative; overflow: hidden;">
        {{-- FONDO TRANSPARENTE DE LA PORTADA --}}
        @if($portada)
            <img src="{{ asset('storage/' . $portada->url_imagen) }}" alt="Fondo Portada" 
                 style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; 
                        opacity: 0.35; z-index: 0; pointer-events: none;
                        -webkit-mask-image: radial-gradient(ellipse, rgba(0,0,0,1) 30%, rgba(0,0,0,0) 80%);
                        mask-image: radial-gradient(ellipse, rgba(0,0,0,1) 30%, rgba(0,0,0,0) 80%);">
        @endif

        <div class="historia-header-title" style="position: relative; z-index: 1;">
            <h2>{{ $objeto->titulo }}</h2>
            @if($objeto->anio)
                <p style="color: #666; font-style: italic;">Año de creación: {{ $objeto->anio }}</p>
            @endif
            <div style="margin-top: 40px; font-family: Arial; font-size: 0.75rem; letter-spacing: 2px; color: #aaa; text-transform: uppercase;">
                Haz scroll hacia abajo ↓
            </div>
        </div>
    </section>

    @forelse($galeria as $index => $imagen)
        <section class="fase-slide">
            <div class="fase-content">
                <div class="fase-imagen-wrapper">
                    <img src="{{ asset('storage/' . $imagen->url_imagen) }}" alt="Vista {{ $index + 1 }}">
                </div>
                <div class="fase-texto">
                    <span class="fase-num">
                        VISTA {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }} 
                        @if($imagen->anio) | {{ $imagen->anio }} @endif
                    </span>
                    <p>{{ $imagen->descripcion }}</p>
                </div>
            </div>
        </section>
    @empty
        <section class="fase-slide">
            <div style="text-align: center; font-style: italic; color: #888; font-size: 1.2rem; position: relative; z-index: 1;">
                La ficha técnica visual de este objeto está siendo documentada.<br>
                Estará disponible pronto.
            </div>
        </section>
    @endforelse
</div>