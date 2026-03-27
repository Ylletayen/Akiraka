@php
    $serviciosChat = \App\Models\Servicio::where('activo', 1)->get();
@endphp

<style>
    /* --- Botón Flotante (Arqui-dog) --- */
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

    /* --- Ventana del Chat (Estilo curvo y amigable) --- */
    .chat-window {
        position: fixed; bottom: 110px; right: 30px; width: 350px; height: 520px;
        background: #fff; border-radius: 15px; box-shadow: 0 15px 40px rgba(0,0,0,0.25);
        display: flex; flex-direction: column; overflow: hidden; z-index: 9999;
        transform: scale(0); transform-origin: bottom right; transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 1px solid #eaeaea; font-family: Arial, sans-serif;
    }
    .chat-window.open { transform: scale(1); }

    .chat-header {
        background: #111; color: #fff; padding: 15px; font-family: "Garamond", serif;
        display: flex; justify-content: space-between; align-items: center;
    }
    .chat-header h4 { margin: 0; font-size: 1.1rem; letter-spacing: 1px; }
    .close-chat { cursor: pointer; background: none; border: none; color: #fff; font-size: 1.2rem; transition: color 0.2s; }
    .close-chat:hover { color: #d9534f; }

    .chat-body {
        flex-grow: 1; padding: 20px 15px; overflow-y: auto; background: #fdfdfd;
        display: flex; flex-direction: column; gap: 15px;
    }

    .msg-bot-container { display: flex; gap: 10px; align-items: flex-end; }
    .bot-avatar-small { width: 35px; height: 35px; border-radius: 50%; object-fit: cover; border: 1px solid #ddd; background: #fff; }
    .msg-user-container { display: flex; justify-content: flex-end; }

    .msg { max-width: 80%; padding: 12px 16px; border-radius: 18px; font-size: 0.85rem; line-height: 1.4; word-wrap: break-word; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
    .msg-bot { background: #fff; color: #111; border: 1px solid #eee; border-bottom-left-radius: 4px; }
    .msg-user { background: #111; color: #fff; border-bottom-right-radius: 4px; }

    .chat-footer { padding: 15px; background: #fff; border-top: 1px solid #eee; display: flex; gap: 10px; }
    .chat-input { flex-grow: 1; border: 1px solid #ccc; padding: 10px 15px; border-radius: 25px; outline: none; font-size: 0.85rem; transition: border-color 0.3s; }
    .chat-input:focus { border-color: #111; }
    .chat-send { background: #111; color: #fff; border: none; width: 42px; height: 42px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: background 0.3s; }
    .chat-send:hover { background: #333; }

    .chat-option-btn { background: #fff; border: 1px solid #111; color: #111; padding: 10px 15px; border-radius: 20px; font-size: 0.8rem; cursor: pointer; margin-top: 8px; transition: all 0.2s; text-align: left; width: 100%; font-weight: bold; }
    .chat-option-btn:hover { background: #111; color: #fff; }

    /* --- MODAL GIGANTE DE VICTORIA (SIN ANIMACIONES, COLORES SOBRIOS) --- */
    .success-modal-overlay {
        position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
        background: rgba(0, 0, 0, 0.7); backdrop-filter: blur(8px);
        display: none; align-items: center; justify-content: center; 
        z-index: 999999; padding: 20px; box-sizing: border-box;
    }
    .success-modal-overlay.active { display: flex; }

    .success-modal-box {
        background: #fff; width: 100%; max-width: 600px; border-radius: 20px;
        padding: 30px; text-align: center; box-shadow: 0 20px 50px rgba(0,0,0,0.3);
        max-height: 90vh; overflow-y: auto; 
    }

    .success-icon-anim {
        font-size: 4rem; margin-bottom: 10px; display: inline-block;
    }

    .sm-title { font-family: "Garamond", serif; font-size: 2rem; margin-bottom: 20px; color: #111; }
    .sm-data-text { font-family: Arial, sans-serif; font-size: 0.95rem; color: #555; margin-bottom: 8px; }
    .sm-data-text strong { color: #111; }

    /* --- TABLA BLANCO / NEGRO / GRIS --- */
    .table-horarios {
        width: 100%; border-collapse: separate; border-spacing: 4px; margin: 20px 0;
        font-family: Arial, sans-serif; font-size: 0.8rem;
    }
    .table-horarios th { padding: 10px 5px; border-radius: 8px; font-weight: bold; }
    .table-horarios td { padding: 10px 5px; background: #f9f9f9; border-radius: 8px; color: #666; transition: all 0.3s; }
    
    /* Cabeceras de días en NEGRO sólido */
    .col-hora { background: #eee; font-weight: bold; color: #111 !important; }
    .th-lun, .th-mar, .th-mie, .th-jue, .th-vie { background: #111; color: #fff; }

    /* Celdas activas en GRIS elegante */
    .celda-activa { background: #777 !important; color: #fff !important; font-weight: bold; box-shadow: 0 4px 10px rgba(0,0,0,0.15); }
    .celda-tentativa { background: #dcdcdc !important; color: #333 !important; font-weight: bold; }

    .btn-cerrar-logro {
        background: #111; color: #fff; border: none; padding: 12px 35px;
        border-radius: 25px; font-weight: bold; letter-spacing: 1px; cursor: pointer;
        transition: background 0.3s; margin-top: 15px; font-size: 0.9rem;
    }
    .btn-cerrar-logro:hover { background: #333; }
</style>

<div class="chat-widget-btn" onclick="toggleChat()">
    <img src="{{ asset('images/bot_akira.jpeg') }}" alt="Asistente Arqui">
    <div class="online-dot"></div>
</div>

<div class="chat-window" id="akiraChatWindow">
    <div class="chat-header">
        <div style="display: flex; align-items: center; gap: 10px;">
            <img src="{{ asset('images/bot_akira.jpeg') }}" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover; border: 2px solid #fff;">
            <h4>Arqui-Bot</h4>
        </div>
        <div>
            <button class="close-chat" onclick="reiniciarChat()" title="Reiniciar Chat" style="margin-right: 10px;"><i class="bi bi-arrow-clockwise"></i></button>
            <button class="close-chat" onclick="toggleChat()"><i class="bi bi-x-lg"></i></button>
        </div>
    </div>
    
    <div class="chat-body" id="chatBody"></div>
    
    <div class="chat-footer" id="chatFooter">
        <input type="text" id="chatInput" class="chat-input" placeholder="Escribe aquí tu respuesta..." autocomplete="off" onkeypress="handleKeyPress(event)">
        <button class="chat-send" onclick="procesarEntrada()"><i class="bi bi-send-fill"></i></button>
    </div>
</div>

<div class="success-modal-overlay" id="modalLogroCita">
    <div class="success-modal-box">
        <div class="success-icon-anim">🎉🐾</div>
        <h3 class="sm-title">¡Solicitud Registrada!</h3>
        
        <p class="sm-data-text"><strong>A nombre de:</strong> <span id="logroNombre"></span></p>
        <p class="sm-data-text"><strong>Servicio solicitado:</strong> <span id="logroServicio"></span></p>
        <p class="sm-data-text"><strong>Fecha sugerida por ti:</strong> <span id="logroFecha"></span></p>

        <table class="table-horarios" id="tablaHorariosMagica">
            <thead>
                <tr>
                    <th class="col-hora">Hora</th>
                    <th class="th-lun">Lun</th><th class="th-mar">Mar</th><th class="th-mie">Mié</th><th class="th-jue">Jue</th><th class="th-vie">Vie</th>
                </tr>
            </thead>
            <tbody>
                <tr id="row-12"><td class="col-hora">12:00 PM</td><td class="c-1">Libre</td><td class="c-2">Libre</td><td class="c-3">Libre</td><td class="c-4">Libre</td><td class="c-5">Libre</td></tr>
                <tr id="row-1"><td class="col-hora">1:00 PM</td><td class="c-1">Libre</td><td class="c-2">Libre</td><td class="c-3">Libre</td><td class="c-4">Libre</td><td class="c-5">Libre</td></tr>
                <tr id="row-2"><td class="col-hora">2:00 PM</td><td class="c-1">Libre</td><td class="c-2">Libre</td><td class="c-3">Libre</td><td class="c-4">Libre</td><td class="c-5">Libre</td></tr>
                <tr id="row-3"><td class="col-hora">3:00 PM</td><td class="c-1">Libre</td><td class="c-2">Libre</td><td class="c-3">Libre</td><td class="c-4">Libre</td><td class="c-5">Libre</td></tr>
                <tr id="row-4"><td class="col-hora">4:00 PM</td><td class="c-1">Libre</td><td class="c-2">Libre</td><td class="c-3">Libre</td><td class="c-4">Libre</td><td class="c-5">Libre</td></tr>
            </tbody>
        </table>

        <p class="sm-data-text"><strong>Notas:</strong> <span id="logroNotas"></span></p>
        
        <p style="font-family: 'Garamond', serif; font-size: 1rem; color: #111; margin-top: 15px; font-style: italic;">
            "Revisaremos nuestros horarios y nos comunicaremos contigo al <span id="logroCorreo" style="font-weight:bold;"></span> para confirmarlo."
        </p>

        <button class="btn-cerrar-logro" onclick="cerrarModalLogro()">ACEPTAR Y CERRAR</button>
    </div>
</div>

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
    const chatInput = document.getElementById('chatInput');

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
            chatInput.focus();
        }
    }

    function reiniciarChat() {
        sessionStorage.removeItem('akiraChatState');
        location.reload(); 
    }

    function saveState() {
        sessionStorage.setItem('akiraChatState', JSON.stringify(chatState));
    }

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
                div.innerHTML = `<img src="${rutaAvatarBot}" alt="Bot" class="bot-avatar-small"><div class="msg msg-bot">${msg.text}</div>`;
            } else {
                div.className = 'msg-user-container';
                div.innerHTML = `<div class="msg msg-user">${msg.text}</div>`;
            }
            chatBody.appendChild(div);
        });
        scrollToBottom();
    }

    function scrollToBottom() {
        chatBody.scrollTop = chatBody.scrollHeight;
    }

    function handleKeyPress(e) {
        if (e.key === 'Enter') procesarEntrada();
    }

    function procesarEntrada(valorBoton = null, textoBoton = null) {
        let userInput = valorBoton !== null ? valorBoton : chatInput.value.trim();
        let userText = textoBoton !== null ? textoBoton : userInput;

        if (!userInput && chatState.step !== 4) return; 

        if(valorBoton === null) addMessage('user', userText); 
        chatInput.value = '';

        switch (chatState.step) {
            case 1: 
                chatState.formData.nombre = userInput;
                chatState.step = 2;
                addMessage('bot', `¡Mucho gusto, ${userInput}! 🦴 ¿Me podrías proporcionar tu correo electrónico?`);
                break;
            case 2: 
                if (!userInput.includes('@')) {
                    addMessage('bot', 'Hmmm... ese no parece un correo válido. 🤔 ¿Podrías escribirlo de nuevo?');
                    return;
                }
                chatState.formData.correo = userInput;
                chatState.step = 3;
                addMessage('bot', '¡Perfecto! Ahora, ¿cuál es tu número de teléfono o WhatsApp? 📱');
                break;
            case 3: 
                chatState.formData.telefono = userInput;
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
                    chatState.formData.id_servicio = userInput;
                    chatState.step = 5;
                    addMessage('bot', '¡Me encanta! 👷‍♂️ ¿Para qué fecha y hora te gustaría que agendáramos la reunión? (Ej. Viernes de 2 a 4pm)');
                }
                break;
            case 5: 
                chatState.formData.fecha_hora = userInput;
                chatState.step = 6;
                addMessage('bot', '¡Anotado! ✍️ Por último, cuéntame un poco sobre tu proyecto o la idea que tienes en mente:');
                break;
            case 6: 
                chatState.formData.notas = userInput;
                addMessage('bot', '⏳ Ladrando... digo, ¡procesando tu solicitud! Dame un segundito...');
                chatInput.disabled = true;
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
            chatInput.disabled = false;
            if(chatState.isOpen) chatInput.focus();
        }
    }

    // ================= LA MAGIA QUE PINTA LA TABLA =================
    function pintarHorariosEnTabla(textoFecha) {
        let txt = textoFecha.toLowerCase();
        
        let diaCol = 0; 
        if(txt.includes('lun')) diaCol = 1;
        else if(txt.includes('mar')) diaCol = 2;
        else if(txt.includes('mie') || txt.includes('mié')) diaCol = 3;
        else if(txt.includes('jue')) diaCol = 4;
        else if(txt.includes('vie')) diaCol = 5;

        let regex = /\b(12|1|2|3|4)\b/g;
        let match;
        let horas = [];
        while ((match = regex.exec(txt)) !== null) {
            horas.push(parseInt(match[1]));
        }

        let filasPintar = [];
        let ordenHoras = [12, 1, 2, 3, 4];

        if (horas.length >= 2) {
            let inicio = horas[0];
            let fin = horas[1];
            let idxInicio = ordenHoras.indexOf(inicio);
            let idxFin = ordenHoras.indexOf(fin);
            
            if(idxInicio !== -1 && idxFin !== -1 && idxInicio <= idxFin) {
                filasPintar = ordenHoras.slice(idxInicio, idxFin + 1);
            } else {
                filasPintar = horas; 
            }
        } else if (horas.length === 1) {
            filasPintar = horas; 
        }

        filasPintar.forEach(h => {
            let row = document.getElementById('row-' + h);
            if(row) {
                if (diaCol > 0) {
                    let celda = row.querySelector('.c-' + diaCol);
                    if(celda) {
                        celda.classList.add('celda-activa');
                        celda.innerText = 'CITA';
                    }
                } else {
                    for(let i = 1; i <= 5; i++) {
                        let celda = row.querySelector('.c-' + i);
                        celda.classList.add('celda-tentativa');
                        celda.innerText = 'SUG.';
                    }
                }
            }
        });
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
            
            // Llenamos textos
            document.getElementById('logroNombre').innerText = data.nombre;
            document.getElementById('logroCorreo').innerText = data.correo;
            document.getElementById('logroFecha').innerText = data.fecha_hora;
            document.getElementById('logroNotas').innerText = data.notas;
            
            let servicioElegido = serviciosActivos.find(s => s.id_servicio == data.id_servicio);
            document.getElementById('logroServicio').innerText = servicioElegido ? servicioElegido.nombre : 'Servicio General';

            // ¡LLAMAMOS A LA MAGIA PARA PINTAR LA TABLA!
            pintarHorariosEnTabla(data.fecha_hora);

            chatWindow.classList.remove('open'); 
            chatState.isOpen = false;
            saveState();
            
            document.getElementById('modalLogroCita').classList.add('active'); 
        })
        .catch(error => {
            console.error("Error de Laravel:", error);
            addMessage('bot', 'Oh no... hubo un error en la Matrix. 🔌 Intenta contactarnos desde la página de Contacto.');
            chatInput.disabled = false;
        });
    }

    function cerrarModalLogro() {
        document.getElementById('modalLogroCita').classList.remove('active');
        sessionStorage.removeItem('akiraChatState');
        location.reload();
    }
</script>