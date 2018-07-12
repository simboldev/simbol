<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Recomendaciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('recomendaciones', function (Blueprint $table) {
            $table->increments('idrecomendaciones');
            $table->string('cuerpomensaje',500);
            $table->date('fecha');
            $table->integer('idrecomienda')->unsigned();
            $table->integer('idrecomendado')->unsigned();
            $table->timestamps();
            $table->integer('posturasMatches_idposturasMatch')->unsigned();
            $table->integer('amigos_idamigos')->unsigned();

        });

        Schema::table('recomendaciones', function($table)
        {
            $table->foreign('idrecomienda')->references('id')->on('users')->onDelete('cascade');
            
            $table->foreign('idrecomendado')->references('id')->on('users')->onDelete('cascade');

            $table->foreign('posturasMatches_idposturasMatch')->references('idposturasMatch')->on('posturasMatches')->onDelete('cascade');

            $table->foreign('amigos_idamigos')->references('idamigos')->on('amigos')->onDelete('cascade');
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
        Schema::dropIfExists('recomendaciones');
    }
}
