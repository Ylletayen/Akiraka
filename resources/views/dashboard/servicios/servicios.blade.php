@extends('layouts.app')

@section('content')
<div class="dash-admin-view">
    <style>
        .dash-admin-view { min-height: 100vh; background-color: #ffffff; font-family: "Helvetica Neue", Arial, sans-serif; color: #111; padding: 20px; display: flex; justify-content: center; }
        .dashboard-container { display: flex; width: 100%; max-width: 1400px; gap: 20px; align-items: stretch; }
        .main-content { flex-grow: 1; background: #ffffff; padding: 40px 50px; border-radius: 12px; position: relative; }
        
        .header-section { margin-top: 30px; border-bottom: 1px solid #f0f0f0; padding-bottom: 20px; margin-bottom: 40px; display: flex; justify-content: space-between; align-items: flex-end; }
        .header-section h1 { font-size: 2.2rem; font-family: 'Garamond', serif; margin: 0; color: #111; }
        .header-section p { color: #888; font-style: italic; margin: 5px 0 0 0; font-size: 0.95rem; }
        
        .btn-dark-akiraka { background: #111; color: #fff; padding: 14px 25px; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1.5px; font-weight: bold; border: none; border-radius: 4px; cursor: pointer; transition: all 0.3s; }
        .btn-dark-akiraka:hover { background: #333; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }

        .table-container { background: #fff; border: 1px solid #eaeaea; border-radius: 8px; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.03); }
        .table-akiraka { width: 100%; border-collapse: collapse; text-align: left; }
        .table-akiraka th { background: #fafafa; padding: 15px 25px; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; color: #555; border-bottom: 1px solid #eaeaea; }
        .table-akiraka td { padding: 18px 25px; border-bottom: 1px solid #f5f5f5; font-size: 0.95rem; vertical-align: middle; }
        .table-akiraka tbody tr:hover { background: #fcfcfc; }
        .table-akiraka tbody tr:last-child td { border-bottom: none; }

        .btn-action { background: none; border: none; font-size: 1rem; color: #888; cursor: pointer; margin-right: 12px; transition: color 0.2s; }
        .btn-action.edit:hover { color: #111; }
        .btn-action.delete:hover { color: #d9534f; }

        .custom-modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(5px); z-index: 9999; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.3s ease; }
        .custom-modal-overlay.active { display: flex; opacity: 1; }
        .custom-modal-box { background: #ffffff; border: 1px solid #eaeaea; border-radius: 8px; padding: 40px; width: 90%; max-width: 500px; box-shadow: 0 30px 60px rgba(0,0,0,0.08); position: relative; }
        .modal-title { font-family: 'Garamond', serif; font-size: 1.5rem; margin-bottom: 25px; color: #111; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-size: 0.7rem; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px; color: #555; margin-bottom: 8px; }
        .form-control { width: 100%; padding: 12px 15px; background-color: #fafafa; border: 1px solid #eaeaea; border-radius: 4px; font-size: 0.95rem; font-family: inherit; transition: all 0.3s; }
        .form-control:focus { outline: none; border-color: #111; background-color: #fff; }
        
        .modal-actions { display: flex; justify-content: flex-end; gap: 15px; margin-top: 30px; }
        .btn-cancel { background: #fff; border: 1px solid #ddd; color: #555; padding: 12px 20px; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; font-weight: bold; border-radius: 4px; cursor: pointer; transition: all 0.2s; }
        .btn-cancel:hover { background: #fafafa; color: #111; }
        .btn-close-abs { position: absolute; top: 20px; right: 20px; background: none; border: none; font-size: 1.2rem; color: #888; cursor: pointer; }

        .empty-state { padding: 50px; text-align: center; color: #888; font-style: italic; font-family: 'Garamond', serif; font-size: 1.1rem; }
    </style>

    <div class="dashboard-container">
        @include('partials.sidebar')

        <main class="main-content">
            @include('partials.topbar')
            
            <div class="header-section">
                <div>
                    <h1>Catálogo de Servicios</h1>
                    <p>Gestiona los servicios y especialidades que ofrece el estudio.</p>
                </div>
                <div>
                    <button class="btn-dark-akiraka" onclick="openModal('modal-crear')">
                        <i class="fas fa-plus me-2"></i> Nuevo Servicio
                    </button>
                </div>
            </div>

            @if(session('success'))
                <div style="background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; padding: 15px 20px; border-radius: 6px; margin-bottom: 25px; font-size: 0.9rem;">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                </div>
            @endif

            <div class="table-container">
                <table class="table-akiraka">
                    <thead>
                        <tr>
                            <th style="width: 80px;">ID</th>
                            <th>Servicio</th>
                            <th>Descripción</th>
                            <th style="text-align: right; width: 120px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($servicios as $servicio)
                        <tr>
                            <td><span style="color: #888; font-size: 0.8rem;">#{{ str_pad($servicio->id_servicio, 3, '0', STR_PAD_LEFT) }}</span></td>
                            <td style="font-weight: bold; color: #111;">{{ $servicio->nombre }}</td>
                            <td style="color: #666; max-width: 450px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ $servicio->descripcion ?? 'Sin descripción...' }}
                            </td>
                            <td style="text-align: right;">
                                <button class="btn-action edit" title="Editar" 
                                    onclick="openEditModal({{ $servicio->id_servicio }}, '{{ addslashes($servicio->nombre) }}', '{{ addslashes($servicio->descripcion) }}')">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-action delete" title="Eliminar" 
                                    onclick="openDeleteModal({{ $servicio->id_servicio }}, '{{ addslashes($servicio->nombre) }}')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4">
                                <div class="empty-state">
                                    Aún no has registrado ningún servicio arquitectónico. <br>Haz clic en "+ Nuevo Servicio" para comenzar a armar tu catálogo.
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </main>
    </div>
</div>

<!-- MODAL: CREAR -->
<div id="modal-crear" class="custom-modal-overlay">
    <div class="custom-modal-box">
        <button class="btn-close-abs" onclick="closeModal('modal-crear')"><i class="fas fa-times"></i></button>
        <h2 class="modal-title">Registrar Nuevo Servicio</h2>
        <form action="{{ route('servicios.store') }}" method="POST">
            @csrf
            <!-- Input oculto para no romper el controlador -->
            <input type="hidden" name="activo" value="1">
            
            <div class="form-group">
                <label>Nombre del Servicio</label>
                <input type="text" name="nombre" class="form-control" required placeholder="Ej: Diseño Arquitectónico">
            </div>
            <div class="form-group">
                <label>Descripción</label>
                <textarea name="descripcion" class="form-control" rows="4" placeholder="Explica brevemente en qué consiste este servicio..."></textarea>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeModal('modal-crear')">Cancelar</button>
                <button type="submit" class="btn-dark-akiraka">Guardar Servicio</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: EDITAR -->
<div id="modal-editar" class="custom-modal-overlay">
    <div class="custom-modal-box">
        <button class="btn-close-abs" onclick="closeModal('modal-editar')"><i class="fas fa-times"></i></button>
        <h2 class="modal-title">Editar Servicio</h2>
        <form id="form-editar" method="POST">
            @csrf @method('PUT')
            <!-- Input oculto para no romper el controlador -->
            <input type="hidden" name="activo" value="1">
            
            <div class="form-group">
                <label>Nombre del Servicio</label>
                <input type="text" id="edit-nombre" name="nombre" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Descripción</label>
                <textarea id="edit-descripcion" name="descripcion" class="form-control" rows="4"></textarea>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeModal('modal-editar')">Cancelar</button>
                <button type="submit" class="btn-dark-akiraka">Actualizar</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: ELIMINAR -->
<div id="modal-eliminar" class="custom-modal-overlay">
    <div class="custom-modal-box" style="text-align: center; max-width: 420px;">
        <i class="fas fa-exclamation-triangle" style="font-size: 3.5rem; color: #d9534f; margin-bottom: 20px;"></i>
        <h2 class="modal-title" style="border: none; margin-bottom: 10px;">¿Eliminar Servicio?</h2>
        <p style="color: #555; margin-bottom: 30px; font-size: 0.95rem;">
            Estás a punto de borrar <strong id="delete-nombre" style="color:#111;"></strong> de forma permanente.
        </p>
        <form id="form-eliminar" method="POST">
            @csrf @method('DELETE')
            <div class="modal-actions" style="justify-content: center; gap: 20px;">
                <button type="button" class="btn-cancel" onclick="closeModal('modal-eliminar')">Mantener</button>
                <button type="submit" class="btn-dark-akiraka" style="background: #d9534f; box-shadow: 0 4px 15px rgba(217, 83, 79, 0.2);">Eliminar Servicio</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id) { document.getElementById(id).classList.add('active'); }
    function closeModal(id) { document.getElementById(id).classList.remove('active'); }

    function openEditModal(id, nombre, descripcion) {
        document.getElementById('form-editar').action = `/dashboard/servicios/${id}`;
        document.getElementById('edit-nombre').value = nombre;
        document.getElementById('edit-descripcion').value = descripcion;
        openModal('modal-editar');
    }

    function openDeleteModal(id, nombre) {
        document.getElementById('form-eliminar').action = `/dashboard/servicios/${id}`;
        document.getElementById('delete-nombre').innerText = `"${nombre}"`;
        openModal('modal-eliminar');
    }
</script>
@endsection