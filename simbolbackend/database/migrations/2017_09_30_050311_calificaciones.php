<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Calificaciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('calificaciones', function (Blueprint $table) {
            $table->increments('idcalificaciones');
            $table->integer('puntos')->nullable();
            $table->string('comentario',500)->nullable();
            $table->timestamps();
            $table->integer('iduser')->default(1)->unsigned();
        });
        
        Schema::table('calificaciones', function($table) {
            $table->foreign('iduser')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('calificaciones');
    }
}
