@extends('layouts.app')

@section('content')
<div class="dash-admin-view">
    <style>
        /* ================= TODA TU BASE ORIGINAL INTACTA ================= */
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

        /* Estilos del Sidebar mantenidos para que el Partial se vea bien */
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

        /* ================= ESTILOS DE MENSAJES ================= */
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

        .header-section h1 { font-size: 2rem; margin: 0; }

        .message-list { list-style: none; padding: 0; margin: 0; }

        .message-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 25px 10px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
        }

        .message-item:hover { background: #fafafa; }

        .message-left { display: flex; align-items: center; gap: 20px; }

        .message-name { font-size: 1.3rem; }

        .message-subject { color: #888; font-size: .9rem; }

        .message-date { font-size: .8rem; font-weight: bold; }

        .btn-delete { border: none; background: none; cursor: pointer; font-size: 1rem; }

        /* ================= ESTILOS DE MODAL ================= */
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

        .modal-actions { margin-top: 20px; display: flex; gap: 10px; }

        .modal-actions button { padding: 10px 20px; border: none; cursor: pointer; }

        .btn-dark { background: #000; color: #fff; }

        .btn-light { background: #eee; }

        textarea { width: 100%; height: 120px; padding: 10px; margin-top: 10px; border: 1px solid #ccc; }
        /* MENSAJE NO LEIDO */
        .no-leido .message-name{
            font-weight: bold;
        }

        .no-leido .message-subject{
            font-weight: bold;
        }

        /* CIRCULO DE NOTIFICACION */
        .notificacion{
            display:none;
        }

        .no-leido .notificacion{
            display:inline-block;
            width:8px;
            height:8px;
            background:red;
            border-radius:50%;
            margin-right:6px;
            animation:parpadear 1s infinite;
        }

        @keyframes parpadear{
            0%{opacity:1;}
            50%{opacity:.2;}
            100%{opacity:1;}
        }

    </style>

    <div class="dashboard-container">
        
        @include('partials.sidebar')

        <div class="main-content">

        <!-- EL TOPBAR LIMPIO VA AQUÍ ARRIBA -->
            @include('partials.topbar')
            
            <div class="header-section">
                <h1>Mensajes</h1>
                <span onclick="marcarTodos()" style="cursor:pointer;">Marcar todos como leídos</span>
            </div>

            <ul class="message-list">
               <li class="message-item no-leido"onclick="abrirMensaje(this,'Elena Firne','Consulta de presupuesto','Estimados, quisiera información sobre costos de remodelación.')">
                    <div class="message-left">
                        <div class="message-name"> Elena Firne</div>
                        <div class="message-subject">Consulta de presupuesto</div>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500"><span class="notificacion"></span> 05/03/2026</span>
                        <button class="btn-delete" onclick="abrirEliminar(event,'Elena Firne')">🗑</button>
                    </div>
                </li>

                <li class="message-item no-leido" onclick="abrirMensaje(this,'Maria Ana','Colaboración Editorial','Nos gustaría colaborar con su estudio en una publicación.')">
                    <div class="message-left">
                        <div class="message-name">Maria Ana</div>
                        <div class="message-subject">Colaboración Editorial</div>
                    </div>
                    <div>
                       <span class="text-sm text-gray-500"><span class="notificacion"></span> 04/04/2026</span>
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
           function abrirMensaje(elemento,nombre,asunto,contenido) {
            document.getElementById("tituloMensaje").innerText = nombre + " - " + asunto;
            document.getElementById("contenidoMensaje").innerText = contenido;
            document.getElementById("modalMensaje").style.display = "flex";

            // quitar estado no leído
            elemento.classList.remove("no-leido");
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
        function marcarTodos(){
            let mensajes = document.querySelectorAll(".message-item");

            mensajes.forEach(function(msg){
                msg.classList.remove("no-leido");
            });
        }
    </script>
</div>
@endsection