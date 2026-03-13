@extends('layouts.app')

@section('content')
@php
    $config = \App\Models\Configuracion::first();
    
    // Textos completos por defecto sin los "..."
    $defaultQuienesSomos = 'Somos AKIRAKA, un estudio de arquitectura que encuentra su nombre y filosofía en el concepto japonés de 明か (akiraka), que significa claro, evidente y brillante. Creado por el arq. Akira Kameta, mexicano - japonés, que lleva su percepción de ambos mundos a una interpretación de solución de los proyectos.';
    $defaultValores = "- Colaboración y Empatía: Se establece una relación con el cliente y la comunidad, diseñando desde un entendimiento profundo de sus necesidades para lograr un éxito compartido.\n- Impacto Regenerativo: El enfoque supera la sostenibilidad convencional buscando la regeneración activa de los ecosistemas y el fortalecimiento del tejido social.\n- Materialidad Sostenible: La madera de origen responsable es la protagonista (\"materia viva\"), valorada por su estética, capacidad de secuestro de carbono y beneficios biológicos.\n- Simplicidad y Honestidad: Se apuesta por la claridad conceptual para transformar ideas complejas en soluciones ejecutables (ideales para la autoconstrucción) y una transparencia radical en cuanto a costos, plazos y origen de los materiales.";
@endphp

<style>
    .akira-container { max-width: 1000px; margin: 0 auto; padding: 50px 20px; font-family: "Georgia", "Times New Roman", serif; color: #333; }
    .akira-header { font-size: 1.2rem; margin-bottom: 60px; }
    .akira-header strong { font-weight: bold; }
    .akira-header span { color: #ccc; margin-left: 5px; }
    .akira-description { font-size: 0.95rem; line-height: 1.8; margin-bottom: 80px; max-width: 850px; }
    .team-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 50px; }
    .team-title { font-weight: bold; font-size: 1rem; margin-bottom: 25px; }
    .team-list { list-style: none; padding: 0; font-size: 0.9rem; }
    .team-list li { margin-bottom: 15px; }
    .text-muted-akira { color: #888; }
</style>

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
        <!-- Aquí está el texto completo limpio -->
        {!! nl2br(e($config->quienes_somos_texto ?? $defaultQuienesSomos)) !!}
    </div>

    <div class="akira-description">
        <strong>VALORES DE LA EMPRESA</strong><br>
        {!! nl2br(e($config->valores_texto ?? $defaultValores)) !!}
    </div>

    <div class="team-grid">
        <div>
            <div class="team-title">Equipo actual</div>
            <ul class="team-list">
                <li>Arq. Alberto Akira Kameta Miyamoto <span class="text-muted-akira"><br>Arquitecto mexicano japonés...</span></li>
                <li>Arq. Ana Regnia Torres Tapia <span class="text-muted-akira"><br>Arquitecta egresada de la Universidad Anáhuac Norte...</span></li>
                <li>Alejandra <span class="text-muted-akira"><br>Licenciada en derecho</span></li>
            </ul>
        </div>
        <div>
            <div class="team-title">Roles dentro de la empresa</div>
            <ul class="team-list">
                <li>Dirección general: Arq. Akira</li>
                <li>Área de proyectos: Arq. Ana</li>
                <li>Área Administrativa: Lic. Alejandra</li>
            </ul>
        </div>
        <div>
            <div style="text-align: left; margin-top: 20px;">
                <p>
                    ESTUDIO DE ARQUITECTURA AKIRAKA<br>
                    {!! nl2br(e($config->direccion ?? "Parque Santa María 10, Santa María Ahuacatlán,\n51200 Valle de Bravo, Estado de México")) !!}<br>
                    Cel. {{ $config->telefono ?? '722 165 5901' }}<br>
                    C.E: administracion@akirakastudio.com y {{ $config->correo_contacto ?? 'akiraka.estudio@gmail.com' }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection