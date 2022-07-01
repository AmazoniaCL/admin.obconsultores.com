<?php

namespace App\Jobs;

use App\Models\Actuacion;
use App\Models\Proceso;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\MensajeCliente;
use App\Mail\ResponderConsulta;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Notifications\Action;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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
        $procesos = Proceso::whereNotNull('radicado')->with('clientes')->get();
        $procesosnuevos = [];
        $nuevos = [];

        // dd($procesos[0]['clientes']['nombre']);

        Log::info("Job executing");

        foreach ($procesos as $proceso) {
            if(strlen((string) $proceso->radicado) != 23) continue;

            $ResponseProceso = Http::get('https://consultaprocesos.ramajudicial.gov.co:448/api/v2/Procesos/Consulta/NumeroRadicacion?SoloActivos=false&numero=' . $proceso->radicado);
            $DataProceso = json_decode($ResponseProceso, true);

            // dd($DataProceso);

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
            }
        }

        // dd($procesosnuevos);

        $mensaje = "Reultado de sincronización ".date('Y-m-d H:i:s')."<br><br><br><br>";
        foreach ($nuevos as $key => $value) {
            $mensaje .= "Proceso ".$procesosnuevos[$key]['tipo']." ".$procesosnuevos[$key]['num_proceso']." de ".$procesosnuevos[$key]['clientes']['nombre']." tiene ".$value." estados nuevos.";
            $mensaje .= "<br><br>";
        }

        Mail::to('leicortega@gmail.com')->send(new MensajeCliente($mensaje, 'Sincronización de estados', null, null, null));

        echo $mensaje;
    }
}
