<?php

namespace App\Http\Controllers;

use App\Models\Email;
use App\Models\Email_mensaje;
use App\Models\Email_mensaje_adjunto;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class EmailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $estado = 'Sin Leer';
        if($request->estado){$estado = $request->estado;}
        $cliente = Cliente::find($request->id);
        $datos=Email::where('cliente_id', $request->id)->where('estado',$estado)->orderBy('created_at', 'desc')->get();
        return view('emails.index',['emails'=>$datos, 'cliente'=>$cliente]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $cliente = Cliente::find($request->id);
        $datos=Email::where('cliente_id', $request->id)->where('estado','<>','Borrado')->orderBy('created_at', 'desc')->get();
        return view('emails.create',['emails'=>$datos, 'cliente'=>$cliente]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->file('adjunto_correo')) {
            $extension_file_adjunto = pathinfo($request->file('adjunto_correo')->getClientOriginalName(), PATHINFO_EXTENSION);
            $ruta_file_adjunto = 'docs/clientes/documentos/email/';
            $nombre_file_adjunto = 'adjunto_'.$request['cliente_id'].'.'.$extension_file_adjunto;
            $nombre_completo_file_adjunto = $ruta_file_adjunto.$nombre_file_adjunto;
            Storage::disk('public')->put($nombre_completo_file_adjunto, File::get($request->file('adjunto_correo')));
        }

        $user_id = (int)auth()->user()->id;
        $cliente_id = (int)$request['cliente_id'];
        
        $email = Email::create([
            'asunto'=> $request['asunto'],
            'estado'=> 'Sin Leer',
            'user_id'=> $user_id,
            'cliente_id'=> $cliente_id,
        ]);

        $email->mensajes()->create([
            'mensaje'=>$request['mensaje']
        ]);

       if ($request->file('adjunto_correo')) {
            $email = Email_mensaje_adjunto::create([
                'nombre'=>$nombre_file_adjunto,
                'file'=>$nombre_completo_file_adjunto,
                'user_id'=> $user_id,
                'emails_mensaje_id' => $email->id,
            ]);
        }

        if ($email->save()) {
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
        $email=Email::join('emails_mensajes', 'emails.id', '=', 'emails_mensajes.email_id')
            ->join('users', 'users.id', '=', 'emails.user_id')
            ->join('clientes', 'clientes.id', '=', 'emails.cliente_id')
            //->join('emails_mensajes_adjuntos', 'emails_mensajes.id', '=', 'emails_mensajes_adjuntos.emails_mensaje_id')
            ->where('emails.id', $request->id)
            ->get();
        //$mensaje=Email_mensaje::where('email_id', $request->id)->get();
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
}
