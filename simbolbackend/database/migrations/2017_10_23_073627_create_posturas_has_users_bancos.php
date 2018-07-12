<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePosturasHasUsersBancos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posturas_has_users_bancos', function (Blueprint $table) {
            $table->increments('idposturas_has_users_bancos');
            $table->integer('posturas_id')->unsigned();
            $table->integer('users_bancos_pais_monedas_id')->unsigned();
            
            $table->timestamps();

        });

        Schema::table('posturas_has_users_bancos', function($table)
        {
            $table->foreign('posturas_id')->references('idposturas')->on('posturas')->onDelete('cascade');
            $table->foreign('users_bancos_pais_monedas_id')->references('idusers_bancos_pais_monedas')->on('users_bancos_pais_monedas')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posturas_has_users_bancos');
    }
}
