<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servicio;
use Illuminate\Support\Facades\DB;

class ServicioController extends Controller
{
    public function index()
    {
        $servicios = Servicio::all();
        return view('dashboard.servicios.servicios', compact('servicios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'activo' => 'boolean'
        ]);

        Servicio::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'activo' => $request->has('activo') ? 1 : 0
        ]);

        return back()->with('success', 'Servicio creado correctamente en el catálogo.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'activo' => 'boolean'
        ]);

        $servicio = Servicio::findOrFail($id);
        $servicio->nombre = $request->nombre;
        $servicio->descripcion = $request->descripcion;
        $servicio->activo = $request->has('activo') ? 1 : 0;
        $servicio->save();

        return back()->with('success', 'Servicio actualizado correctamente.');
    }

    public function destroy($id)
    {
        $servicio = Servicio::findOrFail($id);
        $servicio->delete();
        DB::statement('ALTER TABLE servicios AUTO_INCREMENT = 1;');

        return back()->with('success', 'Servicio eliminado.');
    }
}