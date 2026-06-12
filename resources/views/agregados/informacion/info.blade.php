@extends('layouts.app')

@section('content')
@php
    $config = \App\Models\Configuracion::first();
    
    // --- ESTA ES LA LÍNEA QUE DEBES CORREGIR ---
    $mediaUrl = $config && $config->landing_hero_image 
                 ? asset('storage/' . $config->landing_hero_image) // <-- DEBE DECIR 'storage/'
                 : 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&q=80&w=1920';
                 
    // Detectamos si es un video MP4
    $isVideo = preg_match('/\.(mp4|webm)$/i', $mediaUrl);
    // --------------------------------------------------------

    $defaultQuienesSomos = 'Somos AKIRAKA, un estudio de arquitectura que encuentra su nombre y filosofía en el concepto japonés de 明か (akiraka), que significa claro, evidente y brillante. Creado por el arq. Akira Kameta, mexicano - japonés, que lleva su percepción de ambos mundos a una interpretación de solución de los proyectos.';
    $defaultValores = "- Colaboración y Empatía: Se establece una relación con el cliente y la comunidad, diseñando desde un entendimiento profundo de sus necesidades para lograr un éxito compartido.\n- Impacto Regenerativo: El enfoque supera la sostenibilidad convencional buscando la regeneración activa de los ecosistemas y el fortalecimiento del tejido social.\n- Materialidad Sostenible: La madera de origen responsable es la protagonista (\"materia viva\"), valorada por su estética, capacidad de secuestro de carbono y beneficios biológicos.\n- Simplicidad y Honestidad: Se apuesta por la claridad conceptual para transformar ideas complejas en soluciones ejecutables (ideales para la autoconstrucción) y una transparencia radical en cuanto a costos, plazos y origen de los materiales.";

    $tienePuesto = \Illuminate\Support\Facades\Schema::hasColumn('equipo', 'puesto');
    
    $columnas = ['usuarios.nombre', 'equipo.biografia'];
    if ($tienePuesto) {
        $columnas[] = 'equipo.puesto';
    }

    $equipoDB = \Illuminate\Support\Facades\DB::table('equipo')
        ->join('usuarios', 'equipo.id_usuario', '=', 'usuarios.id_usuario')
        ->select($columnas)
        ->whereNull('equipo.deleted_at')
        ->get();

    $miembros = [];
    
    if($equipoDB->count() > 0) {
        foreach($equipoDB as $miembro) {
            $nombres = explode(' ', trim($miembro->nombre));
            $nombre_corto = $nombres[0];
            if(in_array(strtolower($nombres[0]), ['arq.', 'lic.', 'ing.', 'mtro.'])) {
                $nombre_corto = $nombres[0] . ' ' . ($nombres[1] ?? '');
            }

            $miembros[] = [
                'nombre' => $miembro->nombre,
                'biografia' => $miembro->biografia,
                'rol' => $tienePuesto && $miembro->puesto ? $miembro->puesto : 'Definir puesto en panel', 
                'nombre_corto' => $nombre_corto
            ];
        }
    } else {
        $miembros = [
            [
                'nombre' => 'Arq. Alberto Akira Kameta Miyamoto',
                'biografia' => 'Arquitecto mexicano japonés, egresado de la universidad Iberoamericana, estudios de postgrado en diseño y construcción sostenible.',
                'rol' => 'Dirección general',
                'nombre_corto' => 'Arq. Akira'
            ],
            [
                'nombre' => 'Arq. Ana Regnia Torres Tapia',
                'biografia' => 'Arquitecta egresada de la Universidad Anáhuac Norte, Ciudad de México, Proyectos',
                'rol' => 'Área de proyectos',
                'nombre_corto' => 'Arq. Ana'
            ],
            [
                'nombre' => 'Alejandra',
                'biografia' => 'Licenciada en derecho',
                'rol' => 'Área Administrativa',
                'nombre_corto' => 'Lic. Alejandra'
            ]
        ];
    }
@endphp

<style>
    /* --- ESTILOS DE LAS COLUMNAS MULTIMEDIA LATERALES (DIFUMINADAS) --- */
    .side-media {
        position: fixed;
        top: 0;
        width: 14vw; 
        height: 100vh;
        z-index: -1;
        overflow: hidden;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        
        filter: blur(6px) opacity(0.45) grayscale(20%); 
        transition: filter 0.8s ease;
    }

    .side-left { 
        left: 0; 
        -webkit-mask-image: linear-gradient(to right, black 30%, transparent 100%);
        mask-image: linear-gradient(to right, black 30%, transparent 100%);
    }
    
    .side-right { 
        right: 0; 
        -webkit-mask-image: linear-gradient(to left, black 30%, transparent 100%);
        mask-image: linear-gradient(to left, black 30%, transparent 100%);
    }
    
    .hero-video-bg {
        position: absolute;
        top: 50%;
        left: 50%;
        min-width: 100%;
        min-height: 100%;
        width: auto;
        height: auto;
        transform: translateX(-50%) translateY(-50%);
        object-fit: cover;
    }

    @media (max-width: 1100px) {
        .side-media { display: none !important; }
    }
    /* ------------------------------------------------------------ */

    /* Liberamos el texto central */
    .akira-container { 
        max-width: 780px; 
        margin: 0 auto; 
        padding: 50px 30px; 
        font-family: "Georgia", "Times New Roman", serif; 
        color: #333; 
        position: relative;
        z-index: 1; 
    }
    
    .akira-header { font-size: 1.2rem; margin-bottom: 60px; }
    .akira-header strong { font-weight: bold; }
    .akira-header span { color: #ccc; margin-left: 5px; }
    
    /* --- INDICADOR DE PÁGINA ACTUAL (LÍNEA INFERIOR ANIMADA) --- */
    .nav-link-akira {
        position: relative;
        display: inline-block;
        padding-bottom: 2px;
        text-decoration: none !important;
        color: #8c8c8c; /* Color desaturado por defecto */
        transition: color 0.3s ease;
    }
    
    .nav-link-akira::after {
        content: '';
        position: absolute;
        width: 0;
        height: 1px;
        bottom: 0;
        left: 0;
        background-color: currentColor;
        transition: width 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
    }
    
    .nav-link-akira:hover {
        color: #111111;
    }
    .nav-link-akira:hover::after {
        width: 100%;
    }
    
    /* Estilo cuando es la página activa */
    .active-link {
        font-weight: bold !important;
        color: #111111 !important;
    }
    .active-link::after {
        width: 100% !important;
    }
    /* ------------------------------------------------------------- */

    .akira-description { font-size: 0.95rem; line-height: 1.8; margin-bottom: 80px; max-width: 850px; }
    .team-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 50px; }
    .team-title { font-weight: bold; font-size: 1rem; margin-bottom: 25px; }
    .team-list { list-style: none; padding: 0; font-size: 0.9rem; margin-bottom: 40px; }
    
    .team-list li { margin-bottom: 25px; } 
    .text-muted-akira { color: #888; }

    /* --- ESTILOS PARA EL BOTÓN DE REGRESAR FLOTANTE (CRISTAL PREMIUM) --- */
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

    /* FOOTER ESTÉTICO PARA LOS BOTONES DE IDIOMA */
    .site-footer-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.95rem;
        color: #8c8c8c;
        padding-top: 40px;
        margin-top: 40px;
        border-top: 1px solid #eee;
    }
    .site-footer-info a {
        color: #8c8c8c;
        text-decoration: none;
        transition: color 0.3s ease;
    }
    .site-footer-info a:hover {
        color: #111;
    }

    body {
        -webkit-user-select: none;
        -moz-user-select: none; 
        -ms-user-select: none;  
        user-select: none;         
    }
</style>

{{-- COLUMNA MULTIMEDIA IZQUIERDA --}}
<div class="side-media side-left {{ !$isVideo ? 'hero-image-bg' : '' }}" @if(!$isVideo) style="background-image: url('{{ $mediaUrl }}');" @endif>
    @if($isVideo)
        <video autoplay loop muted playsinline class="hero-video-bg"><source src="{{ $mediaUrl }}" type="video/mp4"></video>
    @endif
</div>

{{-- COLUMNA MULTIMEDIA DERECHA --}}
<div class="side-media side-right {{ !$isVideo ? 'hero-image-bg' : '' }}" @if(!$isVideo) style="background-image: url('{{ $mediaUrl }}'); transform: scaleX(-1);" @endif>
    @if($isVideo)
        <video autoplay loop muted playsinline class="hero-video-bg" style="transform: translateX(-50%) translateY(-50%) scaleX(-1);"><source src="{{ $mediaUrl }}" type="video/mp4"></video>
    @endif
</div>

<a href="{{ route('landing') }}" class="btn-flotante-regresar">&larr; regresar</a>

<div class="akira-container">
    <div class="akira-header">
        {{-- AQUÍ APLICAMOS LA MAGIA DE LARAVEL (active-link) --}}
        <a href="{{ route('project.detail') ?? '#' }}" class="nav-link-akira {{ request()->routeIs('project.detail') ? 'active-link' : '' }}">Estudio Akiraka ,</a>
        <a href="{{ route('info') ?? '#' }}" class="nav-link-akira {{ request()->routeIs('info') ? 'active-link' : '' }}">Info ,</a>
        <a href="{{ route('contacto') ?? '#' }}" class="nav-link-akira {{ request()->routeIs('contacto') ? 'active-link' : '' }}">Contacto</a>
    </div>

    <div class="akira-description">
        <strong>¿QUIÉNES SOMOS?</strong><br>
        {!! nl2br(e($config->quienes_somos_texto ?? $defaultQuienesSomos)) !!}
    </div>

    <div class="akira-description">
        <strong>VALORES DE LA EMPRESA</strong><br>
        {!! nl2br(e($config->valores_texto ?? $defaultValores)) !!}
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 50px;">
        <div class="team-title" style="margin-bottom: 15px;">Equipo actual</div>
        <div class="team-title" style="margin-bottom: 15px;">Roles dentro de la empresa</div>
    </div>

    <ul class="team-list">
        @foreach($miembros as $miembro)
        <li style="display: grid; grid-template-columns: 1fr 1fr; gap: 50px; align-items: start;">
            <div>
                {{ $miembro['nombre'] }} 
                <span class="text-muted-akira"><br>{{ $miembro['biografia'] }}</span>
            </div>
            <div>
                {{ $miembro['rol'] }}: {{ $miembro['nombre_corto'] }}
            </div>
        </li>
        @endforeach
    </ul>

    <footer class="site-footer-info">
        <div>2026</div>
        <div>
            <a href="#" id="btn-traducir" onclick="cambiarIdioma('en', event)">Read in English</a>
            <a href="#" id="btn-espanol" onclick="cambiarIdioma('es', event)" style="display:none;">Leer en Español</a>
        </div>
    </footer>
</div>

@include('Principal.cita')

<div id="google_translate_element" style="display:none;"></div>
<script type="text/javascript">
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({pageLanguage: 'es', autoDisplay: false}, 'google_translate_element');
    }
</script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

<script>
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
        }
    });
</script>

@endsection