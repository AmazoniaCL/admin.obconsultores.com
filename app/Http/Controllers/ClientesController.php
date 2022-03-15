<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use App\Models\Sistema\Acceso_proceso;
use App\Models\Mensajes_cliente;
use Illuminate\Http\Request;
use App\Mail\MensajeCliente;
use App\Models\Cliente;
use Carbon\Carbon;

class ClientesController extends Controller
{
    public function index() {
        $clientes = Cliente::with('procesos')->orderBy('nombre', 'ASC')->paginate(10);

        return view('clientes.index', ['clientes' => $clientes]);
    }

    public function search(Request $request) {
        $clientes = Cliente::with('procesos')
            ->where('identificacion','LIKE','%'.$request['search'].'%')
            ->orWhere('nombre','LIKE','%'.$request['search'].'%')
            ->orWhere('telefono','LIKE','%'.$request['search'].'%')
            ->orWhere('correo','LIKE','%'.$request['search'].'%')
            ->orWhere('direccion','LIKE','%'.$request['search'].'%')
            ->paginate(10);

        return view('clientes.index', ['clientes' => $clientes]);
    }

    public function ver(Request $request) {
        $procesos = [];

        if (auth()->user()->hasRole('admin')) {
            $cliente = Cliente::with(['procesos' => function ($q) {
                $q->orderBy('num_proceso', 'asc');
            }])->find($request['id']);

            foreach ($cliente['procesos'] as $row) {
                array_push($procesos, $row);
            }
        } else {
            $cliente = Cliente::with(['procesos' => function ($q) {
                $q->where('users_id', auth()->user()->id);
                $q->orderBy('num_proceso', 'asc');
            }])->find($request['id']);

            $accesos = Acceso_proceso::with('proceso')->where('users_id', auth()->user()->id)->get();

            foreach ($cliente['procesos'] as $row) {
                array_push($procesos, $row);
            }

            if ($accesos->count() > 0) {
                foreach ($accesos as $acceso) {
                    array_push($procesos, $acceso->proceso);
                }
            }
        }

        // dd($procesos);

        return view('clientes.ver', ['cliente' => $cliente, 'procesos' => $procesos]);
    }

    public function search_proceso(Request $request) {
        $cliente = Cliente::with(['procesos' => function ($q) use ($request) {
            $q->where('users_id', auth()->user()->id);
            $q->where(function ($query) use ($request) {
                $query->where('num_proceso','LIKE','%'.$request['search'].'%');
                $query->orWhere('tipo','LIKE','%'.$request['search'].'%');
                $query->orWhere('radicado','LIKE','%'.$request['search'].'%');
                $query->orWhere('juzgado','LIKE','%'.$request['search'].'%');
                $query->orWhere('juez','LIKE','%'.$request['search'].'%');
            });
        }])->find($request['id']);

        $accesos = Acceso_proceso::with(['proceso' => function ($q) use ($request) {
                $q->where('num_proceso','LIKE','%'.$request['search'].'%');
                $q->orWhere('tipo','LIKE','%'.$request['search'].'%');
                $q->orWhere('radicado','LIKE','%'.$request['search'].'%');
                $q->orWhere('juzgado','LIKE','%'.$request['search'].'%');
                $q->orWhere('juez','LIKE','%'.$request['search'].'%');
            }])
            ->where('users_id', auth()->user()->id)
            ->get();

        $procesos = [];

        foreach ($cliente['procesos'] as $row) {
            array_push($procesos, $row);
        }

        if ($accesos->count() > 0) {
            foreach ($accesos as $acceso) {
                if ($acceso->proceso) {
                    array_push($procesos, $acceso->proceso);
                }
            }
        }

        return view('clientes.ver', ['cliente' => $cliente, 'procesos' => $procesos]);
    }

    public function crear() {
        return view('clientes.crear');
    }

    public function create(Request $request) {
        if ($request->file('cedula')) {
            $extension_file_cedula = pathinfo($request->file('cedula')->getClientOriginalName(), PATHINFO_EXTENSION);
            $ruta_file_cedula = 'docs/clientes/documentos/';
            $nombre_file_cedula = 'cedula_'.$request['identificacion'].'.'.$extension_file_cedula;
            $nombre_completo_file_cedula = $ruta_file_cedula.$nombre_file_cedula;
            Storage::disk('public')->put($nombre_completo_file_cedula, File::get($request->file('cedula')));
        }

        if ($request->file('contrato')) {
            $extension_file_contrato = pathinfo($request->file('contrato')->getClientOriginalName(), PATHINFO_EXTENSION);
            $ruta_file_contrato = 'docs/clientes/documentos/';
            $nombre_file_contrato = 'contrato_'.$request['identificacion'].'.'.$extension_file_contrato;
            $nombre_completo_file_contrato = $ruta_file_contrato.$nombre_file_contrato;
            Storage::disk('public')->put($nombre_completo_file_contrato, File::get($request->file('contrato')));
        }

        if ($request->file('poder')) {
            $extension_file_poder = pathinfo($request->file('poder')->getClientOriginalName(), PATHINFO_EXTENSION);
            $ruta_file_poder = 'docs/clientes/documentos/';
            $nombre_file_poder = 'poder_'.$request['identificacion'].'.'.$extension_file_poder;
            $nombre_completo_file_poder = $ruta_file_poder.$nombre_file_poder;
            Storage::disk('public')->put($nombre_completo_file_poder, File::get($request->file('poder')));
        }

        if ($request->file('titulo_valor')) {
            $extension_file_titulo_valor = pathinfo($request->file('titulo_valor')->getClientOriginalName(), PATHINFO_EXTENSION);
            $ruta_file_titulo_valor = 'docs/clientes/documentos/';
            $nombre_file_titulo_valor = 'titulo_valor_'.$request['identificacion'].'.'.$extension_file_titulo_valor;
            $nombre_completo_file_titulo_valor = $ruta_file_titulo_valor.$nombre_file_titulo_valor;
            Storage::disk('public')->put($nombre_completo_file_titulo_valor, File::get($request->file('titulo_valor')));
        }

        $cliente = Cliente::create([
            'tipo_cliente' => $request['tipo_cliente'],
            'identificacion' => $request['identificacion'],
            'verificacion' => $request['verificacion'],
            'nombre' => $request['nombre'],
            'direccion' => $request['direccion'],
            'telefono' => $request['telefono'],
            'celular' => $request['celular'],
            'correo' => $request['correo'],
            'correo_dos' => $request['correo_dos'],
            'identificacion_representante' => $request['identificacion_representante'],
            'nombre_representante' => $request['nombre_representante'],
            'direccion_representante' => $request['direccion_representante'],
            'celular_representante' => $request['celular_representante'],
            'cedula' => $nombre_completo_file_cedula ?? NULL,
            'contrato' => $nombre_completo_file_contrato ?? NULL,
            'poder' => $nombre_completo_file_poder ?? NULL,
            'titulo_valor' => $nombre_completo_file_titulo_valor ?? NULL,
            'eps' => $request['eps'],
            'arl' => $request['arl'],
            'afp' => $request['afp']
        ]);

        if ($cliente->save()) {
            return redirect()->route('ver-cliente', ['id' => $cliente->id]);
        }
    }

    public function add_cedula(Request $request) {
        $extension_file_cedula = pathinfo($request->file('cedula')->getClientOriginalName(), PATHINFO_EXTENSION);
        $ruta_file_cedula = 'docs/clientes/documentos/';
        $nombre_file_cedula = 'cedula_'.$request['identificacion'].'.'.$extension_file_cedula;
        Storage::disk('public')->put($ruta_file_cedula.$nombre_file_cedula, File::get($request->file('cedula')));

        $cliente = Cliente::find($request['id']);

        $cliente->update([
            'cedula' => $nombre_file_cedula
        ]);

        return redirect()->back();
    }

    public function add_contrato(Request $request) {
        $extension_file_contrato = pathinfo($request->file('contrato')->getClientOriginalName(), PATHINFO_EXTENSION);
        $ruta_file_contrato = 'docs/clientes/documentos/';
        $nombre_file_contrato = 'contrato_'.$request['identificacion'].'.'.$extension_file_contrato;
        Storage::disk('public')->put($ruta_file_contrato.$nombre_file_contrato, File::get($request->file('contrato')));

        $cliente = Cliente::find($request['id']);

        $cliente->update([
            'contrato' => $nombre_file_contrato
        ]);

        return redirect()->back();
    }

    public function update(Request $request) {
        $cliente = Cliente::find($request['id']);

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

        if ($request->file('poder')) {
            $extension_file_poder = pathinfo($request->file('poder')->getClientOriginalName(), PATHINFO_EXTENSION);
            $ruta_file_poder = 'docs/clientes/documentos/';
            $nombre_file_poder = 'poder_'.$request['identificacion'].'.'.$extension_file_poder;
            Storage::disk('public')->put($ruta_file_poder.$nombre_file_poder, File::get($request->file('poder')));

            $nombre_completo_file_poder = $ruta_file_poder.$nombre_file_poder;

            $cliente->update([
                'poder' => $nombre_completo_file_poder,
            ]);
        }

        if ($request->file('titulo_valor')) {
            $extension_file_titulo_valor = pathinfo($request->file('titulo_valor')->getClientOriginalName(), PATHINFO_EXTENSION);
            $ruta_file_titulo_valor = 'docs/clientes/documentos/';
            $nombre_file_titulo_valor = 'titulo_valor_'.$request['identificacion'].'.'.$extension_file_titulo_valor;
            Storage::disk('public')->put($ruta_file_titulo_valor.$nombre_file_titulo_valor, File::get($request->file('titulo_valor')));

            $nombre_completo_file_titulo_valor = $ruta_file_titulo_valor.$nombre_file_titulo_valor;

            $cliente->update([
                'titulo_valor' => $nombre_completo_file_titulo_valor,
            ]);
        }

        $cliente->update([
            'tipo_cliente' => $request['tipo_cliente'],
            'identificacion' => $request['identificacion'],
            'nombre' => $request['nombre'],
            'direccion' => $request['direccion'],
            'telefono' => $request['telefono'],
            'celular' => $request['celular'],
            'correo' => $request['correo'],
            'correo_dos' => $request['correo_dos'],
            'identificacion_representante' => $request['identificacion_representante'],
            'nombre_representante' => $request['nombre_representante'],
            'direccion_representante' => $request['direccion_representante'],
            'celular_representante' => $request['celular_representante'],
            'eps' => $request['eps'],
            'arl' => $request['arl'],
            'afp' => $request['afp']
        ]);

        return redirect()->back()->with(['update' => 1]);
    }

    public function delete(Request $request) {
        return Cliente::find($request['id'])->delete();
    }

    public function enviar_mensaje(Request $request) {
        $date = Carbon::now('America/Bogota');

        $path = ($request->file('adjunto_correo')) ? $request->file('adjunto_correo')->getPathname() : '';
        $nombre_file = ($request->file('adjunto_correo')) ? $request->file('adjunto_correo')->getClientOriginalName() : '';
        $mine = ($request->file('adjunto_correo')) ? $request->file('adjunto_correo')->getClientMimeType() : '';

        Mensajes_cliente::create([
            'fecha' => $date->format('Y-m-d'),
            'asunto' => $request['asunto'],
            'mensaje' => $request['mensaje'],
            'user_id' => auth()->user()->id,
            'clientes_id' => $request['id'],
        ])->save();

        Mail::to($request['correo'])->send(new MensajeCliente($request['mensaje'], $request['asunto'], $path, $nombre_file, $mine));

        return redirect()->back()->with(['mensaje_enviado' => 1]);
    }


    public function callAction($method, $parameters)
    {
        return parent::callAction($method, array_values($parameters));
    }
}
