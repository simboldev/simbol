<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PosturasMatches extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('posturasMatches', function (Blueprint $table) {
            $table->increments('idposturasMatch');
            $table->integer('posturas_idposturas')->unsigned();
            $table->boolean('estatusMatch')->default(false);
            $table->integer('users_idusers')->unsigned();
            $table->integer('iduser2')->unsigned();
            $table->dateTime('cronometro');
            $table->timestamps();
            $table->integer('confiabilidads_idconfiabilidad')->unsigned()->nullable();
            $table->integer('denuncias_iddenuncias')->unsigned()->nullable();
            $table->integer('trackings_idtracking')->unsigned()->nullable();

        });

        Schema::table('posturasMatches', function($table)
        {
            $table->foreign('posturas_idposturas')->references('idposturas')->on('posturas')->onDelete('cascade');
            $table->foreign('users_idusers')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('iduser2')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('confiabilidads_idconfiabilidad')->references('idconfiabilidad')->on('confiabilidads')->onDelete('cascade');
            $table->foreign('denuncias_iddenuncias')->references('iddenuncias')->on('denuncias')->onDelete('cascade');
            $table->foreign('trackings_idtracking')->references('idtracking')->on('trackings')->onDelete('cascade');
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
        Schema::dropIfExists('posturasMatches');
    }
}
