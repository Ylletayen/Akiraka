<?php

namespace App\Http\Controllers;

use App\Models\Objeto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ObjetoController extends Controller
{
    public function index()
    {
        $objetos = Objeto::orderBy('anio', 'desc')->get();
        return view('dashboard.objetos.objetos', compact('objetos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:150',
            'anio' => 'nullable|string|max:4',
            'portada' => 'nullable|mimes:jpeg,png,jpg,gif,mp4,webm|max:20480' 
        ]);

        $objeto = Objeto::create([
            'titulo' => $request->titulo,
            'anio' => $request->anio
        ]);
        
        if ($request->hasFile('portada')) {
            $rutaImagen = $request->file('portada')->store('historias_objetos', 'public');
            DB::table('imagenes_objeto')->insert([
                'id_objeto' => $objeto->id_objeto,
                'url_imagen' => $rutaImagen,
                'descripcion' => 'Portada principal',
                'anio' => $request->anio,
                'orden' => 1
            ]);
        }
        return redirect()->back()->with('success', 'Objeto registrado correctamente.');
    }

    public function destroy($id)
    {
        Objeto::findOrFail($id)->delete();

        DB::statement('ALTER TABLE objetos AUTO_INCREMENT = 1;');
        
        return redirect()->back()->with('success', 'Objeto eliminado del catálogo.');
    }

    public function show($id)
    {
        $objeto = Objeto::findOrFail($id);
        $imagenes = DB::table('imagenes_objeto')
                    ->where('id_objeto', $id)
                    ->orderBy('orden', 'asc')
                    ->get();
        return view('partials.main_objeto_view', compact('objeto', 'imagenes'));
    }

    public function historias($id)
    {
        $objeto = Objeto::findOrFail($id);
        $imagenes = DB::table('imagenes_objeto')->where('id_objeto', $id)->orderBy('orden', 'asc')->get();
        return view('dashboard.objetos.historia_objeto', compact('objeto', 'imagenes'));
    }

    public function storeHistoria(Request $request, $id)
    {
        $request->validate([
            'imagen' => 'nullable|mimes:jpeg,png,jpg,gif,mp4,webm|max:20480', 
            'descripcion' => 'required|string',
            'anio' => 'nullable|string|max:4',
            'orden' => 'nullable|integer'
        ]);

        $rutaImagen = $request->file('imagen')->store('historias_objetos', 'public');

        DB::table('imagenes_objeto')->insert([
            'id_objeto' => $id,
            'url_imagen' => $rutaImagen,
            'descripcion' => $request->descripcion,
            'anio' => $request->anio,
            'orden' => $request->orden ?? 0
        ]);
        return redirect()->back()->with('success', 'Nueva imagen agregada a la ficha técnica.');
    }

    public function updateHistoria(Request $request, $id_imagen)
    {
        $request->validate([
            'imagen' => 'nullable|mimes:jpeg,png,jpg,gif,mp4,webm|max:20480', 
            'descripcion' => 'required|string',
            'anio' => 'nullable|string|max:4',
            'orden' => 'nullable|integer'
        ]);

        $imagenActual = DB::table('imagenes_objeto')->where('id_imagen', $id_imagen)->first();

        if (!$imagenActual) {
            return redirect()->back()->withErrors(['error' => 'No se encontró la imagen especificada.']);
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
            
            $rutaImagen = $request->file('imagen')->store('historias_objetos', 'public');
            $datosActualizar['url_imagen'] = $rutaImagen;
        }

        DB::table('imagenes_objeto')
            ->where('id_imagen', $id_imagen)
            ->update($datosActualizar);

        return redirect()->back()->with('success', 'Los detalles de la imagen han sido actualizados.');
    }

    public function destroyHistoria($id_imagen)
    {
        $imagen = DB::table('imagenes_objeto')->where('id_imagen', $id_imagen)->first();
        
        if ($imagen) {
            Storage::disk('public')->delete($imagen->url_imagen);
            DB::table('imagenes_objeto')->where('id_imagen', $id_imagen)->delete();
            
            DB::statement('ALTER TABLE imagenes_objeto AUTO_INCREMENT = 1;');
        }
        
        return redirect()->back()->with('success', 'Imagen eliminada de la ficha técnica.');
    }
}