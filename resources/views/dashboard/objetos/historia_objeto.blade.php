@extends('layouts.app')

@section('content')
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
        .image-card img { width: 100%; height: 180px; object-fit: cover; }
        .image-info { padding: 15px; font-family: Arial, sans-serif; }
        .badge-year { background: #111; color: #fff; padding: 2px 6px; border-radius: 4px; font-size: 0.7rem; font-weight: bold; }
        .btn-delete-img { position: absolute; top: 10px; right: 10px; background: #d9534f; color: #fff; border: none; width: 30px; height: 30px; border-radius: 50%; font-weight: bold; cursor: pointer; }
    </style>

    <div class="dashboard-container">
        @include('partials.sidebar')

        <main class="main-content">
            @include('partials.topbar')
            
            <div class="header-section">
                <div>
                    <a href="{{ route('dashboard.objetos') }}" style="color: #888; text-decoration: none; font-size: 0.9rem;">← Volver al catálogo</a>
                    <h1 style="font-size: 2rem; margin-top: 10px;">Ficha Técnica: {{ $objeto->titulo }}</h1>
                    <p style="color: #666;">Añade imágenes de proceso, detalles de materiales y ángulos de este objeto.</p>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-dark mb-4">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger mb-4"><ul>@foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul></div>
            @endif

            <div style="background: #fdfdfd; padding: 25px; border-radius: 8px; border: 1px dashed #ccc; margin-bottom: 40px;">
                <h4 style="font-family: Arial, sans-serif; font-size: 1.1rem; margin-bottom: 20px;">Subir Nueva Imagen</h4>
                
                <form action="{{ route('objetos.historias.store', $objeto->id_objeto) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-bold text-uppercase opacity-75">Seleccionar Foto</label>
                            <input type="file" name="imagen" class="form-control bg-light border-0" accept="image/*" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-bold text-uppercase opacity-75">Año (Opcional)</label>
                            <input type="text" name="anio" class="form-control bg-light border-0" placeholder="Ej. 2024" maxlength="4">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-bold text-uppercase opacity-75">Orden de aparición</label>
                            <input type="number" name="orden" class="form-control bg-light border-0" placeholder="1, 2, 3..." value="0">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-uppercase opacity-75">Descripción (Materiales, detalles, etc)</label>
                        <textarea name="descripcion" class="form-control bg-light border-0" rows="2" required></textarea>
                    </div>
                    <button type="submit" class="btn-dark-custom">Guardar en Ficha Técnica</button>
                </form>
            </div>

            <h3 style="font-size: 1.5rem; border-bottom: 1px solid #eee; padding-bottom: 10px;">Galería del Objeto</h3>
            
            <div class="image-grid">
                @forelse($imagenes as $img)
                    {{-- Le ponemos un borde más grueso y sombra si es la portada --}}
                    <div class="image-card" @if($img->descripcion == 'Portada principal') style="border: 2px solid #111; box-shadow: 0 4px 15px rgba(0,0,0,0.15);" @endif>
                        
                        {{-- ETIQUETA VISUAL DE PORTADA --}}
                        @if($img->descripcion == 'Portada principal')
                            <div style="position: absolute; top: 10px; left: 10px; background: #111; color: #fff; padding: 4px 10px; font-size: 0.7rem; font-weight: bold; border-radius: 4px; z-index: 10; letter-spacing: 1px;">
                                ★ PORTADA
                            </div>
                        @endif

                        <img src="{{ asset('storage/' . $img->url_imagen) }}" alt="Foto">
                        
                        <form action="{{ route('objetos.historias.destroy', $img->id_imagen) }}" method="POST" onsubmit="return confirm('¿Borrar esta foto de la ficha?');">
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
                    <p style="color: #888; font-style: italic; grid-column: 1 / -1;">Aún no has subido fotos a la ficha técnica de este objeto.</p>
                @endforelse
            </div>
            
        </main>
    </div>
</div>
@endsection