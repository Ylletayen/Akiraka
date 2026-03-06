@extends('layouts.app')

@section('content')
<div class="dash-admin-view">
    <style>
        /* ================= BASE ================= */
        .dash-admin-view {
            min-height: 100vh;
            background: #f8f8f8;
            font-family: "Garamond", "Baskerville", serif;
            padding: 20px;
            display: flex;
            justify-content: center;
        }

        .dashboard-container {
            display: flex;
            width: 100%;
            max-width: 1400px;
            gap: 20px;
            align-items: stretch;
        }

        /* ================= SIDEBAR ================= */
        .sidebar {
            width: 260px;
            background: #1c1c1c;
            color: #fff;
            padding: 25px;
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .logo-sidebar {
            width: 80px;
            border-radius: 50%;
            margin-bottom: 10px;
            background: #fff;
            padding: 5px;
        }

        .nav-link {
            color: #fff;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            background: #2c2c2c;
            border-radius: 8px;
            padding: 10px 15px;
            transition: all .3s ease;
            text-decoration: none;
            font-family: Arial, sans-serif;
            font-size: .9rem;
        }

        .nav-link:hover,
        .nav-link.active {
            background: #4b4b4b;
        }

        /* ================= MAIN ================= */
        .main-content {
            flex-grow: 1;
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, .05);
        }

        .header-section {
            border-bottom: 1px solid #eaeaea;
            padding-bottom: 20px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-section h1 {
            font-size: 2rem;
            margin: 0;
        }

        /* ================= MENSAJES ================= */
        .message-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .message-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 25px 10px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
        }

        .message-item:hover {
            background: #fafafa;
        }

        .message-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .message-name {
            font-size: 1.3rem;
        }

        .message-subject {
            color: #888;
            font-size: .9rem;
        }

        .message-date {
            font-size: .8rem;
            font-weight: bold;
        }

        .btn-delete {
            border: none;
            background: none;
            cursor: pointer;
            font-size: 1rem;
        }

        /* ================= MODAL ================= */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, .5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 999;
        }

        .modal-box {
            background: #fff;
            padding: 40px;
            width: 500px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .2);
        }

        .modal-box h3 {
            margin-top: 0;
        }

        .modal-actions {
            margin-top: 20px;
            display: flex;
            gap: 10px;
        }

        .modal-actions button {
            padding: 10px 20px;
            border: none;
            cursor: pointer;
        }

        .btn-dark {
            background: #000;
            color: #fff;
        }

        .btn-light {
            background: #eee;
        }

        textarea {
            width: 100%;
            height: 120px;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ccc;
        }
    </style>

    <div class="dashboard-container">
        <aside class="sidebar">
            <div>
                <div class="text-center">
                    <img src="{{ asset('images/logo_akiraka.png') }}" alt="Logo" class="logo-img">
                    <p class="small mb-4 text-uppercase" style="letter-spacing: 2px;">Akiraka Estudio</p>
                </div>
                
                <ul class="nav flex-column" style="list-style: none; padding: 0;">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard.main') }}"><i class="fas fa-home me-2"></i> Inicio <span class="icon-badge"></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-pencil-alt me-2"></i> Proyectos <span class="icon-badge"></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('dashboard.quienes_somos') }}"><i class="fas fa-globe me-2"></i> Quienes somos <span class="icon-badge"></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-newspaper me-2"></i> Publicaciones <span class="icon-badge"></a>
                    </li>
                    <li class="nav-item">
                         <a href="{{ route('mensajes') }}" class="nav-link active"><i class="fas fa-globe me-2"></i>Mensajes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard.opciones') }}"><i class="fas fa-cog me-2"></i> Opciones <span class="icon-badge"></a>
                    </li>
                </ul>
            </div>
            
            <div>
                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="nav-link border-top pt-3 w-100 text-start" style="background:none; border:none; cursor:pointer;">
                        <i class="fas fa-sign-out-alt me-2"></i> Salir <span class="icon-badge">
                    </button>
                </form>
                <div class="mt-4 text-center" style="font-size: 0.75rem; color: #888;">
                    <p class="mb-0">© {{ date('Y') }} AKIRAKA ESTUDIO</p>
                </div>
            </div>
        </aside>

        <div class="main-content">
            <div class="header-section">
                <h1>Mensajes</h1>
                <span>Marcar todos como leídos</span>
            </div>

            <ul class="message-list">
                <li class="message-item" onclick="abrirMensaje('Elena Firne','Consulta de presupuesto','Estimados, quisiera información sobre costos de remodelación.')">
                    <div class="message-left">
                        <div class="message-name">• Elena Firne</div>
                        <div class="message-subject">Consulta de presupuesto</div>
                    </div>
                    <div>
                        <span class="message-date">05 FEB</span>
                        <button class="btn-delete" onclick="abrirEliminar(event,'Elena Firne')">🗑</button>
                    </div>
                </li>

                <li class="message-item" onclick="abrirMensaje('Maria Ana','Colaboración Editorial','Nos gustaría colaborar con su estudio en una publicación.')">
                    <div class="message-left">
                        <div class="message-name">Maria Ana</div>
                        <div class="message-subject">Colaboración Editorial</div>
                    </div>
                    <div>
                        <span class="message-date">04 FEB</span>
                        <button class="btn-delete" onclick="abrirEliminar(event,'Maria Ana')">🗑</button>
                    </div>
                </li>
            </ul>
        </div>
    </div>

    <div id="modalMensaje" class="modal-overlay">
        <div class="modal-box">
            <h3 id="tituloMensaje"></h3>
            <p id="contenidoMensaje"></p>
            <div class="modal-actions">
                <button class="btn-dark" onclick="abrirResponder()">Responder</button>
                <button class="btn-light" onclick="cerrarTodo()">Cerrar</button>
            </div>
        </div>
    </div>

    <div id="modalResponder" class="modal-overlay">
        <div class="modal-box">
            <h3>Redactando respuesta</h3>
            <textarea placeholder="Escriba su respuesta"></textarea>
            <div class="modal-actions">
                <button class="btn-dark">Enviar respuesta</button>
                <button class="btn-light" onclick="cerrarTodo()">Cancelar</button>
            </div>
        </div>
    </div>

    <div id="modalEliminar" class="modal-overlay">
        <div class="modal-box">
            <h3>¿Eliminar mensaje?</h3>
            <p id="nombreEliminar"></p>
            <div class="modal-actions">
                <button class="btn-dark">Eliminar definitivamente</button>
                <button class="btn-light" onclick="cerrarTodo()">Cancelar</button>
            </div>
        </div>
    </div>

    <script>
        function abrirMensaje(nombre, asunto, contenido) {
            document.getElementById("tituloMensaje").innerText = nombre + " - " + asunto;
            document.getElementById("contenidoMensaje").innerText = contenido;
            document.getElementById("modalMensaje").style.display = "flex";
        }

        function abrirResponder() {
            cerrarTodo();
            document.getElementById("modalResponder").style.display = "flex";
        }

        function abrirEliminar(event, nombre) {
            event.stopPropagation();
            document.getElementById("nombreEliminar").innerText = "El mensaje de " + nombre + " se perderá.";
            document.getElementById("modalEliminar").style.display = "flex";
        }

        function cerrarTodo() {
            document.getElementById("modalMensaje").style.display = "none";
            document.getElementById("modalResponder").style.display = "none";
            document.getElementById("modalEliminar").style.display = "none";
        }
    </script>
</div>
@endsection