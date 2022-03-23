<?php

namespace App\Jobs;

use App\Models\Actuacion;
use App\Models\Proceso;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\MensajeCliente;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Notifications\Action;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

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
        $procesos = Proceso::whereNotNull('radicado')->get();
        $nuevos = [];

        foreach ($procesos as $proceso) {
            if(strlen((string) $proceso->radicado) != 23) continue;

            $ResponseProceso = Http::get('https://consultaprocesos.ramajudicial.gov.co:448/api/v2/Procesos/Consulta/NumeroRadicacion?SoloActivos=false&numero=' . $proceso->radicado);
            $DataProceso = json_decode($ResponseProceso, true);

            if(is_array($DataProceso['procesos'])) {
                $ResponseActuaciones =  Http::get('https://consultaprocesos.ramajudicial.gov.co:448/api/v2/Proceso/Actuaciones/' . $DataProceso['procesos'][0]['idProceso'] . '?pagina=1');
                $DataActuaciones = json_decode($ResponseActuaciones, true);

                // dd($DataActuaciones);

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
                        }
                    }
                }

                if($DataActuaciones['paginacion']['cantidadPaginas'] > 1) {
                    for ($i = 2; $i <= $DataActuaciones['paginacion']['cantidadPaginas']; $i++) {
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
                                }
                            }
                        }
                    }
                }
            }

        }

        // if (count($nuevos)) {
        //     Mail::send(array(), array(), function ($message) {
        //         $message->to(..)
        //           ->subject('Nuevas actuaciones ')
        //           ->setBody('', 'text/html');
        //     });
        // }
    }
}
