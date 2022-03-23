<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Audiencia;
use App\Models\Demandado;
use App\Models\Cliente;
use App\Models\Consulta;
use App\Models\Proceso;
use Carbon\Carbon;

class HomeController extends Controller
{
    public $date;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('cliente');
        $this->date = Carbon::now()->format('Y-m-d');
    }

    public function index() {
        $clientes = Cliente::all()->count();
        $eventos = Audiencia::whereDate('fecha', '>=', $this->date)->get()->count();
        $consultas = Consulta::where('leido', '0')->get()->count();
        $audiencias = Audiencia::whereDate('fecha', '>=', $this->date)->with(array('procesos' => function ($query) {
            $query->with('clientes');
        }))->get();

        return view('welcome', [
            'clientes' => $clientes,
            'eventos' => $eventos,
            'consultas' => $consultas,
            'audiencias' => $audiencias,
        ]);
    }

    public function calendario() {
        $audiencias = Audiencia::whereDate('fecha', '>=', $this->date)->with(array('procesos' => function ($query) {
            $query->with('clientes');
        }))->get();

        return view('calendario.index', ['audiencias' => $audiencias]);
    }

    public function notificaciones() {
        return $audiencias = Audiencia::whereDate('fecha', '>=', $this->date)->get()->count();
    }

    public function cargar_notificaciones() {
        return $audiencias = Audiencia::whereDate('fecha', '>=', $this->date)->with(array('procesos' => function ($query) {
            $query->with('clientes');
        }))->get();
    }

    public function get_procesos_for_day(Request $request) {

        $fecha = Carbon::createFromDate($request['fecha'])->format('Y-m-d');
        $data = [];

        for ($i=0; $i <= 10; $i++) {
            array_push($data, Proceso::whereDate('created_at', $fecha)->get()->count());
            $fecha = Carbon::createFromDate($fecha)->addDay()->format('Y-m-d');
        }

        return $data;
    }

    public function get_procesos_for_type() {
        return [
            Proceso::where('tipo', 'Civil')->get()->count(),
            Proceso::where('tipo', 'Familia')->get()->count(),
            Proceso::where('tipo', 'Laboral')->get()->count(),
            Proceso::where('tipo', 'Seguridad Social')->get()->count(),
            Proceso::where('tipo', 'Administrativo')->get()->count(),
            Proceso::where('tipo', 'Penal')->get()->count(),
            Proceso::where('tipo', 'Otros')->get()->count(),
        ];
    }

    public function get_terceros() {
        return [
            Cliente::all()->count(),
            Demandado::all()->count(),
        ];
    }

    public function callAction($method, $parameters)
    {
        return parent::callAction($method, array_values($parameters));
    }

}

