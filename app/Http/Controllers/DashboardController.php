<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // =======================================================
        // 1. ESTADÍSTICAS BÁSICAS DE PROYECTOS Y OBJETOS
        // =======================================================
        $totalProyectos = \App\Models\Proyecto::count();
        $totalObjetos = \App\Models\Objeto::count();
        
        $proyectosEnProceso = \App\Models\Proyecto::where('id_estado', 1)->take(2)->get();
        $proyectosFuturos = \App\Models\Proyecto::whereIn('id_estado', [2, 3])->take(2)->get();

        // =======================================================
        // 2. CITAS RECIENTES (Las más nuevas aparecen primero)
        // =======================================================
        $citasRecientes = DB::table('citas')
            ->join('clientes', 'citas.id_cliente', '=', 'clientes.id_cliente')
            ->join('servicios', 'citas.id_servicio', '=', 'servicios.id_servicio')
            ->select(
                'citas.id_cita',
                'citas.created_at',
                'clientes.nombre as cliente_nombre',
                'servicios.nombre as servicio_nombre'
            )
            ->where('citas.estado', 'Pendiente')
            ->orderBy('citas.created_at', 'desc') // ✅ Corregido para que muestre las más nuevas arriba
            ->take(4)
            ->get();

        // =======================================================
        // 3. GRÁFICA SEMANAL
        // =======================================================
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

        // =======================================================
        // 4. GRÁFICA MENSUAL (Tipos de visitante)
        // =======================================================
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

        // Retornamos de forma limpia las variables que tu archivo Blade real necesita
        return view('dashboard.dash.main', compact(
            'totalProyectos', 'totalObjetos', 'proyectosEnProceso', 'proyectosFuturos',
            'citasRecientes',
            'visitasSemanales', 'labelsSemanales', 'totalVisitasMes', 'visitantesNuevos',
            'visitantesRecurrentes'
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
                'citas.notes_cliente as descripcion_proyecto',
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