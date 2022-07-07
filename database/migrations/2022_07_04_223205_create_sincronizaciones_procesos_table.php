<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSincronizacionesProcesosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sincronizaciones_procesos', function (Blueprint $table) {
            $table->id();

            $table->integer('cantidad');
            $table->foreignId('sincronizaciones_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('procesos_id')
                ->constrained()
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sincronizaciones_procesos');
    }
}
