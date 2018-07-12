<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersBancosPaisMonedas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_bancos_pais_monedas', function (Blueprint $table) {
            $table->increments('idusers_bancos_pais_monedas');
            $table->integer('iduser')->unsigned();
            $table->integer('idbancos_pais_monedas')->unsigned();
            
            $table->timestamps();

        });

        Schema::table('users_bancos_pais_monedas', function($table)
        {
            $table->foreign('iduser')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('idbancos_pais_monedas')->references('idbancos_pais_monedas')->on('bancos_pais_monedas')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_bancos_pais_monedas');
    }
}
