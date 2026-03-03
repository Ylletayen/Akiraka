@extends('layouts.app')

@section('content')
<style>
    .akira-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 50px 20px;
        font-family: "Georgia", "Times New Roman", serif;
        color: #333;
    }
    .akira-header {
        font-size: 1.2rem;
        margin-bottom: 60px;
    }
    .akira-header strong { font-weight: bold; }
    .akira-header span { color: #ccc; margin-left: 5px; }

    .akira-description {
        font-size: 0.95rem;
        line-height: 1.8;
        margin-bottom: 80px;
        max-width: 850px;
    }

    .team-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 50px;
    }
    .team-title {
        font-weight: bold;
        font-size: 1rem;
        margin-bottom: 25px;
    }
    .team-list {
        list-style: none;
        padding: 0;
        font-size: 0.9rem;
    }
    .team-list li {
        margin-bottom: 15px;
    }
    .text-muted-akira { color: #888; }
</style>

<div class="akira-container">
    <div class="akira-header">
    <a href="{{ route('project.detail') }}" style="text-decoration: none; color: inherit; font-weight: normal;">
        Estudio Akiraka
    </a>

    <span class="text-muted-akira">
        <a href="{{ route('info') }}" style="text-decoration: none; color: #1a1a1a; font-weight: bold;">Info</a>, 
        <a href="{{ route('contacto') }}" style="text-decoration: none; color: inherit;">Contacto</a>.
    </span>
</div>

    <div class="akira-description">
        Estudio Akiraka fue fundado en la Ciudad de México como una práctica dedicada a la arquitectura contemporánea, explorando la relación entre materialidad, luz y espacio.
    </div>

    <div class="team-grid">
        <div>
            <div class="team-title">Equipo actual</div>
            <ul class="team-list">
                <li>pendiente --- <span class="text-muted-akira">(nombre trabajador)</span></li>
                <li>pendiente --- <span class="text-muted-akira">(nombre trabajador)</span></li>
                <li>pendiente --- <span class="text-muted-akira">(nombre trabajador)</span></li>
            </ul>
        </div>
        <div>
            <div class="team-title">Anteriores</div>
            <ul class="team-list">
                <li>Nombre anterior 1</li>
                <li>Nombre anterior 2</li>
                <li>Nombre anterior 3</li>
            </ul>
        </div>
    </div>
</div>
@endsection