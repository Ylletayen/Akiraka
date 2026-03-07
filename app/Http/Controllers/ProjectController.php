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

    public function show($id)
    {
        // 1. Buscamos el proyecto por su ID. Si no existe, Laravel tira un 404 automático.
        $proyecto = Proyecto::findOrFail($id);
        
        // 2. Traemos todas las imágenes e historias asociadas a este proyecto
        $imagenes = \Illuminate\Support\Facades\DB::table('imagenes_proyecto')
                    ->where('id_proyecto', $id)
                    ->get();

        // 3. Retornamos la vista principal (el carrusel) mandándole el proyecto y sus fotos
        return view('partials.main_view', compact('proyecto', 'imagenes'));
    }

    // Mostrar el panel de historia de un proyecto específico
    public function historias($id)
    {
        $proyecto = Proyecto::findOrFail($id);
        $imagenes = \Illuminate\Support\Facades\DB::table('imagenes_proyecto')
                        ->where('id_proyecto', $id)
                        ->get();

        return view('dashboard.proyectos.historia', compact('proyecto', 'imagenes'));
    }

    // Guardar una nueva imagen y descripción
    public function storeHistoria(Request $request, $id)
    {
        $request->validate([
            'imagen' => 'required|image|max:5120', // Máximo 5MB
            'descripcion' => 'required|string'
        ]);

        // Guardamos la imagen en la carpeta storage/app/public/historias
        $rutaImagen = $request->file('imagen')->store('historias', 'public');

        \Illuminate\Support\Facades\DB::table('imagenes_proyecto')->insert([
            'id_proyecto' => $id,
            'url_imagen' => $rutaImagen,
            'descripcion' => $request->descripcion
        ]);

        return redirect()->back()->with('success', 'Nueva fase agregada a la historia del proyecto.');
    }

    // Eliminar una fase de la historia
    public function destroyHistoria($id_imagen)
    {
        $imagen = \Illuminate\Support\Facades\DB::table('imagenes_proyecto')->where('id_imagen', $id_imagen)->first();
        
        if ($imagen) {
            // Borramos el archivo físico del servidor
            \Illuminate\Support\Facades\Storage::disk('public')->delete($imagen->url_imagen);
            // Borramos el registro de la BD
            \Illuminate\Support\Facades\DB::table('imagenes_proyecto')->where('id_imagen', $id_imagen)->delete();
        }

        return redirect()->back()->with('success', 'Fase eliminada correctamente.');
    }

    public function destroy($id)
    {
        Proyecto::findOrFail($id)->delete();
        
        return redirect()->back()->with('success', 'Proyecto eliminado del portafolio.');
    }
}