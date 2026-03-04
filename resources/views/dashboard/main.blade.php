@extends('layouts.app')

@section('content')
<div class="dash-admin-view">
    <style>
        /* Estética de Arquitecto para el Dashboard */
        .dash-admin-view {
            height: 100vh;
            width: 100%;
            background-color: #fdfdfd; /* El mismo blanco roto del estudio */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-family: "Garamond", "Baskerville", serif;
            color: #111;
            text-align: center;
            padding: 20px;
        }

        /* El mensaje de Dari */
        .admin-message {
            font-size: clamp(2rem, 5vw, 4rem); /* Tamaño fluido */
            font-weight: normal;
            letter-spacing: 5px;
            text-transform: uppercase;
            margin-bottom: 60px;
            opacity: 0;
            animation: fadeInDown 1.2s ease forwards;
        }

        /* El botón grande de regreso */
        .btn-back-center {
            display: inline-block;
            padding: 25px 80px;
            border: 1px solid #111;
            background: transparent;
            color: #111;
            text-decoration: none;
            font-size: 1.1rem;
            letter-spacing: 4px;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            opacity: 0;
            animation: fadeInUp 1.2s ease 0.5s forwards; /* Empieza un poco después */
        }

        .btn-back-center:hover {
            background-color: #111;
            color: #fff !important;
            transform: scale(1.02);
        }

        /* Animaciones sutiles para el "feeling" de ingeniería/diseño */
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <h1 class="admin-message">
        Aca se encarga Dari
    </h1>

    <a href="{{ route('landing') }}" class="btn-back-center">
        REGRESAR AL ESTUDIO
    </a>
</div>
@endsection