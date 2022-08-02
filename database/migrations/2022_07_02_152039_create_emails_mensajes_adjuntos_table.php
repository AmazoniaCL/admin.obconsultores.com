<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailsMensajesAdjuntosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emails_mensajes_adjuntos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('file');

            $table->foreignId('emails_mensaje_id')
            ->constrained('emails_mensajes')
            ->cascadeOnDelete();

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
        Schema::dropIfExists('emails_mensajes_adjuntos');
    }
}
