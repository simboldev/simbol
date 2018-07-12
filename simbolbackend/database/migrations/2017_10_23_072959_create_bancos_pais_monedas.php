<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBancosPaisMonedas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bancos_pais_monedas', function (Blueprint $table) {
            $table->increments('idbancos_pais_monedas');
            $table->integer('idpais')->unsigned();
            $table->integer('idbanco')->unsigned();
            $table->integer('idmoneda')->unsigned();
            $table->timestamps();

        });

        Schema::table('bancos_pais_monedas', function($table)
        {
            $table->foreign('idpais')->references('idpais')->on('pais')->onDelete('cascade');
            $table->foreign('idbanco')->references('idbancos')->on('bancos')->onDelete('cascade');
            $table->foreign('idmoneda')->references('idmonedas')->on('monedas')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bancos_pais_monedas');
    }
}
