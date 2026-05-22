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
        
        /* ================= TABLA DE USUARIOS ================= */
        .user-table { width: 100%; border-collapse: separate; border-spacing: 0 12px; }
        .user-row { background: #fff; outline: 1px solid #eee; transition: all 0.3s ease; }
        .user-row:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .user-row td { padding: 15px 20px; vertical-align: middle; }
        .img-avatar { width: 45px; height: 45px; object-fit: cover; border-radius: 50%; border: 1px solid #ddd; }
        
        /* ================= ESTILOS DE LOS BOTONES ICONO (MONOCROMÁTICO) ================= */
        .btn-icon-action {
            background: none; border: none; font-size: 1.2rem; cursor: pointer;
            transition: transform 0.2s ease, color 0.3s ease; padding: 5px; margin-left: 10px;
            display: inline-flex; align-items: center; justify-content: center;
            color: #888; /* Gris tenue por defecto */
            text-decoration: none;
        }
        .btn-icon-action:hover { 
            transform: scale(1.15); 
            color: #111; /* Negro puro al pasar el ratón */
        }

        .badge-role { font-family: Arial, sans-serif; font-size: 0.75rem; font-weight: bold; padding: 4px 10px; border-radius: 12px; background: #eee; color: #333; }

        .modal-glass { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(15px); border: 1px solid rgba(0, 0, 0, 0.1); border-radius: 12px; }

        /* =========================================
           MENSAJES DE ERROR PERSONALIZADOS (AKIRAKA STYLE)
           ========================================= */
        .input-error {
            border-color: #d9534f !important;
            background-color: #fffcfc !important;
            box-shadow: 0 0 0 3px rgba(217, 83, 79, 0.1) !important;
        }

        .custom-error-msg {
            display: none; /* Oculto por defecto */
            background-color: #1c1c1c;
            color: #ffffff;
            padding: 8px 12px;
            font-size: 0.75rem;
            border-radius: 6px;
            margin-top: 5px; 
            margin-bottom: 10px;
            position: relative;
            font-family: "Helvetica Neue", Arial, sans-serif;
            letter-spacing: 0.05em;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            animation: slideDownFade 0.3s ease;
        }

        /* El piquito del globo de diálogo apuntando hacia arriba */
        .custom-error-msg::before {
            content: '';
            position: absolute;
            top: -4px;
            left: 20px;
            width: 10px;
            height: 10px;
            background-color: #1c1c1c;
            transform: rotate(45deg);
        }

        .custom-error-msg.show {
            display: block;
        }

        @keyframes slideDownFade {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <div class="dashboard-container">
        @include('partials.sidebar')

        <main class="main-content">
            @include('partials.topbar')
            
            <div class="header-section">
                <div>
                    <h1>Gestión de Usuarios</h1>
                    <p>Administración general de cuentas, roles y accesos.</p>
                </div>
                <button class="btn-add-new" data-bs-toggle="modal" data-bs-target="#modalCrearUsuario" onclick="prepararNuevo()">
                    + Añadir Usuario
                </button>
            </div>

            @if(session('success'))
                <div class="alert alert-dark mb-4" style="font-family: Arial; font-size: 0.85rem;">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger mb-4" style="font-family: Arial; font-size: 0.85rem;">{{ session('error') }}</div>
            @endif

            <table class="user-table">
                <thead>
                    <tr style="text-align: left; color: #888; font-family: Arial; font-size: 0.75rem; letter-spacing: 2px; text-transform: uppercase;">
                        <th>Usuario</th>
                        <th>Correo Electrónico</th>
                        <th>Rol</th>
                        <th style="text-align: right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usuarios as $usuario)
                        
                        @if(Auth::user()->id_rol == 2 && $usuario->id_rol == 1)
                            @continue
                        @endif

                        <tr class="user-row">
                            <td style="width: 300px;">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $usuario->foto ? asset('storage/'.$usuario->foto) : 'https://ui-avatars.com/api/?name='.urlencode($usuario->nombre).'&background=111&color=fff' }}" class="img-avatar">
                                    <div style="font-weight: bold; font-size: 1rem; font-family: Arial, sans-serif;">
                                        {{ $usuario->nombre }}
                                    </div>
                                </div>
                            </td>
                            <td style="font-family: Arial, sans-serif; font-size: 0.9rem; color: #555;">
                                {{ $usuario->correo }}
                            </td>
                            <td>
                                <span class="badge-role" style="background: {{ $usuario->id_rol == 1 ? '#111' : '#eee' }}; color: {{ $usuario->id_rol == 1 ? '#fff' : '#333' }};">
                                    {{ $usuario->id_rol == 1 ? 'Superadmin' : ($usuario->id_rol == 2 ? 'Administrador' : ($usuario->id_rol == 3 ? 'Colaborador' : 'Pendiente')) }}
                                </span>
                            </td>
                            <td style="text-align: right; white-space: nowrap;">
                                
                                {{-- Icono Editar --}}
                                <button type="button" class="btn-icon-action" title="Editar Rol" onclick='editarRolUsuario(@json($usuario))'>
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                
                                {{-- Icono Eliminar (No te puedes eliminar a ti mismo) --}}
                                @if($usuario->id_usuario !== Auth::user()->id_usuario)
                                    <button type="button" class="btn-icon-action" title="Eliminar Usuario" onclick="confirmarEliminacion('{{ route('usuarios.destroy', $usuario->id_usuario) }}')">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted" style="font-style: italic;">No hay usuarios registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </main>
    </div>
</div>

<!-- ==============================================
     MODAL PARA CREAR USUARIO (CON VALIDACIONES)
     ============================================== -->
<div class="modal fade" id="modalCrearUsuario" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-glass p-4 border-0 shadow-lg">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="m-0 fw-bold">Añadir Usuario</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="formCrearUsuario" action="{{ route('usuarios.store') }}" method="POST">
                @csrf
                
                <!-- CAMPO NOMBRE -->
                <div class="mb-3 position-relative">
                    <label class="form-label small fw-bold text-uppercase opacity-75" style="font-family: Arial; letter-spacing: 1px;">Nombre</label>
                    <input type="text" name="nombre" id="crear_nombre" class="form-control border-0 bg-light" required>
                    <!-- Globo de Error -->
                    <div id="error_crear_nombre" class="custom-error-msg">
                        <i class="bi bi-shield-exclamation" style="margin-right: 6px; color: #d9534f;"></i>
                        Por favor, ingresa el nombre del usuario.
                    </div>
                </div>

                <!-- CAMPO CORREO -->
                <div class="mb-3 position-relative">
                    <label class="form-label small fw-bold text-uppercase opacity-75" style="font-family: Arial; letter-spacing: 1px;">Correo</label>
                    <input type="email" name="correo" id="crear_correo" class="form-control border-0 bg-light" required>
                    <!-- Globo de Error -->
                    <div id="error_crear_correo" class="custom-error-msg">
                        <i class="bi bi-shield-exclamation" style="margin-right: 6px; color: #d9534f;"></i>
                        Ingresa un formato de correo válido (ej. correo@akiraka.com).
                    </div>
                </div>

                <!-- CAMPO CONTRASEÑA -->
                <div class="mb-3 position-relative">
                    <label class="form-label small fw-bold text-uppercase opacity-75" style="font-family: Arial; letter-spacing: 1px;">Contraseña <span style="text-transform:none; letter-spacing:normal; font-weight:normal; color:#888;">(Mín. 6 caracteres)</span></label>
                    <input type="password" name="password" id="crear_password" class="form-control border-0 bg-light" required minlength="6">
                    <!-- Globo de Error -->
                    <div id="error_crear_password" class="custom-error-msg">
                        <i class="bi bi-shield-exclamation" style="margin-right: 6px; color: #d9534f;"></i>
                        La contraseña debe tener al menos 6 caracteres.
                    </div>
                </div>

                <!-- CAMPO ROL -->
                <div class="mb-4 position-relative">
                    <label class="form-label small fw-bold text-uppercase opacity-75" style="font-family: Arial; letter-spacing: 1px;">Asignar Rol</label>
                    <select name="id_rol" id="crear_id_rol" class="form-select border-0 bg-light" required>
                        <option value="" disabled selected>Selecciona un rol...</option>
                        @foreach($roles as $rol)
                            {{-- MAGIA DE SEGURIDAD 2: Un Administrador (2) NO puede crear un Superadmin (1) --}}
                            @if(Auth::user()->id_rol == 2 && $rol->id_rol == 1)
                                @continue
                            @endif
                            <option value="{{ $rol->id_rol }}">{{ $rol->nombre_rol }}</option>
                        @endforeach
                    </select>
                    <!-- Globo de Error -->
                    <div id="error_crear_id_rol" class="custom-error-msg">
                        <i class="bi bi-shield-exclamation" style="margin-right: 6px; color: #d9534f;"></i>
                        Debes seleccionar el nivel de acceso para este usuario.
                    </div>
                </div>

                <button type="submit" class="btn-add-new w-100 py-3 shadow-sm" style="font-family: Arial;">Crear Usuario</button>
            </form>
        </div>
    </div>
</div>

<!-- ==============================================
     MODAL PARA EDITAR ROL 
     ============================================== -->
<div class="modal fade" id="modalEditarRol" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-glass p-4 border-0 shadow-lg">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="m-0 fw-bold">Modificar Permisos</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="alert alert-light border mb-4" style="font-size: 0.85rem; color: #666; font-family: Arial, sans-serif;">
                <strong>Nota de Seguridad:</strong> Por protección, el nombre y la contraseña solo pueden ser modificados por el propio usuario.
            </div>

            <form id="formEditarRol" action="" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label class="form-label small fw-bold text-uppercase opacity-75" style="font-family: Arial; letter-spacing: 1px;">Asignar nuevo rol a:</label>
                    <div id="nombreUsuarioBadge" class="form-control border-0 bg-light mb-3" style="font-weight:bold; color:#333; font-family: Arial;"></div>

                    <select name="id_rol" id="editar_id_rol" class="form-select border-0 bg-light" required>
                        <option value="" disabled>Selecciona el nivel de acceso...</option>
                        @foreach($roles as $rol)
                            {{-- MAGIA DE SEGURIDAD 3: Un Administrador (2) NO puede ascender a alguien a Superadmin (1) --}}
                            @if(Auth::user()->id_rol == 2 && $rol->id_rol == 1)
                                @continue
                            @endif
                            <option value="{{ $rol->id_rol }}">{{ $rol->nombre_rol }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn-add-new w-100 py-3 shadow-sm" style="font-family: Arial;">Actualizar Rol</button>
            </form>
        </div>
    </div>
</div>

<form id="formEliminarMaestro" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
    // =======================================================
    // MAGIA DE VALIDACIONES (Evita que el navegador cierre o muestre alertas nativas)
    // =======================================================
    document.addEventListener("DOMContentLoaded", function() {
        // Los IDs de los campos que queremos validar en el modal de crear
        const inputsAValidar = ['crear_nombre', 'crear_correo', 'crear_password', 'crear_id_rol'];
        
        inputsAValidar.forEach(id => {
            const inputElement = document.getElementById(id);
            const errorElement = document.getElementById('error_' + id);
            
            if (inputElement && errorElement) {
                // Cuando el usuario le da a "Crear" y el campo no cumple las reglas (vacío, corto, sin @)
                inputElement.addEventListener('invalid', function(e) {
                    e.preventDefault(); // Cancelamos la alerta fea del navegador
                    errorElement.classList.add('show'); // Mostramos nuestro globito
                    inputElement.classList.add('input-error'); // Pintamos rojo el borde
                });

                // Mientras el usuario empieza a escribir o selecciona algo, ocultamos el error
                inputElement.addEventListener('input', function(e) {
                    errorElement.classList.remove('show');
                    inputElement.classList.remove('input-error');
                });
                
                // Evento específico para el <select> de Rol
                inputElement.addEventListener('change', function(e) {
                    errorElement.classList.remove('show');
                    inputElement.classList.remove('input-error');
                });
            }
        });
    });

    // Modificamos la función para que también limpie los errores al abrir el modal
    function prepararNuevo() {
        document.getElementById('crear_nombre').value = '';
        document.getElementById('crear_correo').value = '';
        document.getElementById('crear_password').value = '';
        document.getElementById('crear_id_rol').value = '';

        // Limpiar globitos rojos por si cerraron el modal cuando estaban activos
        const inputsAValidar = ['crear_nombre', 'crear_correo', 'crear_password', 'crear_id_rol'];
        inputsAValidar.forEach(id => {
            const inputElement = document.getElementById(id);
            const errorElement = document.getElementById('error_' + id);
            if (inputElement) inputElement.classList.remove('input-error');
            if (errorElement) errorElement.classList.remove('show');
        });
    }

    function editarRolUsuario(usuario) {
        document.getElementById('nombreUsuarioBadge').innerText = usuario.nombre;
        document.getElementById('editar_id_rol').value = usuario.id_rol;
        
        let urlUpdate = "{{ route('usuarios.update', ':id') }}";
        urlUpdate = urlUpdate.replace(':id', usuario.id_usuario);
        document.getElementById('formEditarRol').action = urlUpdate; 
        
        var myModal = new bootstrap.Modal(document.getElementById('modalEditarRol'));
        myModal.show();
    }

    function confirmarEliminacion(url) {
        if(confirm('¿Estás 100% seguro de que quieres eliminar a este usuario? Esta acción asignará sus publicaciones al Administrador.')) {
            let form = document.getElementById('formEliminarMaestro');
            form.action = url;
            form.submit();
        }
    }
</script>
@endsection