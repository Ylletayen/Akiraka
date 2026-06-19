<div id="modalResena" class="akira-modal-fullscreen">
    <button class="modal-close-btn" onclick="cerrarModalResena()" title="Cerrar">✕</button>
    
    <div class="modal-content-wrapper d-flex justify-content-center align-items-center h-100">
        <div class="modal-glass p-5" style="max-width: 550px; width: 90%; position: relative; z-index: 100002;">
            
            <h2 class="text-center mb-2" style="font-weight: 700; letter-spacing: 0.05em; color: #111;">Tu Experiencia</h2>
            <p class="text-center text-muted mb-4" style="font-size: 0.9rem;">
                Tus comentarios nos ayudan a seguir creando espacios increíbles y generan confianza en futuros clientes.
            </p>
            
            <form action="{{ route('resenas.store') }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label for="nombre_cliente" class="form-label text-uppercase" style="font-size: 0.75rem; font-weight: bold; color: #555; letter-spacing: 1px;">Nombre Completo</label>
                    <input type="text" name="nombre_cliente" id="nombre_cliente" class="form-control akira-input" required placeholder="Ej. Ana Torres">
                </div>

                <div class="mb-4 text-center">
                    <label class="form-label text-uppercase mb-2" style="font-size: 0.75rem; font-weight: bold; color: #555; letter-spacing: 1px;">Calificación</label>
                    
                    <div class="star-rating-input d-flex justify-content-center flex-row-reverse">
                        <input type="radio" id="star5" name="calificacion" value="5" required /><label for="star5" title="5 estrellas">★</label>
                        <input type="radio" id="star4" name="calificacion" value="4" /><label for="star4" title="4 estrellas">★</label>
                        <input type="radio" id="star3" name="calificacion" value="3" /><label for="star3" title="3 estrellas">★</label>
                        <input type="radio" id="star2" name="calificacion" value="2" /><label for="star2" title="2 estrellas">★</label>
                        <input type="radio" id="star1" name="calificacion" value="1" /><label for="star1" title="1 estrella">★</label>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="comentario" class="form-label text-uppercase" style="font-size: 0.75rem; font-weight: bold; color: #555; letter-spacing: 1px;">Tu Comentario</label>
                    <textarea name="comentario" id="comentario" rows="4" class="form-control akira-input" required placeholder="¿Cómo fue trabajar con Estudio Akiraka?"></textarea>
                </div>

                <button type="submit" class="btn btn-dark w-100" style="padding: 12px; border-radius: 30px; letter-spacing: 2px; text-transform: uppercase; font-size: 0.85rem; font-weight: bold;">
                    Publicar Reseña
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    /* Estilos súper limpios para los inputs */
    .akira-input {
        border: none;
        border-bottom: 1px solid #ddd;
        border-radius: 0;
        padding: 10px 5px;
        background: transparent;
        box-shadow: none !important;
        transition: border-color 0.3s ease;
        font-size: 0.95rem;
    }
    .akira-input:focus {
        border-bottom-color: #111;
        outline: none;
    }
    .akira-input::placeholder {
        color: #ccc;
    }

    /* Magia visual para las estrellas invertidas */
    .star-rating-input input {
        display: none;
    }
    .star-rating-input label {
        font-size: 2.2rem;
        color: #eaeaea;
        cursor: pointer;
        transition: color 0.2s ease, transform 0.2s ease;
        padding: 0 4px;
    }
    .star-rating-input label:hover,
    .star-rating-input label:hover ~ label,
    .star-rating-input input:checked ~ label {
        color: #eab308; /* Dorado elegante */
    }
    .star-rating-input label:hover {
        transform: scale(1.15);
    }
</style>

<script>
    function abrirModalResena() {
        document.getElementById('modalResena').classList.add('active');
        document.body.style.overflow = 'hidden'; // Quita el scroll de fondo
    }

    function cerrarModalResena() {
        document.getElementById('modalResena').classList.remove('active');
        document.body.style.overflow = 'auto'; // Regresa el scroll
    }
</script>