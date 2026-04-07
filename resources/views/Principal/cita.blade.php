@php
    $serviciosChat = \App\Models\Servicio::where('activo', 1)->get();
@endphp

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" />

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
        position: fixed; bottom: 110px; right: 30px; width: 350px; height: 520px;
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
    .bot-avatar-small { width: 35px; height: 35px; border-radius: 50%; object-fit: cover; border: 2px solid #111; background: #fff; }
    .msg-user-container { display: flex; justify-content: flex-end; }

    .msg { max-width: 80%; padding: 12px 16px; border-radius: 18px; font-size: 0.85rem; line-height: 1.4; word-wrap: break-word; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
    .msg-bot { background: #fff; color: #111; border: 2px solid #111; border-bottom-left-radius: 4px; }
    .msg-user { background: #111; color: #fff; border: 2px solid #111; border-bottom-right-radius: 4px; }

    .chat-footer { padding: 15px; background: #fff; border-top: 2px solid #111; display: flex; gap: 10px; align-items: flex-end;}
    
    .chat-input { 
        flex-grow: 1; border: 1px solid #111; padding: 10px 15px; border-radius: 15px; 
        outline: none; font-size: 0.85rem; transition: border-color 0.3s; 
        resize: none; overflow-y: hidden; min-height: 38px; max-height: 120px; font-family: Arial, sans-serif;
    }
    .chat-input:focus { box-shadow: inset 0 0 0 1px #111; }
    
    /* Arreglo para que el campo del teléfono con banderita no rompa el diseño */
    .iti { width: 100%; flex-grow: 1; }
    
    .chat-send { background: #111; color: #fff; border: none; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: background 0.3s; padding-bottom: 3px; flex-shrink: 0;}
    .chat-send:hover { background: #333; }

    .chat-option-btn { background: #fff; border: 1px solid #111; color: #111; padding: 10px 15px; border-radius: 20px; font-size: 0.8rem; cursor: pointer; margin-top: 8px; transition: all 0.2s; text-align: left; width: 100%; font-weight: bold; }
    .chat-option-btn:hover { background: #111; color: #fff; }

    .small-instruction { display: block; font-size: 0.75rem; color: #888; margin-top: 5px; font-style: italic; }

    /* --- MODAL GIGANTE DE VICTORIA --- */
    .success-modal-overlay {
        position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
        background: rgba(0, 0, 0, 0.7); backdrop-filter: blur(8px);
        display: none; align-items: center; justify-content: center; 
        z-index: 999999; padding: 20px; box-sizing: border-box;
    }
    .success-modal-overlay.active { display: flex; }

    .success-modal-box {
        background: #fff; width: 100%; max-width: 600px; border-radius: 20px;
        padding: 40px; text-align: center; box-shadow: 0 20px 50px rgba(0,0,0,0.3);
        max-height: 90vh; overflow-y: auto; border: 2px solid #111;
    }

    .success-icon-anim { font-size: 4rem; margin-bottom: 10px; display: inline-block; }
    .sm-title { font-family: "Garamond", serif; font-size: 2rem; margin-bottom: 30px; color: #111; }
    
    .sm-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 25px; text-align: left; background: #f9f9f9; padding: 20px; border-radius: 12px; border: 1px solid #eee; }
    .sm-data-text { font-family: Arial, sans-serif; font-size: 0.95rem; color: #555; margin-bottom: 8px; }
    .sm-data-text strong { display: block; color: #111; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px; }

    .btn-cerrar-logro {
        background: #111; color: #fff; border: none; padding: 12px 35px;
        border-radius: 25px; font-weight: bold; letter-spacing: 1px; cursor: pointer;
        transition: background 0.3s; margin-top: 15px; font-size: 0.9rem;
    }
    .btn-cerrar-logro:hover { background: #333; }
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
        <div>
            <button class="close-chat" onclick="reiniciarChat()" title="Reiniciar Chat" style="margin-right: 10px;"><i class="bi bi-arrow-clockwise"></i></button>
            <button class="close-chat" onclick="toggleChat()"><i class="bi bi-x-lg"></i></button>
        </div>
    </div>
    
    <div class="chat-body" id="chatBody"></div>
    
    <div class="chat-footer" id="chatFooter">
        <textarea id="chatInput" class="chat-input" rows="1" placeholder="Escribe aquí tu respuesta..."></textarea>
        
        <input type="tel" id="chatInputPhone" class="chat-input" style="display:none;" onkeypress="handleKeyPress(event)">
        
        <input type="datetime-local" id="chatInputDate" class="chat-input" style="display:none;" onkeypress="handleKeyPress(event)">
        
        <button class="chat-send" onclick="procesarEntrada()"><i class="bi bi-send-fill"></i></button>
    </div>
</div>

<div class="success-modal-overlay" id="modalLogroCita">
    <div class="success-modal-box">
        <div class="success-icon-anim">🎉🐾</div>
        <h3 class="sm-title">¡Solicitud Registrada!</h3>
        
        <div class="sm-grid">
            <div class="sm-data-text"><strong>A nombre de:</strong> <span id="logroNombre"></span></div>
            <div class="sm-data-text"><strong>Contacto:</strong> <span id="logroCorreo"></span></div>
            <div class="sm-data-text"><strong>Servicio:</strong> <span id="logroServicio"></span></div>
            <div class="sm-data-text"><strong>Fecha sugerida:</strong> <span id="logroFecha" style="font-weight: bold; color: #111;"></span></div>
        </div>

        <div class="sm-data-text" style="text-align: left; background: #f9f9f9; padding: 20px; border-radius: 12px; border: 1px solid #eee;">
            <strong>Notas del proyecto:</strong> 
            <span id="logroNotas" style="word-wrap: break-word;"></span>
        </div>
        
        <p style="font-family: 'Garamond', serif; font-size: 1rem; color: #111; margin-top: 25px; font-style: italic;">
            "Te he enviado un <b>correo electrónico</b> con la información y un enlace para agregarlo a tu Google Calendar.<br>Nos comunicaremos al <span id="logroTelefono" style="font-weight:bold;"></span> para confirmar. ¡Gracias por confiar en Akiraka!"
        </p>

        <button class="btn-cerrar-logro" onclick="cerrarModalLogro()">ACEPTAR Y CERRAR</button>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>

<script>
    const serviciosActivos = @json($serviciosChat);
    const rutaAvatarBot = "{{ asset('images/bot_akira.jpeg') }}";
</script>

<script>
    let chatState = JSON.parse(sessionStorage.getItem('akiraChatState')) || {
        isOpen: false,
        step: 1,
        history: [
            { sender: 'bot', text: '¡Hola! ¡Guau! 🐾 Soy Aki, guardián del Estudio Akiraka. Estoy aquí para ayudarte a iniciar tu proyecto. ¿Cuál es tu nombre completo?' }
        ],
        formData: { nombre: '', correo: '', telefono: '', id_servicio: '', fecha_hora: '', notas: '' }
    };

    const chatWindow = document.getElementById('akiraChatWindow');
    const chatBody = document.getElementById('chatBody');
    const chatInputArea = document.getElementById('chatInput');
    const chatInputDate = document.getElementById('chatInputDate');
    const chatInputPhone = document.getElementById('chatInputPhone');

    // Inicializamos el plugin de la banderita en el input de teléfono
    const phoneIti = window.intlTelInput(chatInputPhone, {
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
        preferredCountries: ["mx", "co", "ar", "es"], // México primero
        initialCountry: "mx"
    });

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
        verificarInputEspecial();
    });

    function toggleChat() {
        chatState.isOpen = !chatState.isOpen;
        chatWindow.classList.toggle('open');
        saveState();
        if (chatState.isOpen) {
            scrollToBottom();
            enfocarInputCorrecto();
        }
    }

    function enfocarInputCorrecto() {
        if (chatState.step === 5) chatInputDate.focus();
        else if (chatState.step === 3) chatInputPhone.focus();
        else chatInputArea.focus();
    }

    function reiniciarChat() {
        sessionStorage.removeItem('akiraChatState');
        location.reload(); 
    }

    function saveState() { sessionStorage.setItem('akiraChatState', JSON.stringify(chatState)); }

    function addMessage(sender, text) {
        chatState.history.push({ sender, text });
        saveState();
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
    function handleKeyPress(e) { if (e.key === 'Enter') procesarEntrada(); }

    function procesarEntrada(valorBoton = null, textoBoton = null) {
        // Saber de qué input sacar el valor dependiendo del paso
        let inputActivo = chatInputArea;
        if(chatState.step === 5) inputActivo = chatInputDate;
        if(chatState.step === 3) inputActivo = chatInputPhone;
        
        let userInputRaw = valorBoton !== null ? valorBoton : inputActivo.value.trim();
        let userText = textoBoton !== null ? textoBoton : userInputRaw;

        if (!userInputRaw && chatState.step !== 4) return; 

        // Caso especial paso 3: Extraer el número internacional formateado (ej. +52 722...)
        if(chatState.step === 3 && valorBoton === null) {
            userInputRaw = phoneIti.getNumber(); 
            userText = userInputRaw; // Lo mostramos bonito en el chat
        }

        // Caso especial paso 5: Formatear la fecha para que se vea bonita en el globo
        if(chatState.step === 5 && valorBoton === null) {
            try {
                let dObj = new Date(userInputRaw);
                let opciones = { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric', hour: 'numeric', minute: 'numeric', hour12: true };
                userText = dObj.toLocaleDateString('es-MX', opciones);
            } catch(e) {}
        }

        if(valorBoton === null) addMessage('user', userText); 
        
        inputActivo.value = '';
        if(chatState.step !== 5 && chatState.step !== 3) inputActivo.style.height = '38px';

        let inputMin = userInputRaw.toLowerCase();

        switch (chatState.step) {
            case 1: 
                let nameRegex = /^[\p{L}\s]+$/u; 
                if (!nameRegex.test(userInputRaw)) {
                    addMessage('bot', 'Aki está confundido 🐾. Ese nombre tiene números o símbolos extraños. ¿Podrías escribirlo usando solo letras?');
                    return; 
                }
                if (userInputRaw.length < 3) {
                    addMessage('bot', 'Ese nombre parece muy corto. ¿Podrías escribir tu nombre completo, por favor?');
                    return;
                }

                let tieneVocales = /[aeiouáéíóúü]/i.test(userInputRaw);
                let muchasConsonantes = /[bcdfghjklmnpqrstvwxyz]{4,}/i.test(userInputRaw);
                
                if(!tieneVocales || muchasConsonantes) {
                    chatState.formData.nombre_temporal = userInputRaw; 
                    chatState.step = 1.5;
                    addMessage('bot', `Veo que escribiste "<b>${userInputRaw}</b>". ¿Es correcto?<span class="small-instruction">Escribe <b>Sí</b> o <b>No</b> para responder.</span>`);
                } else {
                    chatState.formData.nombre = userInputRaw;
                    chatState.step = 2;
                    addMessage('bot', `¡Mucho gusto, ${userInputRaw}! 🦴 ¿Me podrías proporcionar tu correo electrónico?`);
                }
                break;
                
            case 1.5: 
                if (inputMin === 'si' || inputMin === 'sí') {
                    chatState.formData.nombre = chatState.formData.nombre_temporal;
                    chatState.step = 2;
                    addMessage('bot', `¡Entendido! ¿Me podrías proporcionar tu correo electrónico?`);
                } else if (inputMin === 'no') {
                    chatState.step = 1;
                    addMessage('bot', `Mi patita resbaló. ¿Me podrías escribir nuevamente tu nombre completo?`);
                } else {
                    addMessage('bot', `Aki no comprendió 🐶. Por favor responde con un <b>Sí</b> o un <b>No</b>.`);
                }
                break;

            case 2: 
                let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(userInputRaw)) {
                    addMessage('bot', 'Ese formato no parece un correo electrónico válido (recuerda usar el @ y un dominio como .com). ¿Podrías escribirlo de nuevo?');
                    return;
                }
                chatState.formData.correo_temporal = userInputRaw;
                chatState.step = 2.5;
                addMessage('bot', `Ingresaste el correo: <b>${userInputRaw}</b>. ¿Estás seguro de registrar este correo?<span class="small-instruction">Escribe <b>Sí</b> o <b>No</b>.</span>`);
                break;

            case 2.5: 
                if (inputMin === 'si' || inputMin === 'sí') {
                    chatState.formData.correo = chatState.formData.correo_temporal;
                    chatState.step = 3;
                    addMessage('bot', '¡Perfecto! Ahora, ¿cuál es tu número de teléfono o WhatsApp? 📱<span class="small-instruction">Asegúrate de seleccionar la bandera de tu país.</span>');
                } else if (inputMin === 'no') {
                    chatState.step = 2;
                    addMessage('bot', 'No te preocupes. Escribe nuevamente tu correo electrónico:');
                } else {
                    addMessage('bot', `Aki está confundido 🐾. Por favor confirma respondiendo <b>Sí</b> o <b>No</b>.`);
                }
                break;

            case 3: 
                // LA MAGIA DE LA LIBRERÍA: Valida si es un número real según las reglas internacionales
                if (!phoneIti.isValidNumber()) {
                    addMessage('bot', 'Ese número no parece válido para el país seleccionado. Por favor, verifica y escríbelo de nuevo:');
                    return;
                }

                chatState.formData.telefono = userInputRaw; // Guarda el formato +52...
                chatState.step = 4;
                let botonesHTML = 'Genial. ¿En cuál de nuestros servicios estás interesado? 📐<br>';
                serviciosActivos.forEach(serv => {
                    botonesHTML += `<button class="chat-option-btn" onclick="seleccionarServicio('${serv.id_servicio}', '${serv.nombre}')">${serv.nombre}</button>`;
                });
                addMessage('bot', botonesHTML);
                break;

            case 4: 
                if(valorBoton !== null) {
                    addMessage('user', userText); 
                    chatState.formData.id_servicio = userInputRaw;
                    chatState.step = 5;
                    addMessage('bot', '¡Me encanta! 👷‍♂️ Selecciona en el calendario de abajo para qué fecha y hora te gustaría que agendáramos la reunión.<span class="small-instruction">🕒 Atención a citas: De 12:00 PM a 5:00 PM.</span>');
                }
                break;

            case 5: 
                let fechaElegida = new Date(userInputRaw);
                let horaElegida = fechaElegida.getHours();
                
                if (horaElegida < 12 || horaElegida > 17 || (horaElegida === 17 && fechaElegida.getMinutes() > 0)) {
                    addMessage('bot', '¡Ups! Recuerda que nuestro horario para citas es entre las 12:00 PM y las 5:00 PM. Por favor elige otro horario en el calendario.');
                    return;
                }

                chatState.formData.fecha_hora = userInputRaw; // Lo guardamos en crudo (Y-m-dTH:i) para Laravel
                chatState.formData.fecha_hora_texto = userText; // Lo guardamos bonito para el Modal
                chatState.step = 6;
                addMessage('bot', '¡Anotado! ✍️ Por último, cuéntame un poco sobre tu proyecto o la idea que tienes en mente: <span class="small-instruction">(Puedes escribir libremente, el cuadro crecerá para ti)</span>');
                break;

            case 6: 
                chatState.formData.notas = userInputRaw;
                addMessage('bot', '⏳ Ladrando... digo, ¡procesando tu solicitud y enviándote un correo! Dame un segundito...');
                chatInputArea.disabled = true;
                enviarCita(chatState.formData);
                break;
        }
        
        verificarInputEspecial();
        saveState();
    }

    function seleccionarServicio(id, nombre) {
        procesarEntrada(id, nombre);
    }

    function verificarInputEspecial() {
        if (chatState.step === 4 || chatState.step > 6) {
            document.getElementById('chatFooter').style.display = 'none';
        } else {
            document.getElementById('chatFooter').style.display = 'flex';
            
            // Ocultamos todos los inputs primero
            chatInputArea.style.display = 'none';
            chatInputDate.style.display = 'none';
            
            // Ocultamos el contenedor .iti (del teléfono)
            let phoneContainer = document.querySelector('.iti');
            if(phoneContainer) phoneContainer.style.display = 'none';

            // Mostramos el que toque
            if (chatState.step === 5) {
                chatInputDate.style.display = 'block';
            } else if (chatState.step === 3) {
                if(phoneContainer) phoneContainer.style.display = 'block';
            } else {
                chatInputArea.style.display = 'block';
                chatInputArea.disabled = false;
            }

            if(chatState.isOpen) enfocarInputCorrecto();
        }
    }

    function enviarCita(data) {
        fetch('{{ route("chatbot.agendar") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if(!response.ok) return response.json().then(err => { throw err; });
            return response.json();
        })
        .then(result => {
            chatState.step = 7; 
            saveState();
            
            document.getElementById('logroNombre').innerText = data.nombre;
            document.getElementById('logroCorreo').innerText = data.correo;
            document.getElementById('logroFecha').innerText = data.fecha_hora_texto;
            document.getElementById('logroTelefono').innerText = data.telefono;
            document.getElementById('logroNotas').innerText = data.notas;
            
            let servicioElegido = serviciosActivos.find(s => s.id_servicio == data.id_servicio);
            document.getElementById('logroServicio').innerText = servicioElegido ? servicioElegido.nombre : 'Servicio General';

            chatWindow.classList.remove('open'); 
            chatState.isOpen = false;
            saveState();
            
            document.getElementById('modalLogroCita').classList.add('active'); 
        })
        .catch(error => {
            console.error("Error de Laravel:", error);
            addMessage('bot', 'Oh no... hubo un error en la Matrix. 🔌 Intenta contactarnos desde la página de Contacto.');
            chatInputArea.disabled = false;
        });
    }

    function cerrarModalLogro() {
        document.getElementById('modalLogroCita').classList.remove('active');
        sessionStorage.removeItem('akiraChatState');
        location.reload();
    }
</script>