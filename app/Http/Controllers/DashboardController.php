<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Bloquea el acceso si no han pasado por el login
    }

    public function index()
    {
        return view('dashboard.main'); // Tu vista principal de administrador
    }

    // En tu controlador del Dashboard (ej. DashboardController@citas)
    public function solicitudesCitas()
    {
        // MAGIA: Unimos Citas + Clientes + Servicios en una sola consulta
        $solicitudes = \DB::table('citas')
            ->join('clientes', 'citas.id_cliente', '=', 'clientes.id_cliente')
            ->join('servicios', 'citas.id_servicio', '=', 'servicios.id_servicio')
            ->select(
                'citas.id_cita',
                'citas.fecha_hora',
                'citas.estado',
                'citas.notas_cliente as descripcion_proyecto',
                'clientes.nombre as cliente_nombre',
                'clientes.correo as cliente_correo',
                'clientes.telefono as cliente_telefono',
                'servicios.nombre as asunto_servicio'
            )
            ->orderBy('citas.created_at', 'desc')
            ->get();

        return view('dashboard.citas.index', compact('solicitudes'));
    }
}