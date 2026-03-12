@extends('layouts.app')

@section('content')
<div class="dash-admin-view">
    <style>
        /* ================= ESTILOS DEL CONTENEDOR PRINCIPAL ================= */
        .dash-admin-view { min-height: 100vh; background-color: #f8f8f8; font-family: "Garamond", "Baskerville", serif; color: #111; padding: 20px; display: flex; justify-content: center; }
        .dashboard-container { display: flex; width: 100%; max-width: 1400px; gap: 20px; align-items: stretch; }
        .main-content { flex-grow: 1; background-color: #fff; padding: 40px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }

        /* ================= ESTILOS DE LA TABLA Y BOTONES ================= */
        .header-section { border-bottom: 1px solid #eaeaea; padding-bottom: 20px; margin-bottom: 30px; display: flex; justify-content: space-between; align-items: flex-end; }
        .header-section h1 { font-size: 2.2rem; font-weight: 700; margin: 0; }
        .header-section p { margin-bottom: 0; color: #666; }
        .btn-add-new { background: #111; color: #fff; border: none; padding: 10px 20px; border-radius: 6px; font-size: 0.8rem; letter-spacing: 1px; text-transform: uppercase; cursor: pointer; transition: background 0.3s; }
        .btn-add-new:hover { background: #333; }
        
        .user-table { width: 100%; border-collapse: separate; border-spacing: 0 12px; }
        .user-row { background: #fff; outline: 1px solid #eee; transition: transform 0.2s; }
        .user-row:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .user-row td { padding: 15px 20px; vertical-align: middle; }
        
        .btn-action-minimal { background: none; border: none; color: #111; text-transform: uppercase; font-size: 0.7rem; font-weight: bold; letter-spacing: 1px; text-decoration: underline; margin-right: 15px; cursor: pointer; }
    </style>

    <div class="dashboard-container">
        @include('partials.sidebar')

        <main class="main-content">
            @include('partials.topbar')
            
            <div class="header-section">
                <div>
                    <h1>Catálogo de Objetos</h1>
                    <p>Administración de mobiliario, esculturas y piezas de diseño.</p>
                </div>
                <button class="btn-add-new" data-bs-toggle="modal" data-bs-target="#modalObjeto">+ Añadir Objeto</button>
            </div>

            @if(session('success'))
                <div class="alert alert-dark mb-4">{{ session('success') }}</div>
            @endif

            <table class="user-table">
                <thead>
                    <tr style="text-align: left; color: #888; font-size: 0.75rem; letter-spacing: 2px; text-transform: uppercase;">
                        <th>Nombre del Objeto</th>
                        <th>Año</th>
                        <th style="text-align: right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($objetos as $objeto)
                        <tr class="user-row">
                            <td style="font-weight: bold; font-size: 1.1rem;">{{ $objeto->titulo }}</td>
                            <td style="color: #555;">{{ $objeto->anio ?? 'N/A' }}</td>
                            <td style="text-align: right;">
                                <a href="{{ route('objetos.historias', $objeto->id_objeto) }}" class="btn-action-minimal" style="color: #007bff; text-decoration: none;">+ Ficha Técnica</a>
                                
                                <form action="{{ route('objetos.destroy', $objeto->id_objeto) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar este objeto permanentemente?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-action-minimal" style="color: #d9534f;">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-5 text-muted">No hay objetos registrados aún.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </main>
    </div>
</div>

<div class="modal fade" id="modalObjeto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-4 border-0 shadow-lg" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(15px); border-radius: 12px;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="m-0 fw-bold">Añadir Nuevo Objeto</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('objetos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-bold text-uppercase opacity-75">Nombre de la pieza</label>
                    <input type="text" name="titulo" class="form-control bg-light border-0" placeholder="Ej. Silla Akira 01" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-uppercase opacity-75">Año de creación</label>
                    <input type="text" name="anio" class="form-control bg-light border-0" placeholder="Ej. 2024" maxlength="4" required>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-uppercase opacity-75">Imagen (Portada)</label>
                    <input type="file" name="portada" class="form-control bg-light border-0" accept="image/*" required>
                </div>

                <button type="submit" class="btn-add-new w-100 py-3 shadow-sm">Guardar Objeto</button>
            </form>
        </div>
    </div>
</div>
@endsection