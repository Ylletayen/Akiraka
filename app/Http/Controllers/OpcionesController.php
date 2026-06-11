<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Configuracion;
use App\Models\Equipo;

class OpcionesController extends Controller
{
    public function index()
    {
        $configuracion = Configuracion::first() ?: new Configuracion();

        $equipo = Equipo::with('usuario')
            ->whereNull('deleted_at')
            ->orderBy('id_miembro', 'asc')
            ->get();

        return view('dashboard.opciones.opciones', compact('configuracion', 'equipo'));
    }

    public function updatePerfil(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nombre'         => 'required|string|max:100',
            'correo'         => 'required|email|unique:usuarios,correo,' . $user->id_usuario . ',id_usuario',
            'foto'           => 'nullable|image|max:2048',
            'password_nueva' => 'nullable|string|min:6'
        ]);

        if ($request->hasFile('foto')) {
            if ($user->foto) {
                Storage::disk('public')->delete($user->foto);
            }

            $user->foto = $request->file('foto')->store('perfiles', 'public');
        }

        if ($request->filled('password_nueva')) {
            $user->password = Hash::make($request->password_nueva);
        }

        $user->nombre = $request->nombre;
        $user->correo = $request->correo;
        $user->save();

        return back()->with('success', 'Perfil actualizado.');
    }

    public function updatePublicos(Request $request)
    {
        $request->validate([
            'telefono'              => 'nullable|string|max:50',
            'correo_contacto'       => 'nullable|email|max:150',
            'correo_prensa'         => 'nullable|email|max:150',
            'correo_laboral_1'      => 'nullable|email|max:150',
            'correo_laboral_2'      => 'nullable|email|max:150',
            'instagram'             => 'nullable|url|max:255',
            'facebook'              => 'nullable|url|max:255',
            'direccion'             => 'nullable|string',
            'quienes_somos_texto'   => 'nullable|string',
            'valores_texto'         => 'nullable|string',
            'landing_hero_image'    => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,webm|max:20480',

            'equipo_items'                  => 'nullable|array',
            'equipo_items.*.id_miembro'      => 'nullable',
            'equipo_items.*.nombre'          => 'nullable|string|max:150',
            'equipo_items.*.biografia'       => 'nullable|string',
            'equipo_items.*.puesto'          => 'nullable|string|max:150',
            'equipo_items.*.eliminar'        => 'nullable',
        ]);

        DB::transaction(function () use ($request) {
            $configuracion = Configuracion::first() ?: new Configuracion();

            if ($request->hasFile('landing_hero_image')) {
                if ($configuracion->landing_hero_image) {
                    Storage::disk('public')->delete($configuracion->landing_hero_image);
                }

                $configuracion->landing_hero_image = $request->file('landing_hero_image')->store('landing', 'public');
            }

            $configuracion->fill($request->only([
                'telefono',
                'correo_contacto',
                'correo_prensa',
                'correo_laboral_1',
                'correo_laboral_2',
                'direccion',
                'instagram',
                'facebook',
                'quienes_somos_texto',
                'valores_texto',
            ]));

            $configuracion->save();

            $items = $request->input('equipo_items', []);

            foreach ($items as $item) {
                $idMiembro = $item['id_miembro'] ?? null;
                $nombre = trim($item['nombre'] ?? '');
                $biografia = trim($item['biografia'] ?? '');
                $puesto = trim($item['puesto'] ?? '');
                $eliminar = isset($item['eliminar']) && $item['eliminar'] == '1';

                if ($idMiembro) {
                    $miembro = Equipo::with('usuario')->find($idMiembro);

                    if (!$miembro) {
                        continue;
                    }

                    if ($eliminar) {
                        $miembro->delete();
                        continue;
                    }

                    if ($miembro->usuario && $nombre !== '') {
                        $miembro->usuario->nombre = $nombre;
                        $miembro->usuario->save();
                    }

                    $miembro->biografia = $biografia;

                    if (Schema::hasColumn('equipo', 'puesto')) {
                        $miembro->puesto = $puesto;
                    }

                    $miembro->save();

                    continue;
                }

                if ($eliminar) {
                    continue;
                }

                if ($nombre === '' && $biografia === '' && $puesto === '') {
                    continue;
                }

                $correoFake = 'equipo_' . uniqid() . '@akiraka.local';

                $usuarioData = [
                    'nombre'   => $nombre !== '' ? $nombre : 'Nuevo integrante',
                    'correo'   => $correoFake,
                    'password' => Hash::make('EquipoPublico123'),
                    'id_rol'   => 3,
                ];

                if (Schema::hasColumn('usuarios', 'foto')) {
                    $usuarioData['foto'] = null;
                }

                if (Schema::hasColumn('usuarios', 'created_at')) {
                    $usuarioData['created_at'] = now();
                }

                if (Schema::hasColumn('usuarios', 'updated_at')) {
                    $usuarioData['updated_at'] = now();
                }

                if (Schema::hasColumn('usuarios', 'deleted_at')) {
                    $usuarioData['deleted_at'] = null;
                }

                $idUsuario = DB::table('usuarios')->insertGetId($usuarioData);

                $equipoData = [
                    'id_usuario' => $idUsuario,
                    'biografia'  => $biografia,
                ];

                if (Schema::hasColumn('equipo', 'puesto')) {
                    $equipoData['puesto'] = $puesto;
                }

                if (Schema::hasColumn('equipo', 'created_at')) {
                    $equipoData['created_at'] = now();
                }

                if (Schema::hasColumn('equipo', 'updated_at')) {
                    $equipoData['updated_at'] = now();
                }

                if (Schema::hasColumn('equipo', 'deleted_at')) {
                    $equipoData['deleted_at'] = null;
                }

                DB::table('equipo')->insert($equipoData);
            }
        });

        return back()->with('success', 'Información pública del sitio actualizada correctamente.');
    }
}