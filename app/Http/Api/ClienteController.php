<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    // GET /api/clientes -> Obtiene todos los clientes
    public function index()
    {
        // Ordenados del más reciente al más antiguo
        $clientes = Cliente::orderBy('created_at', 'desc')->get();
        return response()->json($clientes, 200);
    }

    // POST /api/clientes -> Crea un nuevo cliente
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'   => 'required|string|max:150',
            'correo'   => 'required|email|max:150|unique:clientes,correo',
            'telefono' => 'nullable|string|max:50',
        ]);

        $cliente = Cliente::create($validated);

        return response()->json([
            'mensaje' => 'Cliente registrado exitosamente',
            'data'    => $cliente
        ], 201); // 201 Created
    }

    // GET /api/clientes/{id} -> Muestra un cliente en específico
    public function show($id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json(['mensaje' => 'Cliente no encontrado'], 404);
        }

        return response()->json($cliente, 200);
    }

    // PUT /api/clientes/{id} -> Actualiza la info de un cliente
    public function update(Request $request, $id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json(['mensaje' => 'Cliente no encontrado'], 404);
        }

        $validated = $request->validate([
            'nombre'   => 'sometimes|required|string|max:150',
            // La validación unique excluye el ID actual para que no marque error al actualizar
            'correo'   => 'sometimes|required|email|max:150|unique:clientes,correo,'.$id.',id_cliente',
            'telefono' => 'nullable|string|max:50',
        ]);

        $cliente->update($validated);

        return response()->json([
            'mensaje' => 'Datos del cliente actualizados',
            'data'    => $cliente
        ], 200);
    }

    // DELETE /api/clientes/{id} -> Elimina a un cliente
    public function destroy($id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json(['mensaje' => 'Cliente no encontrado'], 404);
        }

        $cliente->delete();

        return response()->json(['mensaje' => 'Cliente eliminado correctamente'], 200);
    }
}