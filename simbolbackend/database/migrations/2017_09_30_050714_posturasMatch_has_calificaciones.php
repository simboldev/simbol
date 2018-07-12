<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PosturasMatchHasCalificaciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('posturasMatch_has_calificaciones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idposturasMatch')->unsigned();
            $table->integer('idcalificaciones')->unsigned();
            $table->timestamps(); 
        });

        Schema::table('posturasMatch_has_calificaciones', function($table)
        {
            $table->foreign('idposturasMatch')->references('idposturasMatch')->on('posturasMatches')->onDelete('cascade');
            $table->foreign('idcalificaciones')->references('idcalificaciones')->on('calificaciones')->onDelete('cascade');
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
        Schema::dropIfExists('posturasMatch_has_calificaciones');
    }
}
