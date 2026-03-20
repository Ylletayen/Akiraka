@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<div class="dash-admin-view">
    <style>
        /* ================= ESTILOS PRINCIPALES ================= */
        .dash-admin-view { min-height: 100vh; background-color: #f8f8f8; font-family: "Garamond", "Baskerville", serif; color: #111; padding: 20px; display: flex; justify-content: center; }
        .dashboard-container { display: flex; width: 100%; max-width: 1400px; gap: 20px; align-items: stretch; }
        .main-content { flex-grow: 1; background-color: #fff; padding: 40px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .header-section { border-bottom: 1px solid #eaeaea; padding-bottom: 20px; margin-bottom: 30px; display: flex; justify-content: space-between; align-items: flex-end; }
        .header-section h1 { font-size: 2.2rem; font-weight: 700; margin: 0; }
        .btn-add-new { background: #111; color: #fff; border: none; padding: 10px 20px; border-radius: 6px; font-size: 0.8rem; letter-spacing: 1px; text-transform: uppercase; cursor: pointer; transition: background 0.3s ease; }
        .btn-add-new:hover { background: #333; }
        .project-table { width: 100%; border-collapse: separate; border-spacing: 0 12px; }
        .project-row { background: #fff; outline: 1px solid #eee; transition: all 0.3s ease; }
        .project-row:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .project-row td { padding: 15px 20px; vertical-align: middle; }
        
        /* ================= ESTILOS DE LOS BOTONES ICONO ================= */
        .btn-icon-action {
            background: none; border: none; font-size: 1.2rem; cursor: pointer;
            transition: transform 0.2s ease, color 0.3s ease; padding: 5px; margin-left: 10px;
            display: inline-flex; align-items: center; justify-content: center;
            color: #888; text-decoration: none;
        }
        .btn-icon-action:hover { transform: scale(1.15); color: #111; }

        .badge-estado { font-family: Arial, sans-serif; font-size: 0.75rem; font-weight: bold; padding: 4px 10px; border-radius: 12px; background: #eee; color: #333; }
        .badge-anio { font-family: Arial, sans-serif; font-size: 0.7rem; font-weight: bold; padding: 2px 6px; border-radius: 6px; background: #111; color: #fff; margin-left: 8px; }
        .desc-text { color: #666; font-size: 0.85rem; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; margin-top: 5px; }
        .modal-glass { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(15px); border: 1px solid rgba(0, 0, 0, 0.1); border-radius: 12px; }
        
        /* Estilos para la previsualización */
        .media-preview-container {
            width: 100%; height: 180px; border-radius: 8px; border: 1px dashed #ccc; 
            display: flex; align-items: center; justify-content: center; background: #f9f9f9; overflow: hidden;
            margin-top: 10px; display: none;
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
                    <h1>Portafolio de Obras</h1>
                    <p>Gestión de obras, costos estimados y estado de construcción.</p>
                </div>
                <button class="btn-add-new" data-bs-toggle="modal" data-bs-target="#modalProyecto" onclick="prepararNuevo()">
                    + Añadir Obra
                </button>
            </div>

            @if(session('success'))
                <div class="alert alert-dark mb-4" style="font-family: Arial; font-size: 0.85rem;">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger mb-4" style="font-family: Arial; font-size: 0.85rem;">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <table class="project-table">
                <thead>
                    <tr style="text-align: left; color: #888; font-size: 0.75rem; letter-spacing: 2px; text-transform: uppercase;">
                        <th style="width: 35%;">Detalles de la Obra</th>
                        <th>Costos (Inicial / Final)</th>
                        <th>Estado</th>
                        <th style="text-align: right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($proyectos as $proyecto)
                        <tr class="project-row">
                            <td>
                                <div style="font-weight: bold; font-size: 1.05rem; display:flex; align-items:center;">
                                    {{ $proyecto->titulo }}
                                    @if($proyecto->anio) <span class="badge-anio">{{ $proyecto->anio }}</span> @endif
                                </div>
                                <div class="desc-text">{{ $proyecto->descripcion ?? 'Sin descripción.' }}</div>
                            </td>
                            <td style="font-family: Arial, sans-serif; font-size: 0.9rem; color: #555;">
                                <div><strong>Inicial:</strong> ${{ number_format($proyecto->costo_inicial, 2) }}</div>
                                <div><strong>Final:</strong> ${{ number_format($proyecto->costo_final, 2) }}</div>
                            </td>
                            <td>
                                <span class="badge-estado">
                                    @php
                                        $nombreEstado = 'Desconocido';
                                        foreach($estados as $estado) {
                                            if($estado->id_estado == $proyecto->id_estado) {
                                                $nombreEstado = $estado->nombre_estado; break;
                                            }
                                        }
                                    @endphp
                                    {{ $nombreEstado }}
                                </span>
                            </td>
                            <td style="text-align: right; white-space: nowrap;">
                                <a href="{{ route('proyectos.historias', $proyecto->id_proyecto) }}" class="btn-icon-action" title="Gestionar Ficha Técnica / Fotos">
                                    <i class="bi bi-images"></i>
                                </a>
                                <button type="button" class="btn-icon-action" title="Editar Obra" onclick='editarProyecto(@json($proyecto))'>
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <form action="{{ route('proyectos.destroy', $proyecto->id_proyecto) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar esta obra definitivamente?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-icon-action" title="Eliminar Obra">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted" style="font-style: italic;">No hay obras registradas en el portafolio.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </main>
    </div>
</div>

<div class="modal fade" id="modalProyecto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content modal-glass p-4 border-0 shadow-lg">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 id="modalTitle" class="m-0 fw-bold">Añadir Obra</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="formProyecto" action="{{ route('proyectos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div id="methodField"></div>
                
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label small fw-bold text-uppercase opacity-75" style="font-family: Arial; letter-spacing: 1px;">Título de la Obra</label>
                        <input type="text" name="titulo" id="titulo" class="form-control border-0 bg-light" required maxlength="150">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label small fw-bold text-uppercase opacity-75" style="font-family: Arial; letter-spacing: 1px;">Año</label>
                        <input type="text" name="anio" id="anio" class="form-control border-0 bg-light" placeholder="Ej. 2026" maxlength="4">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-uppercase opacity-75" style="font-family: Arial; letter-spacing: 1px;">Descripción</label>
                    <textarea name="descripcion" id="descripcion" class="form-control border-0 bg-light" rows="3"></textarea>
                </div>

                <div class="row mb-3 align-items-center">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-uppercase opacity-75" style="font-family: Arial; letter-spacing: 1px;">Tipo de Portada</label>
                        <select id="tipo_media" class="form-select border-0 bg-light" onchange="cambiarTipoMedia()">
                            <option value="imagen" selected>Imagen</option>
                            <option value="video">Video (MP4)</option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <label id="label_media" class="form-label small fw-bold text-uppercase opacity-75" style="font-family: Arial; letter-spacing: 1px;">Archivo (Portada)</label>
                        <input type="file" name="portada" id="portada" class="form-control border-0 bg-light" accept="image/*" onchange="previsualizarMedia(this)">
                        <small class="text-muted" style="font-size: 0.7rem;">Solo al crear una obra nueva</small>
                    </div>
                </div>
                
                <div class="media-preview-container" id="preview_container">
                    <img id="preview_img" src="" style="display: none;">
                    <video id="preview_video" src="" controls style="display: none;"></video>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label small fw-bold text-uppercase opacity-75" style="font-family: Arial; letter-spacing: 1px;">Costo Inicial ($)</label>
                        <input type="number" step="0.01" name="costo_inicial" id="costo_inicial" class="form-control border-0 bg-light">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label small fw-bold text-uppercase opacity-75" style="font-family: Arial; letter-spacing: 1px;">Costo Final ($)</label>
                        <input type="number" step="0.01" name="costo_final" id="costo_final" class="form-control border-0 bg-light">
                    </div>
                    <div class="col-md-4 mb-4">
                        <label class="form-label small fw-bold text-uppercase opacity-75" style="font-family: Arial; letter-spacing: 1px;">Estado</label>
                        <select name="id_estado" id="id_estado" class="form-select border-0 bg-light" required>
                            <option value="" disabled selected>Selecciona...</option>
                            @foreach($estados as $estado)
                                <option value="{{ $estado->id_estado }}">{{ $estado->nombre_estado }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn-add-new w-100 py-3 shadow-sm" id="btnSubmit" style="font-family: Arial;">Guardar Obra</button>
            </form>
        </div>
    </div>
</div>

<script>
    // ================= FUNCIONES PARA LA PREVISUALIZACIÓN =================
    function cambiarTipoMedia() {
        let tipo = document.getElementById('tipo_media').value;
        let input = document.getElementById('portada');
        
        // Limpiar el input y la previsualización
        input.value = '';
        document.getElementById('preview_container').style.display = 'none';
        document.getElementById('preview_img').style.display = 'none';
        document.getElementById('preview_video').style.display = 'none';

        if (tipo === 'video') {
            input.accept = 'video/mp4,video/webm';
        } else {
            input.accept = 'image/*';
        }
    }

    function previsualizarMedia(input) {
        let container = document.getElementById('preview_container');
        let img = document.getElementById('preview_img');
        let video = document.getElementById('preview_video');
        let file = input.files[0];

        if (file) {
            container.style.display = 'flex'; // Mostrar contenedor
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
            container.style.display = 'none'; // Ocultar si cancela
        }
    }

    // ================= FUNCIONES DEL MODAL =================
    function prepararNuevo() {
        document.getElementById('modalTitle').innerText = 'Añadir Nueva Obra';
        document.getElementById('formProyecto').action = "{{ route('proyectos.store') }}";
        document.getElementById('methodField').innerHTML = ''; 
        
        document.getElementById('titulo').value = '';
        document.getElementById('descripcion').value = '';
        document.getElementById('anio').value = '';
        
        // Limpiar multimedia
        document.getElementById('portada').value = ''; 
        document.getElementById('preview_container').style.display = 'none';
        document.getElementById('tipo_media').value = 'imagen';
        cambiarTipoMedia();

        document.getElementById('costo_inicial').value = '';
        document.getElementById('costo_final').value = '';
        document.getElementById('id_estado').value = '';

        document.getElementById('btnSubmit').innerText = 'Guardar Obra';
    }

    function editarProyecto(proyecto) {
        document.getElementById('modalTitle').innerText = 'Editar Obra';
        
        let urlUpdate = "{{ route('proyectos.update', ':id') }}";
        urlUpdate = urlUpdate.replace(':id', proyecto.id_proyecto);
        
        document.getElementById('formProyecto').action = urlUpdate; 
        document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        
        document.getElementById('titulo').value = proyecto.titulo;
        document.getElementById('descripcion').value = proyecto.descripcion || '';
        document.getElementById('anio').value = proyecto.anio || '';
        
        // Limpiar multimedia (No se pueden rellenar inputs file por seguridad)
        document.getElementById('portada').value = ''; 
        document.getElementById('preview_container').style.display = 'none';

        document.getElementById('costo_inicial').value = proyecto.costo_inicial || '';
        document.getElementById('costo_final').value = proyecto.costo_final || '';
        document.getElementById('id_estado').value = proyecto.id_estado;

        document.getElementById('btnSubmit').innerText = 'Actualizar Cambios';
        
        var myModal = new bootstrap.Modal(document.getElementById('modalProyecto'));
        myModal.show();
    }
</script>
@endsection