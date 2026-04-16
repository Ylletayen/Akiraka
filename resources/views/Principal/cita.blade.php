@php
    $serviciosChat = \App\Models\Servicio::where('activo', 1)->get();
    $configuracionChat = \App\Models\Configuracion::first();
@endphp

<style>
    /* Botón Flotante */
    .chat-widget-btn {
        position: fixed; bottom: 30px; right: 30px; width: 60px; height: 60px;
        background-color: #fff; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; box-shadow: 0 8px 25px rgba(0,0,0,0.25);
        z-index: 9998; transition: transform 0.3s; border: 3px solid #111;
    }
    .chat-widget-btn:hover { transform: scale(1.1) rotate(-5deg); }
    .chat-widget-btn img { width: 100%; height: 100%; border-radius: 50%; object-fit: cover; }
    
    .online-dot {
        position: absolute; bottom: 2px; right: 2px; width: 14px; height: 14px;
        background-color: #25d366; border-radius: 50%; border: 2px solid #fff;
    }

    /* --- Ventana del Chat --- */
    .chat-window {
        position: fixed; bottom: 105px; right: 30px; width: 320px; height: 480px; 
        background: #fff; border-radius: 15px; box-shadow: 0 15px 40px rgba(0,0,0,0.25);
        display: flex; flex-direction: column; overflow: hidden; z-index: 9999;
        transform: translateY(50px) scale(0.8); opacity: 0; pointer-events: none; 
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        transform-origin: bottom right; border: 2px solid #111; font-family: Arial, sans-serif;
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

    /* Animaciones de Burbujas */
    @keyframes slideUpPop {
        0% { opacity: 0; transform: translateY(15px) scale(0.95); }
        100% { opacity: 1; transform: translateY(0) scale(1); }
    }

    .msg-bot-container { display: flex; gap: 8px; align-items: flex-end; animation: slideUpPop 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; }
    .bot-avatar-small { width: 32px; height: 32px; border-radius: 50%; object-fit: cover; border: 2px solid #111; background: #fff; flex-shrink: 0; }
    
    .msg-user-container { display: flex; justify-content: flex-end; animation: slideUpPop 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; }

    .msg { max-width: 85%; padding: 10px 14px; border-radius: 18px; font-size: 0.8rem; line-height: 1.4; word-wrap: break-word; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
    .msg-bot { background: #fff; color: #111; border: 2px solid #111; border-bottom-left-radius: 4px; }
    .msg-user { background: #111; color: #fff; border: 2px solid #111; border-bottom-right-radius: 4px; }

    /* Animación de Escribiendo */
    .typing-indicator { display: flex; gap: 4px; align-items: center; justify-content: center; padding: 12px 16px; }
    .typing-dot { width: 6px; height: 6px; background-color: #111; border-radius: 50%; animation: typingAnim 1.4s infinite ease-in-out both; }
    .typing-dot:nth-child(1) { animation-delay: -0.32s; }
    .typing-dot:nth-child(2) { animation-delay: -0.16s; }
    @keyframes typingAnim {
        0%, 80%, 100% { transform: scale(0); }
        40% { transform: scale(1); }
    }

    /* --- FOOTER Y BOTONES (CHIPS) COMPACTOS --- */
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
        text-align: center; font-weight: bold; width: auto; /* Ancho dinámico */
    }
    .chat-option-btn:hover { background: #111; color: #fff; transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.1); }

    .small-instruction { display: block; font-size: 0.7rem; color: #888; text-align: center; font-style: italic; margin-bottom: 2px; }
</style>

<div class="chat-widget-btn" onclick="toggleChat()">
    <img src="{{ asset('images/bot_akira.jpeg') }}" alt="Asistente Arqui" onerror="this.src='{{ asset('images/bot_akira.jpg') }}'">
    <div class="online-dot"></div>
</div>

<div class="chat-window" id="akiraChatWindow"> 
    <div class="chat-header">
        <div style="display: flex; align-items: center; gap: 10px;">
            <img src="{{ asset('images/bot_akira.jpeg') }}" onerror="this.src='{{ asset('images/bot_akira.jpg') }}'" style="width: 28px; height: 28px; border-radius: 50%; object-fit: cover; border: 2px solid #fff;">
            <h4>Guardian Aki</h4>
        </div>
        <div class="header-actions">
            <button class="close-chat" onclick="reiniciarChat()" title="Reiniciar Chat"><i class="bi bi-arrow-clockwise"></i></button>
            <button class="close-chat" onclick="toggleChat()"><i class="bi bi-x-lg"></i></button>
        </div>
    </div>
    
    <div class="chat-body" id="chatBody"></div>
    
    <div class="chat-footer">
        <span class="small-instruction">Selecciona una opción:</span>
        <div class="options-wrapper">
            <button class="chat-option-btn" onclick="procesarEntrada('Horarios')">🕒 Horarios</button>
            <button class="chat-option-btn" onclick="procesarEntrada('Costos')">💰 Costos</button>
            <button class="chat-option-btn" onclick="procesarEntrada('Servicios')">📐 Servicios</button>
            <button class="chat-option-btn" onclick="procesarEntrada('Contacto')">📞 Contacto</button>
            <button class="chat-option-btn" onclick="procesarEntrada('Nosotros')">👷‍♂️ Nosotros</button>
        </div>
    </div>
</div>

<script>
    const serviciosDB = @json($serviciosChat);
    const configDB = @json($configuracionChat);
    const rutaAvatarBot = "{{ asset('images/bot_akira.jpeg') }}";
</script>

<script>
    let chatState = {
        isOpen: false,
        history: [
            { sender: 'bot', text: '¡Hola! ¡Guau! 🐾 Soy Aki, el asistente virtual del Estudio Akiraka. ¿En qué te puedo ayudar hoy?' }
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
            div.innerHTML = `<img src="${rutaAvatarBot}" onerror="this.src='{{ asset('images/bot_akira.jpg') }}'" alt="Bot" class="bot-avatar-small"><div class="msg msg-bot">${text}</div>`;
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
        div.innerHTML = `
            <img src="${rutaAvatarBot}" onerror="this.src='{{ asset('images/bot_akira.jpg') }}'" class="bot-avatar-small">
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

    // CEREBRO DE AKI
    function buscarRespuestaAki(pregunta) {
        let texto = pregunta.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");

        if (texto.includes('horario')) {
            return "Nuestro horario de atención a clientes y revisión de proyectos es de <b>Lunes a Viernes, de 12:00 PM a 5:00 PM</b>. 🕒 ¿Te puedo ayudar con otra duda?";
        }

        if (texto.includes('costo') || texto.includes('presupuesto')) {
            return "En Akiraka creemos que cada proyecto arquitectónico es único, por lo que <b>no tenemos un costo 'mínimo' estándar</b>. 💰 Nos adaptamos a tus necesidades. Te invitamos a enviarnos un correo en la sección de Contacto para cotizar tu idea.";
        }

        if (texto.includes('servicio')) {
            let listaServicios = serviciosDB.map(s => "• <b>" + s.nombre + "</b>: " + s.descripcion).join('<br>');
            return "¡Claro! 📐 Actualmente ofrecemos los siguientes servicios:<br><br>" + listaServicios + "<br><br>Puedes ver ejemplos de nuestro trabajo en la sección de Proyectos.";
        }

        if (texto.includes('contacto')) {
            let tel = configDB?.telefono || 'No disponible por ahora';
            let correo = configDB?.correo_contacto || 'akirakaestudio14@gmail.com';
            return "¡Nos encantaría platicar contigo! 🐾<br><br>Puedes escribirnos al correo: <b>" + correo + "</b><br>O llamarnos al número: <b>" + tel + "</b>.";
        }

        if (texto.includes('arquitecto') || texto.includes('akira') || texto.includes('Estudio')) {
            let quienesSomos = configDB?.quienes_somos_texto || "Somos un estudio de arquitectura que encuentra su filosofía en el concepto japonés de 'akiraka', creado por el Arq. Akira Kameta.";
            return "👷‍♂️ <b>Sobre nosotros:</b><br>" + quienesSomos;
        }

        return "Aki está un poco confundido 🐶. Intenta seleccionar otra opción de abajo.";
    }
</script>