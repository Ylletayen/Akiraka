@extends('layouts.app')

@section('content')
<div class="dash-admin-view">
    <style>
        /* Mantenemos tus estilos base del dashboard */
        .dash-admin-view { min-height: 100vh; background-color: #f8f8f8; font-family: "Garamond", serif; padding: 20px; display: flex; justify-content: center; color: #111; }
        .dashboard-container { display: flex; width: 100%; max-width: 1400px; gap: 20px; align-items: stretch; }
        .main-content { flex-grow: 1; background-color: #fff; padding: 40px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .header-section { border-bottom: 1px solid #eaeaea; padding-bottom: 20px; margin-bottom: 30px; display: flex; justify-content: space-between; align-items: flex-end; }
        .header-section h1 { font-size: 2.2rem; font-weight: 700; margin: 0; }
        .btn-add-new { background: #111; color: #fff; border: none; padding: 10px 20px; border-radius: 6px; font-size: 0.8rem; letter-spacing: 1px; text-transform: uppercase; cursor: pointer; transition: background 0.3s; }
        .btn-add-new:hover { background: #333; }
        
        .upload-card { background: #fcfcfc; border: 1px dashed #ccc; padding: 25px; border-radius: 8px; margin-bottom: 40px; }
        
        .fase-table { width: 100%; border-collapse: separate; border-spacing: 0 12px; }
        .fase-row { background: #fff; outline: 1px solid #eee; transition: transform 0.2s; }
        .fase-row:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .fase-row td { padding: 15px; vertical-align: middle; }
        .img-preview { width: 140px; height: 90px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd; }
        
        .badge-info { font-family: Arial, sans-serif; font-size: 0.75rem; color: #888; font-weight: bold; margin-right: 15px; }

        /* Estilo para los botones de texto de acción */
        .btn-text-action { background: none; border: none; font-weight: bold; text-decoration: underline; font-size: 0.75rem; cursor: pointer; text-transform: uppercase; }
        .btn-text-action.edit { color: #111; margin-bottom: 10px; display: block; }
        .btn-text-action.delete { color: #d9534f; }

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
                    <h1>Historia: {{ $proyecto->titulo }}</h1>
                    <p style="font-family: Arial, sans-serif; font-size: 0.9rem; color: #666;">Agrega imágenes o videos para narrar la línea del tiempo de esta obra.</p>
                </div>
                <a href="{{ route('dashboard.proyectos') }}" style="color: #666; font-family: Arial; text-decoration: none; font-size: 0.9rem; border: 1px solid #ddd; padding: 8px 15px; border-radius: 20px;">&larr; Volver a Obras</a>
            </div>

            @if(session('success'))
                <div class="alert alert-dark mb-4" style="font-family: Arial; font-size: 0.85rem;">{{ session('success') }}</div>
            @endif
            
            @if($errors->any())
                <div class="alert alert-danger mb-4" style="font-family: Arial; font-size: 0.85rem;">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <div class="upload-card">
                <form action="{{ route('proyectos.historias.store', $proyecto->id_proyecto) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <label class="form-label small fw-bold text-uppercase opacity-75" style="font-family: Arial;">Tipo</label>
                            <select id="tipo_media_crear" class="form-select" onchange="cambiarTipoMedia('crear')">
                                <option value="imagen" selected>Imagen</option>
                                <option value="video">Video</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-bold text-uppercase opacity-75" style="font-family: Arial;">Archivo</label>
                            <input type="file" name="imagen" id="imagen_crear" class="form-control" accept="image/*" required onchange="previsualizarMedia(this, 'crear')">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label small fw-bold text-uppercase opacity-75" style="font-family: Arial;">Año</label>
                            <input type="text" name="anio" class="form-control" placeholder="Ej. 2025" maxlength="4">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label small fw-bold text-uppercase opacity-75" style="font-family: Arial;">Orden</label>
                            <input type="number" name="orden" class="form-control" placeholder="1, 2..." value="0">
                        </div>
                    </div>

                    <div class="row align-items-end">
                        <div class="col-md-9 mb-3">
                            <label class="form-label small fw-bold text-uppercase opacity-75" style="font-family: Arial;">Descripción de la Fase</label>
                            <textarea name="descripcion" class="form-control" rows="1" placeholder="¿Qué ocurrió aquí?" required></textarea>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button type="submit" class="btn-add-new w-100 py-2" style="height: 38px;">Agregar Fase</button>
                        </div>
                    </div>

                    <div class="media-preview-container" id="preview_container_crear">
                        <img id="preview_img_crear" src="" style="display: none;">
                        <video id="preview_video_crear" src="" controls style="display: none;"></video>
                    </div>
                </form>
            </div>

            <h4 style="font-family: Arial; font-size: 1.1rem; margin-bottom: 20px;">Línea del tiempo documentada</h4>
            <table class="fase-table">
                <tbody>
                    @forelse($imagenes as $index => $img)
                        <tr class="fase-row" @if($img->descripcion == 'Portada principal') style="outline: 2px solid #111; box-shadow: 0 4px 15px rgba(0,0,0,0.1);" @endif>
                            
                            <td style="width: 160px; position: relative;">
                                @if($img->descripcion == 'Portada principal')
                                    <div style="position: absolute; top: 10px; left: 10px; background: #111; color: #fff; padding: 2px 6px; font-size: 0.65rem; font-weight: bold; border-radius: 4px; z-index: 10; letter-spacing: 1px;">
                                        ★ PORTADA
                                    </div>
                                @endif

                                {{-- MAGIA PARA MOSTRAR VIDEO O IMAGEN --}}
                                @php $esVideoFase = preg_match('/\.(mp4|webm)$/i', $img->url_imagen); @endphp
                                @if($esVideoFase)
                                    <video src="{{ asset('storage/' . $img->url_imagen) }}" class="img-preview" muted loop playsinline></video>
                                @else
                                    <img src="{{ asset('storage/' . $img->url_imagen) }}" class="img-preview" alt="Fase">
                                @endif
                            </td>
                            
                            <td>
                                <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                    <span class="badge-info">FASE {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                    @if($img->anio) <span class="badge-info">| AÑO: {{ $img->anio }}</span> @endif
                                    <span class="badge-info" style="font-weight: normal;">| Orden: {{ $img->orden }}</span>
                                </div>
                                
                                <p style="font-size: 1rem; color: #444; margin: 0; line-height: 1.4;">
                                    @if($img->descripcion == 'Portada principal')
                                        <strong>Imagen principal del catálogo.</strong> 
                                        <span style="font-size: 0.85rem; color: #888;">(Se usa como fondo transparente al inicio del proyecto).</span>
                                    @else
                                        {{ $img->descripcion }}
                                    @endif
                                </p>
                            </td>
                            
                            <td style="text-align: right; width: 120px;">
                                <button type="button" class="btn-text-action edit" onclick='editarFase(@json($img))'>Editar</button>

                                <form action="{{ route('proyectos.historias.destroy', $img->id_imagen) }}" method="POST" onsubmit="return confirm('¿Eliminar esta fase de la historia?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-text-action delete">Eliminar</button>
                                </form>
                            </td>
                            
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-5 text-muted" style="font-family: Arial;">No hay archivos registrados en la historia de este proyecto.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </main>
    </div>
</div>

<div class="modal fade" id="modalEditarFase" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content p-4 border-0 shadow-lg" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(15px); border-radius: 12px;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="m-0 fw-bold" style="font-family: 'Garamond', serif;">Editar Fase</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="formEditarFase" method="POST" enctype="multipart/form-data">
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
                    <label class="form-label small fw-bold text-uppercase opacity-75" style="font-family: Arial; letter-spacing: 1px;">Descripción de la Fase</label>
                    <textarea name="descripcion" id="edit_descripcion" class="form-control border-0 bg-light" rows="2" required></textarea>
                </div>

                <button type="submit" class="btn-add-new w-100 py-3 shadow-sm" style="font-family: Arial;">Actualizar Fase</button>
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

    // ================= FUNCIONES DEL MODAL EDITAR =================
    function editarFase(fase) {
        let urlUpdate = "{{ route('proyectos.historias.update', ':id') }}";
        urlUpdate = urlUpdate.replace(':id', fase.id_imagen);
        
        document.getElementById('formEditarFase').action = urlUpdate; 
        
        // Rellenamos los campos
        document.getElementById('edit_anio').value = fase.anio || '';
        document.getElementById('edit_orden').value = fase.orden || '0';
        document.getElementById('edit_descripcion').value = fase.descripcion || '';
        
        // Limpiamos previsualizaciones
        document.getElementById('imagen_editar').value = '';
        document.getElementById('preview_container_editar').style.display = 'none';
        document.getElementById('tipo_media_editar').value = 'imagen';
        cambiarTipoMedia('editar');

        // Lanzamos el modal
        var myModal = new bootstrap.Modal(document.getElementById('modalEditarFase'));
        myModal.show();
    }
</script>
@endsection