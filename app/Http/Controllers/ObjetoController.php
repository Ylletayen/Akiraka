<?php

namespace App\Http\Controllers;

use App\Models\Objeto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ObjetoController extends Controller
{
    // 1. Dashboard de Objetos
    public function index()
    {
        $objetos = Objeto::orderBy('anio', 'desc')->get();
        return view('dashboard.objetos.objetos', compact('objetos'));
    }

    // 2. Guardar nuevo Objeto
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:150',
            'anio' => 'nullable|string|max:4',
            'portada' => 'nullable|image|max:15360' 
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

    // 3. Eliminar Objeto
    public function destroy($id)
    {
        Objeto::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Objeto eliminado del catálogo.');
    }

    // 4. Vista pública (El carrusel)
    public function show($id)
    {
        $objeto = Objeto::findOrFail($id);
        $imagenes = DB::table('imagenes_objeto')
                    ->where('id_objeto', $id)
                    ->orderBy('orden', 'asc')
                    ->get();
        return view('partials.main_objeto_view', compact('objeto', 'imagenes'));
    }

    // 5. Dashboard Ficha Técnica (Historia)
    public function historias($id)
    {
        $objeto = Objeto::findOrFail($id);
        $imagenes = DB::table('imagenes_objeto')->where('id_objeto', $id)->orderBy('orden', 'asc')->get();
        return view('dashboard.objetos.historia_objeto', compact('objeto', 'imagenes'));
    }

    // 6. Guardar foto en Ficha Técnica
    public function storeHistoria(Request $request, $id)
    {
        $request->validate([
            'imagen' => 'required|image|max:15360', 
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

    // 7. Eliminar foto de Ficha Técnica
    public function destroyHistoria($id_imagen)
    {
        $imagen = DB::table('imagenes_objeto')->where('id_imagen', $id_imagen)->first();
        if ($imagen) {
            Storage::disk('public')->delete($imagen->url_imagen);
            DB::table('imagenes_objeto')->where('id_imagen', $id_imagen)->delete();
        }
        return redirect()->back()->with('success', 'Imagen eliminada de la ficha técnica.');
    }
}