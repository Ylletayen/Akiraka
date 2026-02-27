<!DOCTYPE html>
<html lang="es">
<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AkiraArquitectura | Portafolio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Times New Roman', Times, serif; background-color: #fff; color: #111; }
        .landing-image-area { 
            height: 70vh; background-image: url('https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&q=80&w=1920');
            background-size: cover; background-position: center; cursor: pointer;
        }
        #preview-container {
            position: fixed; top: 20%; right: 5%; width: 400px; height: 250px;
            background-size: cover; opacity: 0; transition: 0.3s; pointer-events: none; z-index: 1000;
        }
        .project-link:hover { color: #777; cursor: pointer; }
        .carousel-item-custom { height: 60vh; background-size: cover; background-position: center; }
    </style>
</head>
<body>
    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function entrarAlSitio() {
            document.getElementById('landing-view').classList.add('d-none');
            document.getElementById('main-view').classList.remove('d-none');
        }
        // Lógica de hover para las imágenes
        document.querySelectorAll('.project-link').forEach(link => {
            link.addEventListener('mouseenter', e => {
                const container = document.getElementById('preview-container');
                container.style.backgroundImage = `url(${e.target.dataset.img})`;
                container.style.opacity = '1';
            });
            link.addEventListener('mouseleave', () => {
                document.getElementById('preview-container').style.opacity = '0';
            });
        });
    </script>
</body>
</html>