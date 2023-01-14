<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\Formato;
use Carbon\Carbon;

class FormatosController extends Controller
{
    public function formatos()
    {
        $formatos = Formato::paginate(15);
        return view('administrador.formatos', ['documentacion' => $formatos]);
    }

    public function delete_formatos(Request $request)
    {
        $documento = Formato::find($request->id);
        Storage::disk('public')->delete($documento->adjunto);
        $documento->delete();
    }

    public function getFormatos()
    {
        $formatos = Formato::get();
        return response()->json($formatos);
    }

    public function agg_formatos(Request $request)
    {
        $nombre_completo_file = '';
        if ($request->file('file') != null && $request->file('file') != '') {
            $date = Carbon::now('America/Bogota');
            $extension_file = pathinfo($request->file('file')->getClientOriginalName(), PATHINFO_EXTENSION);
            $ruta_file = 'docs/formatos/';
            $nombre_file = 'formato_' . $date->isoFormat('YMMDDHmmss') . '.' . $extension_file;
            Storage::disk('public')->put($ruta_file . $nombre_file, File::get($request->file('file')));

            $nombre_completo_file = $ruta_file . $nombre_file;
        }

        if ($request->id != null && $request->id != '') {
            $documentacion = Formato::find($request->id);
            $documentacion->update([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
            ]);
            if ($nombre_completo_file != '' || $nombre_completo_file != null) {
                Storage::disk('public')->delete($documentacion->adjunto);
                $documentacion->update([
                    'adjunto' => $nombre_completo_file,
                ]);
            }
            return redirect()->back()->with(['edit' => 1]);
        } else {
            Formato::create([
                'nombre' => $request->nombre,
                'adjunto' => $nombre_completo_file,
                'descripcion' => $request->descripcion,
            ]);
            return redirect()->back()->with(['create' => 1]);
        }
    }
}
