@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<div class="dash-admin-view" style="min-height: 100vh; background-color: #f8f8f8; font-family: 'Garamond', serif; padding: 20px; color: #111;">
    
    <style>
        /* ================= ESTILOS DE LOS BOTONES ICONO (MONOCROMÁTICO) ================= */
        .btn-icon-action {
            background: none; border: none; font-size: 1.2rem; cursor: pointer;
            transition: transform 0.2s ease, color 0.3s ease; padding: 5px; margin-left: 10px;
            display: inline-flex; align-items: center; justify-content: center;
            color: #888; /* Gris tenue por defecto para que no sature la vista */
        }
        .btn-icon-action:hover { 
            transform: scale(1.15); 
            color: #111; /* Negro puro al pasar el ratón (Hover) */
        }

        /* ================= BOTÓN VER MÁS ================= */
        .btn-ver-mas {
            background: none; border: none; color: #111; font-size: 0.75rem; 
            font-weight: bold; text-decoration: underline; padding: 0; 
            margin-top: 5px; cursor: pointer; text-transform: uppercase; 
            letter-spacing: 1px; transition: color 0.3s;
        }
        .btn-ver-mas:hover { color: #666; }

        /* ================= MODALES (GLASSMORPHISM) ================= */
        .modal-glass {
            background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(15px);
            border: 1px solid rgba(0, 0, 0, 0.1); border-radius: 12px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1); padding: 30px;
        }
    </style>

    <div class="dashboard-container" style="max-width: 1400px; margin: 0 auto; display: flex; gap: 20px;">
        
        @include('partials.sidebar')

        <main class="main-content" style="flex-grow: 1; background-color: #fff; padding: 40px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
            @include('partials.topbar')
            
            <div style="border-bottom: 1px solid #eaeaea; padding-bottom: 20px; margin-bottom: 30px;">
                <h1 style="font-size: 2.2rem; font-weight: 700; margin: 0;">Bandeja de Prospectos</h1>
                <p style="font-family: Arial; font-size: 0.9rem; color: #666;">Solicitudes de clientes y agendamiento de citas.</p>
            </div>

            @if(session('success'))
                <div class="alert alert-dark mb-4" style="font-family: Arial; font-size: 0.85rem;">{{ session('success') }}</div>
            @endif

            <table style="width: 100%; border-collapse: separate; border-spacing: 0 12px;">
                <thead>
                    <tr style="text-align: left; color: #888; font-family: Arial; font-size: 0.75rem; letter-spacing: 2px; text-transform: uppercase;">
                        <th>Cliente / Contacto</th>
                        <th>Asunto (Servicio)</th>
                        <th>Descripción del Proyecto</th>
                        <th>Estado</th>
                        <th style="text-align: right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($solicitudes as $solicitud)
                        <tr style="background: #fff; outline: 1px solid #eee; transition: transform 0.2s;">
                            <td style="padding: 15px;">
                                <div style="font-weight: bold; font-size: 1.1rem; color: #111;">{{ $solicitud->cliente_nombre }}</div>
                                <div style="font-family: Arial; font-size: 0.85rem; color: #666;">
                                    {{ $solicitud->cliente_correo }} <br>
                                    {{ $solicitud->cliente_telefono ?? 'No proporcionado' }}
                                </div>
                            </td>
                            <td style="padding: 15px; font-weight: bold; color: #333;">
                                {{ $solicitud->asunto_servicio }}
                            </td>
                            
                            {{-- CELDA DE DESCRIPCIÓN CON RECORTE Y BOTÓN --}}
                            <td style="padding: 15px; font-family: Arial; font-size: 0.9rem; color: #555; max-width: 300px;">
                                {{ \Illuminate\Support\Str::limit($solicitud->descripcion_proyecto, 75) }}
                                
                                @if(strlen($solicitud->descripcion_proyecto) > 75)
                                    <br>
                                    <button type="button" class="btn-ver-mas" data-mensaje="{{ $solicitud->descripcion_proyecto }}" onclick="verMensajeCompleto(this)">
                                        <i class="bi bi-eye"></i> Leer más
                                    </button>
                                @endif
                            </td>
                            
                            <td style="padding: 15px;">
                                <span style="font-family: Arial; font-size: 0.75rem; font-weight: bold; padding: 4px 10px; border-radius: 12px; 
                                    background: {{ $solicitud->estado == 'Pendiente' ? '#fff3cd' : '#d1e7dd' }};
                                    color: {{ $solicitud->estado == 'Pendiente' ? '#856404' : '#0f5132' }};">
                                    {{ $solicitud->estado }}
                                </span>
                            </td>
                            
                            {{-- COLUMNA DE ACCIONES MINIMALISTAS --}}
                            <td style="padding: 15px; text-align: right; white-space: nowrap;">
                                
                                @if($solicitud->estado == 'Pendiente')
                                    {{-- Botón Aceptar --}}
                                    <form action="{{ route('dashboard.citas.estado', $solicitud->id_cita) }}" method="POST" style="display: inline-block;">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="estado" value="Confirmada">
                                        <button type="submit" class="btn-icon-action icon-accept" title="Aceptar Cita y Notificar" onclick="return confirm('¿Aceptar esta solicitud y enviar correo al cliente?');">
                                            <i class="bi bi-check-circle-fill"></i>
                                        </button>
                                    </form>

                                    {{-- Botón Rechazar (Abre Modal de Advertencia) --}}
                                    <button type="button" class="btn-icon-action icon-reject" title="Rechazar y Eliminar" onclick="abrirModalRechazo('{{ $solicitud->id_cita }}', '{{ addslashes($solicitud->cliente_nombre) }}')">
                                        <i class="bi bi-x-circle-fill"></i>
                                    </button>
                                @endif

                                {{-- Botón Email Directo --}}
                                <a href="mailto:{{ $solicitud->cliente_correo }}?subject=Sobre tu solicitud de {{ $solicitud->asunto_servicio }}" class="btn-icon-action icon-email" title="Enviar correo manual">
                                    <i class="bi bi-envelope"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 40px; color: #888; font-style: italic;">No hay solicitudes de clientes por el momento.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </main>
    </div>
</div>

<div class="modal fade" id="modalRechazarCita" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-glass border-0">
            <div class="text-center mb-4">
                <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 3rem;"></i>
                <h3 class="mt-3 fw-bold" style="font-family: 'Garamond', serif;">Atención</h3>
            </div>
            
            <p style="font-family: Arial; font-size: 0.95rem; color: #444; text-align: center; line-height: 1.5;">
                Estás a punto de rechazar la solicitud de <strong><span id="nombreClienteModal"></span></strong>.
            </p>
            
            <div style="background: #fdf0f0; border-left: 4px solid #ef4444; padding: 15px; border-radius: 4px; font-family: Arial; font-size: 0.8rem; color: #b91c1c; margin-bottom: 25px;">
                <strong>¿Qué sucederá?</strong><br>
                1. Se enviará un correo automático notificando al cliente sobre la cancelación.<br>
                2. El mensaje será <strong>eliminado permanentemente</strong> de esta bandeja para mantener el orden.
            </div>

            <form id="formRechazar" method="POST" action="">
                @csrf
                @method('PUT')
                <input type="hidden" name="estado" value="Cancelada">
                
                <div class="d-flex gap-3">
                    <button type="button" class="btn btn-light w-50" data-bs-dismiss="modal" style="font-family: Arial; text-transform: uppercase; font-size: 0.8rem; font-weight: bold; letter-spacing: 1px;">Cancelar</button>
                    <button type="submit" class="btn btn-danger w-50" style="font-family: Arial; text-transform: uppercase; font-size: 0.8rem; font-weight: bold; letter-spacing: 1px; border: none;">Sí, rechazar y borrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalVerMensaje" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content modal-glass border-0" style="padding: 40px;">
            <div class="d-flex justify-content-between align-items-center mb-4" style="border-bottom: 1px solid #eee; padding-bottom: 15px;">
                <h3 class="m-0 fw-bold" style="font-family: 'Garamond', serif;">Descripción del Proyecto</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div id="contenidoMensajeModal" style="font-family: Arial; font-size: 1rem; color: #444; line-height: 1.8; white-space: pre-wrap;">
            </div>
            
            <div class="mt-4 text-end">
                <button type="button" class="btn btn-dark" data-bs-dismiss="modal" style="font-family: Arial; text-transform: uppercase; font-size: 0.8rem; font-weight: bold; letter-spacing: 1px;">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    function abrirModalRechazo(idCita, nombreCliente) {
        document.getElementById('nombreClienteModal').innerText = nombreCliente;
        
        let url = "{{ route('dashboard.citas.estado', ':id') }}";
        url = url.replace(':id', idCita);
        document.getElementById('formRechazar').action = url;
        
        var myModal = new bootstrap.Modal(document.getElementById('modalRechazarCita'));
        myModal.show();
    }

    function verMensajeCompleto(boton) {
        const mensajeCompleto = boton.getAttribute('data-mensaje');
        document.getElementById('contenidoMensajeModal').textContent = mensajeCompleto;
        
        var myModal = new bootstrap.Modal(document.getElementById('modalVerMensaje'));
        myModal.show();
    }
</script>
@endsection