<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Cita;

class CitaController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validamos los datos que llegan del formulario
        $request->validate([
            'nombre'      => 'required|string|max:150',
            'correo'      => 'required|email|max:150',
            'telefono'    => 'nullable|string|max:50',
            'id_servicio' => 'required|integer',
            'fecha_hora'  => 'required|date',
            'descripcion' => 'required|string'
        ]);

        // 2. LA MAGIA: Buscamos al cliente por correo. 
        // Si no existe, lo crea usando los datos del array secundario.
        $cliente = Cliente::firstOrCreate(
            ['correo' => $request->correo], // Condición de búsqueda
            [
                'nombre' => $request->nombre,
                'telefono' => $request->telefono
            ] // Datos a insertar si no lo encuentra
        );

        // 3. Creamos la cita vinculada a ese cliente
        Cita::create([
            'id_cliente'    => $cliente->id_cliente,
            'id_servicio'   => $request->id_servicio,
            'fecha_hora'    => $request->fecha_hora,
            'notas_cliente' => $request->descripcion,
            'estado'        => 'Pendiente'
        ]);

        // 4. Redirigimos de vuelta con un mensaje de éxito
        return back()->with('success', '¡Tu solicitud ha sido enviada! Nos pondremos en contacto contigo pronto para confirmar la cita.');
    }
}