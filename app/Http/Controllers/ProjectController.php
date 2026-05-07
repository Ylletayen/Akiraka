<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index()
    {
        $proyectos = Proyecto::orderBy('orden', 'asc')->get();
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
            'id_estado' => 'nullable|exists:estados_proyecto,id_estado',
            'anio' => 'nullable|string|max:4',
            'orden' => 'nullable|integer',
            'portada' => 'nullable|mimes:jpeg,png,jpg,gif,mp4,webm|max:20480' 
        ]);

        $data = $request->except('portada');
        $proyecto = Proyecto::create($data);

        if ($request->hasFile('portada')) {
            $rutaImagen = $request->file('portada')->store('historias', 'public');
            
            DB::table('imagenes_proyecto')->insert([
                'id_proyecto' => $proyecto->id_proyecto,
                'url_imagen' => $rutaImagen,
                'descripcion' => 'Portada principal',
                'anio' => $request->anio,
                'orden' => $request->orden ?? 1
            ]);
        }
        
        return redirect()->back()->with('success', 'Obra registrada correctamente.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'titulo' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'costo_inicial' => 'nullable|numeric',
            'costo_final' => 'nullable|numeric',
            'id_estado' => 'nullable|exists:estados_proyecto,id_estado',
            'anio' => 'nullable|string|max:4',
            'orden' => 'nullable|integer'
        ]);

        $proyecto = Proyecto::findOrFail($id);
        $proyecto->update($request->all());

        return redirect()->back()->with('success', 'Obra actualizada exitosamente.');
    }

    public function destroy($id)
    {
        Proyecto::findOrFail($id)->delete();
        DB::statement('ALTER TABLE proyectos AUTO_INCREMENT = 1;');

        return redirect()->back()->with('success', 'Obra eliminada del portafolio.');
    }

    public function show($id)
    {
        $proyecto = Proyecto::findOrFail($id);
        $imagenes = DB::table('imagenes_proyecto')
                    ->where('id_proyecto', $id)
                    ->orderBy('orden', 'asc')
                    ->get();

        return view('partials.main_view', compact('proyecto', 'imagenes'));
    }

    public function historias($id)
    {
        $proyecto = Proyecto::findOrFail($id);
        $imagenes = DB::table('imagenes_proyecto')
                        ->where('id_proyecto', $id)
                        ->orderBy('orden', 'asc')
                        ->get();

        return view('dashboard.proyectos.historia', compact('proyecto', 'imagenes'));
    }

    public function storeHistoria(Request $request, $id)
    {
        $request->validate([
            'imagen' => 'required|mimes:jpeg,png,jpg,gif,mp4,webm|max:20480', 
            'descripcion' => 'required|string',
            'anio' => 'nullable|string|max:4',
            'orden' => 'nullable|integer'
        ]);

        $rutaImagen = $request->file('imagen')->store('historias', 'public');

        DB::table('imagenes_proyecto')->insert([
            'id_proyecto' => $id,
            'url_imagen' => $rutaImagen,
            'descripcion' => $request->descripcion,
            'anio' => $request->anio,
            'orden' => $request->orden ?? 0
        ]);

        return redirect()->back()->with('success', 'Nueva fase agregada a la historia de la obra.');
    }

    public function updateHistoria(Request $request, $id_imagen)
    {
        $request->validate([
            'imagen' => 'required|mimes:jpeg,png,jpg,gif,mp4,webm|max:20480', 
            'descripcion' => 'required|string',
            'anio' => 'nullable|string|max:4',
            'orden' => 'nullable|integer'
        ]);

        $imagenActual = DB::table('imagenes_proyecto')->where('id_imagen', $id_imagen)->first();

        if (!$imagenActual) {
            return redirect()->back()->withErrors(['error' => 'No se encontró la fase especificada.']);
        }

        $datosActualizar = [
            'descripcion' => $request->descripcion,
            'anio' => $request->anio,
            'orden' => $request->orden ?? 0
        ];

        if ($request->hasFile('imagen')) {
            if ($imagenActual->url_imagen) {
                Storage::disk('public')->delete($imagenActual->url_imagen);
            }
            
            $rutaImagen = $request->file('imagen')->store('historias', 'public');
            $datosActualizar['url_imagen'] = $rutaImagen;
        }

        DB::table('imagenes_proyecto')
            ->where('id_imagen', $id_imagen)
            ->update($datosActualizar);

        return redirect()->back()->with('success', 'La fase de la historia ha sido actualizada correctamente.');
    }

    public function destroyHistoria($id_imagen)
    {
        $imagen = DB::table('imagenes_proyecto')->where('id_imagen', $id_imagen)->first();
        
        if ($imagen) {
            Storage::disk('public')->delete($imagen->url_imagen);
            DB::table('imagenes_proyecto')->where('id_imagen', $id_imagen)->delete();
            DB::statement('ALTER TABLE imagenes_proyecto AUTO_INCREMENT = 1;');
        }

        return redirect()->back()->with('success', 'Fase eliminada correctamente.');
    }
}