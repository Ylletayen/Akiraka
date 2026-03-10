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

        /* ================= TABLA DE USUARIOS ================= */
        .user-table { width: 100%; border-collapse: separate; border-spacing: 0 12px; }
        .user-row { background: #fff; outline: 1px solid #eee; transition: all 0.3s ease; }
        .user-row:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .user-row td { padding: 15px 20px; vertical-align: middle; }

        .img-avatar {
            width: 45px; height: 45px; object-fit: cover;
            border-radius: 50%; border: 1px solid #ddd;
        }

        .btn-action-minimal {
            background: none; border: none; color: #111; text-transform: uppercase;
            font-size: 0.7rem; font-weight: bold; letter-spacing: 1px;
            text-decoration: underline; margin-right: 15px; cursor: pointer;
        }

        .badge-role {
            font-family: Arial, sans-serif; font-size: 0.75rem; font-weight: bold;
            padding: 4px 10px; border-radius: 12px; background: #eee; color: #333;
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
            
        <!-- EL TOPBAR LIMPIO VA AQUÍ ARRIBA -->
            @include('partials.topbar')
            
            <div class="header-section">
                <div>
                    <h1>Gestión de Usuarios</h1>
                    <p>Administración general de cuentas, roles y accesos.</p>
                </div>
                <button class="btn-add-new" data-bs-toggle="modal" data-bs-target="#modalUsuario" onclick="prepararNuevo()">
                    + Añadir Usuario
                </button>
            </div>

            @if(session('success'))
                <div class="alert alert-dark mb-4">{{ session('success') }}</div>
            @endif

            <table class="user-table">
                <thead>
                    <tr style="text-align: left; color: #888; font-size: 0.75rem; letter-spacing: 2px; text-transform: uppercase;">
                        <th>Usuario</th>
                        <th>Correo Electrónico</th>
                        <th>Rol</th>
                        <th style="text-align: right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usuarios as $usuario)
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
                                <span class="badge-role">
                                    {{ $usuario->id_rol == 1 ? 'Superadmin' : ($usuario->id_rol == 2 ? 'Administrador' : ($usuario->id_rol == 3 ? 'Colaborador' : 'Pendiente')) }}
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <button class="btn-action-minimal" onclick="editarUsuario({{ json_encode($usuario) }})">Editar</button>
                                
                                @if($usuario->id_usuario !== Auth::user()->id_usuario)
                                <form action="{{ route('usuarios.destroy', $usuario->id_usuario) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de eliminar este usuario?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-action-minimal" style="color: #d9534f;">Eliminar</button>
                                </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">No hay usuarios registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </main>
    </div>
</div>

<div class="modal fade" id="modalUsuario" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-glass p-4 border-0 shadow-lg">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 id="modalTitle" class="m-0 fw-bold">Añadir Usuario</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="formUsuario" action="{{ route('usuarios.store') }}" method="POST">
                @csrf
                <div id="methodField"></div>
                
                <div class="mb-3">
                    <label class="form-label small fw-bold text-uppercase opacity-75">Nombre Completo</label>
                    <input type="text" name="nombre" id="nombre" class="form-control border-0 bg-light" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-uppercase opacity-75">Correo Electrónico</label>
                    <input type="email" name="correo" id="correo" class="form-control border-0 bg-light" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-uppercase opacity-75">Contraseña</label>
                    <input type="password" name="password" id="password" class="form-control border-0 bg-light">
                    <small id="passwordHelp" class="text-muted" style="font-size: 0.75rem; display: none;">Déjalo en blanco si no deseas cambiar la contraseña actual.</small>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-uppercase opacity-75">Asignar Rol</label>
                    <select name="id_rol" id="id_rol" class="form-select border-0 bg-light" required>
                        <option value="" disabled selected>Selecciona un rol...</option>
                        @foreach($roles as $rol)
                            <option value="{{ $rol->id_rol }}">{{ $rol->nombre_rol }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn-add-new w-100 py-3 shadow-sm" id="btnSubmit">Guardar Usuario</button>
            </form>
        </div>
    </div>
</div>

<script>
    function prepararNuevo() {
        document.getElementById('modalTitle').innerText = 'Añadir Nuevo Usuario';
        document.getElementById('formUsuario').action = "{{ route('usuarios.store') }}";
        document.getElementById('methodField').innerHTML = ''; // Limpiamos el método PUT
        
        // Limpiar campos
        document.getElementById('nombre').value = '';
        document.getElementById('correo').value = '';
        document.getElementById('id_rol').value = '';
        
        // La contraseña es obligatoria al crear
        let passInput = document.getElementById('password');
        passInput.value = '';
        passInput.required = true;
        document.getElementById('passwordHelp').style.display = 'none';

        document.getElementById('btnSubmit').innerText = 'Guardar Usuario';
    }

    function editarUsuario(usuario) {
        document.getElementById('modalTitle').innerText = 'Editar Usuario';
        
        // Generamos la URL dinámica de actualización
        let urlUpdate = "{{ route('usuarios.update', ':id') }}";
        urlUpdate = urlUpdate.replace(':id', usuario.id_usuario);
        
        document.getElementById('formUsuario').action = urlUpdate; 
        document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        
        // Llenar campos
        document.getElementById('nombre').value = usuario.nombre;
        document.getElementById('correo').value = usuario.correo;
        document.getElementById('id_rol').value = usuario.id_rol;
        
        // La contraseña NO es obligatoria al editar
        let passInput = document.getElementById('password');
        passInput.value = '';
        passInput.required = false;
        document.getElementById('passwordHelp').style.display = 'block';

        document.getElementById('btnSubmit').innerText = 'Actualizar Cambios';
        
        var myModal = new bootstrap.Modal(document.getElementById('modalUsuario'));
        myModal.show();
    }
</script>
@endsection