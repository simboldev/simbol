<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Notificaciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->increments('idnotificaciones');
            $table->string('titulo',100);
            $table->string('cuerpo',500);
            $table->binary('adjunto')->nulleble();
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
        //
        Schema::dropIfExists('notificaciones');
    }
}
