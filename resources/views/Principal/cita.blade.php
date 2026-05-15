@php
    $serviciosChat = \App\Models\Servicio::where('activo', 1)->get();
    $configuracionChat = \App\Models\Configuracion::first();
@endphp

<style>
    /* ========================================================
       BOTÓN PRINCIPAL FLOTANTE (CUADRADO COMPLETO)
       ======================================================== */
    .chat-widget-btn {
        position: fixed; bottom: 30px; right: 30px; 
        width: 60px; height: 60px; /* Tamaño del cuadrado */
        background-color: #fff; /* Fondo blanco por si la imagen tiene transparencias */
        border-radius: 8px; /* Un ligero redondeo en las esquinas se ve más profesional, ponlo en 0 si lo quieres totalmente en punta */
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; box-shadow: 0 8px 25px rgba(0,0,0,0.25);
        z-index: 9998; transition: transform 0.3s; 
        border: 2px solid #111; /* Borde del cuadrado */
        
        /* --- LA CLAVE: 0 padding y overflow hidden --- */
        padding: 5 !important; /* Quitamos todo el espacio interior */
        overflow: hidden; /* Asegura que la imagen no se salga de las esquinas del cuadrado */
    }
    .chat-widget-btn:hover { transform: scale(1.1) rotate(-5deg); }
    
    /* LOGO: Ocupa todo el cuadrado */
    .chat-widget-btn img { 
        width: 100%; height: 100%; 
        object-fit: contain; /* Fuerza a la imagen a rellenar el 100% del cuadrado sin deformarse */
        padding: 0; 
        transform: none; 
    }
    
    .online-dot {
        position: absolute; 
        bottom: -4px; right: -4px; /* Lo movemos un poco más afuera para que no tape tu logo */
        width: 14px; height: 14px;
        background-color: #25d366; border-radius: 50%; border: 2px solid #fff;
    }

    /* Animación del modal de chat (se mantiene igual) */
    @keyframes waterEffect {
        0% { border-radius: 15px; }
        25% { border-radius: 25px 10px; }
        50% { border-radius: 10px 25px; }
        75% { border-radius: 20px 15px; }
        100% { border-radius: 15px; }
    }

    /* ========================================================
       VENTANA DEL CHAT
       ======================================================== */
    .chat-window {
        position: fixed; bottom: 105px; right: 30px; width: 320px; height: 480px; 
        background: #fff; border-radius: 15px; box-shadow: 0 15px 40px rgba(0,0,0,0.25);
        display: flex; flex-direction: column; overflow: hidden; z-index: 9999;
        transform: translateY(50px) scale(0.8); opacity: 0; pointer-events: none; 
        transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), opacity 0.4s;
        transform-origin: bottom right; border: 2px solid #111; font-family: Arial, sans-serif;
        animation: waterEffect 6s ease-in-out infinite;
    }
    .chat-window.open { transform: translateY(0) scale(1); opacity: 1; pointer-events: auto; }

    .chat-header {
        background: #111; color: #fff; padding: 12px 15px; font-family: "Garamond", serif;
        display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #111;
    }
    .chat-header h4 { margin: 0; font-size: 1.1rem; letter-spacing: 1px; }
    .close-chat { cursor: pointer; background: none; border: none; color: #fff; font-size: 1.2rem; transition: transform 0.2s; padding: 0; display: flex; }
    .close-chat:hover { transform: scale(1.1); color: #ccc; }
    .header-actions { display: flex; align-items: center; gap: 15px; }

    .chat-body {
        flex-grow: 1; padding: 15px; overflow-y: auto; background: #fdfdfd;
        display: flex; flex-direction: column; gap: 12px; scroll-behavior: smooth;
    }

    @keyframes slideUpPop {
        0% { opacity: 0; transform: translateY(15px) scale(0.95); }
        100% { opacity: 1; transform: translateY(0) scale(1); }
    }

    /* ========================================================
       AVATARES PEQUEÑOS (Zoom sin romper bordes)
       ======================================================== */
    .msg-bot-container { display: flex; gap: 8px; align-items: flex-end; animation: slideUpPop 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; }
    
        .bot-avatar-wrapper { 
                width: 32px; height: 32px; border-radius: 50%; border: 2px solid #111; 
                background: #fff; /* <--- Fondo blanco para que contraste tu logo */
                flex-shrink: 0; overflow: hidden; 
                display: flex; align-items: center; justify-content: center; 
            }
            .bot-avatar-wrapper img { 
                width: 100%; height: 100%; 
                object-fit: contain; /* <--- Evita que se corte */
                transform: scale(0.8); /* <--- Lo hace un poco más pequeño para que no toque los bordes */
            }
    
    .msg-user-container { display: flex; justify-content: flex-end; animation: slideUpPop 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; }

    .msg { max-width: 85%; padding: 10px 14px; border-radius: 18px; font-size: 0.8rem; line-height: 1.4; word-wrap: break-word; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
    .msg-bot { background: #fff; color: #111; border: 2px solid #111; border-bottom-left-radius: 4px; }
    .msg-user { background: #111; color: #fff; border: 2px solid #111; border-bottom-right-radius: 4px; }

    .msg-bot a { color: #d9534f; font-weight: bold; text-decoration: underline; transition: color 0.3s; }
    .msg-bot a:hover { color: #111; }

    .typing-indicator { display: flex; gap: 4px; align-items: center; justify-content: center; padding: 12px 16px; }
    .typing-dot { width: 6px; height: 6px; background-color: #111; border-radius: 50%; animation: typingAnim 1.4s infinite ease-in-out both; }
    .typing-dot:nth-child(1) { animation-delay: -0.32s; }
    .typing-dot:nth-child(2) { animation-delay: -0.16s; }
    @keyframes typingAnim {
        0%, 80%, 100% { transform: scale(0); }
        40% { transform: scale(1); }
    }

    .chat-footer { 
        padding: 12px; background: #fff; border-top: 2px solid #111; 
        display: flex; flex-direction: column; gap: 10px; align-items: center;
    }

    .options-wrapper {
        display: flex; flex-wrap: wrap; gap: 8px; justify-content: center; width: 100%;
    }

    .chat-option-btn { 
        background: #fff; border: 1px solid #111; color: #111; padding: 8px 14px; 
        border-radius: 20px; font-size: 0.75rem; cursor: pointer; transition: all 0.2s; 
        text-align: center; font-weight: bold; width: auto; 
    }
    .chat-option-btn:hover { background: #111; color: #fff; transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
    
    .btn-agendar-chip { background-color: #111; color: #fff; }
    .btn-agendar-chip:hover { background-color: #333; color: #fff; }

    .small-instruction { display: block; font-size: 0.7rem; color: #888; text-align: center; font-style: italic; margin-bottom: 2px; }
</style>

<div class="chat-widget-btn" onclick="toggleChat()">
    <img src="{{ asset('images/logo_akiraka.png') }}" alt="Asistente Arqui">
    <div class="online-dot"></div>
</div>

<div class="chat-window" id="akiraChatWindow"> 
    <div class="chat-header">
        <div style="display: flex; align-items: center; gap: 10px;">
            <!-- Contenedor Wrapper para el header con Zoom -->
           
            <h4>¿Preguntas Frecuentes?</h4>
        </div>
        <div class="header-actions">
            <button class="close-chat" onclick="reiniciarChat()"><i class="bi bi-arrow-clockwise"></i></button>
            <button class="close-chat" onclick="toggleChat()"><i class="bi bi-x-lg"></i></button>
        </div>
    </div>
    
    <div class="chat-body" id="chatBody"></div>
    
    <div class="chat-footer">
        <span class="small-instruction">Selecciona una opcion:</span>
        <div class="options-wrapper">
            <button class="chat-option-btn btn-agendar-chip" onclick="procesarEntrada('Agendar Cita')">Agendar Cita</button>
            <button class="chat-option-btn" onclick="procesarEntrada('Horarios')">Horarios</button>
            <button class="chat-option-btn" onclick="procesarEntrada('Costos')">Costos</button>
            <button class="chat-option-btn" onclick="procesarEntrada('Servicios')">Servicios</button>
            <button class="chat-option-btn" onclick="procesarEntrada('Contacto')">Contacto</button>
            <button class="chat-option-btn" onclick="procesarEntrada('Nosotros')">Nosotros</button>
        </div>
    </div>
</div>

<script>
    const serviciosDB = @json($serviciosChat);
    const configDB = @json($configuracionChat);
    const rutaAvatarBot = "{{ asset('images/logo_akiraka.png') }}";
</script>

<script>
        let chatState = {
            isOpen: false,
            history: [
                { sender: 'bot', text: 'Bienvenido a Estudio Akiraka. ¿En qué podemos asistirle?' }
            ]
        };

    const chatWindow = document.getElementById('akiraChatWindow');
    const chatBody = document.getElementById('chatBody');
    const footerButtons = document.querySelectorAll('.chat-option-btn');

    document.addEventListener("DOMContentLoaded", () => {
        renderHistory();
        if (chatState.isOpen) chatWindow.classList.add('open');
    });

    function toggleChat() {
        chatState.isOpen = !chatState.isOpen;
        chatWindow.classList.toggle('open');
        if (chatState.isOpen) {
            scrollToBottom();
        }
    }

    function reiniciarChat() {
        location.reload(); 
    }

    function addMessage(sender, text) {
        chatState.history.push({ sender, text });
        renderHistory();
    }

    function renderHistory() {
        chatBody.innerHTML = '';
        chatState.history.forEach(msg => {
            crearBurbuja(msg.sender, msg.text);
        });
        scrollToBottom();
    }

    function crearBurbuja(sender, text) {
        let div = document.createElement('div');
        if (sender === 'bot') {
            div.className = 'msg-bot-container';
            // Insertamos el wrapper para que la bolita del chat también tenga zoom
            div.innerHTML = `
                <div class="bot-avatar-wrapper">
                    <img src="${rutaAvatarBot}">
                </div>
                <div class="msg msg-bot">${text}</div>`;
        } else {
            div.className = 'msg-user-container';
            div.innerHTML = `<div class="msg msg-user">${text}</div>`;
        }
        chatBody.appendChild(div);
    }

    function scrollToBottom() { chatBody.scrollTop = chatBody.scrollHeight; }

    function procesarEntrada(textoDirecto) {
        if (!textoDirecto) return; 

        footerButtons.forEach(btn => btn.disabled = true);
        addMessage('user', textoDirecto); 

        mostrarTyping();

        setTimeout(() => {
            ocultarTyping(); 
            let respuesta = buscarRespuestaAki(textoDirecto);
            addMessage('bot', respuesta); 
            footerButtons.forEach(btn => btn.disabled = false); 
        }, 1200);
    }

    function mostrarTyping() {
        let div = document.createElement('div');
        div.className = 'msg-bot-container';
        div.id = 'typingBubble';
        // Insertamos el wrapper para que la bolita al escribir también tenga zoom
        div.innerHTML = `
            <div class="bot-avatar-wrapper">
                <img src="${rutaAvatarBot}">
            </div>
            <div class="msg msg-bot typing-indicator">
                <div class="typing-dot"></div><div class="typing-dot"></div><div class="typing-dot"></div>
            </div>`;
        chatBody.appendChild(div);
        scrollToBottom();
    }

    function ocultarTyping() {
        let typingBubble = document.getElementById('typingBubble');
        if(typingBubble) typingBubble.remove();
    }

    function buscarRespuestaAki(pregunta) {
            let texto = pregunta.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");

            if (texto.includes('agendar') || texto.includes('cita')) {
                let enlaceContacto = "{{ route('contacto') }}";
                return `Para programar una reunión, por favor acceda a nuestra <a href="${enlaceContacto}">sección de contacto</a> e indique su disponibilidad. Nuestro equipo le confirmará a la mayor brevedad.`;
            }

            if (texto.includes('horario')) {
                return "Nuestro horario de atención es de <b>lunes a viernes, de 10:00 AM a 5:00 PM</b>.";
            }

            if (texto.includes('costo') || texto.includes('presupuesto')) {
                return "Cada proyecto arquitectónico requiere una evaluación precisa. Le invitamos a contactarnos directamente para analizar sus requerimientos y emitir una cotización formal.";
            }

            if (texto.includes('servicio')) {
                let listaServicios = serviciosDB.map(s => "• <b>" + s.nombre + "</b>: " + s.descripcion).join('<br>');
                return "Servicios especializados:<br><br>" + listaServicios + "<br><br>Le invitamos a consultar nuestro portafolio en la sección de Proyectos.";
            }

            if (texto.includes('contacto')) {
                let tel = configDB?.telefono || 'No disponible por el momento';
                let correo = configDB?.correo_contacto || 'akirakaestudio@gmail.com';
                return "Vías de contacto oficiales:<br><br>Correo electrónico: <b>" + correo + "</b><br>Teléfono: <b>" + tel + "</b>";
            }

            if (texto.includes('arquitecto') || texto.includes('akira') || texto.includes('nosotros')) {
                let quienesSomos = configDB?.quienes_somos_texto || "Somos un estudio de arquitectura regido por el concepto japonés de Akiraka, fundado por el Arq. Akira Kameta.";
                return "<b>Estudio Akiraka:</b><br><br>" + quienesSomos;
            }

            return "Por favor, seleccione una de las opciones del menú inferior para continuar.";
        }
</script>