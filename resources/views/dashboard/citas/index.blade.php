@extends('layouts.app')

@section('content')
<div class="dash-admin-view" style="min-height: 100vh; background-color: #f8f8f8; font-family: 'Garamond', serif; padding: 20px; color: #111;">
    <div class="dashboard-container" style="max-width: 1400px; margin: 0 auto; display: flex; gap: 20px;">
        
        @include('partials.sidebar')

        <main class="main-content" style="flex-grow: 1; background-color: #fff; padding: 40px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
            @include('partials.topbar')
            
            <div style="border-bottom: 1px solid #eaeaea; padding-bottom: 20px; margin-bottom: 30px;">
                <h1 style="font-size: 2.2rem; font-weight: 700; margin: 0;">Bandeja de Prospectos</h1>
                <p style="font-family: Arial; font-size: 0.9rem; color: #666;">Solicitudes de clientes y agendamiento de citas.</p>
            </div>

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
                                    ✉ {{ $solicitud->cliente_correo }} <br>
                                    ☏ {{ $solicitud->cliente_telefono ?? 'No proporcionado' }}
                                </div>
                            </td>
                            <td style="padding: 15px; font-weight: bold; color: #333;">
                                {{ $solicitud->asunto_servicio }}
                            </td>
                            <td style="padding: 15px; font-family: Arial; font-size: 0.9rem; color: #555; max-width: 300px;">
                                {{ $solicitud->descripcion_proyecto }}
                            </td>
                            <td style="padding: 15px;">
                                <span style="font-family: Arial; font-size: 0.75rem; font-weight: bold; padding: 4px 10px; border-radius: 12px; 
                                    background: {{ $solicitud->estado == 'Pendiente' ? '#fff3cd' : ($solicitud->estado == 'Completada' ? '#d1e7dd' : ($solicitud->estado == 'Confirmada' ? '#cce5ff' : '#f8d7da')) }};
                                    color: {{ $solicitud->estado == 'Pendiente' ? '#856404' : ($solicitud->estado == 'Completada' ? '#0f5132' : ($solicitud->estado == 'Confirmada' ? '#004085' : '#721c24')) }};">
                                    {{ $solicitud->estado }}
                                </span>
                            </td>
                            <td style="padding: 15px; text-align: right;">
                                
                                {{-- Formulario para cambiar estado a CONFIRMADA --}}
                                @if($solicitud->estado == 'Pendiente')
                                    <form action="{{ route('dashboard.citas.estado', $solicitud->id_cita) }}" method="POST" style="display: inline-block;">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="estado" value="Confirmada">
                                        <button type="submit" style="background: none; border: none; color: #004085; font-weight: bold; text-decoration: underline; font-size: 0.75rem; cursor: pointer; text-transform: uppercase; margin-right: 10px;" onclick="return confirm('¿Aceptar cita y notificar al cliente por correo?');">Aceptar</button>
                                    </form>

                                    {{-- Formulario para cambiar estado a CANCELADA --}}
                                    <form action="{{ route('dashboard.citas.estado', $solicitud->id_cita) }}" method="POST" style="display: inline-block;">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="estado" value="Cancelada">
                                        <button type="submit" style="background: none; border: none; color: #721c24; font-weight: bold; text-decoration: underline; font-size: 0.75rem; cursor: pointer; text-transform: uppercase; margin-right: 10px;" onclick="return confirm('¿Rechazar cita y notificar al cliente por correo?');">Rechazar</button>
                                    </form>
                                @endif

                                {{-- Botón para responder directo por correo local (mailto) --}}
                                <a href="mailto:{{ $solicitud->cliente_correo }}?subject=Sobre tu solicitud de {{ $solicitud->asunto_servicio }}" style="color: #111; font-weight: bold; text-decoration: underline; font-size: 0.75rem; text-transform: uppercase; margin-right: 10px;">Email</a>

                                {{-- Formulario para ELIMINAR --}}
                                <form action="{{ route('dashboard.citas.destroy', $solicitud->id_cita) }}" method="POST" style="display: inline-block;">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="background: none; border: none; color: #d9534f; font-weight: bold; text-decoration: underline; font-size: 0.75rem; cursor: pointer; text-transform: uppercase;" onclick="return confirm('¿Eliminar esta solicitud de forma permanente?');">Eliminar</button>
                                </form>
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
@endsection