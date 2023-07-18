<?php

namespace App\Http\Controllers;

use App\Jobs\SincronizarActuaciones;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Mail\ProcesoCreado;

use App\Models\Sistema\Acceso_proceso;
use App\Models\Abogado;
use App\Models\Detalle_proceso;
use App\Models\Actuacion;
use App\Models\Anotacion_files;
use App\Models\Demandado;
use App\Models\Audiencia;
use App\Models\Cliente;
use App\Models\Proceso;
use App\Models\Procesos_archivo;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as AuthUser;
use PDF;
use Spatie\Permission\Traits\HasRoles;

class ProcesosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function crear() {
        return view('procesos.crear');
    }

    public function civil() {
        $procesos = Proceso::with('clientes')->where('tipo', 'Civil')->paginate(10);

        $proceso_name = 'Civil';

        return view('procesos.index', ['proceso_name' => $proceso_name, 'procesos' => $procesos]);
    }

    public function familia() {
        $procesos = Proceso::with('clientes')->where('tipo', 'Familia')->paginate(10);

        $proceso_name = 'Familia';

        return view('procesos.index', ['proceso_name' => $proceso_name, 'procesos' => $procesos]);
    }

    public function laboral() {
        $procesos = Proceso::with('clientes')->where('tipo', 'Laboral')->paginate(10);

        $proceso_name = 'Laboral';

        return view('procesos.index', ['proceso_name' => $proceso_name, 'procesos' => $procesos]);
    }

    public function seguridad_social() {
        $procesos = Proceso::with('clientes')->where('tipo', 'Seguridad Social')->paginate(10);

        $proceso_name = 'Seguridad Social';

        return view('procesos.index', ['proceso_name' => $proceso_name, 'procesos' => $procesos]);
    }

    public function administrativo() {
        $procesos = Proceso::with('clientes')->where('tipo', 'Administrativo')->paginate(10);

        $proceso_name = 'Administrativo';

        return view('procesos.index', ['proceso_name' => $proceso_name, 'procesos' => $procesos]);
    }

    public function penal() {
        $procesos = Proceso::with('clientes')->where('tipo', 'Penal')->paginate(10);

        $proceso_name = 'Penal';

        return view('procesos.index', ['proceso_name' => $proceso_name, 'procesos' => $procesos]);
    }

    public function otros() {
        $procesos = Proceso::with('clientes')->where('tipo', 'Otros')->paginate(10);

        $proceso_name = 'Otros';

        return view('procesos.index', ['proceso_name' => $proceso_name, 'procesos' => $procesos]);
    }

    public function search(Request $request) {
        $cliente = Cliente::where('identificacion', $request['id'])->limit(1)->get();

        return $cliente;
    }

    public function create(Request $request) {

        $date = Carbon::now('America/Bogota');

        $cliente = Cliente::where('identificacion', $request['identificacion'])->limit(1)->get();

        // Si el cliente no Existe, lo creamos
        if ($cliente->isEmpty()) {
            $cliente = Cliente::create([
                'identificacion' => $request['identificacion'],
                'nombre' => $request['nombre'],
                'direccion' => $request['direccion'],
                'telefono' => $request['telefono'],
                'celular' => $request['celular'],
                'correo' => $request['correo'],
                'correo_dos' => $request['correo_dos'],
                'eps' => $request['eps'],
                'arl' => $request['arl'],
                'afp' => $request['afp'],
            ]);
        }

        $last_proceso = Proceso::where('tipo', $request['tipo'])->get()->count();

        if ($last_proceso < 9) {
            $num_proceso = '00'.($last_proceso + 1);
        } else if($last_proceso >= 9 && $last_proceso < 99) {
            $num_proceso = '0'.($last_proceso + 1);
        } else {
            $num_proceso = $last_proceso + 1;
        }

        $extension_file = ($request['proceso_file']) ? pathinfo($request->file('proceso_file')->getClientOriginalName(), PATHINFO_EXTENSION) : '';

        switch ($request['tipo']) {
            case 'Civil':
                $num_proceso = 'CI'.$num_proceso;
                $ruta_file = 'docs/procesos/civil/';
                $nombre_file = 'CI'.$date->format('YmdHis').'.'.$extension_file;

                break;

            case 'Familia':
                $num_proceso = 'FA'.$num_proceso;
                $ruta_file = 'docs/procesos/familia/';
                $nombre_file = 'FA'.$date->format('YmdHis').'.'.$extension_file;

                break;

            case 'Laboral':
                $num_proceso = 'LA'.$num_proceso;
                $ruta_file = 'docs/procesos/laboral/';
                $nombre_file = 'LA'.$date->format('YmdHis').'.'.$extension_file;

                break;

            case 'Seguridad Social':
                $num_proceso = 'SE'.$num_proceso;
                $ruta_file = 'docs/procesos/seguridad_social/';
                $nombre_file = 'SE'.$date->format('YmdHis').'.'.$extension_file;

                break;

            case 'Administrativo':
                $num_proceso = 'AD'.$num_proceso;
                $ruta_file = 'docs/procesos/administrativo/';
                $nombre_file = 'AD'.$date->format('YmdHis').'.'.$extension_file;

                break;

            case 'Penal':
                $num_proceso = 'PE'.$num_proceso;
                $ruta_file = 'docs/procesos/penal/';
                $nombre_file = 'PE'.$date->format('YmdHis').'.'.$extension_file;

                break;

            case 'Derecho de Petición':
                $num_proceso = 'DP'.$num_proceso;
                $ruta_file = 'docs/procesos/derecho_peticion/';
                $nombre_file = 'DP'.$date->format('YmdHis').'.'.$extension_file;

                break;

            case 'Insolvencia':
                $num_proceso = 'IN'.$num_proceso;
                $ruta_file = 'docs/procesos/insolvencia/';
                $nombre_file = 'IN'.$date->format('YmdHis').'.'.$extension_file;

                break;

            case 'Otros':
                $num_proceso = 'OT'.$num_proceso;
                $ruta_file = 'docs/procesos/otros/';
                $nombre_file = 'OT'.$date->format('YmdHis').'.'.$extension_file;

                break;
        }

        if ($request->file('proceso_file')) {
            Storage::disk('public')->put($ruta_file.$nombre_file, File::get($request->file('proceso_file')));

            $nombre_completo = $ruta_file.$nombre_file;
        }

        $codigo = Str::random(4);

        $proceso = Proceso::create([
            'codigo' => $codigo,
            'num_proceso' => $num_proceso,
            'tipo' => $request['tipo'],
            'sub_tipo' => $request['sub_tipo'],
            'tipo_insolvencia' => $request['tipo_insolvencia'],
            'departamento' => $request['departamento'],
            'ciudad' => $request['ciudad'],
            'descripcion' => $request['descripcion'],
            'proceso_file' => $nombre_completo ?? '',
            'clientes_id' => $cliente[0]->id ?? $cliente->id,
            'users_id' => auth()->user()->id,
        ]);

        if ( $proceso->save() ) {
            if ($request['notificacion']) {
                // Enviar correo notificando al cliente
                Mail::to($request['correo'])->send(new ProcesoCreado($proceso->id, $codigo, $request['nombre']));
            }
        }

        if ( $request['nombre_demandado_1'] ) {
            for ($i=1; $i <= $request['cantidad_demandados']; $i++) {
                if ( $request['existe_abogado_'.$i] == 'No' ) {
                    $abogado = Abogado::create([
                        'nombre' => $request['nombre_abogado_'.$i],
                        'identificacion' => $request['identificacion_abogado_'.$i],
                        'telefono' => $request['telefono_abogado_'.$i],
                        'correo' => $request['correo_abogado_'.$i],
                        'direccion' => $request['direccion_abogado_'.$i]
                    ]);
                } else {
                    $abogado = Abogado::where('identificacion', $request['identificacion_abogado_'.$i])->first();
                    if(!$abogado)
                    {
                        $abogado = Abogado::create([
                            'nombre' => $request['nombre_abogado_'.$i],
                            'identificacion' => $request['identificacion_abogado_'.$i],
                            'telefono' => $request['telefono_abogado_'.$i],
                            'correo' => $request['correo_abogado_'.$i],
                            'direccion' => $request['direccion_abogado_'.$i]
                        ]);
                    }
                }

                if ( $request['existe_demandado_'.$i] == 'No' ) {
                    $demandado = Demandado::create([
                        'tipo' => $request['tipo_demandado_'.$i],
                        'nombre' => $request['nombre_demandado_'.$i],
                        'identificacion' => $request['identificacion_demandado_'.$i],
                        'verificacion' => $request['verificacion_demandado_'.$i],
                        'telefono' => $request['telefono_demandado_'.$i],
                        'correo' => $request['correo_demandado_'.$i],
                        'direccion' => $request['direccion_demandado_'.$i]
                    ]);
                } else {
                    $demandado = Demandado::where('identificacion', $request['identificacion_demandado_'.$i])->first();
                    if(!$demandado)
                    {
                        $demandado = Demandado::create([
                            'tipo' => $request['tipo_demandado_'.$i],
                            'nombre' => $request['nombre_demandado_'.$i],
                            'identificacion' => $request['identificacion_demandado_'.$i],
                            'verificacion' => $request['verificacion_demandado_'.$i],
                            'telefono' => $request['telefono_demandado_'.$i],
                            'correo' => $request['correo_demandado_'.$i],
                            'direccion' => $request['direccion_demandado_'.$i]
                        ]);
                    }
                }

                $detalle_proceso = Detalle_proceso::create([
                    'tipo' => 'Demandado',
                    'abogados_id' => $abogado->id ?? NULL,
                    'demandados_id' => $demandado->id,
                    'procesos_id' => $proceso->id,
                ]);
            }
        }

        if ( $request['nombre_demandante_1'] ) {
            for ($i=1; $i <= $request['cantidad_demandantes']; $i++) {
                if ( $request['existe_abogado_demandante_'.$i] == 'No' ) {
                    $abogado = Abogado::create([
                        'nombre' => $request['nombre_abogado_demandante_'.$i],
                        'identificacion' => $request['identificacion_abogado_demandante_'.$i],
                        'telefono' => $request['telefono_abogado_demandante_'.$i],
                        'correo' => $request['correo_abogado_demandante_'.$i],
                        'direccion' => $request['direccion_abogado_demandante_'.$i]
                    ]);
                } else {
                    $abogado = Abogado::where('identificacion', $request['identificacion_abogado_demandante_'.$i])->first();
                    if(!$abogado)
                    {
                        $abogado = Abogado::create([
                            'nombre' => $request['nombre_abogado_demandante_'.$i],
                            'identificacion' => $request['identificacion_abogado_demandante_'.$i],
                            'telefono' => $request['telefono_abogado_demandante_'.$i],
                            'correo' => $request['correo_abogado_demandante_'.$i],
                            'direccion' => $request['direccion_abogado_demandante_'.$i]
                        ]);
                    }
                }

                if ( $request['existe_demandante_'.$i] == 'No' ) {
                    $demandante = Demandado::create([
                        'tipo' => $request['tipo_demandante_'.$i],
                        'nombre' => $request['nombre_demandante_'.$i],
                        'identificacion' => $request['identificacion_demandante_'.$i],
                        'verificacion' => $request['verificacion_demandante_'.$i],
                        'telefono' => $request['telefono_demandante_'.$i],
                        'correo' => $request['correo_demandante_'.$i],
                        'direccion' => $request['direccion_demandante_'.$i]
                    ]);
                } else {
                    $demandante = Demandado::where('identificacion', $request['identificacion_demandante_'.$i])->first();
                    if(!$demandante){
                        $demandante = Demandado::create([
                            'tipo' => $request['tipo_demandante_'.$i],
                            'nombre' => $request['nombre_demandante_'.$i],
                            'identificacion' => $request['identificacion_demandante_'.$i],
                            'verificacion' => $request['verificacion_demandante_'.$i],
                            'telefono' => $request['telefono_demandante_'.$i],
                            'correo' => $request['correo_demandante_'.$i],
                            'direccion' => $request['direccion_demandante_'.$i]
                        ]);
                    }
                }

                $detalle_proceso = Detalle_proceso::create([
                    'tipo' => 'Demandante',
                    'abogados_id' => $abogado->id ?? NULL,
                    'demandados_id' => $demandante->id,
                    'procesos_id' => $proceso->id,
                ]);
            }
        }

        return redirect()->route('ver-proceso', ['id' => $proceso->id]);

    }

    public function ver(Request $request) {
        $proceso = Proceso::where('id', $request['id'])->with('clientes')->with('actuaciones.anotaciones')->with('users')->get();
        $audiencias = Audiencia::where('procesos_id', $request['id'])->orderBy('fecha', 'asc')->get();

        $detalle_proceso_demandado = Detalle_proceso::where('tipo', 'Demandado')->where('procesos_id', $request['id'])->with('demandados')->with('abogados')->get();
        $detalle_proceso_demandante = Detalle_proceso::where('tipo', 'Demandante')->where('procesos_id', $request['id'])->with('demandados')->get();

        $archivos = Procesos_archivo::where('procesos_id', $request['id'])->get();

        if($audiencias->count() > 0) {
            $now = Carbon::parse(Carbon::now()->toDateString());
            $fecha_audiencia = Carbon::parse($audiencias[0]->fecha);

            if ($fecha_audiencia > $now) {
                $proxima_audiencia = $fecha_audiencia->diffInDays($now);

            } else if ($fecha_audiencia < $now) {
                $proxima_audiencia = NULL;
            } else {
                $proxima_audiencia = 'Hoy';
            }
        }

        return view('procesos.ver', [
            'proceso' => $proceso,
            'audiencias' => $audiencias,
            'proxima_audiencia' => $proxima_audiencia ?? NULL,
            'detalle_proceso_demandado' => $detalle_proceso_demandado,
            'detalle_proceso_demandante' => $detalle_proceso_demandante,
            'archivos' => $archivos,
        ]);
    }

    public function agregar_actuacion(Request $request) {
        $date = Carbon::now('America/Bogota');
        $actuacion = Actuacion::create([
            'fecha' => $request['fecha'],
            'actuacion' => $request['actuacion'],
            'anotacion' => $request['anotacion'],
            'f_inicio_termino' => $request['f_inicio_termino'],
            'f_fin_termino' => $request['f_fin_termino'],
            'anotacion_file' => '',
            'procesos_id' => $request['procesos_id']
        ]);

        if ($request->hasfile('anotacion_file')) {
            $file = $request->file('anotacion_file');

            $extension_file = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            $ruta_file = 'docs/procesos/actuaciones/';
            $nombre_file = 'AC'.$date->format('YmdHis').'.'.$extension_file;
            Storage::disk('public')->put($ruta_file.$nombre_file, File::get($file));
            $nombre_completo_file = $ruta_file.$nombre_file;

            Anotacion_files::create([
                'nombre' => $request->nombre_archivo,
                'anotacion_file' => $nombre_completo_file,
                'actuaciones_id' => $actuacion->id
            ]);
        }

        return redirect()->route('ver-proceso', [ 'id' => $request['procesos_id'] ]);
    }

    public function get_archivos_actuacion(Request $request) {
        $files = Anotacion_files::where('actuaciones_id', $request->id)->get()->toArray();
        $file  = Actuacion::find($request->id)->toArray();
        array_push($files,$file);
        return $files;
    }

    public function add_archivos_actuacion(Request $request) {
        $date = Carbon::now('America/Bogota');
        $file = $request->file('anotacion_file');

        if($request->archivo_actuaciones_id) {
            $archivo = Anotacion_files::find($request->archivo_actuaciones_id);

            $archivo->update([
                'nombre' => $request->nombre,
            ]);

            if ($request->hasfile('anotacion_file')) {
                Storage::disk('public')->delete($archivo->anotacion_file);

                $extension_file = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
                $ruta_file = 'docs/procesos/actuaciones/';
                $nombre_file = 'AC'.$date->format('YmdHis').'.'.$extension_file;
                Storage::disk('public')->put($ruta_file.$nombre_file, File::get($file));
                $nombre_completo_file = $ruta_file.$nombre_file;

                $archivo->update([
                    'anotacion_file' => $nombre_completo_file
                ]);

                return redirect()->back()->with(['archivo' => 1]);
            }

            return redirect()->back()->with(['archivo' => 1]);
        }

        if ($request->hasfile('anotacion_file')) {
            $extension_file = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            $ruta_file = 'docs/procesos/actuaciones/';
            $nombre_file = 'AC'.$date->format('YmdHis').'.'.$extension_file;
            Storage::disk('public')->put($ruta_file.$nombre_file, File::get($file));
            $nombre_completo_file = $ruta_file.$nombre_file;

            Anotacion_files::create([
                'nombre' => $request->nombre,
                'anotacion_file' => $nombre_completo_file,
                'actuaciones_id' => $request->actuaciones_id
            ]);

            return redirect()->back()->with(['archivo' => 1]);
        }

        return redirect()->back();
    }

    public function delete_archivos_actuacion(Request $request) {
        $file = Anotacion_files::find($request->id);

        if($file) {
            if(Storage::disk('public')->delete($file->anotacion_file)) {
                $file->delete();
            }
        }

        return $file->delete();
    }

    public function buscar_view(Request $request) {
        return view('procesos.buscar');
    }

    public function buscar(Request $request) {
        $procesos = DB::table('procesos')
                    ->join('clientes', 'clientes.id', '=', 'procesos.clientes_id')
                    ->where('procesos.num_proceso', $request['buscar'])
                    ->orWhere('clientes.identificacion', $request['buscar'])
                    ->get();

        $clientes = Cliente::where('identificacion', $request['buscar'])->get();

        return view('procesos.buscar', ['procesos' => $procesos, 'clientes' => $clientes, 'busqueda' => $request['buscar']]);
    }

    public function update(Request $request) {
        $date = Carbon::now('America/Bogota');

        $cliente = Cliente::find($request['cliente_id']);
        $proceso = Proceso::find($request['proceso_id']);

        if ($request->file('proceso_file')) {
            $extension_file = pathinfo($request->file('proceso_file')->getClientOriginalName(), PATHINFO_EXTENSION);

            switch ($request['tipo']) {
                case 'Civil':
                    $ruta_file = 'docs/procesos/civil/';
                    $nombre_file = 'CI'.$date->format('YmdHis').'.'.$extension_file;
                    Storage::disk('public')->put($ruta_file.$nombre_file, File::get($request->file('proceso_file')));

                    break;

                case 'Familia':
                    $ruta_file = 'docs/procesos/familia/';
                    $nombre_file = 'FA'.$date->format('YmdHis').'.'.$extension_file;
                    Storage::disk('public')->put($ruta_file.$nombre_file, File::get($request->file('proceso_file')));

                    break;

                case 'Laboral':
                    $ruta_file = 'docs/procesos/laboral/';
                    $nombre_file = 'LA'.$date->format('YmdHis').'.'.$extension_file;
                    Storage::disk('public')->put($ruta_file.$nombre_file, File::get($request->file('proceso_file')));

                    break;

                case 'Seguridad Social':
                    $ruta_file = 'docs/procesos/seguridad_social/';
                    $nombre_file = 'SE'.$date->format('YmdHis').'.'.$extension_file;
                    Storage::disk('public')->put($ruta_file.$nombre_file, File::get($request->file('proceso_file')));

                    break;

                case 'Administrativo':
                    $ruta_file = 'docs/procesos/administrativo/';
                    $nombre_file = 'AD'.$date->format('YmdHis').'.'.$extension_file;
                    Storage::disk('public')->put($ruta_file.$nombre_file, File::get($request->file('proceso_file')));

                    break;

                case 'Penal':
                    $ruta_file = 'docs/procesos/penal/';
                    $nombre_file = 'PE'.$date->format('YmdHis').'.'.$extension_file;
                    Storage::disk('public')->put($ruta_file.$nombre_file, File::get($request->file('proceso_file')));

                    break;

                case 'Otros':
                    $ruta_file = 'docs/procesos/otros/';
                    $nombre_file = 'OT'.$date->format('YmdHis').'.'.$extension_file;
                    Storage::disk('public')->put($ruta_file.$nombre_file, File::get($request->file('proceso_file')));

                    break;
            }

            $nombre_completo_proceso_file = $ruta_file.$nombre_file;

            $proceso->update([
                'proceso_file' => $nombre_completo_proceso_file
            ]);
        }

        if ($request->file('cedula')) {
            $extension_file_cedula = pathinfo($request->file('cedula')->getClientOriginalName(), PATHINFO_EXTENSION);
            $ruta_file_cedula = 'docs/clientes/documentos/';
            $nombre_file_cedula = 'cedula_'.$request['identificacion'].'.'.$extension_file_cedula;
            Storage::disk('public')->put($ruta_file_cedula.$nombre_file_cedula, File::get($request->file('cedula')));

            $nombre_completo_file_cedula = $ruta_file_cedula.$nombre_file_cedula;

            $cliente->update([
                'cedula' => $nombre_completo_file_cedula,
            ]);
        }

        if ($request->file('contrato')) {
            $extension_file_contrato = pathinfo($request->file('contrato')->getClientOriginalName(), PATHINFO_EXTENSION);
            $ruta_file_contrato = 'docs/clientes/documentos/';
            $nombre_file_contrato = 'contrato_'.$request['identificacion'].'.'.$extension_file_contrato;
            Storage::disk('public')->put($ruta_file_contrato.$nombre_file_contrato, File::get($request->file('contrato')));

            $nombre_completo_file_contrato = $ruta_file_contrato.$nombre_file_contrato;

            $cliente->update([
                'contrato' => $nombre_completo_file_contrato,
            ]);
        }

        $cliente->update([
            'identificacion' => $request['identificacion'],
            'nombre' => $request['nombre'],
            'celular' => $request['celular'],
            'correo' => $request['correo'],
        ]);

        $proceso->update([
            'tipo' => $request['tipo'],
            'sub_tipo' => $request['sub_tipo'],
            'departamento' => $request['departamento'],
            'ciudad' => $request['ciudad'],
            'descripcion' => $request['descripcion'],
        ]);

        return redirect()->back()->with(['update' => 1]);

    }

    public function delete(Request $request) {
        $tipo = 'danger';
        $mensaje = 'El proceso no se elimino, intente nuevamente';

        if(auth()->user()->hasRole('admin')) {
            if(Proceso::find($request['id'])->delete()) {
                $tipo = 'success';
                $mensaje = 'Proceso eliminado correctamente';
            } else {
                $tipo = 'danger';
                $mensaje = 'El proceso no se elimino, intente nuevamente';
            }
        }

        return redirect()->back()->with([
            'mostrar_alerta' => 1,
            'tipo' => $tipo,
            'mensaje' => $mensaje
        ]);
    }

    public function delete_actuacion(Request $request) {
        return Actuacion::find($request['id'])->delete();
    }

    public function update_actuacion(Request $request) {
        return Actuacion::find($request['id']);
    }

    public function update_actuacion_post(Request $request) {
        $actuacion = Actuacion::find($request['actuacion_id']);
        $files=$request->file('anotacion_file');
        $date = Carbon::now('America/Bogota');

        if ($request->hasfile('anotacion_file')) {
            $actuacion->anotaciones()->delete();
            foreach ($files as $file) {
                $extension_file = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
                $ruta_file = 'docs/procesos/actuaciones/';
                $nombre_file = 'AC'.$date->format('YmdHis').'.'.$extension_file;
                Storage::disk('public')->put($ruta_file.$nombre_file, File::get($file));
                $nombre_completo_file = $ruta_file.$nombre_file;

                $anotacion = Anotacion_files::create([
                    'anotacion_file' => $nombre_completo_file,
                    'actuaciones_id' => $actuacion->id,
                ]);
            }

        }
        $actuacion->update([
            'fecha' => $request['fecha'],
            'actuacion' => $request['actuacion'],
            'anotacion' => $request['anotacion'],
            'f_inicio_termino' => $request['f_inicio_termino'],
            'f_fin_termino' => $request['f_fin_termino'],
            'anotacion_file' => $nombre_completo_file,
        ]);

        return redirect()->back()->with(['update_actuacion' => 1]);

    }

    public function add_proceso(Request $request) {
        $date = Carbon::now('America/Bogota');
        $proceso = Proceso::find($request['id']);

        $extension_file = pathinfo($request->file('proceso_file')->getClientOriginalName(), PATHINFO_EXTENSION);

        switch ($request['tipo']) {
            case 'Civil':
                $num_proceso = 'CI'.$num_proceso;
                $ruta_file = 'docs/procesos/civil/';
                $nombre_file = 'CI'.$date->format('YmdHis').'.'.$extension_file;
                Storage::disk('public')->put($ruta_file.$nombre_file, File::get($request->file('proceso_file')));

                break;

            case 'Familia':
                $num_proceso = 'FA'.$num_proceso;
                $ruta_file = 'docs/procesos/familia/';
                $nombre_file = 'FA'.$date->format('YmdHis').'.'.$extension_file;
                Storage::disk('public')->put($ruta_file.$nombre_file, File::get($request->file('proceso_file')));

                break;

            case 'Laboral':
                $num_proceso = 'LA'.$num_proceso;
                $ruta_file = 'docs/procesos/laboral/';
                $nombre_file = 'LA'.$date->format('YmdHis').'.'.$extension_file;
                Storage::disk('public')->put($ruta_file.$nombre_file, File::get($request->file('proceso_file')));

                break;

            case 'Seguridad Social':
                $num_proceso = 'SE'.$num_proceso;
                $ruta_file = 'docs/procesos/seguridad_social/';
                $nombre_file = 'SE'.$date->format('YmdHis').'.'.$extension_file;
                Storage::disk('public')->put($ruta_file.$nombre_file, File::get($request->file('proceso_file')));

                break;

            case 'Administrativo':
                $num_proceso = 'AD'.$num_proceso;
                $ruta_file = 'docs/procesos/administrativo/';
                $nombre_file = 'AD'.$date->format('YmdHis').'.'.$extension_file;
                Storage::disk('public')->put($ruta_file.$nombre_file, File::get($request->file('proceso_file')));

                break;

            case 'Penal':
                $num_proceso = 'PE'.$num_proceso;
                $ruta_file = 'docs/procesos/penal/';
                $nombre_file = 'PE'.$date->format('YmdHis').'.'.$extension_file;
                Storage::disk('public')->put($ruta_file.$nombre_file, File::get($request->file('proceso_file')));

                break;

            case 'Otros':
                    $num_proceso = 'OT'.$num_proceso;
                    $ruta_file = 'docs/procesos/otros/';
                    $nombre_file = 'OT'.$date->format('YmdHis').'.'.$extension_file;
                    Storage::disk('public')->put($ruta_file.$nombre_file, File::get($request->file('proceso_file')));

                    break;
        }

        $nombre_completo = $ruta_file.$nombre_file;

        $proceso->update([
            'proceso_file' => $nombre_completo
        ]);

        return redirect()->back();
    }

    public function agg_audiencia(Request $request) {
        if($request['audiencia_id']) {
            $audiencia = Audiencia::find($request['audiencia_id']);
            $audiencia->update([
                'fecha' => $request['fecha_audiencia'],
                'observaciones' => $request['observaciones'],
            ]);

            return redirect()->back()->with(['audiencia_update' => 1]);
        } else {
            $audiencia = Audiencia::create([
                'fecha' => $request['fecha_audiencia'],
                'observaciones' => $request['observaciones'] ?? 'NA',
                'procesos_id' => $request['procesos_id'],
            ])->save();

            return redirect()->back()->with(['audiencia' => 1]);
        }
    }

    public function update_audiencia(Request $request) {
        $audiencia = Audiencia::find($request['audiencia_id']);
        $audiencia->update([
            'fecha' => $request['fecha_audiencia'],
            'observaciones' => $request['observaciones'],
        ]);

        return redirect()->back()->with(['audiencia_update' => 1]);
    }

    public function search_demandado(Request $request) {
        $tablas = ['abogados', 'clientes', 'demandados', 'users'];

        foreach ($tablas as $tabla) {
            $demandante = DB::table($tabla)->where('identificacion', $request['identificacion'])->get();
            if(!$demandante->isEmpty())
            {
                return $demandante;
            }
        }
    }

    public function search_abogado(Request $request) {
        $tablas = ['abogados', 'clientes', 'demandados', 'users'];

        foreach ($tablas as $tabla) {
            $abogado = DB::table($tabla)->where('identificacion', $request['identificacion'])->get();
            if(!$abogado->isEmpty())
            {
                return $abogado;
            }
        }

    }

    public function agregar_demandado(Request $request) {
        // Actualizar Demandado/Demandante
        if ( $request['detalle_proceso_id'] ) {

            if ( $request['abogados_id'] ) {
                $abogado_update = Abogado::find($request['abogados_id']);

                $abogado_update->update([
                    'nombre' => $request['nombre_abogado_1'],
                    'identificacion' => $request['identificacion_abogado_1'],
                    'telefono' => $request['telefono_abogado_1'],
                    'correo' => $request['correo_abogado_1'],
                    'direccion' => $request['direccion_abogado_1'],
                ]);

                $demandado_update = Demandado::find($request['demandados_id']);

                $demandado_update->update([
                    'nombre' => $request['nombre_demandado_1'],
                    'identificacion' => $request['identificacion_demandado_1'],
                    'telefono' => $request['telefono_demandado_1'],
                    'correo' => $request['correo_demandado_1'],
                    'direccion' => $request['direccion_demandado_1']
                ]);
            } else {
                $abogado_update = Abogado::find($request['abogado_demandante_id']);

                $abogado_update->update([
                    'nombre' => $request['nombre_abogado_1'],
                    'identificacion' => $request['identificacion_abogado_1'],
                    'telefono' => $request['telefono_abogado_1'],
                    'correo' => $request['correo_abogado_1'],
                    'direccion' => $request['direccion_abogado_1'],
                ]);

                $demandado_update = Demandado::find($request['demandante_id']);

                $demandado_update->update([
                    'nombre' => $request['nombre_demandante_1'],
                    'identificacion' => $request['identificacion_demandante_1'],
                    'telefono' => $request['telefono_demandante_1'],
                    'correo' => $request['correo_demandante_1'],
                    'direccion' => $request['direccion_demandante_1']
                ]);
            }

            return redirect()->back()->with(['demandado_update' => 1]);
        }

        // Crear Demandado/Demandante
        if ( $request['tipo_proceso'] == 'Demandado' ) {
            // Crear Demandado
            if ( $request['existe_abogado_1'] == 'No' ) {
                $abogado = Abogado::create([
                    'nombre' => $request['nombre_abogado_1'],
                    'identificacion' => $request['identificacion_abogado_1'],
                    'telefono' => $request['telefono_abogado_1'],
                    'correo' => $request['correo_abogado_1'],
                    'direccion' => $request['direccion_abogado_1'],
                ]);
            } else {
                $abogado = Abogado::where('identificacion', $request['identificacion_abogado_1'])->first();
            }

            if ( $request['existe_demandado_1'] == 'No' ) {
                $demandado = Demandado::create([
                    'tipo' => $request['tipo_demandado_1'],
                    'nombre' => $request['nombre_demandado_1'],
                    'identificacion' => $request['identificacion_demandado_1'],
                    'verificacion' => $request['verificacion_demandado_1'],
                    'telefono' => $request['telefono_demandado_1'],
                    'correo' => $request['correo_demandado_1'],
                    'direccion' => $request['direccion_demandado_1'],
                ]);
            } else {
                $demandado = Demandado::where('identificacion', $request['identificacion_demandado_1'])->first();
            }

            $detalle_proceso = Detalle_proceso::create([
                'tipo' => 'Demandado',
                'abogados_id' => $abogado->id ?? NUll,
                'demandados_id' => $demandado->id,
                'procesos_id' => $request['procesos_id']
            ]);
        } else {
            // Crear Demandante
            if ( $request['existe_abogado_demandante_1'] == 'No' ) {
                $abogado = Abogado::create([
                    'nombre' => $request['nombre_abogado_1'],
                    'identificacion' => $request['identificacion_abogado_1'],
                    'telefono' => $request['telefono_abogado_1'],
                    'correo' => $request['correo_abogado_1'],
                    'direccion' => $request['direccion_abogado_1'],
                ]);
            } else {
                $abogado = Abogado::where('identificacion', $request['identificacion_abogado_1'])->first();
            }

            if ( $request['existe_demandante_1'] == 'No' ) {
                $demandado = Demandado::create([
                    'tipo' => $request['tipo_demandante_1'],
                    'nombre' => $request['nombre_demandante_1'],
                    'identificacion' => $request['identificacion_demandante_1'],
                    'verificacion' => $request['verificacion_demandante_1'],
                    'telefono' => $request['telefono_demandante_1'],
                    'correo' => $request['correo_demandante_1'],
                    'direccion' => $request['direccion_demandante_1'],
                ]);
            } else {
                $demandado = Demandado::where('identificacion', $request['identificacion_demandante_1'])->first();
            }

            $detalle_proceso = Detalle_proceso::create([
                'tipo' => 'Demandante',
                'abogados_id' => $abogado->id ?? NUll,
                'demandados_id' => $demandado->id,
                'procesos_id' => $request['procesos_id']
            ]);
        }

        return redirect()->back()->with(['demandado' => 1]);
    }

    public function detalle_proceso(Request $request) {
        return Detalle_proceso::with('abogados')->with('demandados')->find($request['id']);
    }

    public function delete_detalle_proceso(Request $request) {
        return Detalle_proceso::find($request['id'])->delete();
    }

    public function detalle_audiencia(Request $request) {
        return Audiencia::find($request['id']);
    }

    public function delete_detalle_audiencia(Request $request) {
        return Audiencia::find($request['id'])->delete();
    }

    public function generar_informe(Request $request) {
        $proceso = Proceso::where('id', $request['id'])->with('clientes')->with(array('actuaciones' => function($query){
            $query->orderBy('fecha','desc');
        }))->with('users')->get();

        $audiencias = Audiencia::where('procesos_id', $request['id'])->orderBy('fecha', 'desc')->limit(1)->get();

        $detalle_proceso = Detalle_proceso::where('procesos_id', $request['id'])->with('demandados')->with('abogados')->get();

        $data = [
            'proceso' => $proceso,
            'audiencias' => $proceso,
            'detalle_proceso' => $detalle_proceso
        ];

        // dd($data);
        return PDF::loadView('procesos.informe_pdf', compact('data'))->setPaper('A4')->stream('informe.pdf');
    }

    public function juzgado(Request $request) {
        $proceso = Proceso::find($request['proceso_id']);

        $proceso->update([
            'radicado' => $request['radicado'],
            'juzgado' => $request['juzgado'],
            'juez' => $request['juez'],
            'telefono' => $request['telefono'],
            'direccion' => $request['direccion'],
            'correo' => $request['correo'],
        ]);

        return redirect()->back()->with(['juzgado' => 1]);
    }

    public function fiscalia(Request $request) {
        $proceso = Proceso::find($request['proceso_id']);

        $proceso->update([
            'fiscalia' => $request['fiscalia'],
            'fiscal' => $request['fiscal'],
            'telefono_fiscal' => $request['telefono_fiscal'],
            'direccion_fiscal' => $request['direccion_fiscal'],
            'correo_fiscal' => $request['correo_fiscal'],
        ]);

        return redirect()->back()->with(['fiscal' => 1]);
    }

    public function acceso(Request $request) {

        $user = User::role('admin')->get();
        $accesos = Acceso_proceso::with(['user', 'proceso'])->where('procesos_id', $request['id'])->get();
        $proceso = Proceso::find($request['id']);

        return view('procesos.acceso', ['accesos' => $accesos, 'proceso' => $proceso, 'usuarios'=>$user]);
    }

    public function agregar_acceso(Request $request) {
        Acceso_proceso::create([
            'users_id' => $request['users_id'],
            'procesos_id' => $request['procesos_id'],
        ]);

        return redirect()->back()->with(['create' => 1]);
    }

    public function delete_acceso(Request $request) {
        return Acceso_proceso::find($request['id'])->delete();
    }

    public function callAction($method, $parameters)
    {
        return parent::callAction($method, array_values($parameters));
    }

    public function agg_archivos(Request $request) {
        $date = Carbon::now('America/Bogota');
        $file = $request->file('file');

        if ($request->hasfile('file')) {
            $extension_file = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            $ruta_file = 'docs/procesos/archivos/';
            $nombre_file = 'FL'.$date->format('YmdHis').'.'.$extension_file;
            Storage::disk('public')->put($ruta_file.$nombre_file, File::get($file));
            $nombre_completo_file = $ruta_file.$nombre_file;
        }

        $file = Procesos_archivo::create([
            'nombre' => $request['nombre'],
            'file' => $nombre_completo_file,
            'procesos_id' => $request['procesos_id']
        ]);

        return redirect()->back()->with(['archivo' => 1]);
        // return redirect()->route('ver-proceso', [ 'id' => $request['procesos_id'] ]);
    }

    public function delete_archivos(Request $request) {
        $file = Procesos_archivo::find($request->id);

        if($file) {
            if(Storage::disk('public')->delete($file->file)) {
                $file->delete();
            }
        }

        return $file->delete();
    }

    public function sincronizar_procesos(Request $request) {
        $job = new SincronizarActuaciones();
        return $job->handle();
    }
}


