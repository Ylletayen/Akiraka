<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // <-- IMPORTADO para manejar la base de datos limpiamente

class DashboardController extends Controller
{
    public function index()
    {
        $totalProyectos = \App\Models\Proyecto::count();
        $inversionTotal = \App\Models\Proyecto::sum('costo_inicial') ?? 0;

        $totalObjetos = \App\Models\Objeto::count();
        
        $proyectosEnProceso = \App\Models\Proyecto::where('id_estado', 1)->take(2)->get();
        $proyectosFuturos = \App\Models\Proyecto::whereIn('id_estado', [2, 3])->take(2)->get();

        $citasRecientes = DB::table('citas')
            ->join('clientes', 'citas.id_cliente', '=', 'clientes.id_cliente')
            ->join('servicios', 'citas.id_servicio', '=', 'servicios.id_servicio')
            ->select(
                'citas.id_cita',
                'citas.created_at',
                'clientes.nombre as cliente_nombre',
                'servicios.nombre as servicio_nombre'
            )
            ->where('citas.estado', 'Pendiente') // Solo mostramos las que no ha atendido
            ->orderBy('citas.created_at', 'asc')
            ->take(4)
            ->get();

        $visitasSemanales = [];
        $labelsSemanales = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $inicioDia = \Carbon\Carbon::now()->subDays($i)->startOfDay()->getTimestamp();
            $finDia = \Carbon\Carbon::now()->subDays($i)->endOfDay()->getTimestamp();
            
            $conteo = DB::table('sessions')
                ->whereNull('user_id') 
                ->whereBetween('last_activity', [$inicioDia, $finDia])
                ->count();
                
            $visitasSemanales[] = $conteo;
            $labelsSemanales[] = \Carbon\Carbon::now()->subDays($i)->isoFormat('ddd D'); 
        }

        $inicioMes = \Carbon\Carbon::now()->startOfMonth()->getTimestamp();
        $sesionesMes = DB::table('sessions')
            ->whereNull('user_id')
            ->where('last_activity', '>=', $inicioMes)
            ->get();

        $totalVisitasMes = $sesionesMes->count();
        $visitantesNuevos = 0;
        $visitantesRecurrentes = 0;

        foreach ($sesionesMes as $sesion) {
            if (isset($sesion->created_at) && \Carbon\Carbon::parse($sesion->created_at)->getTimestamp() < $inicioMes) {
                $visitantesRecurrentes++;
            } else {
                $visitantesNuevos++;
            }
        }

        $inicioAnio = \Carbon\Carbon::now()->startOfYear()->getTimestamp();
        $agent = new \Jenssegers\Agent\Agent();

        $sesionesAnio = DB::table('sessions')
            ->whereNull('user_id')
            ->where('last_activity', '>=', $inicioAnio)
            ->get();

        $totalVisitasAnio = $sesionesAnio->count();
        $vistasMovil = 0;
        $vistasEscritorio = 0;

        foreach ($sesionesAnio as $sesion) {
            $agent->setUserAgent($sesion->user_agent);
            if ($agent->isMobile() || $agent->isTablet()) {
                $vistasMovil++;
            } else {
                $vistasEscritorio++;
            }
        }

        return view('dashboard.dash.main', compact(
            'totalProyectos', 'inversionTotal', 'totalObjetos', 'proyectosEnProceso', 'proyectosFuturos',
            'citasRecientes',
            'visitasSemanales', 'labelsSemanales', 'totalVisitasMes', 'visitantesNuevos',
            'visitantesRecurrentes', 'totalVisitasAnio', 'vistasMovil', 'vistasEscritorio'
        ));
    }

    public function solicitudesCitas()
    {
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

    public function destroyCita($id)
    {
        DB::table('citas')->where('id_cita', $id)->delete();

        DB::statement('ALTER TABLE citas AUTO_INCREMENT = 1;');

        return back()->with('success', 'Solicitud de cita eliminada correctamente.');
    }
}