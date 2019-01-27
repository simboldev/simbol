<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePosturasRechazadasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('posturas_rechazadas'))
        {
          Schema::create('posturas_rechazadas', function (Blueprint $table) {
              $table->increments('id');
              $table->integer('mi_postura_id')->unsigned();
              $table->integer('postura_rechazada_id')->unsigned();
              $table->timestamps();
          });

          Schema::table('posturas_rechazadas', function($table)
          {
              $table->foreign('mi_postura_id')->references('idposturas')->on('posturas')->onDelete('cascade');
              $table->foreign('postura_rechazada_id')->references('idposturas')->on('posturas')->onDelete('cascade');
          });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posturas_rechazadas');
    }
}
