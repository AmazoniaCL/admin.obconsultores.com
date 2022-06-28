<?php

namespace App\Http\Controllers;

use App\Models\Contratos_personal;
use App\Models\Documentacion;
use App\Models\Documentos_personal;
use App\Models\Otro_si;
use App\Models\Personal;
use App\Models\Config_pagina;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use App\Models\Sistema\Departamento;
use App\Models\Sistema\Municipio;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function usuarios() {
        $usuarios = User::paginate(10);

        return view('administrador.usuarios', ['usuarios' => $usuarios]);
    }

    public function usuarios_create(Request $request) {
        if($request->id) {
            $dataSet = [
                'identificacion' => $request['identificacion'],
                'name' => $request['name'],
                'email' => $request['email'],
                'estado' => $request['estado']
            ];

            if($request['password']) $dataSet['password'] =  Hash::make($request['password']);

            $user = User::find($request->id);
            $user->update($dataSet);

            $user->syncRoles([]);
            $user->assignRole($request['rol']);

            $usuarios = User::paginate(10);
            return redirect()->back()->with([
                'creado' => 1,
                'tipo' => 'success',
                'mensaje' => 'Usuario actualizado correctamente',
                'usuarios' => $usuarios
            ]);
        }

        $usuario = User::create([
            'identificacion' => $request['identificacion'],
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'estado' => $request['estado']
        ]);

        if ( $usuario->save() ) {
            $usuario->assignRole($request['rol']);
            $usuario->givePermissionTo($request['permisos']);

            $usuarios = User::paginate(10);
            return redirect()->back()->with([
                'creado' => 1,
                'tipo' => 'success',
                'mensaje' => 'Usuario creado correctamente',
                'usuarios' => $usuarios
            ]);
        }
    }

    public function departamentos() {
        return Departamento::all();
    }

    public function municipios(Request $request) {
        return Departamento::where('nombre', $request['dpt'])->with('municipios')->first();
    }

    public function documentacion(){
        $documentacion = Documentacion::paginate(15);
        return view('administrador.documentacion', ['documentacion' => $documentacion]);
    }

    public function delete_documento(Request $request){
        $documento = Documentacion::find($request->id);
        Storage::disk('public')->delete($documento->file);
        $documento->delete();
    }

    public function agg_documentacion(Request $request){
        $nombre_completo_file = '';
        if($request->file('file') != null && $request->file('file') != ''){
            $date = Carbon::now('America/Bogota');
            $extension_file = pathinfo($request->file('file')->getClientOriginalName(), PATHINFO_EXTENSION);
            $ruta_file = 'docs/documentos_legales/';
            $nombre_file = 'documento_'.$date->isoFormat('YMMDDHmmss').'.'.$extension_file;
            Storage::disk('public')->put($ruta_file.$nombre_file, File::get($request->file('file')));

            $nombre_completo_file = $ruta_file.$nombre_file;
        }

        if($request->id != null && $request->id != ''){
            $documentacion = Documentacion::find($request->id);
            $documentacion->update([
                'nombre' => $request->nombre,
            ]);
            if($nombre_completo_file != '' || $nombre_completo_file != null){
                Storage::disk('public')->delete($documentacion->file);
                $documentacion->update([
                    'file' => $nombre_completo_file,
                ]);

            }
            return redirect()->back()->with(['edit' => 1]);
        }else{
            Documentacion::create([
                'nombre' => $request->nombre,
                'file' => $nombre_completo_file,
            ]);
            return redirect()->back()->with(['create' => 1]);
        }


    }

    public function personal(){
        $personal = Personal::paginate(10);
        return view('administrador.personal', ['personal' => $personal]);
    }

    public function crearPersonal(Request $request) {
        if($request->id != null && $request->id != '') {
            unset($request->id);
            $personal = Personal::find($request->id);
            unset($request->id);
            $personal->update($request->all());
            return redirect()->back()->with(['edit' => 1]);
        } else {
            unset($request->id);
            Personal::create($request->all());
            return redirect()->back()->with(['create' => 1]);
        }
    }

    public function ver_personal(Request $request){
        $personal = Personal::find($request->id);
        $usuario = User::with('roles')->where('identificacion', $personal->identificacion)->first();

        return view('administrador.ver_personal', ['persona' => $personal, 'usuario' => $usuario]);
    }

    public function delete_personal(Request $request){
        Personal::find($request->id)->delete();
    }

    public function caragar_contratos(Request $request){
        return Contratos_personal::where('personal_id', $request->id)->with('otro_si')->get();
    }

    public function crear_contrato(Request $request) {
        if($request->contrato_id != '' && $request->contrato_id != null){
            Contratos_personal::find($request->contrato_id)->update($request->all());
            return $request['personal_id'];
        }else{
            Contratos_personal::create($request->all())->save();
            return $request['personal_id'];
        }

    }

    public function agg_otro_si(Request $request) {
        Otro_si::create($request->all())->save();

        return Contratos_personal::find($request->contratos_personal_id)->personal_id;
    }

    public function print_otrosi(Request $request) {
        $otro_si = Otro_si::with(array('contratos_personal' => function ($query) {
            $query->with('personal');
        }))->find($request['id']);

        return PDF::loadView('administrador.otro_si', compact('otro_si'))->setPaper('A4')->stream('otro_si.pdf');
    }

    public function print_contrato(Request $request) {
        $contrato = Contratos_personal::with('personal')->find($request['id']);

        return PDF::loadView('administrador.contrato', compact('contrato'))->setPaper('A4')->stream('certificado.pdf');
    }

    public function print_certificado(Request $request) {
        $contrato = Contratos_personal::with('personal')->find($request['id']);
        return PDF::loadView('personal.certificado', compact('contrato'))->setPaper('A4')->stream('certificado.pdf');
    }

    public function eliminar_contrato(Request $request) {
        Contratos_personal::find($request['id'])->delete();
        Otro_si::where('contratos_personal_id', $request->id);
        return $request['personal_id'];
    }

    public function editar_contrato(Request $request) {
        return Contratos_personal::find($request['id']);
    }

    public function cargar_documentos(Request $request) {
        return Documentos_personal::where('tipo', $request['tipo'])->where('personal_id', $request['personal_id'])->get();
    }

    public function agg_documento(Request $request) {
        $date = Carbon::now('America/Bogota');
        if ($request['id'] != null && $request['id'] != '') {

            $documento = Documentos_personal::find($request['id']);

            $documento->update([
                'tipo' => $request['tipo'],
                'fecha_expedicion' => $request['fecha_expedicion'],
                'fecha_inicio_vigencia' => $request['fecha_inicio_vigencia'] ?? NULL,
                'fecha_fin_vigencia' => $request['fecha_fin_vigencia'] ?? NULL,
                'observaciones' => $request['observaciones'],
                'personal_id' => $request['personal_id'],
            ]);

            if ($request->file('adjunto')) {
                $extension_file_documento = pathinfo($request->file('adjunto')->getClientOriginalName(), PATHINFO_EXTENSION);
                $ruta_file_documento = 'docs/personal/documentos/';
                $nombre_file_documento = 'documento_'.$date->isoFormat('YMMDDHmmss').'.'.$extension_file_documento;
                Storage::disk('public')->put($ruta_file_documento.$nombre_file_documento, File::get($request->file('adjunto')));

                $nombre_completo_file_documento = $ruta_file_documento.$nombre_file_documento;

                Storage::disk('public')->delete($documento->adjunto);

                $documento->update([
                    'adjunto' => $nombre_completo_file_documento
                ]);
            }


            return ['tipo' => $request['tipo'], 'id_table' => $request['id_table'], 'personal_id' => $request['personal_id'], 'isfecha' => $request['isfecha']];

        } else {
            $documento = Documentos_personal::create([
                'tipo' => $request['tipo'],
                'fecha_expedicion' => $request['fecha_expedicion'],
                'fecha_inicio_vigencia' => $request['fecha_inicio_vigencia'] ?? NULL,
                'fecha_fin_vigencia' => $request['fecha_fin_vigencia'] ?? NULL,
                'observaciones' => $request['observaciones'],
                'adjunto' => 'nombre_temp',
                'personal_id' => $request['personal_id'],
            ]);

            if ($request->file('adjunto')) {
                $extension_file_documento = pathinfo($request->file('adjunto')->getClientOriginalName(), PATHINFO_EXTENSION);
                $ruta_file_documento = 'docs/personal/documentos/';
                $nombre_file_documento = 'documento_'.$date->isoFormat('YMMDDHmmss').'.'.$extension_file_documento;
                Storage::disk('public')->put($ruta_file_documento.$nombre_file_documento, File::get($request->file('adjunto')));

                $nombre_completo_file_documento = $ruta_file_documento.$nombre_file_documento;

                $documento['adjunto'] = $nombre_completo_file_documento;
            }

            if ( $documento->save() ) {
                return ['tipo' => $request['tipo'], 'id_table' => $request['id_table'], 'personal_id' => $request['personal_id'], 'isfecha' => $request['isfecha']];
            }

            return 0;
        }

    }

    public function editar_documento(Request $request) {
        return Documentos_personal::find($request['id']);
    }


    public function exportar_documentos(Request $request){
        $zip = new ZipArchive();


        if(!$zip->open(public_path('storage/docs/documentos_legales/documentacion.zip'), ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE){
            return 'error';
        }


        foreach ($request['documentos'] as $row) {
            $documentos =  Documentacion::all();
            $documento = $documentos->find($row)->file;
            $documento_nombre = $documentos->find($row)->nombre;
            $documento_extencion = pathinfo($documento, PATHINFO_EXTENSION);
            $zip->addFile('storage/'.$documento, $documento_nombre.'.'.$documento_extencion);
        }

        $zip->close();

        return true;
    }


    public function cargar_documentos_all(Request $request){
        return Documentacion::all();
    }


    public function eliminar_documento(Request $request) {
        $documento = Documentos_personal::find($request['id']);
        Storage::disk('public')->delete($documento->adjunto);
        $documento->delete();
        return ['tipo' => $request['tipo'], 'personal_id' => $request['personal_id'], 'isfecha' => $request['isfecha']];
    }

    public function pagina_web() {
        $config = Config_pagina::first();
        return view('administrador.pagina_web', ['config' => $config]);
    }

    public function pagina_web_update(Request $request) {
        $config = Config_pagina::first();

        if(!$config) {
            Config_pagina::create([
                'telefono' => $request['telefono'],
                'direccion' => $request['direccion'],
                'correo' => $request['correo'],
                'facebook' => $request['facebook'],
                'twitter' => $request['twitter'],
                'instagram' => $request['instagram']
            ]);

            return redirect()->route('pagina-web')->with(['update' => 1]);
        }

        $config->update([
            'telefono' => $request['telefono'],
            'direccion' => $request['direccion'],
            'correo' => $request['correo'],
            'facebook' => $request['facebook'],
            'twitter' => $request['twitter'],
            'instagram' => $request['instagram']
        ]);

        return redirect()->route('pagina-web')->with(['update' => 1]);
    }

    public function callAction($method, $parameters)
    {
        return parent::callAction($method, array_values($parameters));
    }

    public function sincronizacion(Request $request) {
        return view('administrador.sincronizacion');
    }

    public function getMenu(Request $request) {
        return Role::all();
    }
}
