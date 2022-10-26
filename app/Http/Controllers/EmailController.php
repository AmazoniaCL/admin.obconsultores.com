<?php

namespace App\Http\Controllers;

use App\Mail\MensajeCliente;
use App\Models\Email;
use App\Models\Email_mensaje;
use App\Models\Email_mensaje_adjunto;
use App\Models\Cliente;
use App\Models\Proceso;
use App\Models\Sistema\Acceso_proceso;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $estado = 'Sin Leer';
        if($request->estado) $estado = $request->estado;

        $cliente = Cliente::find($request->id);

        $datos = Email::with(['mensajes' => function ($q) {
                            $q->orderBy('created_at', 'desc');
                        }])
                        ->with('user')
                        ->with('procesos')
                        ->where('cliente_id', $request->id)
                        ->where('estado', $estado)
                        ->orderBy('created_at', 'desc')->get();

        $emails = User::select('email')->role(['admin','general'])->get();

        // dd($datos);

        return view('emails.index',['emails' => $datos, 'cliente' => $cliente, 'emailsList' => $emails, 'isViewProcesos' => false]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexProceso(Request $request)
    {
        $estado = 'Sin Leer';
        if($request->estado) $estado = $request->estado;

        $proceso = Proceso::with('clientes')->find($request->procesos_id);
        $cliente = $proceso['clientes'];

        $datos = Email::with(['mensajes' => function ($q) {
                            $q->orderBy('created_at', 'desc');
                        }])
                        ->with('user')
                        ->with('procesos')
                        ->where('procesos_id', $request->procesos_id)
                        ->where('estado', $estado)
                        ->orderBy('created_at', 'desc')->get();

        $emails = User::select('email')->role(['admin','general'])->get();

        // dd($datos);

        return view('emails.index',['emails' => $datos, 'cliente' => $cliente, 'emailsList' => $emails, 'isViewProcesos' => true, 'proceso' => $proceso]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $cliente = Cliente::find($request->id);
        $datos = Email::where('cliente_id', $request->id)->where('estado', '<>', 'Borrado')->orderBy('created_at', 'desc')->get();
        $emails = User::select('email')->role(['admin','general'])->get();
        $procesos = Proceso::where('clientes_id', $request->id)->get();

        return view('emails.create',[ 'emails' => $datos, 'cliente' => $cliente, 'emailsList' => $emails, 'procesos' => $procesos, 'isViewProcesos' => false ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createFromProceso(Request $request)
    {
        // $cliente = Cliente::find($request->id);
        $datos = Email::where('procesos_id', $request->procesos_id)->where('estado', '<>', 'Borrado')->orderBy('created_at', 'desc')->get();
        $emails = User::select('email')->role(['admin','general'])->get();
        $procesos = Proceso::where('id', $request->procesos_id)->get();

        $proceso = Proceso::with('clientes')->find($request->procesos_id);
        $cliente = $proceso['clientes'];

        return view('emails.create',[ 'emails' => $datos, 'cliente' => $cliente, 'emailsList' => $emails, 'procesos' => $procesos, 'isViewProcesos' => true, 'proceso' => $proceso ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $date = Carbon::now('America/Bogota');

        $user_id = (int)auth()->user()->id;
        $cliente_id = (int)$request['cliente_id'];

        $email = Email::create([
            'asunto' => $request['asunto'],
            'estado' => 'Sin Leer',
            'tipo' => $request['tipo'],
            'user_id' => $user_id,
            'cliente_id' => $cliente_id,
            'procesos_id' => $request['procesos_id']
        ]);

        if(!$email->save()) {
            return redirect()->back()->with([
                'mostrar_alerta' => 1,
                'tipo' => 'danger',
                'mensaje' => 'Error creando consulta'
            ]);
        }

        $mensaje = Email_mensaje::create([
            'mensaje' => $request['mensaje'],
            'email_id' => $email->id,
            'user_id'=> $user_id,
        ]);

        if(!$mensaje->save()) {
            $email->delete();

            return redirect()->back()->with([
                'mostrar_alerta' => 1,
                'tipo' => 'danger',
                'mensaje' => 'Error creando mensajes de la consulta'
            ]);
        }

        $files = $request->file('adjunto_correo');

        if ($files) {
            foreach ($files as $key => $file) {
                $extension_file_adjunto = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
                $ruta_file_adjunto = 'docs/clientes/documentos/email/';
                $nombre_file_adjunto = 'adjunto_'.$request['cliente_id_'].'_'.$date->format('YmdHis').'.'.$extension_file_adjunto;
                $nombre_completo_file_adjunto = $ruta_file_adjunto.$nombre_file_adjunto;
                $file = Storage::disk('public')->put($nombre_completo_file_adjunto, File::get($file));

                if($file) {
                    $adjunto_result = Email_mensaje_adjunto::create([
                        'nombre' => $nombre_file_adjunto,
                        'file' => $nombre_completo_file_adjunto,
                        'emails_mensaje_id' => $mensaje->id,
                    ]);

                    if(!$adjunto_result->save()) {
                        Storage::disk('public')->delete($nombre_completo_file_adjunto);
                    }
                }
            }
        }

        // Enviar correo de notificacion
        $correosEnviar = array_unique($request->correos_notificar);
        $mensajeEnviar = '
            <h3>Asunto: </h3>
            <p>'.$request['asunto'].'</p>
            <br>
            <h3>Mensaje: </h3>
            '.$request['mensaje'].'
            <br><br>
            <a href="https://admin.obconsultores.com/clientes/cosultas/inbox/'.$cliente_id.'" target="_blank">Clic aqui para responder la consulta</a>
        ';

        $asunto = 'Nueva Consulta en ObConsultores';

        if($request['procesos_id']) {
            $proceso = Proceso::find($request['procesos_id']);
            if($proceso) {
                $asunto .= ', Proceso: ' . $proceso->tipo . ' ' . $proceso->num_proceso . ' (' . $proceso->ciudad . ', ' . $proceso->departamento . ') - ' . ($proceso->radicado ?? 'Sin radicado');
            }
        }

        try {
            Mail::to($correosEnviar)->send(new MensajeCliente($mensajeEnviar, $asunto, null, null, null));
        } catch (\Throwable $th) {
            //throw $th;
            dd($th);
        }

        if($request['retrornar_a'] == 'procesos')
            return redirect()->route('consultas-proceso', ['procesos_id' => $request['procesos_id']]);

        return redirect()->route('consultas-cliente', ['id' => $request['cliente_id']]);
    }

    public function store_mensaje(Request $request)
    {
        $date = Carbon::now('America/Bogota');
        $user_id = (int)auth()->user()->id;

        $email = Email::find($request['mensaje_id']);

        $mensaje = Email_mensaje::create([
            'mensaje' => $request['mensaje'],
            'email_id' => $request['mensaje_id'],
            'user_id'=> $user_id,
        ]);

        if(!$mensaje->save()) {
            return redirect()->back()->with([
                'mostrar_alerta' => 1,
                'tipo' => 'danger',
                'mensaje' => 'Error creando mensajes de la consulta'
            ]);
        }

        $files = $request->file('adjunto_correo');

        if ($files) {
            foreach ($files as $key => $file) {
                $extension_file_adjunto = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
                $ruta_file_adjunto = 'docs/clientes/documentos/email/';
                $nombre_file_adjunto = 'adjunto_'.$request['cliente_id_'].'_'.$date->format('YmdHis').'.'.$extension_file_adjunto;
                $nombre_completo_file_adjunto = $ruta_file_adjunto.$nombre_file_adjunto;
                $file = Storage::disk('public')->put($nombre_completo_file_adjunto, File::get($file));

                if($file) {
                    $adjunto_result = Email_mensaje_adjunto::create([
                        'nombre'=>$nombre_file_adjunto,
                        'file'=>$nombre_completo_file_adjunto,
                        'emails_mensaje_id' => $mensaje->id,
                    ]);

                    if(!$adjunto_result->save()) {
                        Storage::disk('public')->delete($nombre_completo_file_adjunto);
                    }
                }
            }
        }

        // Enviar correo de notificacion
        $correosEnviar = array_unique($request->correos_notificar);
        $mensajeEnviar = '
            <h3>Asunto: </h3>
            <p>'.$email->asunto.'</p>
            <br>
            <h3>Mensaje: </h3>
            '.$request['mensaje'].'
            <br><br>
            <a href="https://admin.obconsultores.com/clientes/cosultas/inbox/'.$email->cliente_id.'" target="_blank">Clic aqui para responder la consulta</a>
        ';

        $asunto = 'Respuesta a Consulta en ObConsultores';

        if($email->procesos_id) {
            $proceso = Proceso::find($email->procesos_id);
            if($proceso) {
                $asunto .= ', Proceso: ' . $proceso->tipo . ' ' . $proceso->num_proceso . ' (' . $proceso->ciudad . ', ' . $proceso->departamento . ') - ' . ($proceso->radicado ?? 'Sin radicado');
            }
        }

        try {
            Mail::to($correosEnviar)->send(new MensajeCliente($mensajeEnviar, $asunto, null, null, null));
        } catch (\Throwable $th) {
            //throw $th;
            dd($th);
        }

        if ($mensaje->save()) {
            $email = Email::find($request['mensaje_id']);
            if($email->estado != 'Borrado'){
                $email->update([
                    'estado' => 'Sin Leer',
                ]);
            }
            return redirect()->route('consultas-cliente', ['id' => $request['cliente_id']]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function get_media(Request $request)
    {
        $email = Email::with(['cliente','mensajes.adjuntos','mensajes.user','user','procesos'])->find($request->id);
        return $email;
    }

    public function cambio_estado(Request $request)
    {
        $email = Email::find($request->id);
        if($email->estado != 'Borrado'){
            $email->update([
                'estado' => 'Leido',
            ]);
        }
        return $email;
    }

    public function reactivar_email(Request $request)
    {
        $email = Email::find($request->id);
        $email->update([
            'estado' => 'Sin Leer',
        ]);
        return redirect()->route('consultas-cliente', $email->cliente_id);
    }

    public function desactivar_email(Request $request)
    {
        $email = Email::find($request->id);
        if($email->estado != 'Borrado'){
            $email->update([
                'estado' => 'Borrado',
            ]);
        }
        return redirect()->route('consultas-cliente', $email->cliente_id);
    }
}
