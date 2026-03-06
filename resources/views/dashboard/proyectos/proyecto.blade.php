@extends('layouts.app')

@section('content')
<div class="dash-admin-view">
    <style>
        /* ================= ESTILOS PRINCIPALES ================= */
        .dash-admin-view {
            min-height: 100vh;
            background-color: #f8f8f8;
            font-family: "Garamond", "Baskerville", serif;
            color: #111;
            padding: 20px;
            display: flex;
            justify-content: center;
        }

        .dashboard-container {
            display: flex;
            width: 100%;
            max-width: 1400px;
            gap: 20px;
            align-items: stretch;
        }

        .main-content {
            flex-grow: 1;
            background-color: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        .header-section {
            border-bottom: 1px solid #eaeaea;
            padding-bottom: 20px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .header-section h1 { font-size: 2.2rem; font-weight: 700; margin: 0; }

        .btn-add-new {
            background: #111; color: #fff; border: none;
            padding: 10px 20px; border-radius: 6px;
            font-size: 0.8rem; letter-spacing: 1px; text-transform: uppercase;
            cursor: pointer; transition: background 0.3s ease;
        }
        .btn-add-new:hover { background: #333; }

        /* ================= TABLA DE PROYECTOS ================= */
        .project-table { width: 100%; border-collapse: separate; border-spacing: 0 12px; }
        .project-row { background: #fff; outline: 1px solid #eee; transition: all 0.3s ease; }
        .project-row:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .project-row td { padding: 15px 20px; vertical-align: middle; }

        .btn-action-minimal {
            background: none; border: none; color: #111; text-transform: uppercase;
            font-size: 0.7rem; font-weight: bold; letter-spacing: 1px;
            text-decoration: underline; margin-right: 15px; cursor: pointer;
        }

        .badge-estado {
            font-family: Arial, sans-serif; font-size: 0.75rem; font-weight: bold;
            padding: 4px 10px; border-radius: 12px; background: #eee; color: #333;
        }

        .desc-text {
            color: #666; font-size: 0.85rem; line-height: 1.4;
            display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
            overflow: hidden; margin-top: 5px;
        }

        /* ================= MODAL GLASSMORPHISM ================= */
        .modal-glass {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 12px;
        }
    </style>

    <div class="dashboard-container">
        
        @include('partials.sidebar')

        <main class="main-content">
            <div class="header-section">
                <div>
                    <h1>Portafolio de Proyectos</h1>
                    <p>Gestión de obras, costos estimados y estado de construcción.</p>
                </div>
                <button class="btn-add-new" data-bs-toggle="modal" data-bs-target="#modalProyecto" onclick="prepararNuevo()">
                    + Añadir Proyecto
                </button>
            </div>

            @if(session('success'))
                <div class="alert alert-dark mb-4">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <table class="project-table">
                <thead>
                    <tr style="text-align: left; color: #888; font-size: 0.75rem; letter-spacing: 2px; text-transform: uppercase;">
                        <th style="width: 35%;">Detalles del Proyecto</th>
                        <th>Costos (Inicial / Final)</th>
                        <th>Estado</th>
                        <th style="text-align: right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($proyectos as $proyecto)
                        <tr class="project-row">
                            <td>
                                <div style="font-weight: bold; font-size: 1.05rem;">{{ $proyecto->titulo }}</div>
                                <div class="desc-text">{{ $proyecto->descripcion ?? 'Sin descripción.' }}</div>
                            </td>
                            <td style="font-family: Arial, sans-serif; font-size: 0.9rem; color: #555;">
                                <div><strong>Inicial:</strong> ${{ number_format($proyecto->costo_inicial, 2) }}</div>
                                <div><strong>Final:</strong> ${{ number_format($proyecto->costo_final, 2) }}</div>
                            </td>
                            <td>
                                <span class="badge-estado">
                                    {{-- Lógica rápida para empatar el ID con el nombre del estado --}}
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
                            <td style="text-align: right;">
                                <button class="btn-action-minimal" onclick="editarProyecto({{ json_encode($proyecto) }})">Editar</button>
                                
                                <form action="{{ route('proyectos.destroy', $proyecto->id_proyecto) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar este proyecto definitivamente?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-action-minimal" style="color: #d9534f;">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">No hay proyectos registrados en el portafolio.</td>
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
                <h3 id="modalTitle" class="m-0 fw-bold">Añadir Proyecto</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="formProyecto" action="{{ route('proyectos.store') }}" method="POST">
                @csrf
                <div id="methodField"></div>
                
                <div class="mb-3">
                    <label class="form-label small fw-bold text-uppercase opacity-75">Título del Proyecto</label>
                    <input type="text" name="titulo" id="titulo" class="form-control border-0 bg-light" required maxlength="150">
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-uppercase opacity-75">Descripción</label>
                    <textarea name="descripcion" id="descripcion" class="form-control border-0 bg-light" rows="3"></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label small fw-bold text-uppercase opacity-75">Costo Inicial Estimado ($)</label>
                        <input type="number" step="0.01" name="costo_inicial" id="costo_inicial" class="form-control border-0 bg-light">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label small fw-bold text-uppercase opacity-75">Costo Final Real ($)</label>
                        <input type="number" step="0.01" name="costo_final" id="costo_final" class="form-control border-0 bg-light">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-uppercase opacity-75">Estado del Proyecto</label>
                    <select name="id_estado" id="id_estado" class="form-select border-0 bg-light" required>
                        <option value="" disabled selected>Selecciona el estado...</option>
                        @foreach($estados as $estado)
                            <option value="{{ $estado->id_estado }}">{{ $estado->nombre_estado }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn-add-new w-100 py-3 shadow-sm" id="btnSubmit">Guardar Proyecto</button>
            </form>
        </div>
    </div>
</div>

<script>
    function prepararNuevo() {
        document.getElementById('modalTitle').innerText = 'Añadir Nuevo Proyecto';
        document.getElementById('formProyecto').action = "{{ route('proyectos.store') }}";
        document.getElementById('methodField').innerHTML = ''; 
        
        // Limpiar campos
        document.getElementById('titulo').value = '';
        document.getElementById('descripcion').value = '';
        document.getElementById('costo_inicial').value = '';
        document.getElementById('costo_final').value = '';
        document.getElementById('id_estado').value = '';

        document.getElementById('btnSubmit').innerText = 'Guardar Proyecto';
    }

    function editarProyecto(proyecto) {
        document.getElementById('modalTitle').innerText = 'Editar Proyecto';
        
        // Generamos la URL dinámica de actualización
        let urlUpdate = "{{ route('proyectos.update', ':id') }}";
        urlUpdate = urlUpdate.replace(':id', proyecto.id_proyecto);
        
        document.getElementById('formProyecto').action = urlUpdate; 
        document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        
        // Llenar campos
        document.getElementById('titulo').value = proyecto.titulo;
        document.getElementById('descripcion').value = proyecto.descripcion || '';
        document.getElementById('costo_inicial').value = proyecto.costo_inicial || '';
        document.getElementById('costo_final').value = proyecto.costo_final || '';
        document.getElementById('id_estado').value = proyecto.id_estado;

        document.getElementById('btnSubmit').innerText = 'Actualizar Cambios';
        
        var myModal = new bootstrap.Modal(document.getElementById('modalProyecto'));
        myModal.show();
    }
</script>
@endsection