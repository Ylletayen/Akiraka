@php
    // Extraemos la información vital de la base de datos para dársela al cerebro de Aki
    $serviciosChat = \App\Models\Servicio::where('activo', 1)->get();
    $configuracionChat = \App\Models\Configuracion::first();
@endphp

<style>
    .chat-widget-btn {
        position: fixed; bottom: 30px; right: 30px; width: 65px; height: 65px;
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

    .chat-window {
        position: fixed; bottom: 110px; right: 30px; width: 350px; height: 500px;
        background: #fff; border-radius: 15px; box-shadow: 0 15px 40px rgba(0,0,0,0.25);
        display: flex; flex-direction: column; overflow: hidden; z-index: 9999;
        transform: scale(0); transform-origin: bottom right; transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 2px solid #111; font-family: Arial, sans-serif;
    }
    .chat-window.open { transform: scale(1); }

    .chat-header {
        background: #111; color: #fff; padding: 15px; font-family: "Garamond", serif;
        display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #111;
    }
    .chat-header h4 { margin: 0; font-size: 1.1rem; letter-spacing: 1px; }
    .close-chat { cursor: pointer; background: none; border: none; color: #fff; font-size: 1.2rem; transition: transform 0.2s; }
    .close-chat:hover { transform: scale(1.1); color: #ccc; }

    .chat-body {
        flex-grow: 1; padding: 20px 15px; overflow-y: auto; background: #fdfdfd;
        display: flex; flex-direction: column; gap: 15px; scroll-behavior: smooth;
    }

    .msg-bot-container { display: flex; gap: 10px; align-items: flex-end; }
    .bot-avatar-small { width: 35px; height: 35px; border-radius: 50%; object-fit: cover; border: 2px solid #111; background: #fff; flex-shrink: 0; }
    .msg-user-container { display: flex; justify-content: flex-end; }

    .msg { max-width: 80%; padding: 12px 16px; border-radius: 18px; font-size: 0.85rem; line-height: 1.5; word-wrap: break-word; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
    .msg-bot { background: #fff; color: #111; border: 2px solid #111; border-bottom-left-radius: 4px; }
    .msg-user { background: #111; color: #fff; border: 2px solid #111; border-bottom-right-radius: 4px; }

    .chat-footer { padding: 15px; background: #fff; border-top: 2px solid #111; display: flex; gap: 10px; align-items: flex-end; }
    
    .chat-input { 
        flex-grow: 1; border: 1px solid #111; padding: 10px 15px; border-radius: 15px; 
        outline: none; font-size: 0.85rem; transition: border-color 0.3s; 
        resize: none; overflow-y: hidden; min-height: 38px; max-height: 120px; font-family: Arial, sans-serif;
    }
    .chat-input:focus { box-shadow: inset 0 0 0 1px #111; }
    
    .chat-send { background: #111; color: #fff; border: none; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: background 0.3s; flex-shrink: 0;}
    .chat-send:hover { background: #333; }

    .chat-suggestion-btn { background: #fff; border: 1px solid #111; color: #111; padding: 8px 12px; border-radius: 20px; font-size: 0.75rem; cursor: pointer; transition: all 0.2s; white-space: nowrap; }
    .chat-suggestion-btn:hover { background: #111; color: #fff; }
    .suggestions-wrapper { display: flex; gap: 8px; overflow-x: auto; padding-bottom: 5px; margin-bottom: -5px; }
    .suggestions-wrapper::-webkit-scrollbar { height: 4px; }
    .suggestions-wrapper::-webkit-scrollbar-thumb { background: #ccc; border-radius: 4px; }
</style>

<div class="chat-widget-btn" onclick="toggleChat()">
    <img src="{{ asset('images/bot_akira.jpeg') }}" alt="Asistente Arqui" onerror="this.src='{{ asset('images/bot_akira.jpg') }}'">
    <div class="online-dot"></div>
</div>

<div class="chat-window" id="akiraChatWindow"> 
    <div class="chat-header">
        <div style="display: flex; align-items: center; gap: 10px;">
            <img src="{{ asset('images/bot_akira.jpeg') }}" onerror="this.src='{{ asset('images/bot_akira.jpg') }}'" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover; border: 2px solid #fff;">
            <h4>Guardian Aki</h4>
        </div>
        <div class="header-actions">
            <button class="close-chat" onclick="reiniciarChat()" title="Reiniciar Chat" style="margin-right: 10px;"><i class="bi bi-arrow-clockwise"></i></button>
            <button class="close-chat" onclick="toggleChat()"><i class="bi bi-x-lg"></i></button>
        </div>
    </div>
    
    <div class="chat-body" id="chatBody"></div>
    
    <div class="chat-footer" style="flex-direction: column; align-items: stretch;">
        <div class="suggestions-wrapper">
            <button class="chat-suggestion-btn" onclick="procesarEntrada('¿Cuáles son sus horarios?')">Horarios</button>
            <button class="chat-suggestion-btn" onclick="procesarEntrada('¿Tienen un costo mínimo?')">Costos</button>
            <button class="chat-suggestion-btn" onclick="procesarEntrada('¿Qué servicios ofrecen?')">Servicios</button>
        </div>
        <div style="display: flex; gap: 10px; width: 100%; align-items: flex-end;">
            <textarea id="chatInput" class="chat-input" rows="1" placeholder="Hazme una pregunta..."></textarea>
            <button class="chat-send" onclick="procesarEntrada()"><i class="bi bi-send-fill"></i></button>
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
    const chatInputArea = document.getElementById('chatInput');

    chatInputArea.addEventListener('input', function() {
        this.style.height = '38px';
        this.style.height = (this.scrollHeight) + 'px';
    });

    chatInputArea.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); procesarEntrada(); }
    });

    document.addEventListener("DOMContentLoaded", () => {
        renderHistory();
        if (chatState.isOpen) chatWindow.classList.add('open');
    });

    function toggleChat() {
        chatState.isOpen = !chatState.isOpen;
        chatWindow.classList.toggle('open');
        if (chatState.isOpen) {
            scrollToBottom();
            chatInputArea.focus();
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
            let div = document.createElement('div');
            if (msg.sender === 'bot') {
                div.className = 'msg-bot-container';
                div.innerHTML = `<img src="${rutaAvatarBot}" onerror="this.src='{{ asset('images/bot_akira.jpg') }}'" alt="Bot" class="bot-avatar-small"><div class="msg msg-bot">${msg.text}</div>`;
            } else {
                div.className = 'msg-user-container';
                div.innerHTML = `<div class="msg msg-user">${msg.text.replace(/\n/g, '<br>')}</div>`;
            }
            chatBody.appendChild(div);
        });
        scrollToBottom();
    }

    function scrollToBottom() { chatBody.scrollTop = chatBody.scrollHeight; }

    function procesarEntrada(textoDirecto = null) {
        let userInputRaw = textoDirecto !== null ? textoDirecto : chatInputArea.value.trim();
        
        if (!userInputRaw) return; 

        addMessage('user', userInputRaw); 
        
        chatInputArea.value = '';
        chatInputArea.style.height = '38px';

        setTimeout(() => {
            let respuesta = buscarRespuestaAki(userInputRaw);
            addMessage('bot', respuesta);
        }, 600);
    }

    function buscarRespuestaAki(pregunta) {
        let texto = pregunta.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");

        if (texto.includes('horario') || texto.includes('hora') || texto.includes('abierto') || texto.includes('asistencia') || texto.includes('disponible') || texto.includes('atienden')) {
            return "Nuestro horario de atención a clientes y revisión de proyectos es de <b>Lunes a Viernes, de 12:00 PM a 5:00 PM</b>. 🕒 ¿Te puedo ayudar con otra duda?";
        }

        if (texto.includes('precio') || texto.includes('costo') || texto.includes('dinero') || texto.includes('presupuesto') || texto.includes('minimo') || texto.includes('cobran') || texto.includes('cuanto')) {
            return "En Akiraka creemos que cada proyecto arquitectónico es único, por lo que <b>no tenemos un costo 'mínimo' estándar</b>. 💰 Nos adaptamos a tus necesidades y a las condiciones del entorno. Te invitamos a enviarnos un correo en la sección de Contacto para cotizar tu idea.";
        }

        if (texto.includes('servicio') || texto.includes('hacen') || texto.includes('ofrecen') || texto.includes('trabajo') || texto.includes('dedican')) {
            let listaServicios = serviciosDB.map(s => "• <b>" + s.nombre + "</b>: " + s.descripcion).join('<br>');
            return "¡Claro! 📐 Actualmente ofrecemos los siguientes servicios:<br><br>" + listaServicios + "<br><br>Puedes ver ejemplos de nuestro trabajo en la sección de Proyectos.";
        }

        if (texto.includes('contacto') || texto.includes('telefono') || texto.includes('whatsapp') || texto.includes('correo') || texto.includes('email') || texto.includes('ubicacion') || texto.includes('donde') || texto.includes('llamar')) {
            let tel = configDB?.telefono || 'No disponible por ahora';
            let correo = configDB?.correo_contacto || 'akirakaestudio14@gmail.com';
            return "¡Nos encantaría platicar contigo! 🐾<br><br>Puedes escribirnos al correo: <b>" + correo + "</b><br>O llamarnos al número: <b>" + tel + "</b>.";
        }

        if (texto.includes('arquitecto') || texto.includes('akira') || texto.includes('kameta') || texto.includes('quien') || texto.includes('historia')) {
            let quienesSomos = configDB?.quienes_somos_texto || "Somos un estudio de arquitectura que encuentra su filosofía en el concepto japonés de 'akiraka', creado por el Arq. Akira Kameta.";
            return "👷‍♂️ <b>Sobre nosotros:</b><br>" + quienesSomos;
        }

        if (texto.includes('valor') || texto.includes('filosofia') || texto.includes('sostenible') || texto.includes('madera')) {
            let valores = configDB?.valores_texto || "Buscamos la sostenibilidad, la simplicidad y el impacto regenerativo en cada diseño.";
            return "Nuestros pilares son:<br><br>" + valores.replace(/\n/g, '<br>');
        }

        if (texto.includes('cita') || texto.includes('reunion') || texto.includes('agendar') || texto.includes('vernos')) {
            return "Por ahora ya no estoy agendando citas directamente en el chat, ¡pero los humanos del estudio estarán felices de atenderte! 📅 Por favor, ve a la sección de Contacto y mándanos un correo. Te responderemos rapidísimo.";
        }

        if (texto.includes('gracias') || texto.includes('ok') || texto.includes('perfecto') || texto.includes('excelente')) {
            return "¡De nada! 🐾 Aquí estaré si tienes más dudas sobre el estudio. ¡Guau!";
        }
        if (texto === 'hola' || texto === 'buenos dias' || texto === 'buenas tardes') {
            return "¡Hola de nuevo! ¿En qué te puedo ayudar?";
        }
        return "Aki está un poco confundido 🐶. Como soy un asistente virtual, solo sé responder preguntas sobre:<br>• Horarios de atención 🕒<br>• Costos y presupuestos 💰<br>• Nuestros servicios 📐<br>• Información de contacto 📞<br>• Historia del estudio 👷‍♂️<br><br>¿Podrías preguntarme sobre alguno de esos temas o usar los botones de abajo?";
    }
</script>