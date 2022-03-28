<?php

namespace App\Console\Commands;

use App\Jobs\SincronizarActuaciones;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DemoCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // dispatch(new SincronizarActuaciones);
        // $job = new SincronizarActuaciones();
        // $job->handle();
        SincronizarActuaciones::dispatch();
        Log::info("Job executed");
        // return 0;
    }
}
