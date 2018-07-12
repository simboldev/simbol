<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Posturas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('posturas', function (Blueprint $table) {
            $table->increments('idposturas');
            $table->integer('quiero_moneda_id')->unsigned();
            $table->integer('tengo_moneda_id')->unsigned();
            $table->string('tengo',100);
            $table->string('tasacambio',100);
            $table->datetime('fechadesde');
            $table->datetime('fechahasta');
            $table->string('comentarios',500)->nullable();
            $table->integer('iduser')->unsigned();
            $table->timestamps();
            $table->integer('estatusPosturas_idestatusPosturas')->unsigned();
        });

        Schema::table('posturas', function($table)
        {
            $table->foreign('iduser')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('quiero_moneda_id')->references('idmonedas')->on('monedas')->onDelete('cascade');
            $table->foreign('tengo_moneda_id')->references('idmonedas')->on('monedas')->onDelete('cascade');
            $table->foreign('estatusPosturas_idestatusPosturas')->references('idestatusPosturas')->on('estatusPosturas')->onDelete('cascade');
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
        Schema::dropIfExists('posturas');
    }
}
