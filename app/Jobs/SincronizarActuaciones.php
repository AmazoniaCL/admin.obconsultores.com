<?php

namespace App\Jobs;

use App\Models\Actuacion;
use App\Models\Proceso;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\MensajeCliente;
use App\Mail\ResponderConsulta;
use App\Models\Sincronizacion;
use App\Models\SincronizacionProceso;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Notifications\Action;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class SincronizarActuaciones implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct() {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        $procesos = Proceso::whereNotNull('radicado')->with('clientes')->with(['acceso_proceso' => function ($query) {
            $query->with('user');
        }])->limit(5)->get();
        $procesosnuevos = [];
        $nuevos = [];

        foreach ($procesos as $proceso) {
            if(strlen((string) $proceso->radicado) != 23) continue;

            $ResponseProceso = Http::get('https://consultaprocesos.ramajudicial.gov.co:448/api/v2/Procesos/Consulta/NumeroRadicacion?SoloActivos=false&numero=' . $proceso->radicado);
            $DataProceso = json_decode($ResponseProceso, true);

            if(isset($DataProceso['procesos']) && is_array($DataProceso['procesos'])) {
                $idProceso = $DataProceso['procesos'][0]['idProceso'] ?? null;
                $ResponseActuaciones = Http::get('https://consultaprocesos.ramajudicial.gov.co:448/api/v2/Proceso/Actuaciones/' . $idProceso . '?pagina=1');
                $DataActuaciones = json_decode($ResponseActuaciones, true);

                // dd($DataActuaciones);

                if(isset($DataActuaciones['actuaciones']) && is_array($DataActuaciones['actuaciones'])) {
                    foreach ($DataActuaciones['actuaciones'] as $actuacion) {
                        // consultar si ya se sincronizo la actuacion
                        $SearchActuacion = Actuacion::where('idsincronizacion', $actuacion['idRegActuacion'])->exists();

                        if(!$SearchActuacion) {
                            Actuacion::create([
                                'fecha' => $actuacion['fechaActuacion'],
                                'actuacion' => $actuacion['actuacion'],
                                'anotacion' => $actuacion['anotacion'],
                                'f_inicio_termino' => $actuacion['fechaInicial'],
                                'f_fin_termino' => $actuacion['fechaFinal'],
                                'idsincronizacion' => $actuacion['idRegActuacion'],
                                'procesos_id' => $proceso['id']
                            ]);

                            $nuevos[$proceso['id']] = (!isset($nuevos[$proceso['id']])) ? 1 : $nuevos[$proceso['id']] + 1;
                            $procesosnuevos[$proceso['id']] = $proceso;
                        }
                    }
                }

                if(isset($DataActuaciones['paginacion']['cantidadPaginas']) && $DataActuaciones['paginacion']['cantidadPaginas'] > 1) {
                    for ($i = 2; $i <= $DataActuaciones['paginacion']['cantidadPaginas']; $i++) {
                        $idProceso = $DataProceso['procesos'][0]['idProceso'] ?? null;
                        $ResponseActuaciones =  Http::get('https://consultaprocesos.ramajudicial.gov.co:448/api/v2/Proceso/Actuaciones/' . $DataProceso['procesos'][0]['idProceso'] . '?pagina=' . $i);
                        $DataActuaciones = json_decode($ResponseActuaciones, true);

                        if(is_array($DataActuaciones['actuaciones'])) {
                            foreach ($DataActuaciones['actuaciones'] as $actuacion) {
                                // consultar si ya se sincronizo la actuacion
                                $SearchActuacion = Actuacion::where('idsincronizacion', $actuacion['idRegActuacion'])->exists();

                                if(!$SearchActuacion) {
                                    Actuacion::create([
                                        'fecha' => $actuacion['fechaActuacion'],
                                        'actuacion' => $actuacion['actuacion'],
                                        'anotacion' => $actuacion['anotacion'],
                                        'f_inicio_termino' => $actuacion['fechaInicial'],
                                        'f_fin_termino' => $actuacion['fechaFinal'],
                                        'idsincronizacion' => $actuacion['idRegActuacion'],
                                        'procesos_id' => $proceso['id']
                                    ]);

                                    $nuevos[$proceso['id']] = (!isset($nuevos[$proceso['id']])) ? 1 : $nuevos[$proceso['id']] + 1;
                                    $procesosnuevos[$proceso['id']] = $proceso;
                                }
                            }
                        }
                    }
                }

                if(isset($nuevos[$proceso['id']]) && count($nuevos[$proceso['id']]) > 0) {
                    // Notificar al cliente a quienes tienen acceso al proceso
                    $correos = [];
                    if (filter_var($proceso['cliente']['correo'], FILTER_VALIDATE_EMAIL)) {
                        $correos[] = $proceso['cliente']['correo'];
                    }

                    foreach ($proceso['acceso_proceso'] as $acceso) {
                        if (filter_var($acceso['cliente']['email'], FILTER_VALIDATE_EMAIL)) {
                            $correos[] = $acceso['cliente']['email'];
                        }
                    }

                    if(count($correos) > 0) {
                        $mensaje = "Reultado de sincronización ".date('Y-m-d H:i:s')."<br><br><br><br>";
                        $mensaje .= "Proceso ".$proceso['tipo']." ".$proceso['radicado']." de ".$proceso['clientes']['nombre']." tiene ".$nuevos[$proceso['id']]." actuaciones nuevas.";
                        $mensaje .= "<br><br>";

                        Mail::to(implode(",", $correos).',gerencia@obconsultores.com')->send(new MensajeCliente($mensaje, 'Sincronización de actuaciones', null, null, null));
                    }
                }

            }
        }

        if(count($nuevos) == 0) return 0;

        $sincronizacion = Sincronizacion::create([
            'fecha' => date('Y-m-d'),
            'users_id' => auth()->user()->id,
        ]);

        $mensaje = "Reultado de sincronización ".date('Y-m-d H:i:s')."<br><br><br><br>";
        foreach ($nuevos as $key => $value) {
            $mensaje .= "Proceso ".$procesosnuevos[$key]['tipo']." ".$procesosnuevos[$key]['radicado']." de ".$procesosnuevos[$key]['clientes']['nombre']." tiene ".$value." actuaciones nuevas.";
            $mensaje .= "<br><br>";

            SincronizacionProceso::create([
                'cantidad' => $value,
                'sincronizaciones_id' => $sincronizacion->id,
                'procesos_id' => $key
            ]);
        }

        Mail::to('gerencia@obconsultores.com')->send(new MensajeCliente($mensaje, 'Sincronización de actuaciones', null, null, null));

        return $sincronizacion->id;
    }
}
