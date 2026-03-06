<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    public function index()
    {
        // Traemos todos los proyectos
        $proyectos = Proyecto::all();
        
        // Traemos los estados disponibles para el select del modal (ej. En Progreso, Terminado)
        $estados = DB::table('estados_proyecto')->get();

        return view('dashboard.proyectos.proyecto', compact('proyectos', 'estados'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'costo_inicial' => 'nullable|numeric',
            'costo_final' => 'nullable|numeric',
            'id_estado' => 'nullable|exists:estados_proyecto,id_estado'
        ]);

        Proyecto::create($request->all());
        
        return redirect()->back()->with('success', 'Proyecto registrado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'titulo' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'costo_inicial' => 'nullable|numeric',
            'costo_final' => 'nullable|numeric',
            'id_estado' => 'nullable|exists:estados_proyecto,id_estado'
        ]);

        $proyecto = Proyecto::findOrFail($id);
        $proyecto->update($request->all());

        return redirect()->back()->with('success', 'Proyecto actualizado exitosamente.');
    }

    public function destroy($id)
    {
        Proyecto::findOrFail($id)->delete();
        
        return redirect()->back()->with('success', 'Proyecto eliminado del portafolio.');
    }
}