@extends('layouts.app')

@section('content')
@php
    $config = \App\Models\Configuracion::first();
    
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
            // Formateamos el nombre corto de forma inteligente (Ej: "Arq. Ana")
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
    .akira-container { max-width: 1000px; margin: 0 auto; padding: 50px 20px; font-family: "Georgia", "Times New Roman", serif; color: #333; }
    .akira-header { font-size: 1.2rem; margin-bottom: 60px; }
    .akira-header strong { font-weight: bold; }
    .akira-header span { color: #ccc; margin-left: 5px; }
    .akira-description { font-size: 0.95rem; line-height: 1.8; margin-bottom: 80px; max-width: 850px; }
    .team-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 50px; }
    .team-title { font-weight: bold; font-size: 1rem; margin-bottom: 25px; }
    .team-list { list-style: none; padding: 0; font-size: 0.9rem; margin-bottom: 40px; }
    
    /* Quitamos el min-height, ahora usaremos CSS Grid en cada fila para un alineamiento perfecto e irrompible */
    .team-list li { margin-bottom: 25px; } 
    .text-muted-akira { color: #888; }

    /* --- ESTILOS PARA EL BOTÓN DE REGRESAR FLOTANTE --- */
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
        font-family: "Garamond", "Baskerville", "Times New Roman", serif;
    }

    .btn-flotante-regresar:hover {
        color: #8c8c8c !important;
        transform: translateX(-5px);
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

<a href="{{ route('landing') }}" class="btn-flotante-regresar">&larr; regresar</a>

<div class="akira-container">
    <div class="akira-header">
        <a href="{{ route('project.detail') }}" style="text-decoration: none; color: inherit; font-weight: normal;">Estudio Akiraka</a>
        <span class="text-muted-akira">
            <a href="{{ route('info') }}" style="text-decoration: none; color: #1a1a1a; font-weight: bold;">Info</a>, 
            <a href="{{ route('contacto') }}" style="text-decoration: none; color: inherit;">Contacto</a>.
        </span>
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

    <div>
        <div style="text-align: left; margin-top: 20px; border-top: 1px solid #eee; padding-top: 30px;">
            <p class="permitir-copiar">
                ESTUDIO DE ARQUITECTURA AKIRAKA<br>
                {!! nl2br(e($config->direccion ?? "Parque Santa María 10, Santa María Ahuacatlán,\n51200 Valle de Bravo, Estado de México")) !!}<br>
                Cel. {{ $config->telefono ?? '722 165 5901' }}<br>
                C.E: administracion@akirakastudio.com y {{ $config->correo_contacto ?? 'akiraka.estudio@gmail.com' }}
            </p>
        </div>
    </div>

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