@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<div class="dash-admin-view">
    <style>
        .dash-admin-view { min-height: 100vh; background-color: #f8f8f8; font-family: "Garamond", serif; padding: 20px; display: flex; justify-content: center; }
        .dashboard-container { display: flex; width: 100%; max-width: 1400px; gap: 20px; align-items: stretch; }
        .main-content { flex-grow: 1; background-color: #fff; padding: 40px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .header-section { border-bottom: 1px solid #eaeaea; padding-bottom: 20px; margin-bottom: 30px; display: flex; justify-content: space-between; align-items: flex-end; }
        .btn-dark-custom { background: #111; color: #fff; border: none; padding: 10px 20px; border-radius: 6px; font-size: 0.8rem; text-transform: uppercase; cursor: pointer; text-decoration: none; }
        .btn-dark-custom:hover { background: #333; color: #fff; }
        
        /* Grid de imágenes */
        .image-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; margin-top: 30px; }
        .image-card { border: 1px solid #eee; border-radius: 8px; overflow: hidden; background: #fafafa; position: relative; }
        .image-card img, .image-card video { width: 100%; height: 180px; object-fit: cover; }
        .image-info { padding: 15px; font-family: Arial, sans-serif; }
        .badge-year { background: #111; color: #fff; padding: 2px 6px; border-radius: 4px; font-size: 0.7rem; font-weight: bold; }
        
        /* Botones flotantes sobre la imagen */
        .btn-delete-img { position: absolute; top: 10px; right: 10px; background: #d9534f; color: #fff; border: none; width: 30px; height: 30px; border-radius: 50%; font-weight: bold; cursor: pointer; box-shadow: 0 2px 5px rgba(0,0,0,0.2); transition: background 0.2s; display: flex; justify-content: center; align-items: center;}
        .btn-delete-img:hover { background: #c9302c; }
        
        .btn-edit-img { position: absolute; top: 10px; right: 48px; background: #fff; color: #111; border: none; width: 30px; height: 30px; border-radius: 50%; font-size: 0.9rem; cursor: pointer; box-shadow: 0 2px 5px rgba(0,0,0,0.2); transition: background 0.2s; display: flex; justify-content: center; align-items: center; }
        .btn-edit-img:hover { background: #eee; }

        /* Estilos para la previsualización */
        .media-preview-container {
            width: 100%; height: 180px; border-radius: 8px; border: 1px dashed #ccc; 
            display: flex; align-items: center; justify-content: center; background: #f9f9f9; overflow: hidden;
            margin-top: 15px; display: none;
        }
        .media-preview-container img, .media-preview-container video {
            max-width: 100%; max-height: 100%; object-fit: contain;
        }
    </style>

    <div class="dashboard-container">
        @include('partials.sidebar')

        <main class="main-content">
            @include('partials.topbar')
            
            <div class="header-section">
                <div>
                    <a href="{{ route('dashboard.objetos') }}" style="color: #888; text-decoration: none; font-size: 0.9rem;">← Volver al catálogo</a>
                    <h1 style="font-size: 2rem; margin-top: 10px;">Ficha Técnica: {{ $objeto->titulo }}</h1>
                    <p style="color: #666;">Añade imágenes o videos del proceso, detalles y materiales de este objeto.</p>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-dark mb-4" style="font-family: Arial; font-size: 0.85rem;">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger mb-4" style="font-family: Arial; font-size: 0.85rem;"><ul>@foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul></div>
            @endif

            <div style="background: #fdfdfd; padding: 25px; border-radius: 8px; border: 1px dashed #ccc; margin-bottom: 40px;">
                <h4 style="font-family: Arial, sans-serif; font-size: 1.1rem; margin-bottom: 20px;">Añadir a la Ficha Técnica</h4>
                
                <form action="{{ route('objetos.historias.store', $objeto->id_objeto) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <label class="form-label small fw-bold text-uppercase opacity-75" style="font-family: Arial;">Tipo</label>
                            <select id="tipo_media_crear" class="form-select bg-light border-0" onchange="cambiarTipoMedia('crear')">
                                <option value="imagen" selected>Imagen</option>
                                <option value="video">Video</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-bold text-uppercase opacity-75" style="font-family: Arial;">Archivo</label>
                            <input type="file" name="imagen" id="imagen_crear" class="form-control bg-light border-0" accept="image/*" required onchange="previsualizarMedia(this, 'crear')">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label small fw-bold text-uppercase opacity-75" style="font-family: Arial;">Año (Opcional)</label>
                            <input type="text" name="anio" class="form-control bg-light border-0" placeholder="Ej. 2024" maxlength="4">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label small fw-bold text-uppercase opacity-75" style="font-family: Arial;">Orden</label>
                            <input type="number" name="orden" class="form-control bg-light border-0" placeholder="1, 2..." value="0">
                        </div>
                    </div>
                    
                    <div class="row align-items-end">
                        <div class="col-md-9 mb-3">
                            <label class="form-label small fw-bold text-uppercase opacity-75" style="font-family: Arial;">Descripción (Materiales, detalles, etc)</label>
                            <textarea name="descripcion" class="form-control bg-light border-0" rows="1" required></textarea>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button type="submit" class="btn-dark-custom w-100" style="height: 38px;">Guardar en Ficha</button>
                        </div>
                    </div>

                    <div class="media-preview-container" id="preview_container_crear">
                        <img id="preview_img_crear" src="" style="display: none;">
                        <video id="preview_video_crear" src="" controls style="display: none;"></video>
                    </div>
                </form>
            </div>

            <h3 style="font-size: 1.5rem; border-bottom: 1px solid #eee; padding-bottom: 10px;">Galería del Objeto</h3>
            
            <div class="image-grid">
                @forelse($imagenes as $img)
                    <div class="image-card" @if($img->descripcion == 'Portada principal') style="border: 2px solid #111; box-shadow: 0 4px 15px rgba(0,0,0,0.15);" @endif>
                        
                        @if($img->descripcion == 'Portada principal')
                            <div style="position: absolute; top: 10px; left: 10px; background: #111; color: #fff; padding: 4px 10px; font-size: 0.7rem; font-weight: bold; border-radius: 4px; z-index: 10; letter-spacing: 1px;">
                                ★ PORTADA
                            </div>
                        @endif

                        {{-- MAGIA PARA MOSTRAR VIDEO O IMAGEN --}}
                        @php $esVideoObjeto = preg_match('/\.(mp4|webm)$/i', $img->url_imagen); @endphp
                        @if($esVideoObjeto)
                            <video src="{{ asset('storage/' . $img->url_imagen) }}" muted loop playsinline></video>
                        @else
                            <img src="{{ asset('storage/' . $img->url_imagen) }}" alt="Foto">
                        @endif
                        
                        {{-- Botón Editar Flotante --}}
                        <button type="button" class="btn-edit-img" title="Editar" onclick='editarFotoObjeto(@json($img))'>
                            <i class="bi bi-pencil-fill"></i>
                        </button>

                        {{-- Botón Eliminar Flotante --}}
                        <form action="{{ route('objetos.historias.destroy', $img->id_imagen) }}" method="POST" onsubmit="return confirm('¿Borrar este archivo de la ficha?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-delete-img" title="Eliminar">✕</button>
                        </form>

                        <div class="image-info">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                @if($img->anio) <span class="badge-year">{{ $img->anio }}</span> @else <span></span> @endif
                                <span style="font-size: 0.75rem; color: #888;">Orden: {{ $img->orden }}</span>
                            </div>
                            <p style="font-size: 0.9rem; color: #444; margin: 0; line-height: 1.4;">
                                @if($img->descripcion == 'Portada principal')
                                    <strong>Imagen principal del catálogo.</strong>
                                @else
                                    {{ $img->descripcion }}
                                @endif
                            </p>
                        </div>
                    </div>
                @empty
                    <p style="color: #888; font-style: italic; grid-column: 1 / -1;">Aún no has subido archivos a la ficha técnica de este objeto.</p>
                @endforelse
            </div>
            
        </main>
    </div>
</div>

<div class="modal fade" id="modalEditarFoto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content p-4 border-0 shadow-lg" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(15px); border-radius: 12px;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="m-0 fw-bold" style="font-family: 'Garamond', serif;">Editar Ficha Técnica</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="formEditarFoto" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-uppercase opacity-75" style="font-family: Arial; letter-spacing: 1px;">Tipo de Archivo</label>
                        <select id="tipo_media_editar" class="form-select border-0 bg-light" onchange="cambiarTipoMedia('editar')">
                            <option value="imagen" selected>Imagen</option>
                            <option value="video">Video</option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label small fw-bold text-uppercase opacity-75" style="font-family: Arial; letter-spacing: 1px;">Cambiar Archivo (Opcional)</label>
                        <input type="file" name="imagen" id="imagen_editar" class="form-control border-0 bg-light" accept="image/*" onchange="previsualizarMedia(this, 'editar')">
                    </div>
                </div>

                <div class="media-preview-container mb-3" id="preview_container_editar" style="height: 140px;">
                    <img id="preview_img_editar" src="" style="display: none;">
                    <video id="preview_video_editar" src="" controls style="display: none;"></video>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label small fw-bold text-uppercase opacity-75" style="font-family: Arial; letter-spacing: 1px;">Año</label>
                        <input type="text" name="anio" id="edit_anio" class="form-control border-0 bg-light" maxlength="4">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label small fw-bold text-uppercase opacity-75" style="font-family: Arial; letter-spacing: 1px;">Orden</label>
                        <input type="number" name="orden" id="edit_orden" class="form-control border-0 bg-light">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-uppercase opacity-75" style="font-family: Arial; letter-spacing: 1px;">Descripción</label>
                    <textarea name="descripcion" id="edit_descripcion" class="form-control border-0 bg-light" rows="3" required></textarea>
                </div>

                <button type="submit" class="btn-dark-custom w-100 py-3 shadow-sm" style="font-family: Arial;">Actualizar Imagen</button>
            </form>
        </div>
    </div>
</div>

<script>
    // ================= FUNCIONES PARA LA PREVISUALIZACIÓN =================
    function cambiarTipoMedia(contexto) {
        let tipo = document.getElementById('tipo_media_' + contexto).value;
        let input = document.getElementById('imagen_' + contexto);
        
        // Limpiar el input y la previsualización
        input.value = '';
        document.getElementById('preview_container_' + contexto).style.display = 'none';
        document.getElementById('preview_img_' + contexto).style.display = 'none';
        document.getElementById('preview_video_' + contexto).style.display = 'none';

        if (tipo === 'video') {
            input.accept = 'video/mp4,video/webm';
        } else {
            input.accept = 'image/*';
        }
    }

    function previsualizarMedia(input, contexto) {
        let container = document.getElementById('preview_container_' + contexto);
        let img = document.getElementById('preview_img_' + contexto);
        let video = document.getElementById('preview_video_' + contexto);
        let file = input.files[0];

        if (file) {
            container.style.display = 'flex'; 
            let fileURL = URL.createObjectURL(file);

            if (file.type.startsWith('video/')) {
                img.style.display = 'none';
                video.src = fileURL;
                video.style.display = 'block';
            } else {
                video.style.display = 'none';
                img.src = fileURL;
                img.style.display = 'block';
            }
        } else {
            container.style.display = 'none';
        }
    }

    function editarFotoObjeto(img) {
        let urlUpdate = "{{ route('objetos.historias.update', ':id') }}";
        urlUpdate = urlUpdate.replace(':id', img.id_imagen);
        
        document.getElementById('formEditarFoto').action = urlUpdate; 
        
        // Rellenamos los campos
        document.getElementById('edit_anio').value = img.anio || '';
        document.getElementById('edit_orden').value = img.orden || '0';
        document.getElementById('edit_descripcion').value = img.descripcion || '';
        
        // Limpiamos previsualizaciones
        document.getElementById('imagen_editar').value = '';
        document.getElementById('preview_container_editar').style.display = 'none';
        document.getElementById('tipo_media_editar').value = 'imagen';
        cambiarTipoMedia('editar');

        // Lanzamos el modal
        var myModal = new bootstrap.Modal(document.getElementById('modalEditarFoto'));
        myModal.show();
    }
</script>
@endsection