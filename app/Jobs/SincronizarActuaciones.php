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
        $procesos = Proceso::whereNotNull('radicado')->get();
        $nuevos = [];

        Log::info("Job executing");

        foreach ($procesos as $proceso) {
            if(strlen((string) $proceso->radicado) != 23) continue;

            $curlHeaders = array(
                "Accept: application/json",
                "Content-Type: application/json; charset=utf-8",
            );

            $options = array(
                CURLOPT_URL => 'https://consultaprocesos.ramajudicial.gov.co/api/v2/Procesos/Consulta/NumeroRadicacion?SoloActivos=false&numero=' . $proceso->radicado,
                CURLOPT_HTTPHEADER => $curlHeaders,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 600,
                CURLOPT_HEADER => false,
                CURLOPT_FOLLOWLOCATION => 0,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_PORT => 448
            );

            $ch = curl_init();
            curl_setopt_array($ch , $options);

            $output = curl_exec($ch);
            $err = curl_error($ch);
            curl_close($ch);

            dd($err);
            dd(json_decode($output));

            $client = new Client();
            // $request = $client->get('https://consultaprocesos.ramajudicial.gov.co:448/api/v2/Procesos/Consulta/NumeroRadicacion?SoloActivos=false&numero=' . $proceso->radicado);
            $request = $client->request('POST', 'https://consultaprocesos.ramajudicial.gov.co:448/api/v2/Procesos/Consulta/NumeroRadicacion?SoloActivos=false&numero=' . $proceso->radicado, [
                'allow_redirects' => [
                    'max'             => 10,        // allow at most 10 redirects.
                    'strict'          => true,      // use "strict" RFC compliant redirects.
                    'referer'         => true,      // add a Referer header
                    'protocols'       => ['https'], // only allow https URLs
                    'track_redirects' => true
                ]
            ]);
            $response = $request->getBody();

            dd($response);

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
                                }
                            }
                        }
                    }
                }
            }

        }

        dd($nuevos);

        // if (count($nuevos)) {
        //     Mail::send(array(), array(), function ($message) {
        //         $message->to(..)
        //           ->subject('Nuevas actuaciones ')
        //           ->setBody('', 'text/html');
        //     });
        // }
    }
}
