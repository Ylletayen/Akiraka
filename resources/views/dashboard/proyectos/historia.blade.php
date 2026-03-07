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
        .btn-add-new { background: #111; color: #fff; border: none; padding: 10px 20px; border-radius: 6px; font-size: 0.8rem; letter-spacing: 1px; text-transform: uppercase; cursor: pointer; }
        
        .upload-card { background: #fcfcfc; border: 1px dashed #ccc; padding: 25px; border-radius: 8px; margin-bottom: 40px; }
        
        .fase-table { width: 100%; border-collapse: separate; border-spacing: 0 12px; }
        .fase-row { background: #fff; outline: 1px solid #eee; }
        .fase-row td { padding: 15px; vertical-align: middle; }
        .img-preview { width: 120px; height: 80px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd; }
    </style>

    <div class="dashboard-container">
        @include('partials.sidebar')

        <main class="main-content">
            <div class="header-section">
                <div>
                    <h1>Historia: {{ $proyecto->titulo }}</h1>
                    <p>Agrega imágenes y textos para narrar el proceso de esta obra.</p>
                </div>
                <a href="{{ route('dashboard.proyectos') }}" style="color: #666; font-family: Arial; text-decoration: none; font-size: 0.9rem;">&larr; Volver a Proyectos</a>
            </div>

            @if(session('success'))
                <div class="alert alert-dark mb-4">{{ session('success') }}</div>
            @endif

            <div class="upload-card">
                <form action="{{ route('proyectos.historias.store', $proyecto->id_proyecto) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row align-items-end">
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-bold text-uppercase opacity-75" style="font-family: Arial;">Seleccionar Imagen</label>
                            <input type="file" name="imagen" class="form-control" accept="image/*" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-uppercase opacity-75" style="font-family: Arial;">Descripción de la Fase</label>
                            <textarea name="descripcion" class="form-control" rows="2" placeholder="Escribe lo que ocurrió en esta etapa..." required></textarea>
                        </div>
                        <div class="col-md-2 mb-3">
                            <button type="submit" class="btn-add-new w-100">Agregar</button>
                        </div>
                    </div>
                </form>
            </div>

            <h4 style="font-family: Arial; font-size: 1.1rem; margin-bottom: 20px;">Fases Registradas</h4>
            <table class="fase-table">
                <tbody>
                    @forelse($imagenes as $index => $img)
                        <tr class="fase-row">
                            <td style="width: 150px;">
                                <img src="{{ asset('storage/' . $img->url_imagen) }}" class="img-preview" alt="Fase">
                            </td>
                            <td>
                                <div style="font-family: Arial; font-size: 0.8rem; color: #888; font-weight: bold; margin-bottom: 5px;">FASE {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</div>
                                <p style="font-size: 0.95rem; color: #444; margin: 0;">{{ $img->descripcion }}</p>
                            </td>
                            <td style="text-align: right; width: 100px;">
                                <form action="{{ route('proyectos.historias.destroy', $img->id_imagen) }}" method="POST" onsubmit="return confirm('¿Eliminar esta fase de la historia?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="background: none; border: none; color: #d9534f; font-weight: bold; text-decoration: underline; font-size: 0.75rem; cursor: pointer; text-transform: uppercase;">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-5 text-muted" style="font-family: Arial;">No hay imágenes registradas en la historia de este proyecto.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </main>
    </div>
</div>
@endsection