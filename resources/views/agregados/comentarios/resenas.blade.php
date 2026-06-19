@extends('layouts.app')

@section('content')
@php
    $config = \App\Models\Configuracion::first();
    
    $mediaUrl = $config && $config->landing_hero_image 
                 ? asset('storage/' . $config->landing_hero_image) 
                 : 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&q=80&w=1920';
                 
    $isVideo = preg_match('/\.(mp4|webm)$/i', $mediaUrl);
@endphp

<style>
    /* ================= ESTILOS GLOBALES Y COLUMNAS MULTIMEDIA ================= */
    body { background-color: #fafafa; }

    .side-media { position: fixed; top: 0; width: 14vw; height: 100vh; z-index: -1; overflow: hidden; background-size: cover; background-position: center; background-repeat: no-repeat; filter: blur(6px) opacity(0.45) grayscale(20%); transition: filter 0.8s ease; }
    .side-left { left: 0; -webkit-mask-image: linear-gradient(to right, black 30%, transparent 100%); mask-image: linear-gradient(to right, black 30%, transparent 100%); }
    .side-right { right: 0; -webkit-mask-image: linear-gradient(to left, black 30%, transparent 100%); mask-image: linear-gradient(to left, black 30%, transparent 100%); }
    .hero-video-bg { position: absolute; top: 50%; left: 50%; min-width: 100%; min-height: 100%; width: auto; height: auto; transform: translateX(-50%) translateY(-50%); object-fit: cover; }
    @media (max-width: 1100px) { .side-media { display: none !important; } }

    /* ================= CONTENEDOR PRINCIPAL ================= */
    .akira-container { max-width: 780px; margin: 0 auto; padding: 50px 30px; font-family: "Georgia", "Times New Roman", serif; color: #333; position: relative; z-index: 1; }

    /* ================= HEADER / MENU TIPOGRÁFICO ================= */
    .site-header-main { margin-bottom: 60px; }
    .nav-link-akira { position: relative; display: inline-block; padding-bottom: 2px; text-decoration: none !important; color: #8c8c8c; transition: color 0.3s ease; font-family: "Georgia", "Times New Roman", serif; }
    .nav-link-akira::after { content: ''; position: absolute; width: 0; height: 1px; bottom: 0; left: 0; background-color: currentColor; transition: width 0.4s cubic-bezier(0.25, 0.8, 0.25, 1); }
    .nav-link-akira:hover { color: #111111; }
    .nav-link-akira:hover::after { width: 100%; }
    .active-link { font-weight: bold !important; color: #111111 !important; }
    .active-link::after { width: 100% !important; }

    /* ================= BOTÓN FLOTANTE REGRESAR ================= */
    .btn-flotante-regresar { position: fixed; bottom: clamp(25px, 5vh, 45px); left: clamp(30px, 5vw, 60px); font-size: 0.90rem; color: #111111 !important; text-decoration: none !important; z-index: 9999; background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(25px); -webkit-backdrop-filter: blur(25px); border: 1px solid rgba(0, 0, 0, 0.1); padding: 12px 32px; border-radius: 50px; opacity: 0.6; transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1); font-family: "Georgia", "Times New Roman", serif; letter-spacing: 1.5px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05); }
    .btn-flotante-regresar:hover { opacity: 1; background: rgba(255, 255, 255, 0.5); border-color: rgba(0, 0, 0, 0.3); transform: translateX(-5px) translateY(-2px); box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15); }

    /* ================= SECCIÓN DE RESEÑAS ================= */
    .header-section { border-bottom: 1px solid #eaeaea; padding-bottom: 20px; margin-bottom: 40px; }
    .header-section h1 { font-size: 2rem; margin-bottom: 10px; color: #111; letter-spacing: 0.02em; font-weight: normal; }
    .header-section p { color: #666; font-size: 0.95rem; line-height: 1.6; font-style: italic; }
    .reviews-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 25px; margin-bottom: 60px; }
    .review-card { background: transparent; border: 1px solid #eaeaea; padding: 30px; transition: transform 0.3s ease, border-color 0.3s ease; display: flex; flex-direction: column; justify-content: space-between; position: relative; }
    .review-card:hover { transform: translateY(-3px); border-color: #ccc; }
    .review-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 18px; }
    .reviewer-name { font-weight: bold; font-size: 1rem; color: #111; margin: 0; }
    .review-time { font-size: 0.75rem; color: #999; margin-top: 3px; font-family: Arial, sans-serif; letter-spacing: 0.5px; text-transform: uppercase; }
    .review-body { font-size: 0.95rem; color: #444; line-height: 1.7; flex-grow: 1; margin-bottom: 25px; font-style: italic; }
    .review-footer { border-top: 1px solid #f5f5f5; padding-top: 15px; font-size: 0.75rem; color: #aaa; display: flex; justify-content: space-between; font-family: Arial, sans-serif; text-transform: uppercase; letter-spacing: 1px; }
    .btn-leave-review { display: inline-block; background: #111; color: #fff; padding: 12px 30px; text-decoration: none; letter-spacing: 2px; text-transform: uppercase; font-size: 0.75rem; transition: background 0.3s ease, transform 0.2s ease; border: none; margin-top: 15px; font-family: Arial, sans-serif; cursor: pointer; }
    .btn-leave-review:hover { background: #333; transform: translateY(-2px); }

    /* ================= SISTEMA DE VOTOS COMUNIDAD ================= */
    .community-rating-box { background: #f9f9f9; padding: 15px; border-radius: 6px; margin-bottom: 20px; font-family: Arial, sans-serif; text-align: center; }
    .average-stars { font-size: 1.1rem; color: #111; margin-bottom: 5px; font-weight: bold; letter-spacing: 2px; }
    .empty-star { color: #ccc; }
    .vote-count { font-size: 0.7rem; color: #888; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px; display: block; }
    
    .interactive-stars { display: flex; justify-content: center; flex-direction: row-reverse; gap: 5px; }
    .interactive-stars span { font-size: 1.5rem; color: #ddd; cursor: pointer; transition: color 0.2s; }
    .interactive-stars span:hover, .interactive-stars span:hover ~ span { color: #111; }
    .voted-msg { color: #2e7d32; font-size: 0.75rem; font-weight: bold; margin-top: 8px; display: none; }

    /* ================= ESTILOS DEL MODAL DE CREACIÓN ================= */
    .modal-overlay { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0, 0, 0, 0.85); backdrop-filter: blur(8px); z-index: 10000; display: none; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.3s ease; padding: 20px; }
    .modal-overlay.active { display: flex; opacity: 1; }
    .modal-box { background: #fff; width: 100%; max-width: 500px; padding: 40px; position: relative; box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15); border-top: 5px solid #111; }
    .btn-close-modal { position: absolute; top: 15px; right: 20px; background: none; border: none; font-size: 1.2rem; color: #888; cursor: pointer; transition: color 0.3s; }
    .btn-close-modal:hover { color: #111; }
    .form-group-modal { margin-bottom: 20px; }
    .form-group-modal label { display: block; font-family: Arial, sans-serif; font-size: 0.7rem; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; color: #555; margin-bottom: 8px; }
    .form-control-modal { width: 100%; padding: 12px; border: 1px solid #eaeaea; background: #fafafa; font-family: Arial, sans-serif; font-size: 0.9rem; outline: none; transition: border-color 0.3s; }
    .form-control-modal:focus { border-color: #111; background: #fff; }
    .btn-submit-modal { width: 100%; padding: 15px; background: #111; color: #fff; border: none; text-transform: uppercase; letter-spacing: 2px; font-weight: bold; font-size: 0.75rem; cursor: pointer; margin-top: 10px; transition: background 0.3s; }
    .btn-submit-modal:hover { background: #333; }
</style>

{{-- COLUMNAS MULTIMEDIA --}}
<div class="side-media side-left {{ !$isVideo ? 'hero-image-bg' : '' }}" @if(!$isVideo) style="background-image: url('{{ $mediaUrl }}');" @endif>
    @if($isVideo) <video autoplay loop muted playsinline class="hero-video-bg"><source src="{{ $mediaUrl }}" type="video/mp4"></video> @endif
</div>
<div class="side-media side-right {{ !$isVideo ? 'hero-image-bg' : '' }}" @if(!$isVideo) style="background-image: url('{{ $mediaUrl }}'); transform: scaleX(-1);" @endif>
    @if($isVideo) <video autoplay loop muted playsinline class="hero-video-bg" style="transform: translateX(-50%) translateY(-50%) scaleX(-1);"><source src="{{ $mediaUrl }}" type="video/mp4"></video> @endif
</div>

<a href="{{ route('landing') }}" class="btn-flotante-regresar">← regresar</a>

<div class="akira-container">
    <header class="site-header-main">
        <a href="{{ route('project.detail') ?? '#' }}" class="nav-link-akira {{ request()->routeIs('project.detail') ? 'active-link' : '' }}">Estudio Akiraka ,</a>
        <a href="{{ route('info') ?? '#' }}" class="nav-link-akira {{ request()->routeIs('info') ? 'active-link' : '' }}">Info ,</a>
        <a href="{{ route('resenas.index') ?? '#' }}" class="nav-link-akira {{ request()->routeIs('resenas.index') ? 'active-link' : '' }}">Reseñas ,</a>
        <a href="{{ route('contacto') ?? '#' }}" class="nav-link-akira {{ request()->routeIs('contacto') ? 'active-link' : '' }}">Contacto</a>
    </header>

    @if(session('success'))
        <div style="background: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9; padding: 15px; text-align: center; margin-bottom: 30px; font-family: Arial; font-size: 0.9rem; letter-spacing: 1px;">
            {{ session('success') }}
        </div>
    @endif

    <div class="header-section">
        <h1>Experiencia Akira</h1>
        <p>Las opiniones de nuestros clientes avalan el compromiso depositado en cada obra. ¡Vota las opiniones de la comunidad!</p>
        <button onclick="abrirModalResena()" class="btn-leave-review">Añadir recomendación</button>
    </div>

    <div class="reviews-grid">
        @forelse($resenas as $resena)
            @php
                // Calculamos el promedio matemático usando las columnas nuevas
                $promedio = $resena->votos_count > 0 ? round($resena->estrellas_sum / $resena->votos_count, 1) : 0;
                $estrellasLlenas = round($promedio);
            @endphp
            <div class="review-card">
                <div>
                    <div class="review-header">
                        <div class="reviewer-info">
                            <h3 class="reviewer-name">{{ $resena->nombre_cliente }}</h3>
                            <span class="review-time">{{ $resena->created_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    <div class="review-body">
                        "{!! e($resena->comentario) !!}"
                    </div>
                </div>

                <!-- SECCIÓN DE VOTACIÓN DE LA COMUNIDAD (TIPO REDDIT/AMAZON) -->
                <div class="community-rating-box">
                    <div class="average-stars" id="avg-stars-{{ $resena->id }}">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $estrellasLlenas) ★ @else <span class="empty-star">★</span> @endif
                        @endfor
                        <span style="font-size: 0.8rem; margin-left: 5px;">{{ $promedio }}</span>
                    </div>
                    <span class="vote-count" id="vote-count-{{ $resena->id }}">{{ $resena->votos_count }} votos de la comunidad</span>
                    
                    <div style="font-size: 0.75rem; color: #444; margin-bottom: 5px;">¿Qué opinas de este testimonio?</div>
                    
                    <div class="interactive-stars" id="vote-box-{{ $resena->id }}">
                        <span onclick="votarResena({{ $resena->id }}, 5)" title="Excelente">★</span>
                        <span onclick="votarResena({{ $resena->id }}, 4)" title="Muy bueno">★</span>
                        <span onclick="votarResena({{ $resena->id }}, 3)" title="Bueno">★</span>
                        <span onclick="votarResena({{ $resena->id }}, 2)" title="Regular">★</span>
                        <span onclick="votarResena({{ $resena->id }}, 1)" title="Malo">★</span>
                    </div>
                    <div class="voted-msg" id="msg-{{ $resena->id }}">¡Gracias por tu voto!</div>
                </div>

                <div class="review-footer">
                    <span>Publicado:</span>
                    <span>{{ $resena->created_at->format('d M, Y') }}</span>
                </div>
            </div>
        @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #888; font-style: italic;">
                Aún no hay reseñas registradas. ¡Sé el primero en dejarnos tu opinión!
            </div>
        @endforelse
    </div>
</div>

<!-- ================= MODAL PARA CREAR RESEÑA ================= -->
<div id="modal-crear-resena" class="modal-overlay">
    <div class="modal-box">
        <button class="btn-close-modal" onclick="cerrarModalResena()">✕</button>
        <div style="text-align: center; margin-bottom: 30px;">
            <h2 style="font-family: 'Garamond', serif; font-size: 1.5rem; color: #111; margin: 0 0 5px 0;">Tu Opinión</h2>
            <p style="font-family: Arial, sans-serif; font-size: 0.75rem; color: #888; text-transform: uppercase; letter-spacing: 1px;">Comparte tu experiencia con nosotros</p>
        </div>
        
        <form action="{{ route('resenas.store') }}" method="POST">
            @csrf
            <div class="form-group-modal">
                <label>Nombre y Apellido</label>
                <input type="text" name="nombre_cliente" class="form-control-modal" placeholder="Ej. Carlos Mendoza" required>
            </div>
            <div class="form-group-modal">
                <label>Tu Comentario / Testimonio</label>
                <textarea name="comentario" class="form-control-modal" rows="4" placeholder="Describe cómo fue tu experiencia..." required></textarea>
            </div>
            <button type="submit" class="btn-submit-modal">Publicar Testimonio</button>
        </form>
    </div>
</div>

<script>
    // ----------------------------------------------------
    // LÓGICA DEL MODAL
    // ----------------------------------------------------
    const modalResena = document.getElementById('modal-crear-resena');
    function abrirModalResena() { modalResena.classList.add('active'); document.body.style.overflow = 'hidden'; }
    function cerrarModalResena() { modalResena.classList.remove('active'); document.body.style.overflow = 'auto'; }
    window.onclick = function(event) { if (event.target == modalResena) { cerrarModalResena(); } }

    // ----------------------------------------------------
    // LÓGICA DE VOTACIÓN DE LA COMUNIDAD (AJAX)
    // ----------------------------------------------------
    function votarResena(resenaId, estrellas) {
        // Validación local: Evitamos que vote 2 veces revisando el almacenamiento del navegador
        if(localStorage.getItem('votado_resena_' + resenaId)) {
            alert("Ya has calificado esta reseña anteriormente. ¡Gracias por participar!");
            return;
        }

        // Hacemos la petición silenciosa al servidor (Fetch API)
        fetch(`/resenas/${resenaId}/votar`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Token de seguridad obligatorio en Laravel
            },
            body: JSON.stringify({ estrellas: estrellas })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                // Actualizamos los textos en la pantalla con los nuevos datos
                document.getElementById('vote-count-' + resenaId).innerText = data.total_votos + ' votos de la comunidad';
                
                // Redibujamos las estrellas amarillas según el nuevo promedio
                let estrellasHtml = '';
                let llenas = Math.round(data.promedio);
                for(let i = 1; i <= 5; i++) {
                    if(i <= llenas) estrellasHtml += '★ ';
                    else estrellasHtml += '<span class="empty-star">★</span> ';
                }
                estrellasHtml += `<span style="font-size: 0.8rem; margin-left: 5px;">${data.promedio}</span>`;
                
                document.getElementById('avg-stars-' + resenaId).innerHTML = estrellasHtml;

                // Ocultamos la caja para votar y mostramos el mensaje de agradecimiento
                document.getElementById('vote-box-' + resenaId).style.display = 'none';
                document.getElementById('msg-' + resenaId).style.display = 'block';

                // Guardamos en la memoria del navegador que este usuario ya votó esta reseña
                localStorage.setItem('votado_resena_' + resenaId, true);
            }
        })
        .catch(error => {
            console.error('Error al procesar el voto:', error);
            alert('Hubo un error al intentar enviar tu calificación. Intenta de nuevo.');
        });
    }

    // ----------------------------------------------------
    // OCULTAR CAJAS DE VOTACIÓN SI YA HABÍAN VOTADO ANTES
    // ----------------------------------------------------
    document.addEventListener('DOMContentLoaded', () => {
        // Al cargar la página, revisamos si el usuario ya votó para esconder las estrellas interactivas
        const voteBoxes = document.querySelectorAll('[id^="vote-box-"]');
        voteBoxes.forEach(box => {
            const resenaId = box.id.replace('vote-box-', '');
            if(localStorage.getItem('votado_resena_' + resenaId)) {
                box.style.display = 'none';
                document.getElementById('msg-' + resenaId).style.display = 'block';
                document.getElementById('msg-' + resenaId).innerText = 'Ya calificaste esta reseña.';
            }
        });
    });
</script>
@endsection