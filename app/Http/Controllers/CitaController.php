<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Cita;
use App\Models\Configuracion;
use Illuminate\Support\Facades\DB;

class CitaController extends Controller
{
    // ==========================================================
    // 1. FUNCIÓN PÚBLICA: Recibe el formulario de la página web
    // ==========================================================
    public function store(Request $request)
    {
        // Validamos los datos
        $request->validate([
            'nombre'      => 'required|string|max:150',
            'correo'      => 'required|email|max:150',
            'telefono'    => 'nullable|string|max:50',
            'id_servicio' => 'required|integer',
            'fecha_hora'  => 'required|date',
            'descripcion' => 'required|string'
        ]);

        // Buscamos o creamos al cliente
        $cliente = Cliente::firstOrCreate(
            ['correo' => $request->correo],
            ['nombre' => $request->nombre, 'telefono' => $request->telefono]
        );

        // Creamos la cita en la BD
        Cita::create([
            'id_cliente'    => $cliente->id_cliente,
            'id_servicio'   => $request->id_servicio,
            'fecha_hora'    => $request->fecha_hora,
            'notas_cliente' => $request->descripcion,
            'estado'        => 'Pendiente'
        ]);

        // (Aquí iría la lógica del correo a Akira si decides activarla después)

        return back()->with('success', '¡Tu solicitud ha sido enviada! Nos pondremos en contacto contigo pronto para confirmar la cita.');
    }

    // ==========================================================
    // 2. FUNCIÓN PRIVADA (DASHBOARD): Muestra la tabla de prospectos
    // ==========================================================
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

    public function actualizarEstado(Request $request, $id)
    {
        $request->validate(['estado' => 'required|in:Confirmada,Cancelada']);

        $cita = Cita::findOrFail($id);
        $cliente = Cliente::findOrFail($cita->id_cliente);
        $servicio = \DB::table('servicios')->where('id_servicio', $cita->id_servicio)->value('nombre');

        
        try {
            $mensaje = $request->estado == 'Confirmada' 
                ? "Hola {$cliente->nombre},\n\nNos alegra informarte que tu solicitud de cita para el servicio de '{$servicio}' ha sido CONFIRMADA. Nos pondremos en contacto contigo a la brevedad para afinar los detalles.\n\nSaludos,\nEl equipo de Akiraka Estudio."
                : "Hola {$cliente->nombre},\n\nTe informamos que por motivos de agenda, tu solicitud de cita para '{$servicio}' ha sido CANCELADA. Te invitamos a solicitar una nueva fecha en nuestra página web.\n\nSaludos,\nEl equipo de Akiraka Estudio.";

            \Illuminate\Support\Facades\Mail::raw($mensaje, function($mail) use ($cliente) {
                $mail->to($cliente->correo)
                     ->subject('Actualización de tu solicitud en Akiraka Estudio');
            });
        } catch (\Exception $e) {
     
        }

        if ($request->estado == 'Cancelada') {
            $cita->delete();
            return back()->with('success', 'La solicitud fue rechazada, se notificó al cliente y se eliminó el registro para mantener limpia tu bandeja.');
        }

        // Si solo la acepta, actualizamos el estado
        $cita->update(['estado' => $request->estado]);
        return back()->with('success', 'El estado de la solicitud ha sido actualizado a: Confirmada');
    }
}