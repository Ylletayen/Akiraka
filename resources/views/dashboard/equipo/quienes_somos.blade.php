@extends('layouts.app')

@section('content')
<div class="dash-admin-view">
    <style>
        /* ================= TODOS TUS ESTILOS ORIGINALES INTACTOS ================= */
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

        .sidebar {
            width: 260px;
            background-color: #1c1c1c;
            color: #fff;
            padding: 25px;
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .logo-img { width: 30px; height: 40px; object-fit: cover; }

        .nav-link {
            color: #fff;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #2c2c2c;
            border-radius: 8px;
            padding: 10px 15px;
            transition: all 0.3s ease;
            text-decoration: none;
            font-family: Arial, sans-serif;
            font-size: 0.9rem;
        }

        .nav-link:hover, .nav-link.active { background-color: #4b4b4b; }

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

        .equipo-table { width: 100%; border-collapse: separate; border-spacing: 0 12px; }
        .equipo-row { background: #fff; outline: 1px solid #eee; transition: all 0.3s ease; }
        .equipo-row:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .equipo-row td { padding: 20px; vertical-align: middle; }

        .img-avatar {
            width: 55px; height: 55px; object-fit: cover;
            border-radius: 8px; border: 1px solid #ddd;
        }

        .biografia-text {
            color: #666; font-size: 0.85rem; line-height: 1.4;
            display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .btn-action-minimal {
            background: none; border: none; color: #111; text-transform: uppercase;
            font-size: 0.7rem; font-weight: bold; letter-spacing: 1px;
            text-decoration: underline; margin-right: 15px; cursor: pointer;
        }

        .btn-add-new {
            background: #111; color: #fff; border: none;
            padding: 10px 20px; border-radius: 6px;
            font-size: 0.8rem; letter-spacing: 1px; text-transform: uppercase;
        }

        /* Estilo para el Modal Glassmorphism */
        .modal-glass {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 12px;
        }
    </style>

    <div class="dashboard-container">
        
        @include('partials.sidebar')

        <main class="main-content">
            <div class="header-section">
                <div>
                    <h1>Nuestro Equipo</h1>
                    <p>Gestión de perfiles profesionales para el portafolio público.</p>
                </div>
                <button class="btn-add-new" data-bs-toggle="modal" data-bs-target="#modalMiembro" onclick="prepararNuevo()">
                    + Añadir Miembro
                </button>
            </div>

            @if(session('success'))
                <div class="alert alert-dark mb-4">{{ session('success') }}</div>
            @endif

            <table class="equipo-table">
                <thead>
                    <tr style="text-align: left; color: #888; font-size: 0.75rem; letter-spacing: 2px; text-transform: uppercase;">
                        <th style="padding-left: 20px;">Miembro</th>
                        <th>Biografía Profesional</th>
                        <th style="text-align: right; padding-right: 20px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($miembros as $miembro)
                        <tr class="equipo-row">
                            <td style="width: 250px;">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $miembro->usuario->foto ? asset('storage/'.$miembro->usuario->foto) : 'https://ui-avatars.com/api/?name='.urlencode($miembro->usuario->nombre).'&background=111&color=fff' }}" class="img-avatar">
                                    <div>
                                        <div style="font-weight: bold; font-size: 1rem;">{{ $miembro->usuario->nombre }}</div>
                                        <div style="font-size: 0.75rem; color: #888; font-family: Arial;">
                                            {{ $miembro->usuario->id_rol == 1 ? 'Superadmin' : ($miembro->usuario->id_rol == 2 ? 'Administrador' : 'Colaborador') }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <p class="biografia-text">{{ $miembro->biografia }}</p>
                            </td>
                            <td style="text-align: right;">
                                <button class="btn-action-minimal" onclick="editarMiembro({{ $miembro }})">Editar</button>
                                <form action="{{ route('equipo.destroy', $miembro->id_miembro) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar a este miembro del equipo?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-action-minimal" style="color: #d9534f;">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-5 text-muted">No hay miembros registrados en el equipo.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </main>
    </div>
</div>

<div class="modal fade" id="modalMiembro" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-glass p-4 border-0 shadow-lg">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 id="modalTitle" class="m-0 fw-bold">Añadir Miembro</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="formMiembro" action="{{ route('equipo.store') }}" method="POST">
                @csrf
                <div id="methodField"></div>
                
                <div class="mb-3" id="usuarioSelection">
                    <label class="form-label small fw-bold text-uppercase opacity-75">Seleccionar Usuario</label>
                    <select name="id_usuario" id="id_usuario" class="form-select border-0 bg-light" required>
                        <option value="" disabled selected>Elegir de la lista...</option>
                        @foreach($usuariosDisponibles as $u)
                            <option value="{{ $u->id_usuario }}">{{ $u->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-uppercase opacity-75">Biografía Profesional</label>
                    <textarea name="biografia" id="biografia" class="form-control border-0 bg-light" rows="5" required placeholder="Escribe aquí la trayectoria profesional..."></textarea>
                </div>

                <button type="submit" class="btn-add-new w-100 py-3 shadow-sm">Guardar Cambios</button>
            </form>
        </div>
    </div>
</div>

<script>
    function prepararNuevo() {
        document.getElementById('modalTitle').innerText = 'Añadir Miembro';
        document.getElementById('formMiembro').action = "{{ route('equipo.store') }}";
        document.getElementById('methodField').innerHTML = '';
        
        let selectWrapper = document.getElementById('usuarioSelection');
        selectWrapper.style.display = 'block';
        document.getElementById('id_usuario').required = true; // Volvemos a hacerlo requerido
        
        document.getElementById('biografia').value = '';
    }

    function editarMiembro(miembro) {
        document.getElementById('modalTitle').innerText = 'Editar Biografía';
        
        // Generamos la URL dinámicamente usando Blade para evitar errores 404 de rutas relativas
        let urlUpdate = "{{ route('equipo.update', ':id') }}";
        urlUpdate = urlUpdate.replace(':id', miembro.id_miembro); // Intercambiamos el comodín por el ID real
        
        document.getElementById('formMiembro').action = urlUpdate; 
        document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        
        let selectWrapper = document.getElementById('usuarioSelection');
        selectWrapper.style.display = 'none'; 
        document.getElementById('id_usuario').required = false; 
        
        document.getElementById('biografia').value = miembro.biografia;
        
        var myModal = new bootstrap.Modal(document.getElementById('modalMiembro'));
        myModal.show();
    }
</script>
@endsection