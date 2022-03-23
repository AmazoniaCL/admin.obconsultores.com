<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActuacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actuaciones', function (Blueprint $table) {
            $table->id();

            $table->date('fecha');
            $table->string('actuacion');
            $table->string('anotacion')->nullable();
            $table->date('f_inicio_termino')->nullable();
            $table->date('f_fin_termino')->nullable();
            $table->string('anotacion_file')->nullable();
            $table->bigInteger('idsincronizacion')->nullable();

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
        Schema::dropIfExists('actuaciones');
    }
}
