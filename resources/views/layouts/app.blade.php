<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AkiraArquitectura | Portafolio</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
                /* Estilos para el Landing basado en la imagen */
        .logo-brand-text {
            font-family: 'Helvetica', sans-serif;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 2px;
            font-style: italic;
            color: #444;
        }

        /* Recreación del logo de tres picos */
        .custom-logo-triangles {
            display: flex;
            align-items: flex-end;
            gap: 2px;
        }
        .custom-logo-triangles div {
            width: 0; height: 0;
            border-left: 15px solid transparent;
            border-right: 15px solid transparent;
            border-bottom: 25px solid #666;
        }
        .custom-logo-triangles .tri-1 { border-bottom-width: 35px; border-left-width: 20px; border-right-width: 20px; }
        .custom-logo-triangles .tri-2 { border-bottom-width: 25px; }
        .custom-logo-triangles .tri-3 { border-bottom-width: 30px; }

        /* Navegación */
        .nav-link-akira {
            font-family: 'Helvetica', sans-serif;
            font-size: 0.8rem;
            font-weight: 600;
            color: #333;
            text-decoration: none;
            letter-spacing: 1px;
        }
        .nav-link-akira:hover { color: #888; }

        .social-links-akira i {
            font-size: 1.2rem;
            color: #fff;
            background-color: #666;
            width: 35px; height: 35px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
        }

        /* Imagen de fondo (Office Interior) */
        .landing-hero-image {
            background-image: url('https://images.unsplash.com/photo-1497366811353-6870744d04b2?auto=format&fit=crop&q=80&w=1920');
            background-size: cover;
            background-position: center;
            cursor: pointer;
            transition: transform 0.8s ease;
        }

        .btn-enter {
            background: rgba(255, 255, 255, 0.8);
            padding: 10px 25px;
            border: 1px solid #333;
            font-size: 0.8rem;
            letter-spacing: 2px;
            color: #333;
            transition: 0.3s;
        }
        .btn-enter:hover { background: #000; color: #fff; }
    </style>
</head>
<body>

    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // 1. Entrar desde Landing al Main
        function entrarAlSitio() {
    document.getElementById('landing-view').classList.add('d-none');
    document.getElementById('project-view').classList.remove('d-none');
}

        // 2. Lógica de Hover (Previsualización)
        document.querySelectorAll('.project-link').forEach(link => {
            link.addEventListener('mouseenter', e => {
                const img = e.target.getAttribute('data-img');
                if(img) {
                    const container = document.getElementById('preview-container');
                    container.style.backgroundImage = `url(${img})`;
                    container.style.opacity = '1';
                }
            });
            link.addEventListener('mouseleave', () => {
                document.getElementById('preview-container').style.opacity = '0';
            });
            
            // 3. Abrir Proyecto al hacer Clic
            link.addEventListener('click', e => {
                const nombre = e.target.innerText;
                const img = e.target.getAttribute('data-img');
                abrirProyecto(nombre, img);
            });
        });

        // 4. Mostrar Vista de Proyecto
        function abrirProyecto(nombre, imagenPrincipal) {
            document.getElementById('main-view').classList.add('d-none');
            const projectView = document.getElementById('project-view');
            projectView.classList.remove('d-none');
            window.scrollTo(0, 0);

            document.getElementById('project-title').innerText = nombre;
            
            // Simulación de imágenes para el carrusel de AkiraArquitectura
            const carousel = document.getElementById('project-carousel');
            carousel.innerHTML = `
                <div class="carousel-item-custom" style="background-image: url('${imagenPrincipal}')"></div>
                <div class="carousel-item-custom" style="background-image: url('https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=1000')"></div>
            `;
        }

        // 5. Regresar al Menú Principal
        function cerrarProyecto() {
            document.getElementById('project-view').classList.add('d-none');
            document.getElementById('main-view').classList.remove('d-none');
            window.scrollTo(0, 0);
        }

        
    </script>
</body>
</html>