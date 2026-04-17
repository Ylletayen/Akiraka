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

    // ==========================================================
    // ACTUALIZAR ESTADO DE LA CITA (ACEPTAR / RECHAZAR)
    // ==========================================================
    public function actualizarEstado(Request $request, $id)
    {
        $request->validate(['estado' => 'required|in:Confirmada,Cancelada']);

        // 1. Buscamos la cita de forma segura (sin que lance 404 si no existe)
        $cita = Cita::where('id_cita', $id)->first();
        if (!$cita) {
            return back()->with('error', 'Esta cita ya no existe en la base de datos.');
        }

        // 2. Buscamos al cliente (Puede ser null si era una cita de prueba rota)
        $cliente = Cliente::where('id_cliente', $cita->id_cliente)->first();
        $servicio = DB::table('servicios')->where('id_servicio', $cita->id_servicio)->value('nombre') ?? 'Servicio';

        // 3. Mandar correo SOLO si el cliente existe y tiene email
        if ($cliente && $cliente->correo) {
            try {
                $mensaje = $request->estado == 'Confirmada' 
                    ? "Hola {$cliente->nombre},\n\nNos alegra informarte que tu solicitud de cita para el servicio de '{$servicio}' ha sido CONFIRMADA. Nos pondremos en contacto contigo a la brevedad para afinar los detalles.\n\nSaludos,\nEl equipo de Akiraka Estudio."
                    : "Hola {$cliente->nombre},\n\nTe informamos que por motivos de agenda, tu solicitud de cita para '{$servicio}' ha sido CANCELADA. Te invitamos a solicitar una nueva fecha en nuestra página web.\n\nSaludos,\nEl equipo de Akiraka Estudio.";

                \Illuminate\Support\Facades\Mail::raw($mensaje, function($mail) use ($cliente) {
                    $mail->to($cliente->correo)
                         ->subject('Actualización de tu solicitud en Akiraka Estudio');
                });
            } catch (\Exception $e) {
                // Si falla el correo (ej. sin internet), no rompemos la página
            }
        }

        // 4. Si la rechazas, la borramos para limpiar la bandeja
        if ($request->estado == 'Cancelada') {
            $cita->delete();
            
            // Reseteo limpio de IDs
            DB::statement('ALTER TABLE citas AUTO_INCREMENT = 1;');
            
            return back()->with('success', 'La solicitud fue rechazada y eliminada del sistema.');
        }

        // 5. Si la aceptas, solo actualizamos su estado
        $cita->update(['estado' => $request->estado]);
        return back()->with('success', 'El estado de la solicitud ha sido actualizado a: Confirmada');
    }

    // ==========================================================
    // 3. FUNCIÓN CHATBOT: Recibe y guarda la cita desde el chat JS
    // ==========================================================
    public function storeDesdeChat(Request $request)
    {
        try {
            $request->validate([
                'nombre'      => 'required|string|max:150',
                'correo'      => 'required|email|max:150',
                'telefono'    => 'required|string|max:50', // Ahora es requerido y trae el código de país
                'id_servicio' => 'required|exists:servicios,id_servicio',
                'fecha_hora'  => 'required|string', 
                'notas'       => 'required|string'
            ]);

            $cliente = Cliente::firstOrCreate(
                ['correo' => $request->correo],
                ['nombre' => $request->nombre, 'telefono' => $request->telefono]
            );

            $idClienteFinal = $cliente->id_cliente ?? $cliente->id;

            $cita = Cita::create([
                'id_cliente'    => $idClienteFinal,
                'id_servicio'   => $request->id_servicio,
                'fecha_hora'    => now()->format('Y-m-d H:i:s'), 
                'estado'        => 'Pendiente',
                'notas_cliente' => "Desea cita para: " . $request->fecha_hora . " | Notas: " . $request->notas,
            ]);

            // ======================================================
            // MAGIA: GENERAR LINK DE GOOGLE CALENDAR Y ENVIAR CORREO
            // ======================================================
            try {
                // Convertimos la fecha que eligió al formato que entiende Google (YmdTHis)
                $fechaInicio = \Carbon\Carbon::parse($request->fecha_hora);
                $fechaFin = $fechaInicio->copy()->addHour(); // Asumimos que la cita dura 1 hora
                $formatoGoogle = 'Ymd\THis';

                // Generamos el link mágico
                $linkCalendario = "https://calendar.google.com/calendar/render?action=TEMPLATE";
                $linkCalendario .= "&text=" . urlencode("Cita Estudio Akiraka: " . $request->nombre);
                $linkCalendario .= "&dates=" . $fechaInicio->format($formatoGoogle) . "/" . $fechaFin->format($formatoGoogle);
                $linkCalendario .= "&details=" . urlencode("Servicio solicitado. Notas: " . $request->notas);
                $linkCalendario .= "&location=" . urlencode("Estudio Akiraka (o vía Zoom)");

                // Armamos el cuerpo del correo
                $cuerpoCorreo = "¡Hola {$request->nombre}!\n\n";
                $cuerpoCorreo .= "¡Guau! 🐾 Hemos recibido tu solicitud de proyecto en el Estudio Akiraka.\n\n";
                $cuerpoCorreo .= "Sugeriste vernos el: {$request->fecha_hora}\n\n";
                $cuerpoCorreo .= "Para que no se te olvide, da clic en el siguiente enlace para agregarlo a tu Google Calendar:\n";
                $cuerpoCorreo .= "👉 {$linkCalendario}\n\n";
                $cuerpoCorreo .= "Nuestro equipo revisará tu solicitud y te contactará al {$request->telefono} para confirmar todo.\n\n";
                $cuerpoCorreo .= "Atte:\nAki, Guardián del Estudio Akiraka.";

                // Mandamos el correo usando la configuración SMTP de tu archivo .env
                \Illuminate\Support\Facades\Mail::raw($cuerpoCorreo, function($mail) use ($request) {
                    $mail->to($request->correo)
                         ->subject('Hemos recibido tu solicitud 🐾 - Estudio Akiraka');
                });
            } catch (\Exception $emailError) {
                // Si falla el envío de correo (ej. falta configurar el .env), no rompemos el proceso de guardar la cita
                \Illuminate\Support\Facades\Log::error('Error enviando correo de chat: ' . $emailError->getMessage());
            }

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

/// ==========================================================
    // ELIMINAR CITA DEFINITIVAMENTE (BOTÓN DE BASURA)
    // ==========================================================
    public function destroy($id)
    {
        $cita = Cita::where('id_cita', $id)->first();
        
        if ($cita) {
            $cita->delete();
        }

        DB::statement('ALTER TABLE citas AUTO_INCREMENT = 1;');

        return back()->with('success', 'La solicitud ha sido eliminada.');
    }
}