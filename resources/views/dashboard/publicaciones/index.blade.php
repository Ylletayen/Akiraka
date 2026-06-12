@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

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
        .user-row { background: #fff; outline: 1px solid #eee; transition: transform 0.2s, box-shadow 0.2s, outline 0.2s; }
        .user-row:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.05); outline-color: #111; }
        .user-row td { padding: 15px 20px; vertical-align: middle; }
        
        /* ================= ESTILOS DE LOS BOTONES ICONO ================= */
        .btn-icon-action {
            background: none; border: none; font-size: 1.2rem; cursor: pointer;
            transition: transform 0.2s ease, color 0.3s ease; padding: 5px; margin-left: 10px;
            display: inline-flex; align-items: center; justify-content: center;
            color: #888; text-decoration: none;
        }
        .btn-icon-action:hover { transform: scale(1.15); color: #111; }
        .btn-icon-action.delete:hover { color: #d9534f; } /* Rojo advertencia para eliminar */

        /* ================= MODAL DE ADVERTENCIA ================= */
        .modal-glass {
            background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(15px);
            border: 1px solid rgba(0, 0, 0, 0.1); border-radius: 12px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }

        /* --- ESTILOS DEL BUSCADOR MINIMALISTA --- */
        .search-wrapper {
            background: #fff; border: 1px solid #eaeaea; border-radius: 8px;
            padding: 10px 20px; margin-bottom: 25px; display: flex; align-items: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02); transition: all 0.3s ease;
        }
        .search-wrapper:focus-within { border-color: #111; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .search-wrapper i { color: #888; font-size: 1.2rem; margin-right: 15px; }
        .search-wrapper input {
            border: none; outline: none; width: 100%; font-family: Arial, sans-serif;
            font-size: 0.95rem; color: #333; background: transparent;
        }
        .search-wrapper input::placeholder { color: #bbb; }
        /* ---------------------------------------- */
    </style>

    <div class="dashboard-container">
        @include('partials.sidebar')

        <main class="main-content">
            @include('partials.topbar')
            
            <div class="header-section">
                <div>
                    <h1>Publicaciones</h1>
                    <p>Administración de artículos, noticias y contenido editorial.</p>
                </div>
                <button class="btn-add-new" data-bs-toggle="modal" data-bs-target="#modalCrear">+ Añadir Publicación</button>
            </div>

            @if(session('success'))
                <div class="alert alert-dark mb-4" style="font-family: Arial; font-size: 0.85rem;">{{ session('success') }}</div>
            @endif

            {{-- BARRA DE BÚSQUEDA INTERACTIVA --}}
            <div class="search-wrapper">
                <i class="bi bi-search"></i>
                <input type="text" id="buscadorPublicaciones" onkeyup="filtrarTabla()" placeholder="Buscar por título de la publicación o fecha...">
            </div>

            <table class="user-table">
                <thead>
                    <tr style="text-align: left; color: #888; font-family: Arial; font-size: 0.75rem; letter-spacing: 2px; text-transform: uppercase;">
                        <th>Título</th>
                        <th>Fecha</th>
                        <th style="text-align: right;">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tablaPublicaciones">
                    @forelse($publicaciones as $pub)
                        <tr class="user-row">
                            <td style="font-weight: bold; font-size: 1.1rem;">{{ $pub->titulo }}</td>
                            <td style="color: #555; font-family: Arial; font-size: 0.9rem;">{{ $pub->fecha }}</td>
                            
                            <td style="text-align: right; white-space: nowrap;">
                                {{-- Botón Editar/Ver --}}
                                <button class="btn-icon-action"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditar{{ $pub->id_publicacion }}"
                                    title="Editar Publicación">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                
                                {{-- Botón Eliminar --}}
                                <form action="{{ route('publicaciones.destroy', $pub->id_publicacion) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar esta publicación?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-icon-action delete" title="Eliminar">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr class="no-results-row">
                            <td colspan="3" class="text-center py-5 text-muted" style="font-style: italic;">No hay publicaciones registradas aún.</td>
                        </tr>
                    @endforelse
                    
                    {{-- Fila oculta por si la búsqueda no encuentra nada --}}
                    <tr id="sinResultados" style="display: none;">
                        <td colspan="3" class="text-center py-5 text-muted" style="font-style: italic; font-family: Arial;">No se encontraron publicaciones con esa búsqueda.</td>
                    </tr>
                </tbody>
            </table>
            
            {{-- MODALES DE EDICIÓN GENERADOS DINÁMICAMENTE --}}
            @foreach($publicaciones as $pub)
            <div class="modal fade" id="modalEditar{{ $pub->id_publicacion }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content modal-glass p-4 border-0">

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3 class="m-0 fw-bold">Editar Publicación</h3>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <form action="{{ route('publicaciones.update', $pub->id_publicacion) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Título</label>
                                <input type="text" name="titulo" class="form-control bg-light border-0" value="{{ $pub->titulo }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Descripción</label>
                                <textarea name="descripcion" class="form-control bg-light border-0">{{ $pub->descripcion }}</textarea>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-bold">URL</label>
                                <input type="text" name="url" class="form-control bg-light border-0" value="{{ $pub->url }}">
                            </div>

                            <button type="submit" class="btn-add-new w-100 py-3">
                                Actualizar Publicación
                            </button>

                        </form>
                    </div>
                </div>
            </div>
            @endforeach
            
        </main>
    </div>
</div>

{{-- MODAL CREAR PUBLICACIÓN --}}
<div class="modal fade" id="modalCrear" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-glass p-4 border-0">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="m-0 fw-bold">Nueva Publicación</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('publicaciones.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-bold text-uppercase opacity-75" style="font-family: Arial; letter-spacing: 1px;">Título</label>
                    <input type="text" name="titulo" class="form-control bg-light border-0" placeholder="Título de la publicación" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-uppercase opacity-75" style="font-family: Arial; letter-spacing: 1px;">Descripción</label>
                    <textarea name="descripcion" class="form-control bg-light border-0" rows="3" placeholder="Breve descripción"></textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-uppercase opacity-75" style="font-family: Arial; letter-spacing: 1px;">Enlace (URL)</label>
                    <input type="text" name="url" class="form-control bg-light border-0" placeholder="https://...">
                </div>

                <button type="submit" class="btn-add-new w-100 py-3 shadow-sm" style="font-family: Arial;">Guardar Publicación</button>
            </form>
        </div>
    </div>
</div>

<script>
    // ================= FUNCIÓN DE BÚSQUEDA EN TIEMPO REAL =================
    function filtrarTabla() {
        let input = document.getElementById("buscadorPublicaciones");
        let filtro = input.value.toLowerCase();
        let tbody = document.getElementById("tablaPublicaciones");
        let filas = tbody.getElementsByClassName("user-row");
        let sinResultados = document.getElementById("sinResultados");
        let coincidencias = 0;

        for (let i = 0; i < filas.length; i++) {
            // Evaluamos la fila completa combinando los textos de sus celdas
            let celdaTitulo = filas[i].getElementsByTagName("td")[0];
            let celdaFecha = filas[i].getElementsByTagName("td")[1];
            
            if (celdaTitulo && celdaFecha) {
                let textoFila = (celdaTitulo.textContent || celdaTitulo.innerText) + " " + 
                                (celdaFecha.textContent || celdaFecha.innerText);
                                
                if (textoFila.toLowerCase().indexOf(filtro) > -1) {
                    filas[i].style.display = "";
                    coincidencias++;
                } else {
                    filas[i].style.display = "none";
                }
            }
        }

        // Si escribes algo y no hay coincidencias, muestra el mensaje de "No se encontraron"
        if (coincidencias === 0 && filas.length > 0) {
            sinResultados.style.display = "";
        } else {
            sinResultados.style.display = "none";
        }
    }
</script>
@endsection