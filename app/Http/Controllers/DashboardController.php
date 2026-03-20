<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // <-- IMPORTADO para manejar la base de datos limpiamente

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

    // =======================================================
    // LISTAR SOLICITUDES DE CITAS
    // =======================================================
    public function solicitudesCitas()
    {
        // Unimos Citas + Clientes + Servicios en una sola consulta
        $solicitudes = DB::table('citas')
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

    // =================================================================
    // ELIMINAR SOLICITUD DE CITA (Con protección de IDs)
    // =================================================================
    public function destroyCita($id)
    {
        // Borramos la cita
        DB::table('citas')->where('id_cita', $id)->delete();

        // Nota: Dependiendo de tu lógica, podrías querer borrar también al cliente 
        // si no tiene más citas, pero por seguridad es mejor solo borrar la cita.

        // =========================================================
        // MAGIA: Resetea el contador para evitar saltos gigantes en BD
        // =========================================================
        DB::statement('ALTER TABLE citas AUTO_INCREMENT = 1;');

        return back()->with('success', 'Solicitud de cita eliminada correctamente.');
    }
}