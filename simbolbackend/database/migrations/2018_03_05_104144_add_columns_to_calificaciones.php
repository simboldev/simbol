<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToCalificaciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('calificaciones', function (Blueprint $table) {
            //
            $table->integer('idusuariocalificado')->default(1)->unsigned();
            $table->integer('idPosturasMatch')->default(1)->unsigned();
            
            $table->foreign('idusuariocalificado')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('idPosturasMatch')->references('idposturasMatch')->on('posturasMatches')->onDelete('cascade');
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
        Schema::table('calificaciones', function (Blueprint $table) {
            //
            $table->dropColumn('idusuariocalificado');
            $table->dropColumn('idPosturasMatch');
        });
    }
}
